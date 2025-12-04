<?php

namespace App\Models\Entity;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

/**
 * Class Book
 * Représente un livre dans le système.
 */
class Book
{
    private int $id;
    private string $title;
    private string $picture;
    private string $author;
    private int $availability;
    private string $comment;
    private DateTimeInterface $createdAt;
    private ?DateTimeInterface $updatedAt = null;
    private int $userId;

    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->hydrate($data);
        }
    }

    /**
     * @param array $data
     * @return void l'ensemble des propriétés de l'entité Book
     */
    public function hydrate(array $data): void
    {
        $this->id = $data['id'] ?? 0;
        $this->title = $data['title'] ?? '';
        $this->picture = $data['picture'] ?? '';
        $this->author = $data['author'] ?? '';
        $this->availability = $data['availability'] ?? 0;
        $this->comment = $data['comment'] ?? '';
        $this->createdAt = isset($data['created_at'])
            ? ($data['created_at'] instanceof DateTimeInterface
                ? $data['created_at']
                : new DateTimeImmutable($data['created_at']))
            : new DateTimeImmutable();

        $this->updatedAt = isset($data['updated_at'])
            ? ($data['updated_at'] instanceof DateTimeInterface
                ? $data['updated_at']
                : new DateTimeImmutable($data['updated_at']))
            : new DateTimeImmutable();
        $this->userId = $data['user_id'] ?? 0;
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
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getPicture(): string
    {
        return $this->picture;
    }

    /**
     * @param string $picture
     */
    public function setPicture(string $picture): void
    {
        $this->picture = $picture;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getAvailability(): string
    {
        return match ($this->availability) {
            1 => 'disponible',
            2 => 'non disponible',
            default => 'inconnu',
        };
    }

    /**
     * @param int $availability
     */
    public function setAvailability(int $availability): void
    {
        $this->availability = $availability;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeImmutable $createdAt
     */
    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTimeImmutable $updatedAt
     */
    public function setUpdatedAt(DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }
}
