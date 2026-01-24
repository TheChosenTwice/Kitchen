<?php

namespace App\Models;

use Framework\Core\Model;

class Recipe extends Model
{
    protected ?int $id = null;
    protected string $title;
    protected string $instructions;
    protected ?string $category = null;
    protected ?int $cooking_time = null;
    protected ?int $serving_size = null;
    protected ?string $image = null;
    protected ?int $author_id = null;

    public function getId(): ?int { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function setTitle(string $title): void { $this->title = $title; }
    public function getInstructions(): string { return $this->instructions; }
    public function setInstructions(string $instructions): void { $this->instructions = $instructions; }
    public function getCategory(): ?string { return $this->category; }
    public function setCategory(?string $category): void { $this->category = $category; }
    public function getCookingTime(): ?int { return $this->cooking_time; }
    public function setCookingTime(?int $cooking_time): void { $this->cooking_time = $cooking_time; }
    public function getServingSize(): ?int { return $this->serving_size; }
    public function setServingSize(?int $serving_size): void { $this->serving_size = $serving_size; }
    public function getImage(): ?string { return $this->image; }
    public function setImage(?string $image): void { $this->image = $image; }
    public function getAuthorId(): ?int { return $this->author_id; }
    public function setAuthorId(?int $author_id): void { $this->author_id = $author_id; }
}