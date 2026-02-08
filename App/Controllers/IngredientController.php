<?php

namespace App\Controllers;

use App\Models\Ingredient;
use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;

class IngredientController extends BaseController
{
    private function isAuthorized(Request $request): bool
    {
        $auth = $this->app->getAuthenticator();
        if (!$auth->getUser()->isLoggedIn()) return false;
        $user = \App\Models\User::findByUsername($auth->getUser()->getName());
        return $user->getRole() === 'ADMIN';
    }

    public function index(Request $request): Response
    {
        $ingredients = Ingredient::getAll(orderBy: 'name asc');
        return $this->html(['ingredients' => $ingredients]);
    }

    public function create(Request $request): Response
    {
        if(!$this->isAuthorized($request)) return $this->redirect($this->url("home.index"));

        $message = null;
        $categories = \App\Models\Category::getAll(orderBy: 'name asc');

        if ($request->hasValue('submit')) {
            $name = (string)$request->value('name');
            $categoryId = (int)$request->value('category');

            $message = $this->validateIngredientData($name, $categoryId);
            if ($message !== null) {
                return $this->html(['message' => $message, 'categories' => $categories]);
            }

            $ingredient = new Ingredient();
            $ingredient->setName($name);
            $ingredient->setCategoryId($categoryId);
            $ingredient->save();
            return $this->redirect($this->url("ingredient.index"));
        }

        return $this->html(['message' => $message, 'categories' => $categories]);
    }

    public function edit(Request $request)
    {
        if(!$this->isAuthorized($request)) return $this->redirect($this->url("home.index"));

        $message = null;
        $categories = \App\Models\Category::getAll(orderBy: 'name asc');

        $id = (int)$request->value('id');
        $ingredient = Ingredient::getOne($id);
        if (!$ingredient) return $this->redirect($this->url("ingredient.index"));

        if ($request->hasValue('submit')) {
            $name = (string)$request->value('name');
            $categoryId = (int)$request->value('category');

            $message = $this->validateIngredientData($name, $categoryId);
            if ($message !== null) {
                return $this->html(compact('message', 'ingredient', 'categories'));
            }

            $ingredient->setName($name);
            $ingredient->setCategoryId($categoryId);
            $ingredient->save();
            return $this->redirect($this->url("ingredient.index"));
        }

        return $this->html(compact('ingredient', 'categories', 'message'));
    }

    public function delete(Request $request)
    {
        if (!$this->isAuthorized($request)) return $this->redirect($this->url("home.index"));

        $id = (int)$request->value('id');
        $ingredient = Ingredient::getOne($id);
        if (!$ingredient) return $this->redirect($this->url("ingredient.index"));
        $ingredient->delete();
        return $this->redirect($this->url("ingredient.index"));
    }

    private function validateIngredientData(string $name, int $categoryId): ?string
    {
        if (trim($name) === '') {
            return 'Name is required';
        }
        if (strlen($name) > 128) {
            return 'Name must be at most 128 characters long';
        }
        $exists = Ingredient::getCount('name = ? AND category_id = ?', [$name, $categoryId]);
        if ($exists > 0) {
            return 'This ingredient already exists in the selected category';
        }
        return null;
    }
}