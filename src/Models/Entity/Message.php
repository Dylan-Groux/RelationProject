<?php
declare(strict_types=1);

namespace App\Models\Entity;

/**
 * Class Message
 * Représente un message échangé entre deux utilisateurs.
 */
class Message
{
    public readonly int $id;
    public readonly int $relationId;
    public readonly int $senderId;
    public readonly string $content;
    public readonly \DateTimeImmutable $sentAt;

    public function __construct(
        int $id,
        int $relationId,
        int $senderId,
        string $content,
        \DateTimeImmutable $sentAt = new \DateTimeImmutable()
    )
    {
        $this->id = (int)$id;
        $this->relationId = (int)$relationId;
        $this->senderId = (int)$senderId;
        $this->content = (string)$content;
        $this->sentAt = $sentAt;
    }

    /** Getters */
    public function getId(): int { return $this->id; }
    public function getRelationId(): int { return $this->relationId; }
    public function getSenderId(): int { return $this->senderId; }
    public function getContent(): string { return $this->content; }
    public function getSentAt(): \DateTimeImmutable { return $this->sentAt; }
}
