<?php
    
namespace App\Models\Repository;

use App\Models\Entity\User;
use App\Models\Database\DBManager;
use PDO;

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
        session_start();
        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        $userId = $_SESSION['user_id'];
        return $this->getUserById($userId);
    }
}