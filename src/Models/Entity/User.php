<?php

namespace App\Models\Entity;

use DateTime;

/**
 * Class User
 * Représente un utilisateur de l'application.
 */
class User
{
    private int $id;
    private string $picture;
    private DateTime $createdAt;
    private DateTime $updatedAt;
    private string $password;
    private string $nickname;
    private string $mail;
    private string $name;

    /**
     * Initialise l'utilisateur à partir d'un tableau associatif.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->id = isset($data['id']) ? (int)$data['id'] : 0;
        $this->picture = $data['picture'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->nickname = $data['nickname'] ?? '';
        $this->mail = $data['mail'] ?? '';
        $this->name = $data['name'] ?? '';
        $this->createdAt = isset($data['created_at']) ? new DateTime($data['created_at']) : new DateTime();
        $this->updatedAt = isset($data['updated_at']) ? new DateTime($data['updated_at']) : new DateTime();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
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
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getNickname(): string
    {
        return $this->nickname;
    }

    /**
     * @param string $nickname
     */
    public function setNickname(string $nickname): void
    {
        $this->nickname = $nickname;
    }

    /**
     * @return string
     */
    public function getMail(): string
    {
        return $this->mail;
    }

    /**
     * @param string $mail
     */
    public function setMail(string $mail): void
    {
        $this->mail = $mail;
    }

    /**
     * Retourne le nombre d'années depuis la création du compte (ex: '1 an', '2 ans').
     */
    public function getMembershipDuration(): string
    {
        $now = new \DateTime();
        $interval = $now->diff($this->createdAt);
        if ($interval->y > 0) {
            return $interval->y . ' ' . ($interval->y > 1 ? 'ans' : 'an');
        } elseif ($interval->m > 0) {
            return $interval->m . ' ' . ($interval->m > 1 ? 'mois' : 'mois');
        } else {
            return $interval->d . ' ' . ($interval->d > 1 ? 'jours' : 'jour');
        }
    }

}
