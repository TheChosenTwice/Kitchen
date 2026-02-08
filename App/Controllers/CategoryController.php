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

            $message = $this->validateCategoryData($name);
            if ($message !== null) {
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

    public function delete(Request $request): Response
    {
        if (!$this->isAuthorized($request)) return $this->redirect($this->url("home.index"));

        $id = (int)$request->value('id');
        $category = Category::getOne($id);
        if (!$category) return $this->redirect($this->url("category.index"));
        $category->delete();
        return $this->redirect($this->url("category.index"));
    }

    public function edit(Request $request): Response
    {
        if (!$this->isAuthorized($request)) return $this->redirect($this->url("home.index"));

        $id = (int)$request->value('id');
        $category = Category::getOne($id);

        if (!$category) return $this->redirect($this->url("category.index"));

        if ($request->hasValue('submit')) {
            $name = (string)$request->value('name');

            $message = $this->validateCategoryData($name, $id);
            if ($message !== null) {
                return $this->html(compact('message', 'category'));
            }

            $category->setName($name);
            $category->save();
            return $this->redirect($this->url("category.index"));
        }

        if (!$category) return $this->redirect($this->url("category.index"));
        return $this->html(compact('category'));
    }

    private function validateCategoryData(string $name, int $id = null): ?string
    {
        if (strlen($name) > 100) {
            return 'Name must be at most 100 characters long';
        }
        $exists = Category::getCount('name = ?' . ($id ? ' AND id != ?' : ''), $id ? [$name, $id] : [$name]);
        if ($exists > 0) {
            return 'A category with this name already exists';
        }
        return null;
    }
}