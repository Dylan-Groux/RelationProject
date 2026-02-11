<?php

namespace App\Controllers;

use App\Models\Repository\BookRepository;
use App\Views\View;
use App\Library\Router;
use App\Controllers\Abstract\AbstractController;

class HomeController extends AbstractController
{
    /**
     * Affiche la page d'accueil de présentation.
     * @return void
     */
    #[Router('/', 'GET')]
    public function index(): void
    {
        $bookRepository = new BookRepository();
        $books = $bookRepository->getLastBooksWithUser(4);

        $view = new View('home');
        $view->render(['books' => $books]);
    }
}
