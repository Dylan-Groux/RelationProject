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
            header('Location: /public');
            exit;
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
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Redirige si l'ID n'est pas numérique ou ne correspond pas à la session
        if (!isset($_SESSION['user_id']) || !is_numeric($id) || $_SESSION['user_id'] != $id) {
            header('Location: /public/user/account/' . $_SESSION['user_id']);
            exit;
        }
        $userRepository = new UserRepository();
        $user = $userRepository->getUserById($id);
        if ($user === null) {
            // Redirige aussi si l'utilisateur n'existe pas
            header('Location: /public/user/account/' . $_SESSION['user_id']);
            exit;
        }

        $userData = $userRepository->getUserWithBooksById($id);

        $view = new View('user_account');
        $view->render(['user' => $user, 'userData' => $userData, 'csrfToken' => $_SESSION['csrf_token']]);
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
            header('Location: /public');
            exit;
        }

        $sanitizedData = \App\Services\UserUpdateService::sanitizeUserObjectInput(
            $_POST['nickname'] ?? '',
            $_POST['email'] ?? '',
            $_POST['password'] ?? null,
            (int)$id
        );

        if (empty($sanitizedData)) {
            header('Location: /public/user/account/' . $id);
            exit;
        }

        $userRepository->updateUser($sanitizedData);
        header('Location: /public/user/account/' . $id);
    }

    /**
     * Met à jour l'image de profil de l'utilisateur.
     * @param int $id
     */
    #[Router('/user/picture/update/{id}', 'POST')]
    public function updateUserPicture(int $id): void
    {
        // Vérifie que l'utilisateur est connecté et que l'ID correspond
        if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != $id) {
            header('Location: /public/user/account/' . $id);
            exit;
        }

        $userRepository = new UserRepository();
        if (!isset($_FILES['picture'])) {
            header('Location: /public/user/account/' . $id);
            exit;
        }

        $result = $userRepository->uploadUserPicture($id, $_FILES['picture']);
        if ($result === false) {
            header('Location: /public/user/account/' . $id);
            exit;
        }

        // Redirection vers le compte utilisateur après succès
        header('Location: /public/user/account/' . $id);
        exit;
    }
}