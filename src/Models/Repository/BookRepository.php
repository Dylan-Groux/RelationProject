<?php
declare(strict_types=1);

namespace App\Models\Repository;

use App\Models\Database\DBManager;
use App\Models\Entity\Book;

/**
 * Class BookRepository
 * Gère les opérations de base de données pour les entités Book.
 */
class BookRepository
{
    private \PDO $pdo;

    public function __construct(?\PDO $pdo = null)
    {
        $this->pdo = $pdo ?? DBManager::getInstance()->getPdo();
    }

    /**
     * * Récupère tous les livres.
     * * @return Book[] array liste des livres
     */
    public function getAllBooks(): array
    {
        $sql = "SELECT * FROM book";
        $stmt = $this->pdo->query($sql);
        $booksData = [];

        while ($book = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            if ($book) {
            $booksData[] = new Book($book);
            }
        }

        return $booksData;
    }

    /**
     * Récupère un livre par son ID.
     * @param int $id
     * @return Book|null
     */
    public function getBookById(int $id): ?Book
    {
        $sql = "SELECT * FROM book WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        $bookData = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($bookData) {
            return new Book($bookData);
        } else {
            return null;
        }
    }

    /**
     * Récupère tous les livres d'un utilisateur par son ID.
     * @param int $userId
     * @return Book[] array liste des livres de l'utilisateur
     */
    public function getAllBooksByUserId(int $userId): array
    {
        $sql = "SELECT * FROM book WHERE user_id = :userId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':userId', $userId, \PDO::PARAM_INT);
        $stmt->execute();

        $booksData = [];
        while ($book = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $booksData[] = new Book($book);
        }

        return $booksData;
    }

    /**
     * Supprime un livre par son ID.
     * @param int $id
     * @return bool
     */
    public function deleteBook(int $id): bool
    {
        if (!is_int($id) || $id <= 0) {
            return false;
        }
        $sql = "DELETE FROM book WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Update d'une entité en ignorant les champs null.
     * @param Book $book
     * @return bool
     * @throws \InvalidArgumentException si l'entité n'a pas d'ID défini.
     */
    public function updateBook(array $data): bool
    {
        if (empty($data['id'])) {
            throw new \InvalidArgumentException("Impossible d'update : l'entité n'a pas d'ID.");
        }

        // Mapping des champs → getters
        $fields = [
            'title' => $data['title'] ?? null,
            'picture' => $data['picture'] ?? null,
            'author' => $data['author'] ?? null,
            'availability' => $data['availability'] ?? null,
            'comment' => $data['comment'] ?? null,
            'user_id' => $data['user_id'] ?? null,
            'id' => $data['id']
        ];

        // On filtre tout champ null : on NE L’UPDATE PAS
        $fields = array_filter(
            $fields,
            fn ($value) => $value !== null
        );

        if (empty($fields)) {
            // Rien à mettre à jour
            return false;
        }

        // Construction dynamique des SET field = :field
        $setParts = [];
        foreach ($fields as $column => $_) {
            $setParts[] = "$column = :$column";
        }
        $setClause = implode(", ", $setParts);

        // On met aussi à jour le updated_at
        $setClause .= ", updated_at = :updated_at";

        $sql = "
            UPDATE book
            SET $setClause
            WHERE id = :id
        ";

        try {
            $stmt = $this->pdo->prepare($sql);

            foreach ($fields as $column => $value) {
                $stmt->bindValue(":$column", $value);
            }

            $stmt->bindValue(':updated_at', (new \DateTimeImmutable())->format('Y-m-d H:i:s'));
            $stmt->bindValue(':id', $data['id'], \PDO::PARAM_INT);

            return $stmt->execute();

        } catch (\PDOException $e) {
            // Logger ici si besoin
            return false;
        }
    }

    /**
     * Récupère les derniers livres ajoutés.
     * @param int $limit
     * @return Book[] array liste des derniers livres ajoutés
     */
    public function getLastBooks(int $limit = 4): array
    {
        $sql = "SELECT * FROM book ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();

        $booksData = [];
        while ($book = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $booksData[] = new Book($book);
        }

        return $booksData;
    }

    /**
     * Récupère les livres paginés.
     * @param int $limit
     * @param int $offset
     * @return Book[] array liste des livres paginés
     */
    public function getBooksPaginated(int $limit, int $offset): array
    {
        $sql = "SELECT * FROM book ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        $booksData = [];

        while ($book = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $booksData[] = new Book($book);
        }

        return $booksData;
    }

    /**
     * Recherche de livres par titre avec une correspondance exacte.
     * @param string $title
     * @return Book[] array liste des livres trouvés
     */
    public function searchBookByTitle(string $title): array
    {
        $sql = "SELECT * FROM book WHERE title LIKE :title";
        $stmt = $this->pdo->prepare($sql);
        $likeTitle = '%' . $title . '%';
        $stmt->bindValue(':title', $likeTitle, \PDO::PARAM_STR);
        $stmt->execute();

        $booksData = [];

        while ($book = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $booksData[] = new Book($book);
        }

        return $booksData;
    }

    /**
     * Recherche de livres par titre avec une correspondance exacte et pagination.
     * @param string $title
     * @param int $limit
     * @param int $offset
     * @return Book[] array liste des livres trouvés
     */
    public function searchBookByTitlePaginated(string $title, int $limit, int $offset): array
    {
        $sql = "SELECT * FROM book WHERE title LIKE :title ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $likeTitle = '%' . $title . '%';
        $stmt->bindValue(':title', $likeTitle, \PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        $booksData = [];
        while ($book = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $booksData[] = new Book($book);
        }
        return $booksData;
    }

    /**
     * Recherche de livres par titre avec une correspondance fuzzy.
     * @param string $title
     * @param int $maxDistance
     * @return Book[] array liste des livres trouvés
     */
    public function searchBookByTitleFuzzy(string $title, int $maxDistance = 4): array
    {
        $allBooks = $this->getAllBooks();
        $results = [];
        foreach ($allBooks as $book) {
            $distance = levenshtein(mb_strtolower($title), mb_strtolower($book->getTitle()));
            if ($distance <= $maxDistance) {
                $results[] = $book;
            }
        }
        return $results;
    }
}
