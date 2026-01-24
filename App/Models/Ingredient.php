<?php

namespace App\Models;

use Framework\Core\Model;

class Ingredient extends Model
{
    protected ?int $id = null;
    protected string $name;
    protected ?string $category_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCategoryId(): ?int
    {
        return $this->category_id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setCategoryId(?int $category_id): void
    {
        $this->category_id = $category_id;
    }

    public function getCategoryName(): ?string
    {
        $category = Category::getOne($this->category_id);
        return $category->getName() ?: 'Uncategorized';
    }
}