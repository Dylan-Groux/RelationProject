<?php
declare(strict_types=1);

namespace App\Models\Entity;

/**
 * enum RelationStatus
 * Représente les différents statuts d'une relation entre utilisateurs.
 */
enum MessageStatus: int {
    case AVAILABLE = 1;
    case PENDING = 2;
    case REJECTED = 3;
    case ERRORED = 4;
}

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

    /** @var MessageStatus */
    public readonly MessageStatus $statut;

    /** @var string */
    public readonly string $content;

    /** @var \DateTimeImmutable */
    public readonly \DateTimeImmutable $sentAt;

    public function __construct(
        int $id,
        int $relationId,
        int $senderId,
        int $statut,
        string $content,
        \DateTimeImmutable $sentAt = new \DateTimeImmutable()
    )
    {
        $this->id = $id;
        $this->relationId = $relationId;
        $this->senderId = $senderId;
        $this->statut = $statut instanceof MessageStatus
                ? $statut
                : MessageStatus::tryFrom($statut) ?? MessageStatus::ERRORED;
        $this->content = $content;
        $this->sentAt = $sentAt;
    }

    /** Getters */
    public function getId(): int { return $this->id; }
    public function getRelationId(): int { return $this->relationId; }
    public function getSenderId(): int { return $this->senderId; }
    public function getStatut(): MessageStatus { return $this->statut; }
    public function getContent(): string { return $this->content; }
    public function getSentAt(): \DateTimeImmutable { return $this->sentAt; }

    /**
     * Retourne le libellé du statut de la relation.
     * @return string
     */
    public function getStatutLabel(): string {
        return match ($this->statut) {
            MessageStatus::AVAILABLE => 'Available',
            MessageStatus::PENDING => 'Pending',
            MessageStatus::REJECTED => 'Rejected',
            MessageStatus::ERRORED => 'Errored',
            default => 'Unknown',
        };
    }
}
