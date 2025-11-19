<header class="header-bar">
    <img class="header-logo" src="/Openclassroom/RELATION/public/assets/utils/logo.png" alt="Logo" width="78px" height="24px">
    <div class="header-firstline">
        <a href="http://localhost/Openclassroom/RELATION/public/" class="header-title">Accueil</a>
        <a href="http://localhost/Openclassroom/RELATION/public/books" class="header-title">Nos livres à l'échange</a>
    </div>
    <div class="header-secondline">
        <a class="header-title"><img src="/Openclassroom/RELATION/public/assets/icon/icon-msg.svg" alt="Messagerie" width="10px" height="10px"> Messagerie</a>
        <a class="header-title"><img src="/Openclassroom/RELATION/public/assets/icon/icon-compte.svg" alt="Messagerie" width="10px" height="10px">Mon compte</a>
        <?php if (isset($_SESSION['user'])): ?>
            <a class="header-title" href="/Openclassroom/RELATION/public/logout">Déconnexion</a>
        <?php else: ?>
            <a class="header-title" href="/Openclassroom/RELATION/public/login">Connexion</a>
        <?php endif; ?>
    </div>
    <div class="header-menu-mobile">
        <button class="header-btn" aria-label="Menu">
            <img class="header-menu-icon" src="/Openclassroom/RELATION/public/assets/utils/menu.png" alt="Menu" width="24px" height="12px">
        </button>
        <div class="header-menu-mobile-content">
            <a href="http://localhost/Openclassroom/RELATION/public/" class="header-title">Accueil</a>
            <a href="http://localhost/Openclassroom/RELATION/public/books" class="header-title">Nos livres</a>
            <a class="header-title"><img src="/Openclassroom/RELATION/public/assets/icon/icon-msg.svg" alt="Messagerie" width="10px" height="10px"> Messagerie</a>
            <a class="header-title"><img src="/Openclassroom/RELATION/public/assets/icon/icon-compte.svg" alt="Messagerie" width="10px" height="10px">Mon compte</a>
            <?php if (isset($_SESSION['user'])): ?>
                <a class="header-title" href="/Openclassroom/RELATION/public/logout">Déconnexion</a>
            <?php else: ?>
                <a class="header-title" href="/Openclassroom/RELATION/public/login">Connexion</a>
            <?php endif; ?>
        </div>
    </div>
</header>
