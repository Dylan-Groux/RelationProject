<?php
use App\Library\EasyHeader;
EasyHeader::addHeader(
    'Page utilisateur',
    ['css/home.css', 'css/footer.css', 'css/navbar.css', 'css/user.css', 'css/common.css'],
    ['js/navbar.js']
);
?>
<body>
    <?php include_once __DIR__ . '/navbar.php'; ?>
    <main class="main-content">
        <section class="user-section">
            <img class="user-avatar" src="<?= htmlspecialchars($user->getPicture()) ?>" alt="Avatar utilisateur" width="150" height="150">
            <h2 class="user-title"><?= htmlspecialchars($user->getNickname()) ?></h2>
            <p class="user-member">Membre depuis <?= htmlspecialchars($memberSince) ?></p>
            <div class="biblio-info">
                <p class="biblio-info-title"><strong>BIBLIOTHEQUE</strong></p>
                <div class="livre-info">
                    <img src="/assets/utils/biblio.svg" alt="Icone livre" width="12" height="12">
                    <p><?= $bookCount ?> livre<?= $bookCount > 1 ? 's' : '' ?></p>
                </div>
            </div>
            <?php foreach ($userBooks as $book): ?>
            <div class="message-section">
                <form method="POST" action="/public/messagerie/start/<?= htmlspecialchars($book->getId()) ?>">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">
                    <button type="submit" class="cta-btn">Écrire un message</button>
                </form>
            </div>
        </section>
        <section class="user-books-section-mobile">
            <div class="user-books-grid">
                    <div class="user-book-card modern-book-card">
                        <div class="modern-book-content">
                            <img src="<?= htmlspecialchars($book->getPicture()) ?>" alt="<?= htmlspecialchars($book->getTitle()) ?>" class="user-book-image" width="100" height="100">
                            <div class="modern-book-text">
                                <h3 class="user-book-title"><?= htmlspecialchars($book->getTitle()) ?></h3>
                                <p class="user-book-author"><?= htmlspecialchars($book->getAuthor()) ?></p>
                            </div>
                        </div>
                        <p class="user-book-description"><?= htmlspecialchars($book->getComment()) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>
        <section class="user-books-section-desktop">
            <div class="user-books-table-wrapper">
                <table class="user-books-table">
                    <thead>
                        <tr class="user-books-table-header">
                            <th>PHOTO</th>
                            <th>TITRE</th>
                            <th>AUTEUR</th>
                            <th>DESCRIPTION</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($userBooks as $book): ?>
                        <tr>
                            <td class="user-book-image-cell"><img src="<?= htmlspecialchars($book->getPicture()) ?>" alt="<?= htmlspecialchars($book->getTitle()) ?>" class="user-book-image" width="70" height="70"></td>
                            <td><?= htmlspecialchars($book->getTitle()) ?></td>
                            <td><?= htmlspecialchars($book->getAuthor()) ?></td>
                            <td>
                                <?php
                                $desc = htmlspecialchars($book->getComment());
                                echo (mb_strlen($desc) > 70) ? mb_substr($desc, 0, 70) . '...' : $desc;
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
    <?php include_once __DIR__ . '/footer.php'; ?>
</body>
</html>
