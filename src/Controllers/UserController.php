<?php

namespace App\Controllers;

use App\Models\Repository\UserRepository;
use App\Views\View;
use App\Library\Router;

class UserController
{
    /**
     * Affiche les détails d'un utilisateur par son ID.
     * @param int $id
     */
    #[Router('/user/{id}', 'GET')]
    public function showUser(int $id): void
    {
        $userRepository = new UserRepository();
        $user = $userRepository->getUserById($id);
        if ($user === null) {
            http_response_code(404);
            echo 'Utilisateur non trouvé';
            return;
        }
        $view = new View('user');
        $view->render(['user' => $user]);
    }

    /**
     * Affiche les détails de l'utilisateur connecté.
     */
    #[Router('/user/account/{id}', 'GET')]
    public function showUserAccount(int $id): void
    {
        $userRepository = new UserRepository();
        $user = $userRepository->getUserById($id);
        if ($user === null) {
            http_response_code(404);
            echo 'Utilisateur non trouvé';
            return;
        }
        $view = new View('user_account');
        $view->render(['user' => $user]);
    }
}