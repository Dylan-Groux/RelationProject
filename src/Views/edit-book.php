<?php
use App\Library\EasyHeader;
EasyHeader::addHeader(
    'Page du livre',
    ['css/home.css', 'css/edit-book.css', 'css/navbar.css', 'css/footer.css', 'css/common.css'],
    ['js/navbar.js']
);
?>
<body>
    <?php include __DIR__ . '/navbar.php'; ?>
    <div class="route-path">
        <a href="<?= '/public/books' ?>" class="back-link">
            &#8592; retour
        </a>
    </div>
    <h1 class="main-title"> Modifier les informations </h1>
    <main class="main-content">
        <div class="book-img-container">
            <p>Photo</p>
            <img src="<?= htmlspecialchars($book->getPicture()) ?>" class="book-img" alt="Couverture du livre" width="720" height="863">
            <!-- Formulaire pour modifier la photo du livre ne marche pas encore -->
            <form class="edit-picture-form" action="<?= '/public/book/' . $book->getId() . '/edit-picture' ?>" method="post" enctype="multipart/form-data">
                <label for="picture" class="edit-picture-label">Modifier la photo</label>
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                <input type="file" id="picture" name="picture" accept="image/*" required>
            </form>
        </div>
        <div class="books-all-container">
            <section class="books-section">
                <!-- CSRF Token à faire -->
                <form class="book-info-container" method="post" action="/public/book/update/<?= $book->getId() ?>">
                    <label for="title">Titre</label>
                    <input class="info-title" type="text" id="title" name="title" value="<?= htmlspecialchars($book->getTitle()) ?>" required>

                    <label for="author">Auteur</label>
                    <input class="info-author" type="text" id="author" name="author" value="<?= htmlspecialchars($book->getAuthor()) ?>" required>
                    <label for="comment">Commentaire</label>
                    <textarea class="info-comment" id="comment" name="comment" rows="5" required><?= htmlspecialchars($book->getComment()) ?></textarea>

                    <label for="availability">Disponibilité</label>
                    <select class="info-disponibility" id="availability" name="availability">
                        <option value="1" <?= $book->getAvailability() == 1 ? 'selected' : '' ?>>Disponible</option>
                        <option value="0" <?= $book->getAvailability() == 0 ? 'selected' : '' ?>>Non disponible</option>
                    </select>
                    <input type="hidden" name="CSRF_token" value="<?= htmlspecialchars($csrfToken) ?>">
                    <button type="submit" class="signup-btn">Valider</button>
                </form>
            </section>
        </div>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
