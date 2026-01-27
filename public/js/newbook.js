/**
 * Gestion de la modal d'ajout de livre
 */
document.addEventListener('DOMContentLoaded', function() {
    const modalOverlay = document.getElementById('newBookModalOverlay');
    const openModalBtn = document.getElementById('openNewBookModal');
    const closeModalBtn = document.getElementById('closeNewBookModal');
    const bookForm = document.getElementById('newBookForm');

    // Ouvrir la modal
    if (openModalBtn) {
        openModalBtn.addEventListener('click', function() {
            modalOverlay.classList.add('active');
            document.body.style.overflow = 'hidden'; // Empêche le scroll du body
        });
    }

    // Fermer la modal
    function closeModal() {
        modalOverlay.classList.remove('active');
        document.body.style.overflow = ''; // Réactive le scroll
    }

    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', closeModal);
    }

    // Fermer en cliquant sur l'overlay
    if (modalOverlay) {
        modalOverlay.addEventListener('click', function(e) {
            if (e.target === modalOverlay) {
                closeModal();
            }
        });
    }

    // Fermer avec la touche Échap
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modalOverlay.classList.contains('active')) {
            closeModal();
        }
    });

    // Gestion de la soumission du formulaire (optionnel)
    if (bookForm) {
        bookForm.addEventListener('submit', function(e) {
            // Tu peux ajouter une validation ou un loader ici si besoin
            const submitBtn = bookForm.querySelector('.submit-button');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Ajout en cours...';
            }
        });
    }
});
