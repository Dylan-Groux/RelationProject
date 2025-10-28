<?php

namespace App\Application;

use App\Domain\Entity\Book;
use App\Infrastructure\DBManager;
use PDO;

class BookHelper
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? DBManager::getInstance()->getPdo();
    }

    /**
     * Affiche les informations de base d'un livre.
     * @param Book $book
     * @return string
     */
    public function showBasicBookInfo(Book $book): string
    {
        return sprintf(
            "Livre : %s par %s (ID: %d)",
            $book->getTitle(),
            $book->getAuthor(),
            $book->getId()
        );
    }
}