<?php

namespace App\Controllers\Abstract;

use App\Models\Exceptions\LoginException;

abstract class AbstractController
{

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function validateCSRFToken(string $token): void
    {
        if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
            throw new LoginException('Invalid CSRF token.');
        }
    }
}
