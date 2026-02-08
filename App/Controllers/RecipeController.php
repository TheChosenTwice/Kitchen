<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\FavouriteRecipe;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\User;
use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;

class RecipeController extends BaseController
{
    public function detail(Request $request) : Response
    {
        $recipeId = (int)$request->get('id');
        $recipe = Recipe::getOne($recipeId);
        $ingredients = $recipe->getIngredients();
        $isBookmarked = false;

        // Je recept uz bookmarked?
        $auth = $this->app->getAuthenticator();
        if ($auth->getUser()->isLoggedIn()) {
            $userId = $auth->getUser()->getId();
            $sql = "SELECT * FROM favourite_recipes WHERE user_id = :userId AND recipe_id = :recipeId";
            $params = [':userId' => $userId, ':recipeId' => $recipeId];
            $favoriteRecipe = FavouriteRecipe::executeRawSQL($sql, $params);
            $isBookmarked = !empty($favoriteRecipe);
        }

        return $this->html([
            'recipe' => [
                'id' => $recipe->getId(),
                'title' => $recipe->getTitle(),
                'cooking_time' => $recipe->getCookingTime(),
                'image' => $recipe->getImage(),
                'instructions' => $recipe->getInstructions(),
                'category' => $recipe->getCategory(),
                'serving_size' => $recipe->getServingSize(),
            ],
            'ingredients' => $ingredients,
            'isBookmarked' => $isBookmarked
        ]);
    }

    public function bookmark(Request $request) : Response
    {
        $auth = $this->app->getAuthenticator();
        if (!$auth->getUser()->isLoggedIn()) {
            return $this->redirect($this->url('auth.index'));
        }

        $recipeId = (int)$request->get('id');
        $userId = $auth->getUser()->getId();
        $recipe = Recipe::getOne($recipeId);

        $sql = "SELECT * FROM favourite_recipes WHERE user_id = :userId AND recipe_id = :recipeId";
        $params = [':userId' => $userId, ':recipeId' => $recipeId];
        $favoriteRecipe = FavouriteRecipe::executeRawSQL($sql, $params);

        // Ak bol uz v oblubenych odstran
        if (!empty($favoriteRecipe)) {
            $sql = "DELETE FROM favourite_recipes WHERE user_id = :userId AND recipe_id = :recipeId";
            FavouriteRecipe::executeRawSQL($sql, [':userId' => $userId, ':recipeId' => $recipeId]);
            return $this->redirect($this->url('detail', ['id' => $recipeId]));
        }

        $favoriteRecipe = new FavouriteRecipe();
        $favoriteRecipe->setUserId($auth->getUser()->getId());
        $favoriteRecipe->setRecipeId($recipe->getId());
        $favoriteRecipe->save();

        return $this->redirect($this->url('detail', ['id' => $recipeId]));
    }

    public function favorites(Request $request) : Response
    {
        $auth = $this->app->getAuthenticator();
        if (!$auth->getUser()->isLoggedIn()) {
            return $this->redirect($this->url('auth.index'));
        }

        $userId = $auth->getUser()->getId();
        // ziska riadky idciek
        $favoriteRecipesIds = FavouriteRecipe::executeRawSQL(
            "SELECT recipe_id FROM favourite_recipes WHERE user_id = :userId", [':userId' => $userId]);
        // premeni na pole integerov
        $ids = array_map('intval', array_column($favoriteRecipesIds, 'recipe_id'));
        $ids = array_values(array_filter($ids));

        if (empty($ids)) return $this->html(['recipes' => []]);

        // vytvori string "?,?,?,...", potom sa doplnia za ne $ids
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $recipes = Recipe::getAll("`id` IN ($placeholders)", $ids);

        return $this->html(['recipes' => $recipes]);
    }

    public function index(Request $request): Response
    {
        $ingredientsIds = $request->get('ingredients');
        if (empty($ingredientsIds)) return $this->html(['recipes' => []]);
        // "33,65,31,43,44,46" => [33,65,31,43,44,46]
        $ids = array_filter(array_map('intval', explode(',', $ingredientsIds)));

        if (empty($ids)) return $this->html(['recipes' => []]);

        // Creates and fills map if keys :id0, :id1, ... and id values
        $namedParams = [];
        foreach ($ids as $k => $id) $namedParams[":id$k"] = $id;

        // Creates string ":id0,:id1,:id2,..." for SQL IN clause
        $inClause = implode(',', array_keys($namedParams));

        $sql = "
        SELECT 
            recipes.id, 
            recipes.title, 
            recipes.cooking_time, 
            recipes.image, 
            COUNT(*) AS num_of_ingredients,
            COUNT(CASE WHEN recipe_ingredients.ingredient_id IN ($inClause) THEN 1 END) AS num_of_ingredients_met
        FROM recipes
        JOIN recipe_ingredients ON recipes.id = recipe_ingredients.recipe_id
        GROUP BY recipes.id
        HAVING num_of_ingredients_met > 0
        ORDER BY num_of_ingredients_met DESC, num_of_ingredients";

        // Executes the raw SQL with named parameters to prevent SQL injection
        $recipes = Recipe::executeRawSQL($sql, $namedParams);
        return $this->html(['recipes' => $recipes]);
    }

