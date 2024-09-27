<?php
require_once 'src/elements/header.php';
require_once 'conf/db.php';
require 'controller/Product.php';

$product = new Product($pdo);
// Récupération des produits
$products = $product->getProduct();
// Récupération des catégories
$category = $product->getCategory();
?>

<style>
   .product-image {
      height: 200px;
      width: 100%;
      object-fit: contain;
      padding: 10px;
   }

   .hidden {
      display: none;
   }
</style>

<body>
   <div class="container mt-5">
      <div class="row">
         <div class="col-md-12">
            <div class="titlepage">
               <h2>Nos produits</h2>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="our_products" id="original-products">
               <div class="row">
                  <!-- Affichage des catégories -->
                  <?php foreach ($category as $c) : ?>
                  <div class="col-md-4 margin_bottom1">
                     <div class="product_box">
                        <h3 data-category="<?=$c['category_id']?>"><?=$c['category_name']?></h3>
                     </div>
                  </div>
                  <?php endforeach; ?>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="container mt-5 hidden" id="filtered-products">
      <div class="row" id="product-list">
         <!-- Les card des produits vont apparaitre ici -->
      </div>
   </div>
<!-- Encodage en json des produits pour les récupérer en JS-->
<script>const products = <?php echo json_encode($products); ?>;</script>
<script src="src/js/index.js"></script>
</body>

<?php require 'src/elements/footer.php'; ?>