<?php

namespace App\Models\Services;

use App\Models\Repository\BookRepository;
use App\Models\Repository\UserRepository;
use App\Models\Database\DBManager;
use PDO;

Class PictureService
{

    private \PDO $pdo;
    private BookRepository $bookRepository;
    private UserRepository $userRepository;
    
    private $fileMover;
    private $nowProvider;
    private string $uploadDir;
    private $unlink;
    private $isDir;
    private $mkdir;
    private $fileExists;

    public function __construct(
        ?\PDO $pdo = null,
        ?callable $fileMover = null,
        ?callable $nowProvider = null,
        ?string $uploadDir = '',
        ?callable $unlink = null,
        ?callable $isDir = null,
        ?callable $mkdir = null,
        ?callable $fileExists = null,
        ?BookRepository $bookRepository = null,
        ?UserRepository $userRepository = null
    )
    {
        $this->pdo = $pdo ?? DBManager::getInstance()->getPdo();
        $this->fileMover = $fileMover ?? "move_uploaded_file";
        $this->nowProvider = $nowProvider ?? fn(): int=> time();
        $this->uploadDir = dirname(__DIR__, 3) . '/public/uploads/';
        $this->unlink = $unlink ?? "unlink";
        $this->isDir = $isDir ?? "is_dir";
        $this->mkdir = $mkdir ?? "mkdir";
        $this->fileExists = $fileExists ?? "file_exists";
        $this->bookRepository = $bookRepository ?? new BookRepository($this->pdo);
        $this->userRepository = $userRepository ?? new UserRepository($this->pdo);
    }

    /**
     *  Fonction utilitaire pour supprimer l'ancienne image d'un livre ou d'un utilisateur avant d'en uploader une nouvelle.
     * @param int $bookId
     * @param int $userId
     */
    public function deleteTheLastPicture(?int $bookId, ?int $userId): void
    {
        if (isset($bookId)) {
            $book = $this->bookRepository->getBookById($bookId);
            if ($book && $book->getPicture()) {
                $oldPicture = $book->getPicture();
                // On ne supprime pas l'image par défaut (on vérifie que c'est bien dans /uploads/books/)
                if (strpos($oldPicture, '/uploads/books/') !== false) {
                    $oldPath = dirname(__DIR__, 3) . '/public' . strstr($oldPicture, '/uploads/books/');
                    if (($this->fileExists)($oldPath)) {
                        ($this->unlink)($oldPath);
                    }
                }
            }
        } elseif (isset($userId)) {
            $user = $this->userRepository->getUserById($userId);
            if ($user && $user->getPicture()) {
                $oldPicture = $user->getPicture();
                // On ne supprime pas l'image par défaut (on vérifie que c'est bien dans /uploads/users/)
                if (strpos($oldPicture, '/uploads/users/') !== false) {
                    $oldPath = dirname(__DIR__, 3) . '/public' . strstr($oldPicture, '/uploads/users/');
                    if (($this->fileExists)($oldPath)) {
                        ($this->unlink)($oldPath);
                    }
                }
            }
        } else {
            throw new \InvalidArgumentException("Either bookId or userId must be provided");
        }
    }

    /**
     * Ajoute ou met à jour l'image de profil d'un livre ou d'un utilisateur.
     * @param int $bookId
     * @param int $userId
     * @param array $file $_FILES['picture']
     * @return bool|string Retourne le nom du fichier en cas de succès, false sinon
     */
    public function uploadNewPicture(?int $bookId, ?int $userId, array $file)
    {
        if (
            !isset($file['tmp_name'], $file['name'], $file['type'], $file['size']) ||
            $file['error'] !== UPLOAD_ERR_OK
        ) {
            return false;
        }

        $allowedTypes = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
        if (!array_key_exists($file['type'], $allowedTypes)) {
            return false;
        }

        $extension = $allowedTypes[$file['type']];
        if (isset($bookId)) {

            $filename = 'book_' . $bookId . '_' . ($this->nowProvider)() . '.' . $extension;
            $uploadDir = $this->uploadDir . 'books/';
            $relativePath = '/uploads/books/' . $filename;
            if (!($this->isDir)($uploadDir)) {
                ($this->mkdir)($uploadDir, 0777, true);
            }
            $destination = $uploadDir . $filename;

            if (!($this->fileMover)($file['tmp_name'], $destination)) {
                return false;
            }
            $this->deleteTheLastPicture($bookId, null);

            $sql = "UPDATE book SET picture = :picture, updated_at = NOW() WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':picture', $relativePath, PDO::PARAM_STR);
            $stmt->bindValue(':id', $bookId, PDO::PARAM_INT);

        } elseif (isset($userId)) {

            $filename = 'user_' . $userId . '_' . ($this->nowProvider)() . '.' . $extension;
            $uploadDir = $this->uploadDir . 'users/';
            $relativePath = '/uploads/users/' . $filename;
            if (!($this->isDir)($uploadDir)) {
                ($this->mkdir)($uploadDir, 0777, true);
            }
            $destination = $uploadDir . $filename;

            if (!($this->fileMover)($file['tmp_name'], $destination)) {
                return false;
            }

            $this->deleteTheLastPicture(null, $userId);

            $sql = "UPDATE user SET picture = :picture, updated_at = NOW() WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':picture', $relativePath, PDO::PARAM_STR);
            $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        } else {
            throw new \InvalidArgumentException("Either bookId or userId must be provided");
        }

        if ($stmt->execute()) {
            return $filename;
        }
        return false;
    }
}