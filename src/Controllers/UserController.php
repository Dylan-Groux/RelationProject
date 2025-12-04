<?php

namespace App\Controllers;

use App\Models\Repository\UserRepository;
use App\Views\View;
use App\Library\Router;
use App\Controllers\Abstract\AbstractController;

class UserController extends AbstractController
{
    /**
     * Affiche les détails d'un utilisateur par son ID.
     * @param int $id
     */
    #[Router('/user/{id}', 'GET')]
    public function showPublicUser(int $id): void
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
    public function showUserAccount(string $id): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Redirige si l'ID n'est pas numérique ou ne correspond pas à la session
        if (!isset($_SESSION['user_id']) || !is_numeric($id) || $_SESSION['user_id'] != $id) {
            header('Location: /Openclassroom/RELATION/public/user/account/' . $_SESSION['user_id']);
            exit;
        }
        $userRepository = new UserRepository();
        $user = $userRepository->getUserById($id);
        if ($user === null) {
            // Redirige aussi si l'utilisateur n'existe pas
            header('Location: /Openclassroom/RELATION/public/user/account/' . $_SESSION['user_id']);
            exit;
        }

        $userData = $userRepository->getUserWithBooksById($id);

        $view = new View('user_account');
        $view->render(['user' => $user, 'userData' => $userData]);
    }

    /**
     * Gère la modification des informations utilisateur.
     * @param int $id
     */
    #[Router('/user/update/{id}', 'POST')]
    public function updateUser(int $id): void
    {
        $userRepository = new UserRepository();
        $user = $userRepository->getUserById($id);
        if ($user === null) {
            http_response_code(404);
            echo 'Utilisateur non trouvé';
            return;
        }

        $sanitizedData = \App\Services\UserUpdateService::sanitizeUserObjectInput(
            $_POST['nickname'] ?? '',
            $_POST['mail'] ?? '',
            $_POST['password'] ?? null,
            (int)$id
        );

        if (empty($sanitizedData)) {
            http_response_code(400);
            echo 'Données invalides';
            return;
        }

        $userRepository->updateUser($sanitizedData);
        header('Location: /Openclassroom/RELATION/public/user/account/' . $id);
    }
}