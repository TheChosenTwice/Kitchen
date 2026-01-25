<?php

namespace Framework\Auth;

use App\Models\User;
use Framework\Core\App;
use Framework\Core\IIdentity;

class DatabaseAuthenticator extends SessionAuthenticator
{
    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    protected function authenticate(string $username, string $password): ?IIdentity
    {
        $user = User::findByUsername($username);
        if ($user && password_verify($password, $user->getPasswordHash()))
            return $user;
        return null;
    }
}