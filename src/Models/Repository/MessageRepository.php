<?php

namespace App\Models\Repository;

use App\Models\Database\DBManager;
use App\Models\Entity\Message;
use PDO;

class MessageRepository
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? DBManager::getInstance()->getPdo();
    }

    /**
     * Récupère les messages d'une relation donnée.
     * @param int $relationId
     * @param int $limit
     * @param int $offset
     * @return Message[] Objet Message[] liée a la relation
     */
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
        $messages = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $messages[] = new Message(
                (int)$row['id'],
                (int)$row['relation_id'],
                (int)$row['sender_id'],
                $row['statut'],
                $row['content'],
                new \DateTimeImmutable($row['sent_at'])
            );
        }
        return array_reverse($messages);
    }

    /**
     * Récupère toutes les relations d'un utilisateur avec les informations de l'autre utilisateur.
     * @param int $userId
     */
    public function getAllRelationWithUserInfos(int $userId): array
    {
        $sql = "SELECT r.*, u.picture, u.nickname, MAX(m.sent_at) as last_message_date
                FROM relation r
                JOIN user u ON u.id = CASE
                    WHEN r.first_user = :userId THEN r.second_user
                    ELSE r.first_user
                END
                LEFT JOIN message m ON m.relation_id = r.id
                WHERE r.first_user = :userId OR r.second_user = :userId
                GROUP BY r.id, u.picture, u.nickname
                ORDER BY last_message_date DESC, r.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les informations d'une conversation spécifique et vérifie que l'utilisateur en fait partie.
     * @param int $relationId
     * @param int $userId
     * @return array|null liste des conversation ou null si non trouvée
     */
    public function getConversationInformations(int $relationId, int $userId): ?array
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
        WHERE r.id = :relationId AND (r.first_user = :userId OR r.second_user = :userId)";
        $pdo = $this->pdo;
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':relationId', $relationId, PDO::PARAM_INT);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result === false) {
            return null;
        }
        return $result;
    }

    /**
     * Envoie un message dans une relation donnée.
     * @param int $senderId
     * @param int $relationId
     * @param string $content
     * @return bool
     */
    public function sendMessage(int $senderId, int $relationId, string $content): bool
    {
        $sql = "INSERT INTO message (sender_id, relation_id, statut, content, sent_at)
                VALUES (:senderId, :relationId, :statut, :content, NOW())";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':senderId', $senderId, PDO::PARAM_INT);
        $stmt->bindParam(':relationId', $relationId, PDO::PARAM_INT);
        $stmt->bindParam(':content', $content, PDO::PARAM_STR);
        $statut = '1'; // Statut par défaut
        $stmt->bindParam(':statut', $statut, PDO::PARAM_STR);
        return $stmt->execute();
    }

    /**
     * Compte le nombre de messages non lus pour un utilisateur donné.
     * @param int $userId
     * @return int Nombre de messages non lus
     */
    public function countMessageNotRead(int $userId): int
    {
        $sql = "SELECT COUNT(*) AS unread_count
                FROM message m
                JOIN relation r ON m.relation_id = r.id
                WHERE (r.first_user = :userId OR r.second_user = :userId)
                  AND m.statut = '1'
                  AND m.sender_id != :userId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['unread_count'] ?? 0);
    }

    public function createNewRelation(int $userId, int $targetUserId): ?int
    {
        $sql = "INSERT INTO relation (first_user, second_user, created_at)
                VALUES (:firstUser, :secondUser, NOW())";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':firstUser', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':secondUser', $targetUserId, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
        return null;
    }
    
    public function findRelationBetweenUsers(int $userId1, int $userId2): ?int
    {
        $sql = "SELECT id FROM relation
                WHERE (first_user = :userId1 AND second_user = :userId2)
                   OR (first_user = :userId2 AND second_user = :userId1)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':userId1', $userId1, PDO::PARAM_INT);
        $stmt->bindParam(':userId2', $userId2, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['id'] : null;
    }
    
    /**
     * Récupère tous les messages d'une conversation avec vérification de sécurité.
     * @param int $relationId
     * @param int $userId
     * @return Message[]
     */
    public function getConversationMessages(int $relationId, int $userId): array
    {
        $sql = "SELECT m.*, u.nickname, u.picture, DATE_FORMAT(m.sent_at, '%H:%i') AS formatted_time
                FROM message m
                JOIN user u ON m.sender_id = u.id
                JOIN relation r ON m.relation_id = r.id
                WHERE m.relation_id = :relationId 
                AND (r.first_user = :userId OR r.second_user = :userId)
                ORDER BY m.sent_at ASC, m.id ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':relationId', $relationId, PDO::PARAM_INT);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        $messages = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $messages[] = new Message(
                (int)$row['id'],
                (int)$row['relation_id'],
                (int)$row['sender_id'],
                $row['statut'],
                $row['content'],
                new \DateTimeImmutable($row['sent_at'])
            );
        }
        return $messages;
    }

    /**
     * Marque tous les messages d'une conversation comme lus pour un utilisateur donné.
     * @param int $relationId
     * @param int $userId
     * @return void
     */
    public function markConversationAsRead(int $relationId, int $userId): void
    {
        $sql = "UPDATE message m
                JOIN relation r ON m.relation_id = r.id
                SET m.statut = '2'
                WHERE m.relation_id = :relationId
                  AND m.sender_id != :userId
                  AND m.statut = '1'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':relationId', $relationId, PDO::PARAM_INT);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
    }
}
