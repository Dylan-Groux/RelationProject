<?php

namespace App\Services;

use App\Models\Exceptions\IdenticalUserException;
use App\Models\Repository\MessageRepository;

class CheckExistingRelation
{
    private MessageRepository $messageRepository;

    public function __construct()
    {
        $this->messageRepository = new MessageRepository();
    }

    /**
     * Vérifie si une relation existe déjà entre deux utilisateurs.
     * @param int $userId1
     * @param int $userId2
     * @return int|null Retourne l'ID de la relation si elle existe, sinon null.
     */
    public function checkRelation(int $userId1, int $userId2): int|null|IdenticalUserException
    {
        if ($userId1 === $userId2) {
            return new IdenticalUserException(); // Un utilisateur ne peut pas avoir une relation avec lui-même
        }

        $relationId = $this->messageRepository->findRelationBetweenUsers($userId1, $userId2);
        if ($relationId) {
            return $relationId;
        } else {
            return null;
        }
    }
}