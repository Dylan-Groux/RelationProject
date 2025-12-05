<?php
declare(strict_types=1);

namespace App\Models\Entity;

/**
 * enum BookAvailability
 * Représente la disponibilité d'un livre.
 */
enum BookAvailability: string {
    case AVAILABLE = 'disponible';
    case UNAVAILABLE = 'non dispo.';
    case UNKNOWN = 'inconnu';
}

/**
 * Class Book
 * Représente un livre dans le système.
 */
final class Book
{
    /** @var int */
    public readonly int $id;

    /** @var string */
    private string $title;

    /** @var string */
    private string $picture;

    /** @var string */
    private string $author;

    /** @var BookAvailability */
    private BookAvailability $availability;

    /** @var string */
    private string $comment;
    
    /** @var \DateTimeImmutable */
    public readonly \DateTimeImmutable $createdAt;

    /** @var \DateTimeImmutable */ /** "\" Pour utiliser la classe native de PHP */
    private ?\DateTimeImmutable $updatedAt = null;

    /** @var int */
    public readonly int $userId;

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
        $this->id = (int)($data['id'] ?? 0);
        $this->title = (string)($data['title'] ?? '');
        $this->picture = (string)($data['picture'] ?? '');
        $this->author = (string)($data['author'] ?? '');
        $this->availability = BookAvailability::tryFrom($this->mapAvailability($data['availability'] ?? 'inconnu')) ?? BookAvailability::UNKNOWN;
        $this->comment = (string)($data['comment'] ?? '');
        $this->createdAt = $this->toDateTimeImmutable($data['created_at'] ?? null);
        $this->updatedAt = $this->toDateTimeImmutable($data['updated_at'] ?? null);
        $this->userId = (int)($data['user_id'] ?? 0);
    }

    /** Getters */
    public function getId(): int { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function getPicture(): string { return $this->picture; }
    public function getAuthor(): string { return $this->author; }
    public function getComment(): string { return $this->comment; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updatedAt; }
    public function getUserId(): int { return $this->userId; }

    /**
     * @return string fonction qui retourne la disponibilité sous forme de chaîne de caractères
     */
    public function getAvailability(): string
    {
        return match ($this->availability) {
            BookAvailability::AVAILABLE => 'disponible',
            BookAvailability::UNAVAILABLE => 'non disponible',
            default => 'inconnu',
        };
    }

    public function getAvailailityInt(): int
    {
        return match ($this->availability) {
            BookAvailability::AVAILABLE => 1,
            BookAvailability::UNAVAILABLE => 2,
            default => 0,
        };
    }

    /** Setters */
    public function setId(int $id): void { $this->id = $id; }
    public function setTitle(string $title): void { $this->title = $title; }
    public function setPicture(string $picture): void { $this->picture = $picture; }
    public function setAuthor(string $author): void { $this->author = $author; }
    public function setComment(string $comment): void { $this->comment = $comment; }
    public function setUserId(int $userId): void { $this->userId = $userId; }
    public function setAvailability(BookAvailability $availability): void { $this->availability = $availability; }

    /**
     * @param mixed $value
     * @return \DateTimeImmutable
     * fonction qui convertit une valeur en DateTimeImmutable
     */
    private function toDateTimeImmutable(mixed $value): \DateTimeImmutable
    {
        if ($value instanceof \DateTimeImmutable) {
            return $value;
        }
        if (is_string($value) && !empty($value)) {
        return new \DateTimeImmutable($value);
        }

        return new \DateTimeImmutable();
    }

    /**
     * @param mixed $value
     * @return string
     * fonction qui mappe la disponibilité depuis la base de données vers l'enum BookAvailability
     */
    private function mapAvailability(mixed $value): string
    {
        return match ($value) {
            1 => 'disponible',
            2 => 'non dispo.',
            default => 'inconnu',
        };
    }
}
