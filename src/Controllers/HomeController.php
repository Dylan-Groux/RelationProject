<?php

namespace App\Controllers;

use App\Models\Repository\BookRepository;
use App\Views\View;

class HomeController
{
    public function index(): void
    {
        $bookRepository = new BookRepository();
        $books = $bookRepository->getAllBooks();

        $view = new View('home');
        $view->render(['books' => $books]);
    }
}
