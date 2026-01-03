<?php

namespace App\Models\Repository;

use App\Models\Database\DBManager;
use PDO;

class MessageRepository
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? DBManager::getInstance()->getPdo();
    }

    public function getAllMessagesForUser(int $userId): array
    {
        $sql = "SELECT DISTINCT relation_id FROM message WHERE sender_id = :userId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'relation_id');
    }

    public function getMessagesByRelationId(int $relationId, int $limit = 20, int $offset = 0): array
    {
        $sql = "SELECT m.*, u.nickname, u.picture, DATE_FORMAT(m.sent_at, '%H:%i') AS formatted_time
                FROM message m
                JOIN user u ON m.sender_id = u.id
                WHERE m.relation_id = :relationId
                ORDER BY m.sent_at DESC, m.id DESC
                LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':relationId', $relationId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_reverse($messages);
    }

    public function getSenderAndReceiverByRelationId(int $relationId): ?array
    {
        $sql = "SELECT r.first_user, r.second_user
                FROM relation r
                WHERE r.id = :relationId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':relationId', $relationId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function getAllRelationWithUserId(int $userId): array
    {
        $sql = "SELECT r.*
                FROM relation r
                WHERE r.first_user = :userId
                ORDER BY r.created_at ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère toutes les relations d'un utilisateur avec les informations de l'autre utilisateur.
     * @param int $userId
     */
    public function getAllRelationWithUserInfos(int $userId): array
    {
        $sql = "SELECT r.*, u.picture, u.nickname
                FROM relation r
                JOIN user u ON u.id = CASE
                    WHEN r.first_user = :userId THEN r.second_user
                    ELSE r.first_user
                END
                WHERE r.first_user = :userId OR r.second_user = :userId
                ORDER BY r.created_at ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Ajouter un UserId pour vérifier que l'utilisateur fait partie de la conversation
    public function getConversationInformations(int $relationId): ?array
    {
        $sql = "SELECT
            r.id AS relation_id,
            u1.id AS user1_id,
            u1.nickname AS user1_nickname,
            u1.picture AS user1_picture,
            u2.id AS user2_id,
            u2.nickname AS user2_nickname,
            u2.picture AS user2_picture
        FROM relation r
        JOIN user u1 ON u1.id = r.first_user
        JOIN user u2 ON u2.id = r.second_user
        WHERE r.id = :relationId";
        $pdo = $this->pdo;
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':relationId', $relationId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result === false) {
            return null;
        }
        return $result;
    }
}
