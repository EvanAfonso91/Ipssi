<?php
require 'conf/db.php';
require 'src/elements/header.php';
require 'controller/Product.php';

$product = new Product($pdo);

if (isset($_POST['search_query'], $_POST['category']) || !empty($_POST['search_query']) || !empty($_POST['category'])) {
    $product->addNewProduct();
}
// Vérification du rôle de l'utilisateur
$security->isAdmin();
?>
<body>
<style>
        .custom-margin-top {
            margin-top: 100px;
        }
    </style>
<div class="container custom-margin-top">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">Rechercher un produit</h3>
                    <form action="get_product.php" method="POST">
                        <div class="form-group">
                            <label for="search_query">Nom du produit</label>
                            <input type="text" required="required" class="form-control" id="search_query" name="search_query" placeholder="Entrez le nom du produit" required>
                        </div>
                        <div class="form-group">
                            <label for="category">Catégorie</label>
                            <input type="text" required="required" class="form-control" id="category" name="category" placeholder="Entrez le nom de la catégorie" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Rechercher et Ajouter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<?php 
require 'src/elements/footer.php';
?>
