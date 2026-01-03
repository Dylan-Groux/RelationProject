<?php
use App\Library\EasyHeader;
EasyHeader::addHeader(
    'Page de connexion',
    ['css/home.css', 'css/footer.css', 'css/navbar.css', 'css/conversation.css'],
    ['js/navbar.js']
);
?>
<body>
    <?php include_once __DIR__ . '/navbar.php'; ?>
    <main class="main">
        <a class="back" href="/public/messagerie/{<?= htmlspecialchars($currentUserId) ?>}">
            &#8592; retour
        </a>
        <div class="receiver-info-grid">
            <img class="receiver-avatar" src="<?= $otherPicture ?>" alt="Avatar utilisateur" width="100px" height="100px">
            <span class="receiver-nickname"> <?= htmlspecialchars($otherNickname) ?> </span>
        </div>
        <?php foreach ($messages as $conv): ?>
            <div class="conversation">
                <div class="message-body">
                    <p>
                        <?php if ($conv['sender_id'] == $currentUserId): ?>
                            <span class="message-sent-by-you"><strong>Vous: </strong></span>
                        <?php else: ?>
                            <img class="receiver-avatar-conversation" src="<?= $otherPicture ?>" alt="Avatar utilisateur" width="100px" height="100px">
                        <?php endif; ?>
                        <span class="timestamp"><?= htmlspecialchars($conv['sent_at']) ?></span>
                        <br>
                        <?= htmlspecialchars($conv['content']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="send-message-form">
            <form action="/public/messagerie/conversation/<?= htmlspecialchars($conversationId) ?>/send" method="POST">
                <textarea name="message" placeholder="Écrire un message..." required></textarea>
                <input type="hidden" name="CSRF_token" value="<?= htmlspecialchars($csrfToken) ?>">
                <button type="submit">Envoyer</button>
            </form>
        </div>
    </main>
</body>
<?php include_once __DIR__ . '/footer.php'; ?>
</html>
