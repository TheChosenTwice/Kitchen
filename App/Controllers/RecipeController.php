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
        // Premení URL parameter na pole čísel
        $ids = array_filter(
            array_map('intval', explode(',', $ingredientsIds))
        );

        if (empty($ids)) {
            return $this->html(['recipes' => []]);
        }
        // Vytvorenie reťazca ?,?,?,?,?,? ktorý sa vloží do SQL dotazu
        $placeholders = implode(',', array_fill(0, count($ids), '?'));


        $sql = "SELECT id, title, cooking_time, image, COUNT(*) AS num_of_ingredients, 
            COUNT(CASE WHEN recipe_ingredients.ingredient_id IN ($placeholders) THEN 1 END) AS num_of_ingredients_met
            FROM recipes JOIN recipe_ingredients ON recipes.id = recipe_ingredients.recipe_id
            GROUP BY recipes.id
            HAVING num_of_ingredients_met > 0
            ORDER BY num_of_ingredients_met DESC, num_of_ingredients";

        // Vykonanie surového SQL dotazu s parametrami na mieste ?,?,?,?,?,?
        $recipes = Recipe::executeRawSQL($sql, $ids);

        return $this->html(['recipes' => $recipes]);
    }
}