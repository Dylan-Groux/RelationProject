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
        $userRepository = new \App\Models\Repository\UserRepository();
        $user = $userRepository->getUserById($book->getUserId());

        if ($book === null) {
            http_response_code(404);
            echo 'Livre non trouvé';
            return;
        }

        $view = new View('book');
        $view->render(['book' => $book, 'userPicture' => $user->getPicture(), 'userNickname' => $user->getNickname()]);
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

        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        if ($book === null) {
            http_response_code(404);
            echo 'Livre non trouvé';
            return;
        }

        $this->checkUserAccess($book->getUserId());

        $view = new View('edit-book');
        $view->render([
            'book' => $book,
            'csrfToken' => $_SESSION['csrf_token']
        ]);
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

        try {
            $this->validateCSRFToken($_POST['CSRF_token'] ?? '');
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
                header('Location: /public/book/' . $id);
                exit;
            } else {
                http_response_code(500);
                echo 'Erreur lors de la mise à jour du livre';
            }
        } catch (\Exception $e) {
            http_response_code(403);
            echo 'CSRF token invalide.';
            return;
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
        $this->checkUserAccess($bookRepository->getBookById($id)->getUserId());
        $success = $bookRepository->deleteBook($id);

        if ($success) {
            header('Location: /public/books');
            exit;
        } else {
            http_response_code(500);
            echo 'Erreur lors de la suppression du livre';
        }
    }

    /**
    * Traite la création d'un nouveau livre.
    * @return void
    */
    #[Router('/book/create/book', 'POST')]
    public function createBook(): void
    {
        try {
            $this->validateCSRFToken($_POST['csrf_token'] ?? '');

            // Gestion de l'upload de l'image
            $picturePath = null;
            if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../public/uploads/';
                $fileName = uniqid() . '_' . basename($_FILES['picture']['name']);
                $targetPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['picture']['tmp_name'], $targetPath)) {
                    $picturePath = '/uploads/' . $fileName;
                }
            }

            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'picture' => $picturePath ?? '',
                'author' => trim($_POST['author'] ?? ''),
                'availability' => isset($_POST['availability']) ? intval($_POST['availability']) : 1,
                'comment' => trim($_POST['comment'] ?? ''),
                'user_id' => $_SESSION['user_id'] ?? null
            ];

            // Validation
            if (empty($data['title']) || empty($data['author']) || $data['user_id'] === null) {
                header('Location: /public/user/account/' . htmlspecialchars((string)$data['user_id']));
                exit;
            }

            // Créer l'objet Book
            $book = new \App\Models\Entity\Book($data);

            $bookRepository = new BookRepository();
            $bookId = $bookRepository->createBook($book);

            if ($bookId) {
                header('Location: /public/book/' . $bookId);
                exit;
            } else {
                header('Location: /public/user/account/' . htmlspecialchars((string)$data['user_id']));
                exit;
            }
        } catch (\Exception $e) {
            error_log('Erreur création livre: ' . $e->getMessage());
            header('Location: /public/user/account/' . htmlspecialchars((string)($_SESSION['user_id'] ?? '')));
            exit;
        }
    }
}
