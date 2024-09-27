// Gestion de la modification des produits
document.querySelectorAll('.edit-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
        const row = btn.closest('tr');
        const titleText = row.querySelector('.title-text');
        const titleInput = row.querySelector('.title-input');
        const saveBtn = row.querySelector('.save-btn');

        // Afficher l'input et le bouton de validation
        titleText.classList.add('d-none');
        titleInput.classList.remove('d-none');
        saveBtn.classList.remove('d-none');

        // Cacher le bouton de modification
        btn.classList.add('d-none');
    });
});

// Gestion de la validation des modifications
document.querySelectorAll('.save-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
        const row = btn.closest('tr');
        const titleInput = row.querySelector('.title-input');
        const productId = row.getAttribute('data-product-id');

        // Récupérer la valeur de l'input
        const newTitle = titleInput.value;

        // Créer un formulaire pour envoyer en POST
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = ''; // Mettre l'URL cible de la modification si besoin
        form.innerHTML = `
                <input type="hidden" name="productId" value="${productId}">
                <input type="hidden" name="newTitle" value="${newTitle}">
            `;
        document.body.appendChild(form);
        form.submit();
    });
});