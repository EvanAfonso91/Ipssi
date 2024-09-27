<?php
require_once 'conf/db.php';

class Product
{
    private $pdo;

    public function __construct($pdo)
    {
        if ($pdo instanceof PDO) {
            $this->pdo = $pdo;
        } else {
            throw new InvalidArgumentException('Invalid PDO object passed to Product');
        }
    }

    // Ajouter de nouveaux produits
    public function addNewProduct()
    {
        // Récupère la requête de recherche et le nom de la catégorie envoyés via POST
        $searchQuery = $_POST['search_query'];
        $categoryName = $_POST['category'];

        // Prépare l'URL pour l'API de recherche de produits en temps réel
        $url = "https://real-time-product-search.p.rapidapi.com/search-v2?q=" . urlencode($searchQuery) . "&language=en&page=1&limit=10&sort_by=BEST_MATCH&product_condition=ANY";

        // Initialise une session cURL pour faire la requête à l'API
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "x-rapidapi-host: real-time-product-search.p.rapidapi.com",
                "x-rapidapi-key: bc7123ae66msha5ab024703e7d1fp13aec7jsn14c5b4dd7e96"
            ],
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        // Vérifie s'il y a des erreurs dans la requête cURL
        if ($err) {
            echo "Erreur cURL :" . $err;
            return;
        }

        // Décode la réponse JSON
        $products = json_decode($response, true);
        if ($products === null) {
            echo "Erreur de décodage JSON.";
            return;
        }

        // Vérifie si des produits ont bien été trouvés dans la réponse
        if (!isset($products['data']['products']) || empty($products['data']['products'])) {
            echo "Aucun produit trouvé.";
            return;
        }

        try {
            $this->pdo->beginTransaction();
            $categoryId = $this->getOrCreateCategory($categoryName);

            // Parcourt chaque produit retourné par l'API pour l'insérer dans la base de données
            foreach ($products['data']['products'] as $product) {
                $title = $product['product_title'];
                $price = $product['offer']['price'];
                $image = $product['product_photos'][0] ?? null;
                $link = $product['product_page_url'];
                $description = $product['product_description'];

                // Requête pour insérer le produit dans la base de données
                $query = "INSERT INTO product (title, price, description, image, link, category_id) 
                          VALUES (:title, :price, :description, :image, :link, :category_id)";
                $stmt = $this->pdo->prepare($query);
                $stmt->bindParam(':title', $title, PDO::PARAM_STR);
                $stmt->bindParam(':price', $price, PDO::PARAM_STR);
                $stmt->bindParam(':description', $description, PDO::PARAM_STR);
                $stmt->bindParam(':image', $image, PDO::PARAM_STR);
                $stmt->bindParam(':link', $link, PDO::PARAM_STR);
                $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
                $stmt->execute();
            }

            $this->pdo->commit(); // Valide la transaction
            echo "Les produits et la catégorie ont été ajoutés à la base de données.";
        } catch (Exception $e) {
            // En cas d'erreur, annule la transaction et affiche un message d'erreur
            $this->pdo->rollBack();
            echo "Erreur: " . $e->getMessage();
        }
    }

    // Vérifie si une catégorie existe ou en créer une nouvelle
    public function getOrCreateCategory($categoryName)
    {
        // Requête pour vérifier si la catégorie existe déjà
        $query = "SELECT category_id FROM category WHERE category_name = :category_name";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':category_name', $categoryName, PDO::PARAM_STR);
        $stmt->execute();
        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si la catégorie existe, renvoie son ID, sinon la créer
        if ($category) {
            return $category['category_id'];
        } else {
            $query = "INSERT INTO category (category_name) VALUES (:category_name)";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':category_name', $categoryName, PDO::PARAM_STR);
            $stmt->execute();
            return $this->pdo->lastInsertId(); // Renvoie l'ID de la nouvelle catégorie
        }
    }

    // Récupère tous les produits
    public function getProduct()
    {
        $query = "SELECT id , title, price, description, image, category_id FROM product";
        $stmt = $this->pdo->query($query);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $products;
    }

    // Récupère toutes les catégories
    public function getCategory()
    {
        $query = "SELECT category_id , category_name FROM category";
        $stmt = $this->pdo->query($query);
        $stmt->execute();
        $category = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $category;
    }

    // Supprime un produit en fonction de son ID
    public function deleteProduct()
    {
        $productID = $_GET['deleteProduct'];

        $query = "DELETE FROM product WHERE id = :product";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':product', $productID, PDO::PARAM_INT);
        $stmt->execute();
        header('Location: panel.php'); // Redirige vers le panneau de gestion
    }

    // Met à jour le titre d'un produit
    public function updateProduct()
    {
        // Vérifie que l'ID du produit et le nouveau titre sont bien présents dans le POST
        if (isset($_POST['productId']) && isset($_POST['newTitle'])) {
            $productId = $_POST['productId'];
            $newTitle = trim($_POST['newTitle']);

            if (!empty($newTitle)) {
                try {
                    // Requête pour mettre à jour le produit
                    $query = "UPDATE product SET title = :newTitle WHERE id = :productId";
                    $stmt = $this->pdo->prepare($query);
                    $stmt->bindParam(':newTitle', $newTitle, PDO::PARAM_STR);
                    $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);

                    if ($stmt->execute()) {
                        echo "Produit mis à jour avec succès.";
                    } else {
                        echo "Échec de la mise à jour du produit.";
                    }
                } catch (PDOException $e) {
                    // Affiche un message en cas d'erreur
                    echo "Erreur lors de la mise à jour du produit : " . $e->getMessage();
                }
            } else {
                echo "Le titre ne peut pas être vide.";
            }
        } else {
            echo "ID du produit ou nouveau titre manquant.";
        }
    }
}
