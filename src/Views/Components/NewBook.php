  <!-- Bouton pour ouvrir la modal -->
<button id="openNewBookModal" class="add-book-trigger">
    <span>➕</span> Ajouter un livre
</button>

<!-- Modal Overlay -->
<div id="newBookModalOverlay" class="modal-overlay">
    <div class="create-book-modal">
        <div class="modal-header">
            <h3>Ajouter un nouveau livre</h3>
            <button id="closeNewBookModal" class="modal-close" type="button" aria-label="Fermer">×</button>
        </div>
        
        <form id="newBookForm" class="book-form" method="post" action="/public/book/create/book" enctype="multipart/form-data">
            <div class="form-grid">
                <div>
                    <label for="book-title">Titre</label>
                    <input type="text" id="book-title" name="title" required>
                </div>

                <div>
                    <label for="book-author">Auteur</label>
                    <input type="text" id="book-author" name="author" required>
                </div>

                <div>
                    <label for="book-description">Description</label>
                    <textarea id="book-description" name="comment" rows="4" required></textarea>
                </div>

                <div>
                    <label for="book-picture">Photo du livre</label>
                    <input type="file" id="book-picture" name="picture" accept=".jpeg,.jpg,.png">
                </div>

                <div>
                    <label for="book-availability">Disponibilité</label>
                    <select id="book-availability" name="availability" required>
                        <option value="1">Disponible</option>
                        <option value="0">Non disponible</option>
                    </select>
                </div>
            </div>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
            <button type="submit" class="submit-button">Ajouter le livre</button>
        </form>
    </div>
</div>