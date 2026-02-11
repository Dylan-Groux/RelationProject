<?php

namespace App\Controllers;

use App\Models\Repository\UserRepository;
use App\Views\View;
use App\Library\Router;
use App\Controllers\Abstract\AbstractController;
use App\Models\Repository\LoginRepository;
use App\Services\UserUpdateService;

class RegisterController extends AbstractController
{
    /**
     * Affiche le formulaire d'inscription
     */
    #[Router('/register', 'GET')]
    public function showRegisterForm(): void
    {
        $this->requireCsrfToken();
        $view = new View('register');
        $view->render();
    }

    /**
     * Traite le formulaire d'inscription
     * @return void
     */
    #[Router('/register/userRegister', 'POST')]
    public function registerUser(): void
    {
        if (isset($_POST['csrf_token'])) {
            $this->validateCSRFToken($_POST['csrf_token']);
        } else {
            header('Location: /public/register');
            exit;
        }
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $nickname = $_POST['nickname'] ?? '';
        $name = $_POST['name'] ?? '';

        $sanitized = UserUpdateService::sanitizeAuthInput($email, $password, $nickname, true, true);
        if (empty($sanitized)) {
            header('Location: /public/register');
            exit;
        }

        $userRepository = new UserRepository();
        $userRepository->createUser([
            'nickname' => $sanitized['nickname'],
            'email' => $sanitized['email'],
            'password' => $sanitized['password'],
            'name' => $name
        ]);

        header('Location: /public/login');
        exit;
    }

    /**
     * Affiche le formulaire de connexion
     */
    #[Router('/login', 'GET')]
    public function showLoginForm(): void
    {
        $this->requireCsrfToken();
        $view = new View('login');
        $view->render();
    }

    #[Router('/login/userLogin', 'POST')]
    public function loginUser(): void
    {

        $this->requireCsrfToken();

        if (isset($_POST['csrf_token'])) {
            $this->validateCSRFToken($_POST['csrf_token']);
        } else {
            header('Location: /public/login');
            exit;
        }
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $sanitized = UserUpdateService::sanitizeAuthInput($email, $password, null, false, false);
        if (empty($sanitized)) {
            header('Location: /public/login');
            exit;
        }

        $loginRepository = new LoginRepository();
        $user = $loginRepository->loginUser($sanitized['email'], $sanitized['password']);
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            header('Location: /public/user/account/' . $user['id']);
            exit;
        } else {
            $view = new View('home');
            $view->render();
        }
    }

    /**
     * Déconnecte l'utilisateur et redirige vers la page d'accueil
     */
    #[Router('/logout', 'GET')]
    public function logout(): void
    {
        session_unset();
        session_destroy();
        header('Location: /public/');
        exit;
    }
}
