<?php

namespace App\Framework;

class Controller
{
    // all methods are protected because this allows the method to be accessed only by the class itself and any class that extends it

    protected function getCurrentUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    protected function isLoggedIn(): bool
    {
        return $this->getCurrentUser() !== null;
    }

    protected function requireRole(string $role): void
    {
        $user = $this->getCurrentUser();
        
        if (!$user || ($user['role'] ?? '') !== $role) {
            $_SESSION['error'] = "Access Denied: You do not have the $role role.";
            header('Location: /login');
            exit;
        }
    }

    protected function requireAdmin(): void
    {
        $this->requireRole('Admin');
    }

    protected function requireEmployee(): void
    {
        $this->requireRole('Employee');
    }
    
    protected function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }
}