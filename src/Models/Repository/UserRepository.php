<?php
    
namespace App\Models\Repository;

use App\Models\Entity\User;
use App\Models\Database\DBManager;
use PDO;
use App\Models\Entity\Book;

class UserRepository
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? DBManager::getInstance()->getPdo();
    }

    /**
     * Récupère un utilisateur par son ID.
     * @param int $id
     * @return User|null
     */
    public function getUserById(int $id): ?User
    {
        $sql = "SELECT * FROM user WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        //PDO::FETCH_CLASS: retourne une nouvelle instance de la classe demandée. L'objet est initialisé en mappan
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($userData) {
            return new User($userData);
        } else {
            return null;
        }
    }

    /**
     * Récupère l'utilisateur connecté via la session.
     * @return User|null
     */
    public function getUserBySession(): ?User
    {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        $userId = $_SESSION['user_id'];
        return $this->getUserById($userId);
    }

    public function createUser(array $userData): bool
    {
        $sql = "INSERT INTO user (nickname, mail, password, name, created_at, updated_at) VALUES (:nickname, :mail, :password, :name, NOW(), NOW())";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':nickname', $userData['nickname'], PDO::PARAM_STR);
        $stmt->bindParam(':mail', $userData['mail'], PDO::PARAM_STR);
        $stmt->bindParam(':password', $userData['password'], PDO::PARAM_STR);
        $stmt->bindParam(':name', $userData['name'], PDO::PARAM_STR);
        return $stmt->execute();
    }

    /**
     * Récupère un utilisateur et ses livres par son ID.
     * @param int $id
     * @return array|null
     */
    public function getUserWithBooksById(int $id): ?array
    {
        // Récupère les infos de l'utilisateur
        $sqlUser = "SELECT * FROM user WHERE id = :id";
        $stmtUser = $this->pdo->prepare($sqlUser);
        $stmtUser->bindParam(':id', $id, PDO::PARAM_INT);
        $stmtUser->execute();
        $userData = $stmtUser->fetch(PDO::FETCH_ASSOC);

        if (!$userData) {
            return null;
        }

        // Récupère les livres de l'utilisateur
        $sqlBooks = "SELECT * FROM book WHERE user_id = :id";
        $stmtBooks = $this->pdo->prepare($sqlBooks);
        $stmtBooks->bindParam(':id', $id, PDO::PARAM_INT);
        $stmtBooks->execute();
        $booksData = $stmtBooks->fetchAll(PDO::FETCH_ASSOC);
        $books = [];
        foreach ($booksData as $bookData) {
            $books[] = new Book($bookData);
        }

        return [
            'user' => new User($userData),
            'books' => $books
        ];
    }

    /**
     * Met à jour les informations d'un utilisateur.
     * @param array $userData
     * @return bool
     */
    public function updateUser(array $userData): bool
    {

        $user = $this->getUserById($userData['id']);
        if ($user === null) {
            return false;
        }

        $fields = [];
        $params = [];
        if (isset($userData['nickname'])) {
            $fields[] = 'nickname = :nickname';
            $params[':nickname'] = $userData['nickname'];
        }
        if (isset($userData['mail'])) {
            $fields[] = 'mail = :mail';
            $params[':mail'] = $userData['mail'];
        }
        if (isset($userData['password'])) {
            $fields[] = 'password = :password';
            $params[':password'] = $userData['password'];
        }
        if (empty($fields)) {
            return false; // rien à mettre à jour
        }
        $fields[] = 'updated_at = NOW()';
        $sql = 'UPDATE user SET ' . implode(', ', $fields) . ' WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $userData['id'], PDO::PARAM_INT);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }
        return $stmt->execute();
    }

    /**
     * Ajoute ou met à jour l'image de profil d'un utilisateur.
     * @param int $userId
     * @param array $file $_FILES['picture']
     * @return bool|string Retourne le nom du fichier en cas de succès, false sinon
     */
    public function uploadUserPicture(int $userId, array $file)
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

        // Supprimer l'ancienne photo si elle existe (et n'est pas l'avatar par défaut)
        $user = $this->getUserById($userId);
        if ($user && $user->getPicture()) {
            $oldPicture = $user->getPicture();
            // On ne supprime pas l'image par défaut (on vérifie que c'est bien dans /uploads/)
            if (strpos($oldPicture, '/uploads/') !== false) {
                $oldPath = dirname(__DIR__, 3) . '/public' . strstr($oldPicture, '/uploads/');
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
        }

        $extension = $allowedTypes[$file['type']];
        $filename = 'user_' . $userId . '_' . time() . '.' . $extension;
        $uploadDir = dirname(__DIR__, 3) . '/public/uploads/';
        $relativePath = '/Openclassroom/RELATION/public/uploads/' . $filename;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $destination = $uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return false;
        }

        $sql = "UPDATE user SET picture = :picture, updated_at = NOW() WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':picture', $relativePath, PDO::PARAM_STR);
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return $filename;
        }
        return false;
    }
}