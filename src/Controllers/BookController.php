<?php

namespace App\Controllers;

use App\Models\Repository\BookRepository;
use App\Views\View;

class BookController
{
    public function index(): void
    {
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 16;
        $offset = ($page - 1) * $limit;

        $totalPages = ceil(count((new BookRepository())->getAllBooks()) / $limit);

        $bookRepository = new BookRepository();
        $books = $bookRepository->getBooksPaginated($limit, $offset);

        $view = new View('books');
        $view->render(['books' => $books, 'totalPages' => $totalPages]);
    }
}
