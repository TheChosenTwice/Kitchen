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
        if(!$this->isAuthorized($request)) return $this->redirect($this->url("home.index"));

        $categories = Category::getAll(orderBy: 'id asc');
        return $this->html(['categories' => $categories]);
    }

    public function create(Request $request): Response
    {
        if(!$this->isAuthorized($request)) return $this->redirect($this->url("home.index"));

        $message = null;

        if ($request->hasValue('submit')) {
            $name = (string)$request->value('name');
            if (strlen($name) > 100) {
                $message = 'Name must be at most 100 characters long';
                return $this->html(compact('message'));
            }
            $exists = Category::getCount('name = ?', [$name]);
            if ($exists > 0) {
                $message = 'A category with this name already exists';
                return $this->html(compact('message'));
            }

            $category = new Category();
            $category->setName($name);
            $category->save();
            return $this->redirect($this->url("category.index"));
        }
        return $this->html(compact('message'));
    }

    private function isAuthorized(Request $request): bool
    {
        $auth = $this->app->getAuthenticator();
        if (!$auth->getUser()->isLoggedIn()) return false;
        $user = \App\Models\User::findByUsername($auth->getUser()->getName());
        return $user->getRole() === 'ADMIN';
    }
}