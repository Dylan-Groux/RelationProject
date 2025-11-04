<?php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil</title>
    <link rel="stylesheet" href="/Openclassroom/RELATION/public/css/home.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header-bar">
        <img class="header-logo" src="/Openclassroom/RELATION/public/assets/utils/logo.png" alt="Logo" width="78px" height="24px">
        <span class="header-title">Mon Application</span>
        <button class="header-btn" aria-label="Menu">
            <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" role="img" aria-hidden="false" focusable="false">
            <rect x="3" y="4" width="4" height="16" rx="2" fill="currentColor"/>
            <rect x="10" y="4" width="4" height="16" rx="2" fill="currentColor"/>
            <rect x="17" y="4" width="4" height="16" rx="2" fill="currentColor"/>
            </svg>
        </button>
    </header>
    <main class="main-content">
        <section class="intro-section">
            <img class="start_img" src="/Openclassroom/RELATION/public/assets/home/header.png" alt="Échange de livres" width="425px" height="503px">
            <p class="author_name">Hamza</p>
        </section>
        <section class="info-section">
            <h2 class="info-title">Rejoignez nos lecteurs passionnés </h2>
            <p class="info-description">Donnez une nouvelle vie à vos livres en les échangeant avec d'autres amoureux de la lecture. Nous croyons en la magie du partage de connaissances et d'histoires à travers les livres. </p>
            <button class="signup-btn">Découvrir</button>
        </section>
        <section class="books-section">
            <div class="books-container">
                <h2 class="books-title">Les derniers livres <br>ajoutés</h2>
                <div class="books-grid">
                    <div class="book">
                        <img src="/Openclassroom/RELATION/public/assets/book/book1.png" class="book-img">
                        <p class="book-title">Titre du livre 1</p>
                        <p class="book-author">Auteur 1</p>
                        <p class="book-seller">Vendu par user 1</p>
                    </div>
                    <div class="book">
                        <img src="/Openclassroom/RELATION/public/assets/book/book2.png" class="book-img">
                        <p class="book-title">Titre du livre 2</p>
                        <p class="book-author">Auteur 2</p>
                        <p class="book-seller">Vendu par user 2</p>
                    </div>
                    <div class="book">
                        <img src="/Openclassroom/RELATION/public/assets/book/book3.png" class="book-img">
                        <p class="book-title">Titre du livre 3</p>
                        <p class="book-author">Auteur 3</p>
                        <p class="book-seller">Vendu par user 3</p>
                    </div>
                    <div class="book">
                        <img src="/Openclassroom/RELATION/public/assets/book/book4.png" class="book-img">
                        <p class="book-title">Titre du livre 4</p>
                        <p class="book-author">Auteur 4</p>
                        <p class="book-seller">Vendu par user 4</p>
                    </div>
                </div>
            </div>
        </section>
        <section class="cta-section">
            <h2 class="cta-title">Comment ça marche ?</h2>
            <h3 class="cta-subtitle">Échanger des livres avec TomTroc c’est simple et amusant ! Suivez ces étapes pour commencer :</h3>
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
            <button class="cta-btn">Voir tous les livres</button>
        </section>
        <section class="value-section">
            <img class="value-img" src="/Openclassroom/RELATION/public/assets/home/sectionbar_mobile.png" alt="Nos valeurs" width="375px" height="425px">
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
    <footer>
        <a href="#">Politique de confidentialité</a>
        <a href="#">Mentions légales</a>
        <p>Tom Troc©<p>
        <img src="/Openclassroom/RELATION/public/assets/utils/smallogo.png" alt="Nos valeurs" width="22px" height="17px">
    </footer>
</body>
</html>