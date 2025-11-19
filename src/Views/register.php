<?php
use App\Services\Path;
use App\Library\EasyHeader;
EasyHeader::addHeader(
    'Page d\'inscription',
    ['css/home.css', 'css/footer.css', 'css/navbar.css', 'css/register.css'],
    ['js/navbar.js']
);
?>
<body>
    <?php include_once __DIR__ . '/navbar.php'; ?>
    <main class="main">
        <div class="main-content">
            <section class="register-form-section">
                <h2 class="register-title">Inscription</h2>
                <form class="register-form" method="POST" action="">
                    <div id="user-label">
                        <label for="username" class="register-label">Pseudo</label>
                        <input type="text" id="username" name="username" class="register-input" required>
                    </div>
                    <div id="name-label">
                        <label for="name" class="register-label">Nom</label>
                        <input type="text" id="name" name="name" class="register-input" required>
                    </div>
                    <div id="email-label">
                        <label for="email" class="register-label">Adresse email</label>
                        <input type="email" id="email" name="email" class="register-input" required>
                    </div>
                    <div id="password-label">
                        <label for="password" class="register-label">Mot de passe</label>
                        <input type="password" id="password" name="password" class="register-input" required>
                    </div>
                    <button type="submit" class="signup-btn">S'inscrire</button>
                </form>
                <div class="login-link">
                    <p>Déjà inscrit ? <a href="<?= Path::url('/public/login') ?>">Connectez-vous</a></p>
                </div>
            </section>
                <img class="start_img" src="<?= Path::url('/public/assets/home/register.png') ?>" alt="Échange de livres" width="425px" height="503px">
        </div>
    </main>
</body>
<?php include_once __DIR__ . '/footer.php'; ?>
</html>
