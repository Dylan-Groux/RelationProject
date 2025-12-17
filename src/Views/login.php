<?php
use App\Library\EasyHeader;
EasyHeader::addHeader(
    'Page de connexion',
    ['css/home.css', 'css/footer.css', 'css/navbar.css', 'css/register.css'],
    ['js/navbar.js']
);
?>
<body>
    <?php include_once __DIR__ . '/navbar.php'; ?>
    <main class="main">
        <div class="main-content">
            <section class="register-form-section">
                <h2 class="register-title">Connexion</h2>
                <form class="register-form" method="POST" action="/public/login/userLogin">
                    <div id="email-label">
                        <label for="mail" class="register-label">Adresse email</label>
                        <input type="mail" id="mail" name="mail" class="register-input" required>
                    </div>
                    <div id="password-label">
                        <label for="password" class="register-label">Mot de passe</label>
                        <input type="password" id="password" name="password" class="register-input" required>
                    </div>
                    <button type="submit" class="signup-btn">Se connecter</button>
                </form>
                <div class="login-link">
                    <p>Pas encore de compte ? <a href="/public/register">Inscrivez-vous</a></p>
                </div>
            </section>
                <img class="start_img" src="/public/assets/home/register.png" alt="Échange de livres" width="425px" height="503px">
        </div>
    </main>
</body>
<?php include_once __DIR__ . '/footer.php'; ?>
</html>
