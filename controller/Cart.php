<?php
require_once 'conf/db.php';

class Cart
{
    private $pdo;

    public function __construct($pdo)
    {
        if ($pdo instanceof PDO) {
            $this->pdo = $pdo;
        } else {
            throw new InvalidArgumentException('Invalid PDO object passed to Cart');
        }
    }

    // Ajoute un produit au panier
    public function addToCart()
    {
        if (isset($_GET['addCart'])) {

            $userID = $_SESSION['user']['users_id'];
            $productID = $_GET['addCart'];

            // Requête pour insérer un produit dans le panier
            $query = "INSERT INTO cart (users_id, product_id) 
                      VALUES (:user, :product)";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(':user', $userID, PDO::PARAM_STR);
            $stmt->bindValue(':product', $productID, PDO::PARAM_STR);
            $stmt->execute();
            header('Location: cart.php');
        }
    }

    // Récupère les produits dans le panier
    public function getCart()
    {
        // Requête pour récupérer les informations des produits du panier
        $query = "SELECT c.cart_id AS id , p.title AS title, p.price AS price, p.description AS description, p.image AS image, p.category_id AS category_id
                  FROM cart AS c
                  JOIN users AS u ON u.users_id = c.users_id
                  JOIN product AS p ON p.id = c.product_id
                  WHERE u.users_id = :usersID";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':usersID', $_SESSION['user']['users_id'], PDO::PARAM_INT);
        $stmt->execute();
        $cart = $stmt->fetchAll();

        return $cart;
    }

    // Supprime un produit spécifique du panier
    public function deleteCart()
    {
        if (isset($_GET['deleteCart'])) {
            $userID = $_SESSION['user']['users_id'];
            $cartID = $_GET['deleteCart'];

            // Requête pour supprimer un produit du panier
            $query = "DELETE FROM cart WHERE users_id = :user AND cart_id = :cart";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(':user', $userID, PDO::PARAM_INT);
            $stmt->bindValue(':cart', $cartID, PDO::PARAM_INT);
            $stmt->execute();
            header('Location: cart.php');
        }
    }

    // Calcule le prix total de tous les produits dans le panier
    public function getTotalPrice()
    {
        // Requête pour calculer la somme des prix des produits dans le panier
        $query = "SELECT 
            SUM(CAST(REPLACE(p.price, '$', '') AS DECIMAL(10, 2))) AS total_prix
          FROM 
            product AS p 
          JOIN cart AS c ON c.product_id = p.id
          JOIN users AS u ON u.users_id = c.users_id
          WHERE u.users_id = :user";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam('user', $_SESSION['user']['users_id'], PDO::PARAM_STR);
        $stmt->execute();
        $price = $stmt->fetch(PDO::FETCH_ASSOC);

        return $price['total_prix'];
    }
}
