<?php

namespace App\Models;

use Framework\Core\Model;

class FavouriteRecipe extends Model
{
    protected static ?string $tableName = 'favourite_recipes';

    protected ?int $user_id = null;
    protected ?int $recipe_id = null;
    protected ?string $created_at = null;

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function getRecipeId(): ?int
    {
        return $this->recipe_id;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->getOneRelated(Recipe::class, 'recipe_id');
    }
}