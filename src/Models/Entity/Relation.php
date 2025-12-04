<?php

namespace App\Models\Entity;

/**
 * enum RelationStatus
 * Représente les différents statuts d'une relation entre utilisateurs.
 */
enum RelationStatus: int {
    case AVAILABLE = 1;
    case PENDING = 2;
    case REJECTED = 3;
    case ERRORED = 4;
}

/**
 * Class Relation
 * Représente une relation entre deux utilisateurs.
 */
final class Relation
{
    /** @var int */
    public readonly int $id;

    /** @var int */
    public readonly int $userId1;

    /** @var int */
    public readonly int $userId2;

    /** @var RelationStatus */
    public readonly RelationStatus $statut;

    /** @var \DateTimeImmutable */
    public readonly \DateTimeImmutable $createdAt;

    /** @var \DateTimeImmutable */
    public readonly \DateTimeImmutable $updatedAt;

    public function __construct(
        int $id,
        int $userId1,
        int $userId2,
        RelationStatus $statut,
        \DateTimeImmutable $createdAt = new \DateTimeImmutable(),
        \DateTimeImmutable $updatedAt = new \DateTimeImmutable()
    )
    {
        $this->id = $id;
        $this->userId1 = $userId1;
        $this->userId2 = $userId2;
        $this->statut = $statut instanceof RelationStatus
                        ? $statut
                        : RelationStatus::tryFrom($statut) ?? RelationStatus::ERRORED;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /** Getters */
    public function getId(): int { return $this->id; }
    public function getUserId1(): int { return $this->userId1; }
    public function getUserId2(): int { return $this->userId2; }
    public function getStatut(): RelationStatus { return $this->statut; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function getUpdatedAt(): \DateTimeImmutable { return $this->updatedAt; }

    /**
     * Retourne le libellé du statut de la relation.
     * @return string
     */
    public function getStatutLabel(): string {
        return match ($this->statut) {
            RelationStatus::AVAILABLE => 'Available',
            RelationStatus::PENDING => 'Pending',
            RelationStatus::REJECTED => 'Rejected',
            RelationStatus::ERRORED => 'Errored',
            default => 'Unknown',
        };
    }
}
