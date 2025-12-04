<?php

namespace App\Models\Repository;

use PDO;
use App\Models\Database\DBManager;

class LoginRepository
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? DBManager::getInstance()->getPdo();
    }

    /**
     * Authentifie un utilisateur avec son email et mot de passe.
     * @param string $email
     * @param string $password
     * @return array|null
     */
    public function loginUser(string $mail, string $password): ?array
    {
        $sql = "SELECT * FROM user WHERE mail = :mail";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData && password_verify($password, $userData['password'])) {
            return $userData;
        } else {
            return null;
        }
    }
    
}
