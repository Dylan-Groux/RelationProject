<?php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil</title>
    <link rel="stylesheet" href="/Openclassroom/RELATION/public/css/home.css">
    <link rel="stylesheet" href="/Openclassroom/RELATION/public/css/books.css">
    <link rel="stylesheet" href="/Openclassroom/RELATION/public/css/footer.css">
    <link rel="stylesheet" href="/Openclassroom/RELATION/public/css/navbar.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/navbar.php'; ?>
    <main class="main-content">
        <section class="search-section">
            <h2 class="search-title">Nos livres à l'échange</h2>
            <div class="search-bar-container">
                 <span class="search-icon">
                    <!-- SVG loupe -->
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <circle cx="11" cy="11" r="8" stroke="#A6A6A6" stroke-width="2"/>
                        <line x1="17" y1="17" x2="22" y2="22" stroke="#A6A6A6" stroke-width="2"/>
                    </svg>
                </span>
                <input type="text" class="search-input" placeholder="Rechercher un livre">
            </div>
        </section>
        <section class="books-section">
            <div class="books-container">
                <div class="books-grid">
                <?php foreach ($books as $book): ?>
                    <div class="book">
                        <img src="/Openclassroom/RELATION/<?= htmlspecialchars($book->getPicture()) ?>" class="book-img" alt="Couverture du livre">
                        <p class="book-title"><?= htmlspecialchars($book->getTitle()) ?></p>
                        <p class="book-author"><?= htmlspecialchars($book->getAuthor()) ?></p>
                        <p class="book-seller">Vendu par <?= htmlspecialchars($book->getUserId()) ?></p>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
            <div class="pagination-container">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a class="pagination-link" href="?page=<?= $i ?>"><?= $i ?></a>
                <?php endfor; ?>
            </div>
        </section>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
