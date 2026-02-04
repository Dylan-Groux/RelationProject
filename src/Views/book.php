<?php
use App\Library\EasyHeader;
EasyHeader::addHeader(
    'Page du livre',
    ['css/common.css', 'css/book.css', 'css/navbar.css', 'css/footer.css'],
    ['js/navbar.js']
);
?>
<body>
    <?php include __DIR__ . '/navbar.php'; ?>
    <div class="route-path">
        <p> Nos livres  >  <?= htmlspecialchars($book->getTitle()) ?> </p>
    </div>
    <main class="main-content">
        <div class="book-img-container">
            <img src="<?= htmlspecialchars($book->getPicture()) ?>" class="book-img" alt="Couverture du livre" width="720" height="863">
        </div>
        <div class="books-all-container">
            <section class="books-section">
                <div class="book-info-container">
                    <h2 class="book-title"><?= htmlspecialchars($book->getTitle()) ?></h2>
                    <h3 class="book-author">par <?= htmlspecialchars($book->getAuthor()) ?></h3>
                    <hr class="custom-line">
                    <h4>DESCRIPTION</h4>
                    <p class="book-comment"><?= nl2br(htmlspecialchars($book->getComment())) ?></p>
                </div>
            </section>
            <section class="user-section">
                <h4>PROPRIÉTAIRE</h4>
                <div class="user-info-grid">
                    <a class="user-info-link" href="/public/user/<?= htmlspecialchars($book->getUserId()) ?>">
                        <img class="user-picture" src="<?= htmlspecialchars($userPicture) ?>" alt="Photo de profil" width="50" height="50">
                        <h2 class="user-nickname"><?= htmlspecialchars($userNickname) ?></h2>
                    </a>
                </div>
                <a href="/public/messagerie/start/<?= htmlspecialchars($book->getId()) ?>" class="signup-btn">Envoyer un message</a>
            </section>
        </div>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
