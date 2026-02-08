<?php

namespace App\Controllers;

use App\Models\Category;
use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;

class CategoryController extends BaseController
{

    public function index(Request $request): Response
    {
        $auth = $this->app->getAuthenticator();
        if (!$auth->getUser()->isLoggedIn()) return $this->redirect($this->url('auth.login'));
        $user = \App\Models\User::findByUsername($auth->getUser()->getName());
        if ($user->getRole() !== 'ADMIN') return $this->redirect($this->url('home.index'));

        $categories = Category::getAll(orderBy: 'id asc');
        return $this->html(['categories' => $categories]);
    }
}