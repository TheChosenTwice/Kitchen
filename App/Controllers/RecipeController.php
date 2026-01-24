<?php

namespace App\Controllers;

use App\Models\Recipe;
use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;

class RecipeController extends BaseController
{

    public function index(Request $request): Response
    {
        $ingredientsIds = $request->get('ingredients');
        if (empty($ingredientsIds))
            return $this->html(['recipes' => []]);

        $sql = "SELECT id, title, cooking_time, image, COUNT(*) AS num_of_ingredients, 
            COUNT(CASE WHEN recipe_ingredients.ingredient_id IN ($ingredientsIds) THEN 1 END) AS num_of_ingredients_met
            FROM recipes JOIN recipe_ingredients ON recipes.id = recipe_ingredients.recipe_id
            GROUP BY recipes.id
            HAVING num_of_ingredients_met > 0
            ORDER BY num_of_ingredients_met DESC, num_of_ingredients";
        $recipes = Recipe::executeRawSQL($sql);

        return $this->html(['recipes' => $recipes]);
    }
}