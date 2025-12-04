<?php

namespace App\Controllers;

use App\Models\Repository\UserRepository;
use App\Views\View;
use App\Library\Router;
use App\Controllers\Abstract\AbstractController;
use App\Models\Repository\LoginRepository;

class RegisterController extends AbstractController
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
     * Traite le formulaire d'inscription
     * @return void
     */
    #[Router('/register/userRegister', 'POST')]
    public function registerUser(): void
    {
        $mail = $_POST['mail'] ?? '';
        $password = $_POST['password'] ?? '';
        $nickname = $_POST['nickname'] ?? '';
        $name = $_POST['name'] ?? '';

        $userRepository = new UserRepository();
        $userRepository->createUser(['nickname' => $nickname, 'mail' => $mail, 'password' => password_hash($password, PASSWORD_BCRYPT), 'name' => $name]);

        header('Location: /Openclassroom/RELATION/public/login');
        exit;
    }

    /**
     * Affiche le formulaire de connexion
     */
    #[Router('/login', 'GET')]
    public function showLoginForm(): void
    {
        $view = new View('login');
        $view->render();
    }

    #[Router('/login/userLogin', 'POST')]
    public function loginUser(): void
    {
        $mail = $_POST['mail'] ?? '';
        $password = $_POST['password'] ?? '';

        $loginRepository = new LoginRepository();
        $user = $loginRepository->loginUser($mail, $password);

        if ($user) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            header('Location: /Openclassroom/RELATION/public/user/account/' . $user['id']);
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
        session_start();
        session_unset();
        session_destroy();
        header('Location: /Openclassroom/RELATION/public/');
        exit;
    }
}
