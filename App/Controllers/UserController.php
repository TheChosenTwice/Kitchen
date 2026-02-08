<?php

namespace App\Controllers;

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
}