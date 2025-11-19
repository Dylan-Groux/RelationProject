<?php
use App\Services\Path;
use App\Library\EasyHeader;
EasyHeader::addHeader(
    'Page du livre',
    ['css/home.css', 'css/book.css', 'css/navbar.css', 'css/footer.css'],
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
            <img src="<?= Path::url(htmlspecialchars($book->getPicture())) ?>" class="book-img" alt="Couverture du livre" width="720px" height="863px">
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
                <div>
                    <p class="user-name">Utilisateur <?= htmlspecialchars($book->getUserId()) ?></p>
                </div>
                <button class="signup-btn">Envoyer un message</button>
            </section>
        </div>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
