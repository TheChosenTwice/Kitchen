<?php

namespace App\Controllers;

use App\Models\FavouriteRecipe;
use App\Models\User;
use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;

class UserController extends BaseController
{
    public function index(Request $request): Response
    {
        $auth = $this->app->getAuthenticator();
        if (!$auth->getUser()->isLoggedIn()) return $this->redirect($this->url('auth.login'));

        $user = User::findByUsername($auth->getUser()->getName());
        return $this->html(['user' => $user]);
    }

    public function password(Request $request): Response
    {
        $auth = $this->app->getAuthenticator();
        if (!$auth->getUser()->isLoggedIn()) return $this->redirect($this->url('auth.login'));

        $message = null;
        $success = false;

        if ($request->hasValue('submit')) {
            $oldPassword = (string)$request->value('old_password');
            $newPassword = (string)$request->value('new_password');
            $newPasswordConfirm = (string)$request->value('new_password_confirm');

            $user = User::findByUsername($auth->getUser()->getName());
            if ($this->validatePasswordChange($oldPassword, $newPassword, $newPasswordConfirm, $user) === null) {
                $user->setPassword($newPassword);
                $user->save();
                $success = true;
                $message = 'Password changed successfully.';
            }
        }
        return $this->html(compact('message', 'success'), 'password');
    }

    private function validatePasswordChange($oldPassword, $newPassword, $newPasswordConfirm, User $user): ?string
    {
        if ($oldPassword === '' || $newPassword === '' || $newPasswordConfirm === '') return 'All fields are required.';
        if (!password_verify($oldPassword, $user->getPasswordHash())) return 'Old password is not correct.';
        if ($newPassword !== $newPasswordConfirm) return 'New passwords do not match.';
        return null;
    }

    private function isAuthorized(Request $request): bool
    {
        $auth = $this->app->getAuthenticator();
        if (!$auth->getUser()->isLoggedIn()) return false;
        $user = \App\Models\User::findByUsername($auth->getUser()->getName());
        return $user->getRole() === 'ADMIN';
    }

    public function read(Request $request): Response
    {
        if (!$this->isAuthorized($request)) return $this->redirect($this->url("home.index"));
        $users = User::getAll(orderBy: 'id asc');
        return $this->html(['users' => $users]);
    }

    public function delete(Request $request): Response
    {
        if (!$this->isAuthorized($request)) return $this->redirect($this->url("home.index"));

        $id = (int)$request->value('id');
        $user = User::getOne($id);
        if (!$user) return $this->redirect($this->url("user.read"));
        FavouriteRecipe::executeRawSQL('DELETE FROM favourite_recipes WHERE user_id = ?', [$id]);
        $user->delete();
        return $this->redirect($this->url("user.read"));
    }

    public function create(Request $request): Response
    {
        if (!$this->isAuthorized($request)) return $this->redirect($this->url("home.index"));

        $message = null;

        if ($request->hasValue('submit')) {
            $username = trim((string)$request->value('username'));
            $password = (string)$request->value('password');
            $role = $request->value('is_admin') ? 'ADMIN' : 'USER';

            if ($username === '' || $password === '') {
                $message = 'Username and password are required';
                return $this->html(compact('message'));
            }
            if (mb_strlen($username) > 64) {
                $message = 'Username must be at most 64 characters.';
                return $this->html(compact('message'));
            }
            $existing = User::getCount('username = ?', [$username]);
            if ($existing > 0) {
                $message = 'Username already taken';
                return $this->html(compact('message'));
            }

            $user = new User();
            $user->setUsername($username);
            $user->setPassword($password);
            $user->setRole($role);
            $user->save();

            return $this->redirect($this->url("user.read"));
        }

        return $this->html(compact('message'));
    }

    public function edit(Request $request): Response
    {
        if (!$this->isAuthorized($request)) return $this->redirect($this->url("home.index"));

        return $this->redirect($this->url("user.read"));
    }
}