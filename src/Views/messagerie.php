<?php
use App\Services\Path;
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
        <?php foreach ($messages as $conv): ?>
            <div class="conversation">
                <img src="<?= Path::url(htmlspecialchars($conv['picture'])) ?>" alt="Photo de profil" />
                <span><?= htmlspecialchars($conv['nickname']) ?></span>
                <span><?= htmlspecialchars($conv['last_message']) ?></span>
                <span><?= htmlspecialchars($conv['last_date']) ?></span>
                <a href="?conversation_id=<?= $conv['relation_id'] ?>">Ouvrir</a>
            </div>
        <?php endforeach; ?>
    </main>
</body>
<?php include_once __DIR__ . '/footer.php'; ?>
</html>
