<?php

namespace App\Services;

use App\Models\Entity\DTO\ConversationDTO;
use App\Models\Repository\MessageRepository;

class ConversationBuilder
{
    /**
     * Construit la liste des conversations pour un utilisateur.
     * Méthode helper pour éviter la duplication de code.
     */
    public function buildConversationsList(int $userId, MessageRepository $messageRepository): array
    {
        $conversations = [];
        
        // Récupérer toutes les relations de l'utilisateur
        $relations = $messageRepository->getAllRelationWithUserInfos($userId);
        
        // Pour chaque relation, construire un DTO
        foreach ($relations as $relation) {
            // Récupérer les messages de cette relation
            $messages = $messageRepository->getMessagesByRelationId($relation['id']);
            
            // Prendre le dernier message
            $lastMessage = end($messages);
            
            // Créer le DTO avec le count de messages non lus de CETTE conversation
            $conversations[] = new ConversationDTO(
                $relation['id'],                                      // relationId
                $relation['nickname'],                                // nickname
                $relation['picture'],                                 // picture
                $lastMessage ? $lastMessage->getContent() : '',       // lastMessage
                $lastMessage ? $lastMessage->getSentAt()->format('H:i') : '', // lastDate
                (int)($relation['unread_count'] ?? 0)                // unreadCount PAR conversation
            );
        }
        
        return $conversations;
    }
}