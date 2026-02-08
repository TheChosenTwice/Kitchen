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
}