<?php

namespace App\Controllers;

use App\Models\Repository\BookRepository;
use App\Views\View;
use App\Library\Router;
use App\Services\BooksPaginator;
use App\Controllers\Abstract\AbstractController;

class BookController extends AbstractController
{
    /**
     * Affiche la liste des livres avec pagination et recherche.
     * @return void
     */
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

    /**
     * Affiche les détails d'un livre spécifique.
     * @param int $id
     * @return void
     */
    #[Router('/book/{id}', 'GET')]
    public function showBookDetail(int $id): void
    {
        $bookRepository = new BookRepository();
        $book = $bookRepository->getBookById($id);

        if ($book === null) {
            http_response_code(404);
            echo 'Livre non trouvé';
            return;
        }

        $view = new View('book');
        $view->render(['book' => $book]);
    }

    /**Affiche l'édition d'un livre spécifique 
     * @param int $id
     * @return void
     */
    #[Router('/book/edit/{id}', 'GET')]
    public function editBook(int $id): void
    {
        $bookRepository = new BookRepository();
        $book = $bookRepository->getBookById($id);

        if ($book === null) {
            http_response_code(404);
            echo 'Livre non trouvé';
            return;
        }

        $view = new View('edit-book');
        $view->render(['book' => $book]);
    }

    /**
     * Met à jour un livre spécifique.
     * @param int $id
     * @return void
     */
    #[Router('/book/update/{id}', 'POST')]
    public function updateBook(int $id): void
    {
        $bookRepository = new BookRepository();

        $data = [
            'title' => $_POST['title'] ?? null,
            'picture' => $_POST['picture'] ?? null,
            'author' => $_POST['author'] ?? null,
            'availability' => isset($_POST['availability']) ? intval($_POST['availability']) : null,
            'comment' => $_POST['comment'] ?? null,
            'user_id' => isset($_POST['user_id']) ? intval($_POST['user_id']) : null,
            'id' => $id
        ];

        if ($data['id'] === null) {
            http_response_code(404);
            echo 'Livre non trouvé';
            return;
        }
        
        $book = $bookRepository->updateBook($data);

        if ($book) {
            header('Location: /Openclassroom/RELATION/public/book/' . $id);
            exit;
        } else {
            http_response_code(500);
            echo 'Erreur lors de la mise à jour du livre';
        }
    }

    /**
     * Supprime un livre spécifique.
     * @param int $id
     * @return void
     */
    #[Router('/book/delete/{id}', 'GET')]
    public function deleteBook(int $id): void
    {
        $bookRepository = new BookRepository();
        $success = $bookRepository->deleteBook($id);

        if ($success) {
            header('Location: /Openclassroom/RELATION/public/books');
            exit;
        } else {
            http_response_code(500);
            echo 'Erreur lors de la suppression du livre';
        }
    }
}
