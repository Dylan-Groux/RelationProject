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

    public function getAllMessageWithUserId(int $userId)
    {
        $sql = "SELECT r.id AS relation_id,
        u.id AS user_id,
        u.nickname,
        u.picture,
        m.content AS last_message,
        m.sent_at AS last_date
        FROM relation r
        JOIN user u ON (u.id = CASE WHEN r.first_user = :userId THEN r.second_user ELSE r.first_user END)
        LEFT JOIN (
        SELECT relation_id, content, sent_at
        FROM message
        WHERE id IN (
            SELECT MAX(id) FROM message GROUP BY relation_id
        )
        ) m ON m.relation_id = r.id
        WHERE r.first_user = :userId OR r.second_user = :userId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
