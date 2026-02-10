<?php

namespace App\Controllers;

use App\Controllers\Abstract\AbstractController;
use App\Models\Entity\DTO\ConversationDTO;
use App\Models\Repository\MessageRepository;
use App\Views\View;
use App\Models\Repository\UserRepository;
use App\Services\CheckExistingRelation;
use App\Services\ConversationBuilder;

class MessageController extends AbstractController
{
    private $currentUser;
    private $userId;
    private ConversationBuilder $conversationBuilder;

    public function __construct()
    {
        parent::__construct();
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
        if ($userId === null) {
            http_response_code(401);
            header('Location: /public/login', true, 302);
            exit();
        }
        $userRepository = new UserRepository();
        $user = $userRepository->getUserById($userId);
        if ($user === null) {
            http_response_code(401);
            header('Location: /public/login', true, 302);
            exit();
        }
        $this->currentUser = $user;
        $this->userId = $userId;
        $this->conversationBuilder = new ConversationBuilder();
    }







    #[\App\Library\Router('/messagerie/start/{book_id}', 'GET')]
    public function startNewRelation(int $book_id): void
    {
        $userId = $this->userId;
        $bookRepository = new \App\Models\Repository\BookRepository();

        $targetUserId = $bookRepository->getUserWithBookId($book_id);
        if ($targetUserId === null || $targetUserId <= 0) {
            http_response_code(400);
            header("Location: /public/books", true, 302);
            exit();
        }

        $relationExists = CheckExistingRelation::class;
        $checker = new $relationExists();

        $existingRelationId = $checker->checkRelation($userId, $targetUserId);
        if ($existingRelationId instanceof \App\Models\Exceptions\IdenticalUserException) {
            header("Location: /public/messagerie/$userId", true, 301);
            exit();
        }  elseif ($existingRelationId !== null) {
            $redirectUrl = "/public/messagerie/$userId/conversation/$existingRelationId";
            header("Location: $redirectUrl", true, 301);
            exit();
        } else {
            $messageRepository = new MessageRepository();
            $relationId = $messageRepository->createNewRelation($userId, $targetUserId);
            if ($relationId === null) {
                http_response_code(500);
                header("Location: /public/books", true, 302);
                exit();
            } else {
                //TODO : Faire un message vide pour initialiser la conversation
                $messageRepository->sendMessage($userId, $relationId, "Bonjour! Je suis intéressé par votre livre.");
            }
        }

        // Redirige vers la conversation nouvellement créée
        $redirectUrl = "/public/messagerie/$userId/conversation/$relationId";
        header("Location: $redirectUrl", true, 301);
        exit();
    }

    #[\App\Library\Router('/messagerie/{userId}', 'GET')]
    #[\App\Library\Router('/messagerie/{userId}/conversation/{conversationId}', 'GET')]
    public function showMessaging(int $userId, ?int $conversationId = null): void
    {
        // Sécurité : Vérifier que l'utilisateur accède à ses propres messages
        try {
            $this->checkUserAccess($userId);
        } catch (LoginException $e) {
            header('Location: /public/login');
            exit();
        }
        
        $currentUser = $this->currentUser;
        $messageRepository = new MessageRepository();

        // Étape 2: Build conversations list avec le service ConversationBuilder
        $conversations = $this->conversationBuilder->buildConversationsList($userId, $messageRepository);

        // Étape 3: Charger la conversation active (conditionnellement)
        $otherNickname = null;
        $otherPicture = null;
        
        if ($conversationId !== null) {
            // Marquer les messages comme lus
            $messageRepository->markConversationAsRead($conversationId, $userId);
        
            // Récupérer les messages
            $messages = $messageRepository->getConversationMessages($conversationId, $userId);
            
            // Récupérer les infos de la conversation (participants)
            $conv = $messageRepository->getConversationInformations($conversationId, $userId);
            
            if ($conv === null) {
                http_response_code(404);
                header("Location: /public/messagerie/$userId", true, 302);
                exit();
            }
            
            // Déterminer qui est l'autre utilisateur
            if ($userId == $conv['user1_id']) {
                $otherNickname = $conv['user2_nickname'];
                $otherPicture = $conv['user2_picture'];
            } else {
                $otherNickname = $conv['user1_nickname'];
                $otherPicture = $conv['user1_picture'];
            }
            
            $hasActiveConversation = true;
        } else {
            $messages = [];
            $hasActiveConversation = false;
        }

        // Étape 4: Générer le token CSRF
        $csrfToken = bin2hex(random_bytes(32));
        $_SESSION['CSRF_token'] = $csrfToken;

        // Étape 5: Render la vue unifiée
        $view = new View('messagerie');
        $view->render([
            'userId' => $userId,
            'conversations' => $conversations,
            'messages' => $messages,
            'hasActiveConversation' => $hasActiveConversation,
            'conversationId' => $conversationId,
            'otherNickname' => $otherNickname,
            'otherPicture' => $otherPicture,
            'csrfToken' => $csrfToken
        ]);
    }

    /**
     * Envoie un message dans une conversation.
     * @param int $conversationId
     * @return void
     */
    #[\App\Library\Router('/messagerie/conversation/{conversationId}/send', 'POST')]
    public function sendMessage(int $conversationId): void
    {
        $currentUser = $this->currentUser;
        $userId = $this->userId;
        $messageRepository = new MessageRepository();

        // Vérification CSRF
        if (!isset($_POST['CSRF_token']) || !hash_equals($_SESSION['CSRF_token'], $_POST['CSRF_token'])) {
            http_response_code(403);
            header('Location: /public/messagerie/' . $userId, true, 302);
            exit();
        }

        // Vérifier que l'utilisateur fait partie de la conversation
        $conv = $messageRepository->getConversationInformations($conversationId, $userId);
        if ($conv === null) {
            http_response_code(404);
            header("Location: /public/messagerie/$userId", true, 302);
            exit();
        }

        // Traiter l'envoi du message
        if (isset($_POST['message'])) {
            $content = trim($_POST['message']);
            if (!empty($content)) {
                $messageRepository->sendMessage($userId, $conversationId, $content);
            }
        }

        // Rediriger vers la conversation pour éviter la soumission multiple
        $redirectUrl = "/public/messagerie/$userId/conversation/$conversationId";
        header("Location: $redirectUrl", true, 303);
        exit();
    }
}
