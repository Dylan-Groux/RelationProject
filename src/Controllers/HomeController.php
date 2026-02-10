<?php

namespace App\Controllers;

use App\Models\Repository\BookRepository;
use App\Views\View;
use App\Library\Router;
use App\Services\CheckAuth;

class HomeController
{
    /**
     * Affiche la page d'accueil de présentation.
     * @return void
     */
    #[Router('/', 'GET')]
    public function index(): void
    {
        $checkAuth = new CheckAuth();
        if (!$checkAuth->checkUserAuthenticated()) {
            return;
        }

        $bookRepository = new BookRepository();
        $books = $bookRepository->getLastBooksWithUser(4);

        $view = new View('home');
        $view->render(['books' => $books]);
    }
}
