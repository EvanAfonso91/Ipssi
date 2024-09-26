// Fonction d'affichage des produits dans le panier
function viewProducts(filterProducts) {
   const productList = document.getElementById('product-list');
   productList.innerHTML = ''; // Vider les produits de la liste
   // Générer les cartes produits
   products.forEach((product) => {
      const productCard = `
         <div class="col-md-4 mb-4">
            <div class="card product-card">
               <img src="${product.image}" class="card-img-top product-image" alt="${product.name}">
               <div class="card-body">
                  <h5 class="card-title" style="font-weight: bold;">${product.title}</h5>
                  <p class="card-text">${limiteText(product.description, 90)}</p>
                  <p class="card-text text-primary"><strong>${product.price} €</strong></p>
                  <a href="cart.php?deleteCart=${product.id}" class="btn btn-danger remove-btn">Supprimer du panier</a>
               </div>
            </div>
         </div>
      `;
      productList.innerHTML += productCard;
   });
 }

 // Fonction permettant de limiter la taille des descriptions
 function limiteText(text, maxLength) {
    if (text && text.length > maxLength) {
       return text.substring(0, maxLength) + '...';
    }
    return text || 'Pas de description';
 }

 // Appel de la fonction pour afficher les produits au chargement de la page
 document.addEventListener("DOMContentLoaded", function() {
    viewProducts(products);
 });