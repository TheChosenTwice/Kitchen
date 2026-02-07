<?php

namespace App\Controllers;

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
            'ingredients' => $ingredients
        ]);
    }

    public function bookmark(Request $request) : Response
    {
        // TODO: Implement bookmarking functionality
        return $this->redirect($this->url('index'));
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