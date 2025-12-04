<?php
declare(strict_types=1);

namespace App\Models\Entity;

/**
 * Class Message
 * Représente un message échangé entre deux utilisateurs.
 */
class Message
{
    /** @var int */
    public readonly int $id;

    /** @var int */
    public readonly int $relationId;

    /** @var int */
    public readonly int $senderId;

    /** @var string */
    public readonly string $content;

    /** @var \DateTimeImmutable */
    public readonly \DateTimeImmutable $sentAt;

    public function __construct(
        int $id,
        int $relationId,
        int $senderId,
        string $content,
        \DateTimeImmutable $sentAt = new \DateTimeImmutable()
    )
    {
        $this->id = $id;
        $this->relationId = $relationId;
        $this->senderId = $senderId;
        $this->content = $content;
        $this->sentAt = $sentAt;
    }

    /** Getters */
    public function getId(): int { return $this->id; }
    public function getRelationId(): int { return $this->relationId; }
    public function getSenderId(): int { return $this->senderId; }
    public function getContent(): string { return $this->content; }
    public function getSentAt(): \DateTimeImmutable { return $this->sentAt; }
}
