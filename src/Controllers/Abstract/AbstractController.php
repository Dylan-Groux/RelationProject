<?php

namespace App\Controllers\Abstract;

use App\Controllers\Exception\LoginException;

abstract class AbstractController
{
    /**
     * Vérifie si l'utilisateur est authentifié.
     * @return bool true si l'utilisateur est authentifié, sinon redirige vers la page de login.
     */
    public function checkUserAuthenticated(): bool
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            return false;
        }
        return true;
    }
}
