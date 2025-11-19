<?php

namespace App\Controllers;

use App\Models\Repository\BookRepository;
use App\Views\View;
use App\Library\Router;

class HomeController
{
    /**
     * Affiche la page d'accueil de présentation.
     * @return void
     */
    #[Router('/', 'GET')]
    public function index(): void
    {
        $bookRepository = new BookRepository();
        $books = $bookRepository->getAllBooks();

        $view = new View('home');
        $view->render(['books' => $books]);
    }
}
