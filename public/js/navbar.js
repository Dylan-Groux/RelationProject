// Mettre en surbrillance l'onglet actif
document.addEventListener('DOMContentLoaded', function() {
    var links = document.querySelectorAll('.header-title');
    links.forEach(function(link) {
        if (link.getAttribute('href') && window.location.pathname === new URL(link.getAttribute('href'), window.location.origin).pathname) {
            link.classList.add('active');
        }
    });
});
// JS pour ouvrir/fermer le menu mobile navbar

document.addEventListener('DOMContentLoaded', function() {
    const menuBtn = document.querySelector('.header-btn');
    const mobileMenuContent = document.querySelector('.header-menu-mobile-content');
    const mobileMenu = document.querySelector('.header-menu-mobile');

    if (menuBtn && mobileMenuContent && mobileMenu) {
        menuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            mobileMenuContent.classList.add('navbar-open');
            menuBtn.style.display = 'none';
        });

        document.addEventListener('click', function(e) {
            // Si le menu est ouvert et qu'on clique ailleurs que sur le menu ou le bouton
            if (mobileMenuContent.classList.contains('navbar-open')) {
                if (!mobileMenuContent.contains(e.target)) {
                    mobileMenuContent.classList.remove('navbar-open');
                    menuBtn.style.display = '';
                }
            }
        });
    }
});