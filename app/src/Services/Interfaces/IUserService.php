<?php 

namespace App\Services\Interfaces;

use App\Models\UserModel;

interface IUserService
{
    public function getAllUsers(): array;
    public function getUserById(int $id): ?UserModel;
    public function createUser(UserModel $user, string $password, ?array $file): void;
    public function updateUser(UserModel $user, ?array $file = null): void;
    public function deleteUser(int $id): bool;
    public function restoreUser(int $id): bool;
    public function adminGetAll(): array;
    public function updateProfile(UserModel $user, array $data, ?array $imageFile): void;
    public function getFilteredUsers(string $search = '', string $role = '', string $sort = ''): array;
}