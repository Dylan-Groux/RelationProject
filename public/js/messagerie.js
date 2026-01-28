// Permet d'envoyer le message avec la touche Entrée dans le textarea du formulaire de messagerie

document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('.send-message-form form');
    
    forms.forEach(function(form) {
        const textarea = form.querySelector('textarea');
        
        if (textarea) {
            // Mettre le focus automatiquement sur le textarea
            textarea.focus();
            
            textarea.addEventListener('keydown', function (event) {
                // Si on appuie sur Entrée sans Shift
                if (event.key === 'Enter' && !event.shiftKey) {
                    event.preventDefault(); // Empêche le retour à la ligne
                    form.submit(); // Soumet le formulaire
                }
            });
        }
    });

    // Scroller automatiquement en bas de la conversation
    const mobileMessagesContainer = document.querySelector('.mobile-messages-container');
    const desktopMessagesContainer = document.querySelector('.conversations-messages-container');
    
    if (mobileMessagesContainer) {
        mobileMessagesContainer.scrollTop = mobileMessagesContainer.scrollHeight;
    }
    
    if (desktopMessagesContainer) {
        desktopMessagesContainer.scrollTop = desktopMessagesContainer.scrollHeight;
    }
});
