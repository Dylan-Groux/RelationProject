<?php

namespace App\Controllers\Abstract;

use App\Models\Exceptions\LoginException;

abstract class AbstractController
{

    public function __construct() {}

    public function validateCSRFToken(string $token): void
    {
        if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
            header('Location: /public/login');
            throw new LoginException("Invalid CSRF token");
        }
    }

    public function checkUserAccess(int $userId): bool
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] !== $userId) {
            throw new LoginException("User not authenticated or unauthorized");
        }

        return true;
    }

    public function requireCsrfToken(): void
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }
}
