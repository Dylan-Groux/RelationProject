<?php
use App\Library\EasyHeader;
EasyHeader::addHeader(
    'Page d\'accueil',
    ['css/home.css', 'css/books.css', 'css/footer.css', 'css/navbar.css'],
    ['js/navbar.js']
);
?>
<body>
    <?php include_once __DIR__ . '/navbar.php'; ?>
    <main class="main-content">
        <section class="search-section">
            <h2 class="search-title">Nos livres à l'échange</h2>
            <form method="GET" class="search-bar-container">
                 <span class="search-icon">
                    <!-- SVG loupe -->
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <circle cx="11" cy="11" r="8" stroke="#A6A6A6" stroke-width="2"/>
                        <line x1="17" y1="17" x2="22" y2="22" stroke="#A6A6A6" stroke-width="2"/>
                    </svg>
                </span>
                <input type="text" name="search" class="search-input" placeholder="Rechercher un livre" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </form>
        </section>
        <section class="books-section">
            <div class="books-container">
                <div class="books-grid">
                <?php foreach ($booksWithUser as $dto): ?>
                    <a class="book" href="<?= '/public/book/' . htmlspecialchars($dto->book->getId()) ?>">
                        <img src="<?= htmlspecialchars($dto->book->getPicture()) ?>" class="book-img" alt="Couverture du livre">
                        <h1 class="book-title"><?= htmlspecialchars($dto->book->getTitle()) ?></h1>
                        <h2 class="book-author"><?= htmlspecialchars($dto->book->getAuthor()) ?></h2>
                        <p class="book-seller">Vendu par <?= htmlspecialchars($dto->userNickname) ?></p>
                    </a>
                <?php endforeach; ?>
                </div>
            </div>
            <div class="pagination-container">
                <?php
                $searchParam = isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '';
                for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a class="pagination-link" href="?page=<?= $i . $searchParam ?>"><?= $i ?></a>
                <?php endfor; ?>
            </div>
        </section>
    </main>
    <?php include_once __DIR__ . '/footer.php'; ?>
</body>
</html>
