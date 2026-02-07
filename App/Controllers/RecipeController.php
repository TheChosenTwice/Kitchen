<?php

namespace App\Controllers;

use App\Models\FavouriteRecipe;
use App\Models\Recipe;
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
        // TODO: Implement bookmarking functionality
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
}