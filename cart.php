<?php
require_once 'src/elements/header.php';
require 'controller/Cart.php';
$security->isConnected();

$cart = new Cart($pdo);
// Appel d'une fonction en fonction du GET (Add ou Delete)
if (isset($_GET['addCart'])) {
    $cart->addToCart();
} elseif (isset($_GET['deleteCart'])) {
    $cart->deleteCart();
}
// Récupération des produits dans le panier
$result = $cart->getCart();
// Récupération du prix total des produits
$totalPrice = $cart->getTotalPrice();
?>
<style>
   .product-image {
      height: 200px;
      width: 100%;
      object-fit: contain;
      padding: 10px;
   }
   .cart-container {
      background-color: #f8f9fa;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
   }
   .cart-header {
      border-bottom: 2px solid #6c757d;
      margin-bottom: 20px;
      padding-bottom: 10px;
   }
   .cart-total {
      font-size: 1.5rem;
      font-weight: bold;
   }
   .remove-btn {
      margin-top: 15px;
   }
</style>
<body>
   <div class="container mt-5 cart-container">
      <div class="row">
         <div class="col-md-12 cart-header">
            <h2 class="text-center">Votre Panier</h2>
         </div>
      </div>
      <!-- Liste des produits -->
      <div class="container mt-4" id="filtered-products">
         <div class="row" id="product-list">
            <!-- Les cartes des produits vont apparaître ici -->
         </div>
      </div>
      <!-- Prix total du panier -->
      <div class="row mt-5">
         <div class="col-md-12 text-right">
            <p class="cart-total">Prix Total : <span class="text-success"><?php echo $totalPrice; ?> €</span></p>
         </div>
      </div>
   </div>

   <script>
      // Encodage en json des produits pour les récupérer en JS
      const products = <?php echo json_encode($result); ?>;
   </script>
   <script src="src/js/cart.js"></script>

</body>

<?php require 'src/elements/footer.php'; ?>
