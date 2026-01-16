<?php

namespace App\Models\Entity;

/**
 * Class User
 * Représente un utilisateur de l'application.
 */
class User
{
    /** @var int */
    public readonly int $id;

    /** @var string */
    private string $picture = '';

    /** @var \DateTimeImmutable */
    public readonly \DateTimeImmutable $createdAt;

    /** @var \DateTimeImmutable */
    private \DateTimeImmutable $updatedAt;

    /** @var string */
    private string $password;

    /** @var string */
    private string $nickname;

    /** @var string */
    private string $email;

    /** @var string */
    private string $name;

    /**
     * Initialise l'utilisateur à partir d'un tableau associatif.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->hydrate($data);
        }
    }

    /**
     * @param array $data
     * @return void l'ensemble des propriétés de l'entité User
     */
    public function hydrate(array $data): void
    {
        $this->id = (int)($data['id'] ?? 0);
        if (isset($data['name'])) {
            $this->name = (string)($data['name'] ?? '');
        }
        if (isset($data['picture'])) {
            $this->picture = (string)($data['picture'] ?? '');
        }
        if (isset($data['updated_at'])) {
            $this->updatedAt = new \DateTimeImmutable($data['updated_at']);
        }
        if (isset($data['created_at'])) {
            $this->createdAt = new \DateTimeImmutable($data['created_at']);
        } else {
            $this->createdAt = new \DateTimeImmutable();
        }
        if (isset($data['password'])) {
            $this->password = (string)($data['password'] ?? '');
        }
        if (isset($data['nickname'])) {
            $this->nickname = (string)($data['nickname'] ?? '');
        }
        if (isset($data['email'])) {
            $this->email = (string)($data['email'] ?? '');
        }
    }

    /** Getters */
    public function getName(): string { return $this->name; }
    public function getId(): int { return $this->id; }
    public function getPicture(): string { return $this->picture; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function getUpdatedAt(): \DateTimeImmutable { return $this->updatedAt; }
    public function getPassword(): string { return $this->password; }
    public function getNickname(): string { return $this->nickname; }
    public function getEmail(): string { return $this->email; }

    /** Setters */
    public function setName(string $name): void { $this->name = $name; }
    public function setPicture(string $picture): void { $this->picture = $picture; }
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): void { $this->updatedAt = $updatedAt; }
    public function setPassword(string $password): void { $this->password = $password; }
    public function setNickname(string $nickname): void { $this->nickname = $nickname; }
    public function setEmail(string $email): void { $this->email = $email; }

    /**
     * Retourne le nombre d'années depuis la création du compte (ex: '1 an', '2 ans').
     */
    public function getMembershipDuration(): string
    {
        $now = new \DateTimeImmutable();
        $interval = $now->diff($this->createdAt);
        
        return match (true) {
        $interval->y > 0 => $interval->y . ' ' . ($interval->y > 1 ? 'ans' : 'an'),
        $interval->m > 0 => $interval->m . ' ' . ($interval->m > 1 ? 'mois' : 'mois'),
        default          => $interval->d . ' ' . ($interval->d > 1 ? 'jours' : 'jour'),
        };
    }
}
