<?php

namespace App\Domain\Entity;

use DateTime;

/**
 * Class Message
 * Représente un message échangé entre deux utilisateurs.
 */
class Message
{
    private int $id;
    private int $relationId;
    private int $senderId;
    private string $content;
    private DateTime $sentAt;

    public function __construct()
    {
        $this->sentAt = new DateTime();
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
    public function getRelationId(): int
    {
        return $this->relationId;
    }

    /**
     * @param int $relationId
     */
    public function setRelationId(int $relationId): void
    {
        $this->relationId = $relationId;
    }

    /**
     * @return int
     */
    public function getSenderId(): int
    {
        return $this->senderId;
    }

    /**
     * @param int $senderId
     */
    public function setSenderId(int $senderId): void
    {
        $this->senderId = $senderId;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getSentAt(): DateTime
    {
        return $this->sentAt;
    }

    /**
     * @param DateTime $sentAt
     */
    public function setSentAt(DateTime $sentAt): void
    {
        $this->sentAt = $sentAt;
    }
}
