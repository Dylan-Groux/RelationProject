<?php
use App\Library\EasyHeader;
EasyHeader::addHeader(
    'Page d\'inscription',
    ['css/home.css', 'css/footer.css', 'css/navbar.css', 'css/register.css', 'css/common.css'],
    ['js/navbar.js']
);
?>
<body>
    <?php include_once __DIR__ . '/navbar.php'; ?>
    <main class="main">
        <div class="main-content">
            <section class="register-form-section">
                <h2 class="register-title">Inscription</h2>
                <!-- CSRF Token à faire -->
                <form class="register-form" method="POST" action="/public/register/userRegister">
                    <div id="nickname-label">
                        <label for="nickname" class="register-label">Pseudo</label>
                        <input type="text" id="nickname" name="nickname" class="register-input" required>
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
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                    <button type="submit" class="signup-btn">S'inscrire</button>
                </form>
                <div class="login-link">
                    <p>Déjà inscrit ? <a href="/public/login">Connectez-vous</a></p>
                </div>
            </section>
                <img class="start_img" src="/assets/home/register.png" alt="Échange de livres" width="425" height="503">
        </div>
    </main>
    <?php include_once __DIR__ . '/footer.php'; ?>
</body>
</html>
