<?php
use App\Services\Path;
use App\Library\EasyHeader;
EasyHeader::addHeader(
    'Page de connexion',
    ['css/home.css', 'css/footer.css', 'css/navbar.css', 'css/user_account.css'],
    ['js/navbar.js']
);
?>
<body>
    <?php include_once __DIR__ . '/navbar.php'; ?>
    <h1 class="main-title"> Mon compte </h1>
    <main class="main">
        <section class="user-section">
            <div>
                <img class="user-avatar" src="<?= Path::url('/public/assets/utils/user-avatar.png') ?>" alt="Avatar utilisateur" width="150px" height="150px">
                <p class="modifier">modifier</p>
            </div>
            <div>
                <div style="height:1px; width:242px; background-color:#F5F3EF; margin:20px auto;"></div>
            </div>
            <div>
                <h2 class="user-title">Alexlecture</h2>
                <p class="user-member">Membre depuis 1 an</p>
                <div class="biblio-info">
                    <p class="biblio-info-title"><strong>BIBLIOTHEQUE</strong></p>
                    <div class="livre-info">
                        <img src="<?= Path::url('/public/assets/utils/biblio.svg') ?>" alt="Icone livre" width="12px" height="12px">
                        <p> <?php // Afficher le nombre de livres de l'utilisateur
                            $bookCount = 5; // Exemple de valeur
                            echo $bookCount . ' livres';
                        ?></p>
                    </div>
                </div>
            </div>
        </section>
        <section class="personal-info-section">
            <h3>Vos informations personnelles</h3>
            <form class="info-form" method="post" action="">
                <div class="info-grid">
                    <label class="info-email" for="email">Adresse email</label>
                    <input type="email" id="email" name="email" value="alexlecture@example.com" required>

                    <label class="info-mdp" for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" value="" placeholder="Nouveau mot de passe">

                    <label class="info-value" for="username">Pseudo</label>
                    <input type="text" id="username" name="username" value="Alexlecture" required>
                </div>
                <button type="submit" class="edit-link">Enregistrer</button>
            </form>
        </section>
        <section class="liste-books-section-mobile">
            <div class="user-books-grid">
                <?php
                // Exemple de livres proposés par l'utilisateur
                $userBooks = [
                    ['title' => 'Le Petit Prince', 'author' => 'Antoine de Saint-Exupéry', 'image' => 'public/assets/book/book2.png', 'description' => 'Un conte poétique et philosophique. Il raconte l\'histoire d\'un petit prince qui voyage de planète en planète, rencontrant divers personnages et apprenant des leçons de vie profondes.'],
                    ['title' => 'Les Misérables', 'author' => 'Victor Hugo', 'image' => 'public/assets/book/book3.png', 'description' => 'Un roman historique et social. Il explore les thèmes de la justice, de la rédemption et de la lutte contre l\'injustice à travers les vies entrelacées de plusieurs personnages dans la France du XIXe siècle.'],
                    ['title' => 'Madame Bovary', 'author' => 'Gustave Flaubert', 'image' => 'public/assets/book/book4.png', 'description' => 'Un roman réaliste sur la vie provinciale. Il raconte l\'histoire d\'Emma Bovary, une femme insatisfaite de sa vie de province, cherchant à échapper à la banalité par des aventures amoureuses et des dépenses extravagantes.'],
                    ['title' => '1984', 'author' => 'George Orwell', 'image' => 'public/assets/book/book1.png', 'description' => 'Un roman dystopique sur la surveillance et le totalitarisme. Je n\'ai jamais rien lu de tel auparavant, c\'est à la fois effrayant et fascinant.'],
                ];
                $i = 0;
                foreach ($userBooks as $book): 
                    $desc = $book['description'];
                    $shortDesc = mb_strlen($desc) > 55 ? mb_substr($desc, 0, 55) . '...' : $desc;
                    $statusValues = ['disponible', 'non dispo.'];
                    $status = $statusValues[$i % 2];
                    $class = ($status === 'disponible') ? 'available' : 'unavailable';
                    $i++;
                ?>
                    <div class="user-book-card modern-book-card">
                        <div class="modern-book-content">
                            <img src="<?= Path::url($book['image']) ?>" alt="<?= htmlspecialchars($book['title']) ?>" class="user-book-image" width="100" height="100">
                            <div class="modern-book-text">
                                <h3 class="user-book-title"><?= htmlspecialchars($book['title']) ?></h3>
                                <p class="user-book-author"><?= htmlspecialchars($book['author']) ?></p>
                                <p class="user-book-status <?= $class ?>"><?= htmlspecialchars($status) ?></p>
                            </div>
                        </div>
                        <p class="user-book-description"><?= htmlspecialchars($shortDesc) ?></p>
                        <div class="user-book-actions">
                            <button class="edit-button">Editer</button>
                            <button class="delete-button">Supprimer</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <section class="liste-books-section-desktop">
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
                    <?php
                    $userBooks = [
                        ['title' => 'Le Petit Prince', 'author' => 'Antoine de Saint-Exupéry', 'image' => 'public/assets/book/book2.png', 'description' => 'Un conte poétique et philosophique. Il raconte l\'histoire d\'un petit prince qui voyage de planète en planète, rencontrant divers personnages et apprenant des leçons de vie profondes.'],
                        ['title' => 'Les Misérables', 'author' => 'Victor Hugo', 'image' => 'public/assets/book/book3.png', 'description' => 'Un roman historique et social. Il explore les thèmes de la justice, de la rédemption et de la lutte contre l\'injustice à travers les vies entrelacées de plusieurs personnages dans la France du XIXe siècle.'],
                        ['title' => 'Madame Bovary', 'author' => 'Gustave Flaubert', 'image' => 'public/assets/book/book4.png', 'description' => 'Un roman réaliste sur la vie provinciale. Il raconte l\'histoire d\'Emma Bovary, une femme insatisfaite de sa vie de province, cherchant à échapper à la banalité par des aventures amoureuses et des dépenses extravagantes.'],
                        ['title' => '1984', 'author' => 'George Orwell', 'image' => 'public/assets/book/book1.png', 'description' => 'Un roman dystopique sur la surveillance et le totalitarisme. Je n\'ai jamais rien lu de tel auparavant, c\'est à la fois effrayant et fascinant.'],
                    ];
                    foreach ($userBooks as $book): ?>
                        <tr>
                            <td class="user-book-image-cell"><img src="<?= Path::url($book['image']) ?>" alt="<?= htmlspecialchars($book['title']) ?>" class="user-book-image" width="70" height="70"></td>
                            <td><?= htmlspecialchars($book['title']) ?></td>
                            <td><?= htmlspecialchars($book['author']) ?></td>
                            <td>
                                <?php
                                $desc = htmlspecialchars($book['description']);
                                echo (mb_strlen($desc) > 70) ? mb_substr($desc, 0, 70) . '...' : $desc;
                                ?>
                            </td>
                            <td>
                                <?php
                                $status = ($book['title'] === '1984') ? 'non dispo.' : 'disponible';
                                $class = ($status === 'disponible') ? 'available' : 'unavailable';
                                ?>
                                <span class="user-book-status <?= $class ?>"><?= htmlspecialchars($status) ?></span>
                            </td>
                            <td>
                                <button class="edit-button">Editer</button>
                                <button class="delete-button">Supprimer</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
<?php include_once __DIR__ . '/footer.php'; ?>
</html>
