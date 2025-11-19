<?php
use App\Services\Path;
use App\Library\EasyHeader;
EasyHeader::addHeader(
    'Page utilisateur',
    ['css/home.css', 'css/footer.css', 'css/navbar.css', 'css/user.css'],
    []
);
?>
<body>
    <?php include_once __DIR__ . '/navbar.php'; ?>
    <main class="main-content">

    </main>
    <?php include_once __DIR__ . '/footer.php'; ?>
</body>
</html>
