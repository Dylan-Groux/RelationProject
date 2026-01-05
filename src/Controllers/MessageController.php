<?php

namespace App\Controllers;

use App\Models\Entity\DTO\ConversationDTO;
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
        $unreadCount = $messageRepository->countMessageNotRead($userId);
        foreach ($relations as $relation) {
            $messages = $messageRepository->getMessagesByRelationId($relation['id']);
            $lastMessage = end($messages);
            $conversations[] = new ConversationDTO(
                $relation['id'],
                $relation['nickname'],
                $relation['picture'],
                $lastMessage->getContent() ?? '',
                $lastMessage->getSentAt()->format('H:i') ?? '',
                $unreadCount
            );
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

        if ($_POST['CSRF_token'] ?? false) {
            $postedToken = $_POST['CSRF_token'];
            if (!hash_equals($_SESSION['CSRF_token'], $postedToken)) {
                http_response_code(403);
                echo 'Invalid CSRF token.';
                exit();
            }
        }

        if ($_POST['message'] ?? false) {
            // Traite l'envoi du message
            $content = trim($_POST['message']);
            var_dump($content);
            if (!empty($content)) {
                $messageRepository->sendMessage($currentUser->getId(), $conversationId, $content);
            }
            // Redirige pour éviter la soumission multiple du formulaire
            $redirectUrl = '/public/conversation/' . htmlspecialchars($conversationId);
            header("Location: $redirectUrl", true, 301);
            exit();
        }

        $baseReturnUrl = '/public/messagerie/' . htmlspecialchars($currentUser->getId());

        if ($conversationId <= 0) {
            http_response_code(400);
            header("Location: $baseReturnUrl", true, 301);
            exit();
        }

        $conv = $messageRepository->getConversationInformations($conversationId, $currentUser->getId());
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
        if (!isset($_SESSION['CSRF_token'])) {
            $_SESSION['CSRF_token'] = bin2hex(random_bytes(32));
        }

        $view->render([
            'conversationId' => $conversationId,
            'messages' => $messages,
            'otherNickname' => $otherNickname,
            'otherPicture' => $otherPicture,
            'currentUserId' => $currentUser->getId(),
            'csrfToken' => $_SESSION['CSRF_token'],
        ]);
    }
}
