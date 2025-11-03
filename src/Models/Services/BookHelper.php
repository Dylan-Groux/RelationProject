<?php

namespace App\Models\Services;

use App\Models\Entity\Book;
use App\Models\Database\DBManager;
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
