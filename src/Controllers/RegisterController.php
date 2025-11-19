<?php

namespace App\Controllers;

use App\Models\Repository\UserRepository;
use App\Views\View;
use App\Library\Router;

class RegisterController
{
    /**
     * Affiche le formulaire d'inscription
     */
    #[Router('/register', 'GET')]
    public function showRegisterForm(): void
    {
        $view = new View('register');
        $view->render();
    }

    /**
     * Affiche le formulaire de connexion
     */
    #[Router('/login', 'GET')]
    public function showLoginForm(): void
    {
        require __DIR__ . '/../Views/login.php';
    }

    /**
     * Déconnecte l'utilisateur et redirige vers la page d'accueil
     */
    #[Router('/logout', 'GET')]
    public function logout(): void
    {
        session_start();
        session_unset();
        session_destroy();
        header('Location: /Openclassroom/RELATION/public/');
        exit;
    }
}
