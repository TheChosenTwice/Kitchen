<?php

namespace App\Models;

use Framework\Core\Model;

class FavouriteRecipe extends Model
{
    protected static ?string $tableName = 'favourite_recipes';
    public ?int $id = null; //Aby framework nepadal
    protected ?int $user_id = null;
    protected ?int $recipe_id = null;
    protected ?string $created_at = null;

    public function getUserId(): ?int { return $this->user_id;}
    public function setUserId(?int $user_id): void { $this->user_id = $user_id;}
    public function getRecipeId(): ?int { return $this->recipe_id;}
    public function setRecipeId(?int $recipe_id): void { $this->recipe_id = $recipe_id;}
    public function getCreatedAt(): ?string { return $this->created_at; }

    public function getRecipe(): ?Recipe
    {
        return $this->getOneRelated(Recipe::class, 'recipe_id');
    }
}