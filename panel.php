<?php 
require_once 'src/elements/header.php';
require_once 'conf/db.php';
require_once 'controller/Product.php';

$product = new Product($pdo);

$products = $product->getProduct();


if (isset($_GET['deleteProduct'])) {
    $product->deleteProduct();
} elseif (isset($_POST['productId'])) {
    $product->updateProduct();
}
?>
<div class="container mt-5">
    <h2 class="mb-4">Liste des Produits</h2>
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Image</th>
                <th scope="col">Titre</th>
                <th scope="col">Prix</th>
                <th scope="col">Catégorie</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product) : ?>
                <tr data-product-id="<?php echo $product['id']; ?>">
                    <!-- Image -->
                    <td><img src="<?php echo $product['image']; ?>" alt="Image du produit" class="img-thumbnail" style="width: 50px; height: 50px;"></td>
                    <!-- Title -->
                    <td class="product-title">
                        <span class="title-text"><?php echo htmlspecialchars($product['title']); ?></span>
                        <input type="text" class="form-control d-none title-input" value="<?php echo htmlspecialchars($product['title']); ?>">
                    </td>
                    <!-- Price -->
                    <td><?php echo htmlspecialchars($product['price']); ?></td>
                    <!-- Category ID -->
                    <td><?php echo htmlspecialchars($product['category_id']); ?></td>
                    <!-- Actions -->
                    <td>
                        <a href="?deleteProduct=<?php echo $product['id']; ?>" class="btn btn-danger btn-sm">Supprimer</a>
                        <button class="btn btn-warning btn-sm edit-btn">Modifier</button>
                        <button class="btn btn-success btn-sm save-btn d-none">Valider</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
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
</script>
