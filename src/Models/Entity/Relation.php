<?php

namespace App\Models\Entity;

use DateTime;

/**
 * Class Relation
 * Représente une relation entre deux utilisateurs.
 */
class Relation
{
    const STATUS_AVAILABLE = 1;
    const STATUS_PENDING = 2;
    const STATUS_REJECTED = 3;
    const STATUS_ERRORED = 4;

    private int $id;
    private int $userId1;
    private int $userId2;
    private int $statut;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getUserId1(): int
    {
        return $this->userId1;
    }

    /**
     * @param int $userId1
     */
    public function setUserId1(int $userId1): void
    {
        $this->userId1 = $userId1;
    }

    /**
     * @return int
     */
    public function getUserId2(): int
    {
        return $this->userId2;
    }

    /**
     * @param int $userId2
     */
    public function setUserId2(int $userId2): void
    {
        $this->userId2 = $userId2;
    }

    /**
     * @return int
     */
    public function getStatut(): int
    {
        return $this->statut;
    }

    /**
     * @param int $statut
     */
    public function setStatut(int $statut): void
    {
        $this->statut = $statut;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return string
     * Retourne le libellé du statut de la relation.
     */
    public function getStatutLabel(): string
    {
        return match ($this->statut) {
            self::STATUS_AVAILABLE => 'Available',
            self::STATUS_PENDING => 'Pending',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_ERRORED => 'Errored',
            default => 'Unknown',
        };
    }
}
