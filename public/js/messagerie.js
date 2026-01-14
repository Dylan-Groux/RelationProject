// Permet d'envoyer le message avec la touche Entrée dans le textarea du formulaire de messagerie

document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('.send-message-form form');
    const textarea = form.querySelector('textarea');

    textarea.addEventListener('keydown', function (event) {
        // Si on appuie sur Entrée sans Shift
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault(); // Empêche le retour à la ligne
            form.submit(); // Soumet le formulaire
        }
    });

    window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
});
