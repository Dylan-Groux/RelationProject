// JS pour le upload de l'avatar utilisateur
const userAvatarInput = document.getElementById('user-avatar-upload');
const bookPictureInput = document.getElementById('picture');

if (userAvatarInput) {
    userAvatarInput.addEventListener('change', function () {
        const form = document.getElementById('user-avatar-form');
        if (form) {
            form.submit();
        }
    });
}

if (bookPictureInput) {
    bookPictureInput.addEventListener('change', function () {
        const form = document.getElementById('edit-picture-form');
        if (form) {
            form.submit();
        }
    });
}