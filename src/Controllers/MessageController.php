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
        $userId = $this->userId;

        $messageRepository = new MessageRepository();

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
                'last_date' => $lastMessage['formatted_time'] ?? '',
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
    #[\App\Library\Router('/messagerie/conversation/{id}', 'GET')]
   public function openConversation(int $conversationId): void
    {
        $currentUser = $this->currentUser;
        $messageRepository = new MessageRepository();

        $baseReturnUrl = '/public/messagerie/' . htmlspecialchars($currentUser->getId());

        if ($conversationId <= 0) {
            http_response_code(400);
            header("Location: $baseReturnUrl", true, 301);
            exit();
        }

        $conv = $messageRepository->getConversationInformations($conversationId);
        if ($conv === null || empty($conv) || !isset($conv['user1_id'], $conv['user2_id']) || $conv === false) {
            http_response_code(404);
            header("Location: $baseReturnUrl", true, 301);
            exit();
        }

        // Détermine qui est l'autre utilisateur // Vérifie si l'utilisateur courant fait partie de la conversation
        if ($currentUser->getId() == $conv['user1_id']) {
            $otherNickname = $conv['user2_nickname'];
            $otherPicture = $conv['user2_picture'];
        } else {
            $otherNickname = $conv['user1_nickname'];
            $otherPicture = $conv['user1_picture'];
        }

        if ($currentUser->getId() != $conv['user1_id'] && $currentUser->getId() != $conv['user2_id']) {
            http_response_code(403);
            header("Location: $baseReturnUrl", true, 301);
            exit();
        }

        $messages = $messageRepository->getMessagesByRelationId($conversationId, 5, 0);

        $view = new View('conversation');
        $view->render([
            'conversationId' => $conversationId,
            'messages' => $messages,
            'otherNickname' => $otherNickname,
            'otherPicture' => $otherPicture,
            'currentUserId' => $currentUser->getId(),
        ]);
    }
}
