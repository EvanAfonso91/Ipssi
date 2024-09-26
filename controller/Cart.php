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

    public function addToCart()
    {
        if (isset($_GET['addCart'])) {

            $userID = $_SESSION['user']['users_id'];
            $productID = $_GET['addCart'];

            $query = "INSERT INTO cart (users_id, product_id) 
                      VALUES (:user, :product)";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(':user', $userID, PDO::PARAM_STR);
            $stmt->bindValue(':product', $productID, PDO::PARAM_STR);
            $stmt->execute();
            header('Location: cart.php');
        }
    }


    public function getCart()
    {
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

    public function deleteCart()
    {
        if (isset($_GET['deleteCart'])) {
            $userID = $_SESSION['user']['users_id'];
            $cartID = $_GET['deleteCart'];

            $query = "DELETE FROM cart WHERE users_id = :user AND cart_id = :cart";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(':user', $userID, PDO::PARAM_INT);
            $stmt->bindValue(':cart', $cartID, PDO::PARAM_INT);
            $stmt->execute();
            header('Location: cart.php');
        }
    }

    public function getTotalPrice()
    {
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
