<?php

namespace App\Models;

use Framework\Core\IIdentity;
use Framework\Core\Model;

class User extends Model implements IIdentity
{
    // DB columns must match these protected properties
    protected ?int $id = null;
    protected string $username;
    protected string $password_hash;
    protected string $created_at;
    protected string $role = 'USER';

    public function getId(): ?int { return $this->id; }
    public function getUsername(): string { return $this->username; }
    public function setUsername(string $username): void { $this->username = $username; }
    public function getPasswordHash(): string { return $this->password_hash; }
    public function getCreatedAt(): ?string { return $this->created_at; }
    public function getRole(): string { return $this->role; }
    public function setRole(string $role): void { $this->role = $role; }

    public function setPassword(string $plain): void
    {
        $this->password_hash = password_hash($plain, PASSWORD_DEFAULT);
    }

    /**
     * IIdentity getName implementation.
     */
    public function getName(): string { return $this->username;}

    public static function findByUsername(string $username): ?User {
        $user = User::getAll('username = ?', [$username], null, 1);
        return $user[0] ?? null;
    }
}
