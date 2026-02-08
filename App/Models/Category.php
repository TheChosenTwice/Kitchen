<?php

namespace App\Models;

use Framework\Core\Model;

class Category extends Model
{

    protected ?int $id = null;
    protected string $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}

