<?php

namespace App\Controllers;

use App\Models\Repository\UserRepository;
use App\Views\View;
use App\Library\Router;
use App\Controllers\Abstract\AbstractController;
use App\Models\Services\PictureService;

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

        $this->requireCsrfToken();
        
        $bookRepository = new \App\Models\Repository\BookRepository();
        $userBooks = $bookRepository->getAllBooksByUserId($id);
        $bookCount = count($userBooks);
        
        $createdAt = $user->getCreatedAt();
        if (is_string($createdAt)) {
            $createdAt = new \DateTime($createdAt);
        }
        $now = new \DateTime();
        $interval = $createdAt->diff($now);
        
        if ($interval->y > 0) {
            $memberSince = $interval->y . ' an' . ($interval->y > 1 ? 's' : '');
        } elseif ($interval->m > 0) {
            $memberSince = $interval->m . ' mois';
        } elseif ($interval->d > 0) {
            $memberSince = $interval->d . ' jour' . ($interval->d > 1 ? 's' : '');
        } else {
            $memberSince = 'aujourd\'hui';
        }
        
        $view = new View('user');
        $view->render([
            'user' => $user,
            'userBooks' => $userBooks,
            'bookCount' => $bookCount,
            'memberSince' => $memberSince,
            'csrfToken' => $_SESSION['csrf_token']
        ]);
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
        $this->requireCsrfToken();
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
        $this->validateCSRFToken($_POST['csrf_token'] ?? '');
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
        $this->validateCSRFToken($_POST['csrf_token'] ?? '');
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

        $pictureService = new PictureService();
        $result = $pictureService->uploadNewPicture(null, $id, $_FILES['picture']);

        if ($result === false) {
            header('Location: /public/user/account/' . $id);
            exit;
        }

        // Redirection vers le compte utilisateur après succès
        header('Location: /public/user/account/' . $id);
        exit;
    }
}