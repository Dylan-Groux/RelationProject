<?php

namespace App\Controllers;

use App\Models\Repository\MessageRepository;
use App\Views\View;
use App\Models\Repository\UserRepository;

class MessageController
{
    private $currentUser;
    private $userId;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
        if ($userId === null) {
            http_response_code(401);
            echo 'Utilisateur non authentifié.';
            return;
        }
        $userRepository = new UserRepository();
        $user = $userRepository->getUserById($userId);
        if ($user === null) {
            http_response_code(401);
            echo 'Utilisateur non authentifié.';
            return;
        }
        $this->currentUser = $user;
        $this->userId = $userId;
    }
    /**
     * Affiche la page de messagerie.
     * @return void
     */
    #[\App\Library\Router('/messagerie/{id}', 'GET')]
    public function showMessages(): void
    {
        $userId = $this->userId; // Pour les tests, on utilise un ID utilisateur fixe

        $userRepository = new UserRepository();
        $messageRepository = new MessageRepository();
        $relations = $messageRepository->getAllRelationWithUserId($userId);
        $otherUserIdPicture = $messageRepository->getAllRelationWithUserInfos($userId);

        $conversations = [];
        $relations = $messageRepository->getAllRelationWithUserInfos($userId);
        foreach ($relations as $relation) {
            $messages = $messageRepository->getMessagesByRelationId($relation['id']);
            $lastMessage = end($messages);
            $conversations[] = [
                'relation_id' => $relation['id'],
                'nickname' => $relation['nickname'],
                'picture' => $relation['picture'],
                'last_message' => $lastMessage['content'] ?? '',
                'last_date' => $lastMessage['sent_at'] ?? '',
            ];
        }
        $view = new View('messagerie');
        $view->render(['conversations' => $conversations]);
    }

    /**
     * Ouvre une conversation spécifique par son ID.
     * @param int $conversationId
     * @return void
     */
   public function openConversation(int $conversationId): void
    {
        $currentUser = $this->currentUser;
        $messageRepository = new MessageRepository();
        var_dump($currentUser);

        $messages = $messageRepository->getMessagesByRelationId($conversationId);
        $user = $messageRepository->getSenderAndReceiverByRelationId($conversationId);
        if ($user === null) {
            http_response_code(404);
            echo 'Conversation introuvable.';
            return;
        }

        if ($currentUser->getId() !== $user['first_user'] && $currentUser->getId() !== $user['second_user']) {
            http_response_code(403);
            echo 'Accès refusé à cette conversation.';
            return;
        }

        $view = new View('conversation');
        $view->render([
            'conversationId' => $conversationId,
            'messages' => $messages,
            'user' => $user,
            'currentUserId' => $currentUser,
        ]);
    }
}


