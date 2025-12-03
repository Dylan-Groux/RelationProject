<?php

namespace App\Controllers;

use App\Models\Repository\MessageRepository;
use App\Views\View;

class MessageController
{
    /**
     * Affiche la page de messagerie.
     * @return void
     */
    #[\App\Library\Router('/messagerie', 'GET')]
    public function showMessages(): void
    {
        // Supposons que l'ID utilisateur est stocké dans la session
        //$userId = $_SESSION['user_id'] ?? null;

        $userId = 1; // Pour les tests, on utilise un ID utilisateur fixe

        $messages = [];
        if ($userId !== null) {
            $messageRepository = new MessageRepository();
            $messages = $messageRepository->getAllMessageWithUserId($userId);
        }

        $view = new View('messagerie');
        $view->render(['messages' => $messages]);
    }
}