<?php

namespace App\Controllers;

use App\Models\Repository\BookRepository;
use App\Views\View;
use App\Library\Router;
use App\Services\BooksPaginator;

class BookController
{
    #[Router('/books', 'GET')]
    public function showBooks(): void
    {
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 16;

        $bookPaginator = new BooksPaginator(new BookRepository());

        $result = $bookPaginator->paginate([], $page, $limit, $search);
        $books = $result['books'];
        $totalPages = $result['totalPages'];
        
        $view = new View('books');
        $view->render(['books' => $books, 'totalPages' => $totalPages]);
    }
}
