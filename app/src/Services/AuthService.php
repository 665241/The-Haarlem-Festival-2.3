<?php

namespace App\Services;

use App\Models\UserModel; 
use App\Repositories\UserRepository;
use App\Services\Interfaces\IAuthService; 
use InvalidArgumentException; 

class AuthService implements IAuthService
{
    public function __construct(private UserRepository $userRepository) 
    {
         $this->userRepository = $userRepository;
    }

    public function emailExists(string $email): bool
    {
        return $this->userRepository->findByEmail($email) !== null;
    }

    public function createUser(UserModel $user): bool
    {
        return $this->userRepository->create($user);
    }

    public function getUserByEmail(string $email): ?array
    {
        return $this->userRepository->findByEmail($email);
    }
    public function logout(): void
    {
        session_destroy();
    }

    public function validateUser(UserModel $user, string $password, bool $isNew): void
    {

        if (!filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid email format.");
        }

        if ($isNew && $this->emailExists($user->getEmail())) {
            throw new InvalidArgumentException("Email is already in use.");
        }

        if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $user->getUserName())) {
            throw new InvalidArgumentException("Username must be 3-20 alphanumeric characters.");
        }

        if ($isNew) {
            if (strlen($password) < 8 || !preg_match('/[0-9]/', $password)) {
                throw new InvalidArgumentException("Password must be at least 8 characters and include a number.");
            }
        }

        $allowedRoles = ['Admin', 'User', 'Editor'];
        if (!in_array($user->getRole(), $allowedRoles)) {
            throw new InvalidArgumentException("Invalid role assigned.");
        }
    }

    
    
}
