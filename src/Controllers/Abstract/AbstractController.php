<?php

namespace App\Controllers\Abstract;

use App\Models\Exceptions\LoginException;

abstract class AbstractController
{

    public function __construct() {}

    public function validateCSRFToken(string $token): void
    {
        if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
            header('Location: /public/');
            exit();
        }
    }

    public function checkUserAccess(int $userId): bool
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] !== $userId) {
            header('Location: /public/');
            exit();
        }

        return true;
    }
}
