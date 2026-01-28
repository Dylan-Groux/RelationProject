<?php
$messageRepository = new \App\Models\Repository\MessageRepository();

$countUnread = $messageRepository->countMessageNotRead($_SESSION['user_id'] ?? 0);

?>

<header class="header-bar">
    <img class="header-logo" src="/assets/utils/logo.png" alt="Logo" width="78" height="24">
    <div class="header-firstline">
        <a href="/public/" class="header-title">Accueil</a>
        <a href="/public/books" class="header-title">Nos livres à l'échange</a>
    </div>
    <div class="header-secondline">
        <?php
            if (isset($_SESSION['user_id'])) {
                echo '<a class="header-title" href="/messagerie/' . $_SESSION['user_id'] . '"><img src="/assets/icon/icon-msg.svg" alt="Messagerie" width="10" height="10"> Messagerie <span class="unread-count-badge">' . $countUnread . '</span></a>';
                echo '<a href="/user/account/' . $_SESSION['user_id'] . '" class="header-title"><img src="/assets/icon/icon-compte.svg" alt="Messagerie" width="10" height="10">Mon compte</a>';
                echo '<a class="header-title" href="/public/logout">Déconnexion</a>';
            } else {
                echo '<a class="header-title" href="/public/login">Connexion</a>';
            }
        ?>
    </div>
    <div class="header-menu-mobile">
        <button class="header-btn" aria-label="Menu">
            <img class="header-menu-icon" src="/assets/utils/menu.png" alt="Menu" width="24" height="12">
        </button>
        <div class="header-menu-mobile-content">
            <a href="/public/" class="header-title">Accueil</a>
            <a href="/public/books" class="header-title">Nos livres</a>
            <?php
                if (isset($_SESSION['user_id'])) {
                    echo '<a class="header-title" href="/messagerie/' . $_SESSION['user_id'] . '"><img src="/assets/icon/icon-msg.svg" alt="Messagerie" width="10" height="10"> Messagerie <span class="unread-count-badge">' . $countUnread . '</span></a>';
                    echo '<a href="/user/account/' . $_SESSION['user_id'] . '" class="header-title"><img src="/assets/icon/icon-compte.svg" alt="Messagerie" width="10" height="10">Mon compte</a>';
                    echo '<a class="header-title" href="/public/logout">Déconnexion</a>';
                } else {
                    echo '<a class="header-title" href="/public/login">Connexion</a>';
                }
            ?>
        </div>
    </div>
</header>