    public function create(Request $request) : Response
    {
        $auth = $this->app->getAuthenticator();
        if (!$auth->getUser()->isLoggedIn()) {
            return $this->redirect($this->url('auth.index'));
        }

        $categories = Category::getAll(orderBy: 'name asc');
        $ingredients = Ingredient::getAll(orderBy: 'name asc');
        $message = $request->get('message');

        return $this->html(['categories' => $categories, 'ingredients' => $ingredients, 'message' => $message]);
    }

    private function validateRecipe($title, $instructions, $cookingTime, $category, $servingSize, $imageFile): ?string
    {
        if ($title === '' || $instructions === '') return 'Title and instructions are required';
        if (mb_strlen($title) > 255) return 'Title must be at most 255 characters.';

        $existing = Recipe::getCount('title = ?', [$title]);
        if ($existing > 0) return 'Recipe with this title already exists';

        if ($cookingTime < 0 || $cookingTime > 10000) return 'Cooking time must be between 1 and 10000.';
        if (mb_strlen($category) > 64) return 'Category must be at most 64 characters.';
        if ($servingSize < 1 || $servingSize > 1000) return 'Serving size must be between 1 and 1000.';

        if (isset($imageFile) && $imageFile['error'] === UPLOAD_ERR_OK) {
            $fileTmp = $imageFile['tmp_name'];
            $fileSize = $imageFile['size'];
            $fileType = mime_content_type($fileTmp);
            if (!str_starts_with($fileType, 'image/')) return 'Uploaded file must be an image.';
            if ($fileSize > 2 * 1024 * 1024) return 'Image size must be less than 2MB.';
        }
        return null;
    }

    public function store(Request $request) : Response
    {
        $auth = $this->app->getAuthenticator();
        if (!$auth->getUser()->isLoggedIn()) {
            return $this->redirect($this->url('auth.index'));
        }

        $title = trim((string)$request->value('title'));
        $cookingTime = (int)$request->value('cooking_time');
        $category = trim((string)$request->value('category'));
        $servingSize = (int)$request->value('serving_size');
        $instructions = trim((string)$request->value('instructions'));
        $authorId = User::findByUsername($auth->getUser()->getName())->getId();
        $imageFile = $_FILES['image'] ?? null;

        // Validacia vstupov
        $message = $this->validateRecipe($title, $instructions, $cookingTime, $category, $servingSize, $_FILES['image'] ?? null);
        if ($message !== null) return $this->redirect($this->url('create', ['message' => $message]));

        // Upload obrazku do public/images a ziskanie nazvu suboru
        $fileName = null;
        if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/images/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $extension = pathinfo($imageFile['name'], PATHINFO_EXTENSION);
            $uniqueName = uniqid('img_', true) . '.' . $extension;
            $targetPath = $uploadDir . $uniqueName;
            if (move_uploaded_file($imageFile['tmp_name'], $targetPath)) $fileName = $uniqueName;
        }

        $recipe = new Recipe();
        $recipe->setTitle($title);
        $recipe->setCookingTime($cookingTime);
        $recipe->setCategory($category);
        $recipe->setServingSize($servingSize);
        $recipe->setInstructions($instructions);
        $recipe->setAuthorId($authorId);
        $recipe->setImage($fileName);
        $recipe->save();

        $ingredientIds = $request->value('ingredients');
        if (!is_array($ingredientIds)) $ingredientIds = [];
        foreach ($ingredientIds as $id) {
            $sql = "INSERT INTO recipe_ingredients (recipe_id, ingredient_id) VALUES (:recipeId, :ingredientId)";
            Recipe::executeRawSQL($sql, [':recipeId' => $recipe->getId(), ':ingredientId' => (int)$id]);
        }

        // TODO delete image file if recipe is deleted
        // TODO delete recipe_ingredients rows if recipe is deleted

        return $this->redirect($this->url('create', ['message' => 'Recipe posted successfully!']));
    }
}