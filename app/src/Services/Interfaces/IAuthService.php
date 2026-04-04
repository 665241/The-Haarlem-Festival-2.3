<?php

namespace App\Services\Interfaces;

use App\Models\UserModel;

interface IAuthService
{
    
    public function emailExists(string $email): bool;
    //public function userNameExists(string $userName): bool;
    public function createUser(UserModel $user): bool;
    public function getUserByEmail(string $email): ?array;
    public function logout(): void;
    public function validateUser(UserModel $user, string $password, bool $isNew): void;
}
