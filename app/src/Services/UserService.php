<?php

namespace App\Services;

use App\Models\UserModel;
use App\Repositories\Interfaces\IUserRepository;
use App\Services\Interfaces\IUserService;
use App\Services\Interfaces\IAuthService;
use Exception;
use InvalidArgumentException;


class UserService implements IUserService
{
    private IUserRepository $userRepository;
    private IAuthService $authService;    

    public function __construct(IUserRepository $userRepository, IAuthService $authService)
    {
        $this->userRepository = $userRepository;
        $this->authService = $authService;
    }

    public function getAllUsers(): array
    {
        return $this->userRepository->getAll();
    }

    public function getUserById(int $id): ?UserModel
    {
        return $this->userRepository->getById($id);
    }

    public function createUser(UserModel $user, string $password, ?array $file): void
    {
        $this->authService->validateUser($user, $password, true);

        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $imgName = $this->handleSecureUpload($file, 'user'); 
            
            if (!$imgName) {
                throw new InvalidArgumentException("Invalid image format. Only JPG, PNG, and WebP are allowed.");
            }
            
            $user->setProfilePicture('/assets/uploads/users/' . $imgName);
        }

        $user->setPassword(password_hash($password, PASSWORD_DEFAULT));

        if (!$this->userRepository->create($user)) {
            throw new Exception("Database error: Could not create user.");
        }
    }

    public function updateUser(UserModel $user, ?array $file = null): void
    {
        $this->authService->validateUser($user, '', false);

        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $imgName = $this->handleSecureUpload($file, 'user');
            if ($imgName) {
                $user->setProfilePicture('/assets/uploads/users/' . $imgName);
            }
        }

        if (!$this->userRepository->update($user)) {
            throw new Exception("Failed to update user in the database.");
        }
    }

    public function updateProfile(UserModel $user, array $data, ?array $imageFile): void
    {
        $user->setEmail(strtolower(trim($data['email'] ?? $user->getEmail())));
        $user->setUserName(trim($data['userName'] ?? $user->getUserName()));
        $user->setFullName(trim($data['fullName'] ?? $user->getFullName()));
        $user->setPhoneNumber(trim($data['phoneNumber'] ?? $user->getPhoneNumber()));

        $this->authService->validateUser($user, '', false);

        // checking if the email changed, and if so, if it's taken by someone else
        if (strtolower($data['email']) !== strtolower($user->getEmail()) && 
            $this->authService->emailExists($data['email'])) {
            throw new InvalidArgumentException('Email already exists!');
        }

        if (!empty($data['newPassword'])) {
            if (strlen($data['newPassword']) < 8 || !preg_match('/[0-9]/', $data['newPassword'])) {
                throw new InvalidArgumentException("Password must be at least 8 characters and include a number.");
            }
            $user->setPassword(password_hash($data['newPassword'], PASSWORD_DEFAULT));
        }

        if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
            $fileName = $this->handleSecureUpload($imageFile, 'user');
            if ($fileName) {
                $user->setProfilePicture('/assets/uploads/users/' . $fileName);
            }
        }
        
        if (!$this->userRepository->update($user)) {
            throw new Exception('Failed to update profile in database.');
        }
    }

    public function deleteUser(int $id): bool
    {
        return $this->userRepository->delete($id);
    }

    public function restoreUser(int $id): bool
    {
        return $this->userRepository->restore($id);
    }



    public function adminGetAll(): array
    {
        return $this->userRepository->adminGetAll();
    }
    public function updateOwnProfile(UserModel $user): bool
    {
        return $this->userRepository->updateProfile($user);
    }

    public function getFilteredUsers(string $search = '', string $role = '', string $sort = ''): array
    {
        return $this->userRepository->getAllFiltered($search, $role, $sort);
    }

    private function handleSecureUpload(array $file, string $prefix): ?string
    {
        // validating MIME type (actual file content, not just extension)
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];

        if (!in_array($mimeType, $allowed)) return null;

        $uploadDir = __DIR__ . '/../../public/assets/uploads/users/';
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newName = uniqid($prefix . '_', true) . '.' . $ext;

        return move_uploaded_file($file['tmp_name'], $uploadDir . $newName) ? $newName : null;
    }
}
