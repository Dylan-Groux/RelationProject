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
        <?php foreach ($conversations as $conv): ?>
            <div class="conversation">
                <img class="conversation-picture" src="<?= htmlspecialchars($conv['picture']) ?>" alt="Photo de profil" />
                <span><?= htmlspecialchars($conv['nickname']) ?></span>
                <span><?= htmlspecialchars($conv['last_message']) ?></span>
                <span><?= htmlspecialchars($conv['last_date']) ?></span>
                <a href="/public/conversation/<?= htmlspecialchars($conv['relation_id']) ?>">Ouvrir</a>
            </div>
        <?php endforeach; ?>
    </main>
</body>
<?php include_once __DIR__ . '/footer.php'; ?>
</html>
