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
        <div class="message-header">
            <h1><?= htmlspecialchars($currentUserId->getNickname()) ?></h1>
            <?php
            $userPicture = $currentUserId->getPicture();
            if (empty($userPicture)) {
                $userPicture = '/public/assets/utils/user-avatar.png'; // image par défaut
            } else {
                $userPicture = htmlspecialchars($userPicture);
            }
            ?>
                <img class="user-avatar" src="<?= $userPicture ?>" alt="Avatar utilisateur" width="150px" height="150px">
            <h2>Conversation #<?= htmlspecialchars($conversationId) ?></h2>
        </div>
        <?php foreach ($messages as $conv): ?>
            <div class="conversation">
                <div class="message-body">
                    <p><strong><?= htmlspecialchars($conv['sender_id']) ?>:</strong> <?= htmlspecialchars($conv['content']) ?></p>
                    <span class="timestamp"><?= htmlspecialchars($conv['sent_at']) ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    </main>
</body>
<?php include_once __DIR__ . '/footer.php'; ?>
</html>
