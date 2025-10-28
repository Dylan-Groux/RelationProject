<?php

namespace App\Application;
use App\Domain\Entity\Book;
use App\Infrastructure\DBManager;
use PDO;

class BookManager
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
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

        while ($book = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $booksData[] = new Book($book);
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
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $bookData = $stmt->fetch(PDO::FETCH_ASSOC);
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
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $booksData = [];
        while ($book = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
        $sql = "DELETE FROM book WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Met à jour un livre.
     * @param Book $book
     * @return bool
     */
    public function updateBook(Book $book): bool
    {
        $sql = "UPDATE book SET title = :title, picture = :picture, author = :author, availability = :availability, comment = :comment, updated_at = NOW(), user_id = :user_id WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':title', $book->getTitle());
        $stmt->bindValue(':picture', $book->getPicture());
        $stmt->bindValue(':author', $book->getAuthor());
        $stmt->bindValue(':availability', $book->getAvailability(), PDO::PARAM_INT);
        $stmt->bindValue(':comment', $book->getComment());
        $stmt->bindValue(':user_id', $book->getUserId(), PDO::PARAM_INT);
        $stmt->bindValue(':id', $book->getId(), PDO::PARAM_INT);
        return $stmt->execute();
    }
}
