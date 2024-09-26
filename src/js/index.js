function viewProducts(filterProducts) {

   const productList = document.getElementById("product-list");
   productList.innerHTML = '';

   filterProducts.forEach(product => {
      const productCard = `
  <div class="col-md-4">
          <div class="card product-card">
              <img src="${product.image}" class="card-img-top product-image" alt="${product.name}">
              <div class="card-body">
                  <h5 class="card-title" style="font-weight: bold;">${product.title}</h5>
                  <p class="card-text">${limiteText(product.description, 90)}</p> <!-- Truncate description to 90 chars -->
                  <p class="card-text"><strong>${product.price}</strong></p>
                  <a href="cart.php?addCart=${product.id}" class="btn btn-primary">Ajouter au panier</a>
              </div>
          </div>
      </div>
          `;
      productList.innerHTML += productCard;
   });
}

function limiteText(text, maxLength) {
   if (text && text.length > maxLength) {
      return text.substring(0, maxLength) + '...';
   }
   return text || 'Pas de description';
}

// Ajout d'un event listener sur tous les h3
document.querySelectorAll("h3[data-category]").forEach(header => {
   header.addEventListener("click", function() {
      const categoryId = this.getAttribute("data-category");

      // Filtrer les produits par category_id
      const filteredProducts = products.filter(product => product.category_id == categoryId);

      // Cacher l'affichage original
      document.getElementById('original-products').classList.add('hidden');

      // Montrer les produits filtrés
      document.getElementById('filtered-products').classList.remove('hidden');

      viewProducts(filteredProducts);
   });
});