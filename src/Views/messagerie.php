<?php
use App\Library\EasyHeader;
EasyHeader::addHeader(
    'Messagerie',
    ['css/home.css', 'css/footer.css', 'css/navbar.css', 'css/conversation.css', 'css/messagerie.css', 'css/common.css'],
    ['js/navbar.js', 'js/messagerie.js']
);
?>
<body <?= $hasActiveConversation ? 'class="has-active-conversation"' : '' ?>>
    <?php include_once __DIR__ . '/navbar.php'; ?>
    <main class="main">
        <!-- MOBILE: Liste des conversations OU conversation active -->
        <div class="conversations-list-mobile">
            <?php if (!$hasActiveConversation): ?>
                <!-- Afficher la liste des conversations -->
                <h1 class="main-title">Messagerie</h1>
                <div class="mobile-conversations-list-container">
                    <?php if (empty($conversations)): ?>
                        <p class="no-conversation-message">Vous n'avez aucune conversation pour le moment.</p>
                    <?php else: ?>
                        <?php foreach ($conversations as $conv): ?>
                            <a href="/public/messagerie/<?= htmlspecialchars($userId) ?>/conversation/<?= htmlspecialchars($conv->relationId) ?>">
                                <div class="conversation-grid">
                                    <img class="conversation-picture" src="<?= htmlspecialchars($conv->picture) ?>" alt="Profil">
                                    <span class="conversation-nickname"><?= htmlspecialchars($conv->nickname) ?></span>
                                    <span class="conversation-message">
                                        <?= mb_strlen($conv->lastMessage) > 40
                                            ? htmlspecialchars(mb_substr($conv->lastMessage, 0, 40)) . ',...'
                                            : htmlspecialchars($conv->lastMessage) ?>
                                    </span>
                                    <span class="conversation-date"><?= htmlspecialchars($conv->lastDate) ?></span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <!-- Afficher la conversation active avec bouton retour -->
                <a class="back" href="/public/messagerie/<?= htmlspecialchars($userId) ?>">
                    &#8592; retour
                </a>
                <div class="receiver-info-grid">
                    <img class="receiver-avatar" src="<?= htmlspecialchars($otherPicture) ?>" alt="Avatar utilisateur" width="100" height="100">
                    <span class="receiver-nickname"><?= htmlspecialchars($otherNickname) ?></span>
                </div>
                <div class="mobile-messages-container">
                    <?php foreach ($messages as $message): ?>
                        <div class="conversation">
                            <?php if ($message->getSenderId() == $userId): ?>
                                <div class="sender">
                                    <div class="sender-info">
                                        <span class="datestamp"><?= htmlspecialchars($message->getSentAt()->format('d.m')) ?></span>
                                        <span class="timestamp"><?= htmlspecialchars($message->getSentAt()->format('H:i')) ?></span>
                                    </div>
                                    <div class="message-body-sender">
                                        <p><?= htmlspecialchars($message->getContent()) ?></p>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="receiver">
                                    <div class="receiver-info">
                                        <img class="receiver-avatar-conversation" src="<?= htmlspecialchars($otherPicture) ?>" alt="Avatar utilisateur" width="100" height="100">
                                        <span class="datestamp"><?= htmlspecialchars($message->getSentAt()->format('d.m')) ?></span>
                                        <span class="timestamp"><?= htmlspecialchars($message->getSentAt()->format('H:i')) ?></span>
                                    </div>
                                    <div class="message-body-receiver">
                                        <p><?= htmlspecialchars($message->getContent()) ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="send-message-form">
                    <form action="/public/messagerie/conversation/<?= htmlspecialchars($conversationId) ?>/send" method="POST">
                        <textarea name="message" placeholder="Tapez votre message ici" required></textarea>
                        <input type="hidden" name="CSRF_token" value="<?= htmlspecialchars($csrfToken) ?>">
                        <button type="submit">Envoyer</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>

        <!-- DESKTOP: Les deux colonnes côte à côte -->
        <div class="messagerie-layout">
            <!-- Sidebar gauche: Liste des conversations -->
            <div class="conversations-list-desktop">
                <h1 class="main-title">Messagerie</h1>
                <div class="messages-zones">
                    <?php if (empty($conversations)): ?>
                        <p class="no-conversation-message">Vous n'avez aucune conversation pour le moment.</p>
                    <?php else: ?>
                        <?php foreach ($conversations as $conv): ?>
                            <a href="/public/messagerie/<?= htmlspecialchars($userId) ?>/conversation/<?= htmlspecialchars($conv->relationId) ?>">
                                <div class="conversation-grid <?= $hasActiveConversation && $conversationId == $conv->relationId ? 'active' : '' ?>">
                                    <img class="conversation-picture" src="<?= htmlspecialchars($conv->picture) ?>" alt="Profil">
                                    <span class="conversation-nickname"><?= htmlspecialchars($conv->nickname) ?></span>
                                    <span class="conversation-message">
                                        <?= mb_strlen($conv->lastMessage) > 40
                                            ? htmlspecialchars(mb_substr($conv->lastMessage, 0, 40)) . ',...'
                                            : htmlspecialchars($conv->lastMessage) ?>
                                    </span>
                                    <span class="conversation-date"><?= htmlspecialchars($conv->lastDate) ?></span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Colonne droite: Conversation active -->
            <div class="conversation-details">
                <?php if ($hasActiveConversation): ?>
                    <div class="receiver-info-grid">
                        <img class="receiver-avatar" src="<?= htmlspecialchars($otherPicture) ?>" alt="Avatar utilisateur" width="100" height="100">
                        <span class="receiver-nickname"><?= htmlspecialchars($otherNickname) ?></span>
                    </div>
                    <div class="conversations-messages-container">
                        <?php foreach ($messages as $message): ?>
                            <div class="conversation">
                                <?php if ($message->getSenderId() == $userId): ?>
                                    <div class="sender">
                                        <div class="sender-info">
                                            <span class="datestamp"><?= htmlspecialchars($message->getSentAt()->format('d.m')) ?></span>
                                            <span class="timestamp"><?= htmlspecialchars($message->getSentAt()->format('H:i')) ?></span>
                                        </div>
                                        <div class="message-body-sender">
                                            <p><?= htmlspecialchars($message->getContent()) ?></p>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="receiver">
                                        <div class="receiver-info">
                                            <img class="receiver-avatar-conversation" src="<?= htmlspecialchars($otherPicture) ?>" alt="Avatar utilisateur" width="100" height="100">
                                            <span class="datestamp"><?= htmlspecialchars($message->getSentAt()->format('d.m')) ?></span>
                                            <span class="timestamp"><?= htmlspecialchars($message->getSentAt()->format('H:i')) ?></span>
                                        </div>
                                        <div class="message-body-receiver">
                                            <p><?= htmlspecialchars($message->getContent()) ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="send-message-form">
                        <form action="/public/messagerie/conversation/<?= htmlspecialchars($conversationId) ?>/send" method="POST">
                            <textarea name="message" placeholder="Tapez votre message ici" required></textarea>
                            <input type="hidden" name="CSRF_token" value="<?= htmlspecialchars($csrfToken) ?>">
                            <button type="submit">Envoyer</button>
                        </form>
                    </div>
                <?php else: ?>
                    <p class="no-conversation-selected">Sélectionnez une conversation pour commencer.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <?php include_once __DIR__ . '/footer.php'; ?>
</body>
</html>
