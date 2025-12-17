<?php
use App\Library\EasyHeader;
EasyHeader::addHeader(
    'Page de connexion',
    ['css/home.css', 'css/footer.css', 'css/navbar.css', 'css/messagerie.css'],
    ['js/navbar.js']
);
?>
<body>
    <?php include_once __DIR__ . '/navbar.php'; ?>
    <main class="main">
        <h1 class="main-title"> Messagerie </h1>
        <?php if (empty($conversations)): ?>
            <p class="no-conversation-message">Vous n'avez aucune conversation pour le moment.</p>
        <?php endif; ?>
        <?php foreach ($conversations as $conv): ?>
            <a href="/public/conversation/<?= htmlspecialchars($conv['relation_id']) ?>">
                <div class="conversation-grid">
                    <img class="conversation-picture" src="<?= htmlspecialchars($conv['picture']) ?>" alt="Profil" />
                    <span class="conversation-nickname"><?= htmlspecialchars($conv['nickname']) ?></span>
                    <span class="conversation-message"><?= htmlspecialchars($conv['last_message']) ?></span>
                    <span class="conversation-date"><?= htmlspecialchars($conv['last_date']) ?></span>
                </div>
            </a>
        <?php endforeach; ?>
    </main>
</body>
<?php include_once __DIR__ . '/footer.php'; ?>
</html>
