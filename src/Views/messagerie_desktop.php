<?php
use App\Library\EasyHeader;
EasyHeader::addHeader(
    'Page de connexion',
    ['css/home.css', 'css/footer.css', 'css/navbar.css', 'css/conversation.css', 'css/messagerie.css', 'css/common.css'],
    ['js/navbar.js']
);
?>
<body>
    <?php include_once __DIR__ . '/navbar.php'; ?>
    <main class="main">
        <div class="conversations-list-mobile">
            <h1 class="main-title"> Messagerie </h1>
            <?php foreach ($conversations as $conv): ?>
                <a href="/public/conversation/<?= htmlspecialchars($conv->relationId) ?>">
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
        </div>
        <div class="messagerie-layout">
            <div class="conversations-list-desktop">
                <h1 class="main-title"> Messagerie </h1>
                <div class="messages-zones">
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
                </div>
            </div>
            <div class="conversations-list">
                <div class="receiver-info-grid">
                    <img class="receiver-avatar" src="<?= $otherPicture ?>" alt="Avatar utilisateur" width="100" height="100">
                    <span class="receiver-nickname"> <?= htmlspecialchars($otherNickname) ?> </span>
                </div>
                <?php foreach ($messages as $conv): ?>
                    <div class="conversation">
                            <?php if ($conv->getSenderId() == $currentUserId): ?>
                                <div class="sender">
                                    <div class="sender-info">
                                        <span class="datestamp"><?= htmlspecialchars($conv->getSentAt()->format('d.m')) ?> </span>
                                        <span class="timestamp"><?= htmlspecialchars($conv->getSentAt()->format('H:i')) ?></span>
                                    </div>
                                    <div class="message-body-sender">
                                        <p> <?= htmlspecialchars($conv->getContent()) ?> </p>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="receiver">
                                    <div class="receiver-info">
                                        <img class="receiver-avatar-conversation" src="<?= $otherPicture ?>" alt="Avatar utilisateur" width="100" height="100">
                                        <span class="datestamp"><?= htmlspecialchars($conv->getSentAt()->format('d.m')) ?> </span>
                                        <span class="timestamp"><?= htmlspecialchars($conv->getSentAt()->format('H:i')) ?></span>
                                    </div>
                                    <div class="message-body-receiver">
                                        <p> <?= htmlspecialchars($conv->getContent()) ?> </p>
                                    </div>
                                </div>
                            <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                <div class="send-message-form">
                    <form action="/public/messagerie/conversation/<?= htmlspecialchars($conversationId) ?>/send" method="POST">
                        <textarea name="message" placeholder="Tapez votre message ici" required></textarea>
                        <input type="hidden" name="CSRF_token" value="<?= htmlspecialchars($csrfToken) ?>">
                        <button type="submit">Envoyer</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <?php include_once __DIR__ . '/footer.php'; ?>
</body>
</html>
