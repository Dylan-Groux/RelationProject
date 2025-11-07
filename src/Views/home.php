<?php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil</title>
    <link rel="stylesheet" href="/Openclassroom/RELATION/public/css/home.css">
    <link rel="stylesheet" href="/Openclassroom/RELATION/public/css/navbar.css">
    <link rel="stylesheet" href="/Openclassroom/RELATION/public/css/footer.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <main class="main-content">
        <div class="header-container">
            <div class="header-flex">
            <section class="intro-section">
                <img class="start_img" src="/Openclassroom/RELATION/public/assets/home/header.png" alt="Échange de livres" width="425px" height="503px">
                <p class="author_name">Hamza</p>
            </section>
            <section class="info-section">
                <h2 class="info-title">Rejoignez nos lecteurs passionnés </h2>
                <p class="info-description">Donnez une nouvelle vie à vos livres en les échangeant avec d'autres amoureux de la lecture. Nous croyons en la magie du partage de connaissances et d'histoires à travers les livres. </p>
                <button class="signup-btn">Découvrir</button>
            </section>
            </div>
        </div>
        <section class="books-section">
            <div class="books-container">
                <h2 class="books-title">Les derniers livres ajoutés</h2>
                <div class="books-grid">
                <?php $count = 0; ?>
                <?php foreach ($books as $book): ?>
                    <?php if ($count >= 4) break; ?>
                    <div class="book">
                        <img src="/Openclassroom/RELATION/<?= htmlspecialchars($book->getPicture()) ?>" class="book-img" alt="Couverture du livre">
                        <p class="book-title"><?= htmlspecialchars($book->getTitle()) ?></p>
                        <p class="book-author"><?= htmlspecialchars($book->getAuthor()) ?></p>
                        <p class="book-seller">Vendu par user <?= htmlspecialchars($book->getUserId()) ?></p>
                    </div>
                    <?php $count++; ?>
                <?php endforeach; ?>
                </div>
                <div class="btn-container">
                    <button class="books-btn">Voir tous les livres</button>
                </div>
            </div>
        </section>
        <section class="cta-section">
            <h2 class="cta-title">Comment ça marche ?</h2>
            <h3 class="cta-subtitle">Échanger des livres avec TomTroc c’est simple et amusant ! Suivez ces étapes pour commencer :</h3>
            <div class="cta-wrapper">
                <div class="cta-card">
                    <p>Inscrivez-vous gratuitement sur <br> notre plateforme.</p>
                </div>
                <div class="cta-card">
                    <p>Ajoutez les livres que vous souhaitez <br> échanger à votre profil.</p>
                </div>
                <div class="cta-card">
                    <p>Parcourez les livres disponibles chez <br> d'autres membres.</p>
                </div>
                <div class="cta-card">
                    <p>Proposez un échange et discutez avec <br> d'autres passionnés de lecture.</p>
                </div>
            </div>
            <div class="btn-container">
                <button class="cta-btn">Voir tous les livres</button>
            </div>
        </section>
        <section class="value-section">
            <img class="value-img-mobile" src="/Openclassroom/RELATION/public/assets/home/sectionbar_mobile.png" alt="Nos valeurs" width="375px" height="425px">
            <img class="value-img-desktop" src="/Openclassroom/RELATION/public/assets/home/sectionbar_desktop.png" alt="Nos valeurs" width="1440px" height="230px">
            <div class="value-content">
                <h2 class="value-title">Nos valeurs</h2>
                <p class="value-description">
                    Chez Tom Troc, nous mettons l'accent sur le partage, la découverte et la communauté. 
                    Nos valeurs sont ancrées dans notre passion pour les livres et notre désir de créer 
                    des liens entre les lecteurs. Nous croyons en la puissance des histoires pour rassembler 
                    les gens et inspirer des conversations enrichissantes.
                </p>
                <p class="value-description">
                    Notre association a été fondée avec 
                    une conviction profonde : chaque livre mérite d'être lu et partagé. 
                </p>
                <p class="value-description">
                    Nous sommes passionnés
                    par la création d'une plateforme conviviale qui permet aux lecteurs de se connecter, 
                    de partager leurs découvertes littéraires et d'échanger des livres qui attendent patiemment
                     sur les étagères.
                </p>
                <p class="author_name">L'équipe Tom Troc</p>
            </div>
            <img class="vectorlove-img" src="/Openclassroom/RELATION/public/assets/utils/lovevector.svg" alt="Nos valeurs" width="370px" height="573px">
        </section>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>