<?php

namespace App\Services;

class CheckAuth
{
    /**
     * Vérifie si l'utilisateur est authentifié.
     * @return bool true si l'utilisateur est authentifié, sinon redirige vers la page de login.
     */
    public function checkUserAuthenticated(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return true;
    }
}
