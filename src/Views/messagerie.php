<?php
use App\Library\EasyHeader;
EasyHeader::addHeader(
    'Page de connexion',
    ['css/home.css', 'css/footer.css', 'css/navbar.css', 'css/messagerie.css', 'css/common.css'],
    ['js/navbar.js']
);
?>
<body>
    <?php include_once __DIR__ . '/navbar.php'; ?>
    <main class="main">
        <div class="conversations-list-mobile">
            <h1 class="main-title"> Messagerie </h1>
                <?php if (empty($conversations)): ?>
                    <p class="no-conversation-message">Vous n'avez aucune conversation pour le moment.</p>
                <?php endif; ?>
            <?php foreach ($conversations as $conv): ?>
                <a href="/public/conversation/<?= htmlspecialchars($conv->relationId) ?>">
                    <div class="conversation-grid">
                        <img class="conversation-picture" src="<?= htmlspecialchars($conv->picture) ?>" alt="Profil" >
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
                <?php foreach ($conversations as $conv): ?>
                    <a href="/public/messagerie/<?= htmlspecialchars($userId) ?>/conversation/<?= htmlspecialchars($conv->relationId) ?>">
                        <div class="conversation-grid">
                            <img class="conversation-picture" src="<?= htmlspecialchars($conv->picture) ?>" alt="Profil" />
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
            <div class="conversation-details">
                <?php if (empty($conversations)): ?>
                    <p class="no-conversation-message">Vous n'avez aucune conversation pour le moment.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <?php include_once __DIR__ . '/footer.php'; ?>
</body>
</html>
