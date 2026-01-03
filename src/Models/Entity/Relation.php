<?php

namespace App\Models\Entity;

/**
 * Class Relation
 * Représente une relation entre deux utilisateurs.
 */
final class Relation
{
    /** @var int */
    public readonly int $userId1;

    /** @var int */
    public readonly int $userId2;

    /** @var \DateTimeImmutable */
    public readonly \DateTimeImmutable $createdAt;

    /** @var \DateTimeImmutable */
    public readonly \DateTimeImmutable $updatedAt;

    public function __construct(
        public readonly int $id,
        int $userId1,
        int $userId2,
        \DateTimeImmutable $createdAt = new \DateTimeImmutable(),
        \DateTimeImmutable $updatedAt = new \DateTimeImmutable()
    )
    {
        $this->id = $id;
        $this->userId1 = $userId1;
        $this->userId2 = $userId2;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /** Getters */
    public function getId(): int { return $this->id; }
    public function getUserId1(): int { return $this->userId1; }
    public function getUserId2(): int { return $this->userId2; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function getUpdatedAt(): \DateTimeImmutable { return $this->updatedAt; }
}
