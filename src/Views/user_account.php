<?php
use App\Library\EasyHeader;
EasyHeader::addHeader(
    'Page de connexion',
    ['css/home.css', 'css/footer.css', 'css/navbar.css', 'css/user_account.css', 'css/Components/newbook.css', 'css/common.css'],
    ['js/navbar.js', 'js/button.js', 'js/newbook.js']
);
?>
<body>
    <?php include_once __DIR__ . '/navbar.php'; ?>
    <h1 class="main-title"> Mon compte </h1>
    <main class="main">
        <section class="user-section">
            <div>
                <?php
                $userPicture = $userData['user']->getPicture();
                if (empty($userPicture)) {
                    $userPicture = 'assets/utils/user-avatar.png'; // image par défaut
                } else {
                    $userPicture = htmlspecialchars($userPicture);
                }
                ?>
                <img class="user-avatar" src="<?= $userPicture ?>" alt="Avatar utilisateur" width="150" height="150">
                <form class="avatar-form" id='user-avatar-form' method="post" action="/public/user/picture/update/<?= htmlspecialchars($userData['user']->getId()) ?>" enctype="multipart/form-data">
                    <label class="modifier" for="user-avatar-upload">modifier</label>
                    <input type="file" id="user-avatar-upload" name="picture" accept=".jpeg,.jpg,.png">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                </form>
            </div>
            <div>
                <div class="line-bar"></div>
            </div>
            <?php include_once __DIR__ . '/Components/newbook.php'; ?>
            <div>
                <h2 class="user-title"><?= htmlspecialchars($userData['user']->getNickName()); ?></h2>
                <p class="user-member">Membre depuis <?= htmlspecialchars($userData['user']->getMembershipDuration()); ?></p>
                <div class="biblio-info">
                    <p class="biblio-info-title"><strong>BIBLIOTHEQUE</strong></p>
                    <div class="livre-info">
                        <img src="/assets/utils/biblio.svg" alt="Icone livre" width="12" height="12">
                        <p>
                            <?php
                            $availableBooks = array_filter($userData['books'], fn($book) => $book->getAvailability() === 'disponible' || $book->getAvailability() === "non disponible");
                            echo count($availableBooks) . ' livres';
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <section class="personal-info-section">
            <h3>Vos informations personnelles</h3>
            <form class="info-form" method="post" action="/public/user/update/<?= htmlspecialchars($userData['user']->getId()) ?>">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                <div class="info-grid">
                    <label class="info-email" for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($userData['user']->getEmail()) ?>">

                    <label class="info-mdp" for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" value="" placeholder="Nouveau mot de passe">

                    <label class="info-value" for="nickname">Pseudo</label>
                    <input type="text" id="nickname" name="nickname" value="<?= htmlspecialchars($userData['user']->getNickName()) ?>">
                </div>
                <button type="submit" class="edit-link">Enregistrer</button>
            </form>
        </section>
        <section class="liste-books-section-mobile">
            <div class="user-books-grid">
                <?php if (empty($userData['books'])): ?>
                    <div class="user-book-card modern-book-card empty-state">
                        <div class="modern-book-content">
                            <img src="/assets/utils/mystery-book.jpeg" alt="Aucun livre" class="user-book-image" width="100" height="100">
                            <div class="modern-book-text">
                                <p>Aucun livre ajouté</p>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                <?php
                $i = 0;
                foreach ($userData['books'] as $book):
                    $desc = $book->getComment();
                    $shortDesc = mb_strlen($desc) > 55 ? mb_substr($desc, 0, 55) . '...' : $desc;
                    $statusValues = ['disponible', 'non dispo.'];
                    $status = $statusValues[$i % 2];
                    $class = ($status === 'disponible') ? 'available' : 'unavailable';
                    $i++;
                ?>
                    <div class="user-book-card modern-book-card">
                        <div class="modern-book-content">
                            <img src="<?= htmlspecialchars($book->getPicture()) ?>" alt="<?= htmlspecialchars($book->getTitle()) ?>" class="user-book-image" width="100" height="100">
                            <div class="modern-book-text">
                                <h3 class="user-book-title"><?= htmlspecialchars($book->getTitle()) ?></h3>
                                <p class="user-book-author"><?= htmlspecialchars($book->getAuthor()) ?></p>
                                <p class="user-book-status <?= $class ?>"><?= htmlspecialchars($status) ?></p>
                            </div>
                        </div>
                        <p class="user-book-description"><?= htmlspecialchars($shortDesc) ?></p>
                        <div class="user-book-actions">
                            <a href="/public/book/edit/<?= $book->getId() ?>" class="edit-button">Editer</a>
                            <form method="POST" action="/public/book/delete/<?= $book->getId() ?>">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                                <button type="submit" class="delete-button">Supprimer</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
        <div class="liste-books-section-desktop">
            <div class="user-books-table-wrapper">
                <table class="user-books-table">
                    <thead>
                        <tr class="user-books-table-header">
                            <th>PHOTO</th>
                            <th>TITRE</th>
                            <th>AUTEUR</th>
                            <th>DESCRIPTION</th>
                            <th>DISPONIBILITE</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($userData['books'])): ?>
                        <tr>
                            <td class="user-book-image-cell">
                                <img src="/assets/utils/mystery-book.jpeg" alt="Aucun livre" class="user-book-image" width="70" height="70">
                            </td>
                            <td colspan="5">Aucun livre ajouté</td>
                        </tr>
                    <?php else: ?>
                    <?php foreach ($userData['books'] as $book): ?>
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
                            <td>
                                <?php
                                $statusAvailable = $book->getAvailability();
                                if ($statusAvailable === 'disponible') {
                                    $status = 'disponible';
                                } elseif ($statusAvailable === 'non disponible') {
                                    $status = 'non dispo.';
                                } else {
                                    $status = 'indisponible';
                                }
                                $class = ($status === 'disponible') ? 'available' : 'unavailable';
                                ?>
                                <span class="user-book-status <?= $class ?>"><?= htmlspecialchars($status) ?></span>
                            </td>
                            <td>
                                <a href="/public/book/edit/<?= $book->getId() ?>" class="edit-button">Editer</a>
                                <form method="POST" action="/public/book/delete/<?= $book->getId() ?>">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                                    <button type="submit" class="delete-button">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        form.addEventListener('submit', function(e) {
            submitBtn.disabled = true;
            let seconds = 15;
            timerDiv.textContent = 'Veuillez patienter : ' + seconds + 's';
            const interval = setInterval(function() {
                seconds--;
                timerDiv.textContent = 'Veuillez patienter : ' + seconds + 's';
                if (seconds <= 0) {
                    clearInterval(interval);
                    timerDiv.textContent = '';
                    submitBtn.disabled = false;
                }
            }, 1000);
        });
    });
    </script>
    <?php include_once __DIR__ . '/footer.php'; ?>
</body>
</html>
