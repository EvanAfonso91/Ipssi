<?php
require_once 'conf/db.php';
class User
{
    private $pdo;

    public function __construct($pdo)
    {
        if ($pdo instanceof PDO) {
            $this->pdo = $pdo;
        } else {
            throw new Exception('Invalid PDO object passed to User');
        }
    }

    public function login()
    {
        if (isset($_POST['user']) && isset($_POST['pass'])) {
            $username = $_POST['user'];
            $password = $_POST['pass'];

            $query = "SELECT u.users_id, u.users_name, u.users_password, r.roles_name, u.roles_id 
                      FROM users AS u 
                      JOIN roles AS r ON r.roles_id = u.roles_id 
                      WHERE u.users_name = :user";

            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(':user', $username, PDO::PARAM_STR);
            $stmt->execute();
            $users = $stmt->fetch();

            if ($users && password_verify($password, $users['users_password'])) {
                session_start();
                $_SESSION['user'] = $users;
                $_SESSION['SUCCESS'] = 'Connexion réussie!';
                header('location: index.php');
                return $_SESSION;
            } else {
                $_SESSION['ERROR'] = "Username or password incorrect";
                header('Location: login.php');
                return $_SESSION['ERROR'];
            }
        }
    }

    public function register()
    {   
        $_SESSION['ERREUR'] = [];

        if (isset($_POST['register-user']) && isset($_POST['register-email']) && isset($_POST['register-pass']) && isset($_POST['confirmPassword'])) {

        

        $username = htmlspecialchars($_POST['register-user']);
        $email = $_POST['register-email'];
        $password = $_POST['register-pass'];
        $confirmPassword = $_POST['confirmPassword'];

        if (empty($username)) {
            $_SESSION['ERREUR'][] = "Le nom d'utilisateur ne peut pas être vide.";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['ERREUR'][] = "L'adresse e-mail n'est pas valide.";
        }

        if ($password !== $confirmPassword) {
            $_SESSION['ERREUR'][] = "Les mots de passe ne correspondent pas.";
        }

        if (strlen($password) < 12) {
            $_SESSION['ERREUR'][] = "Le mot de passe doit contenir au moins 12 caractères.";
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $_SESSION['ERREUR'][] = "Le mot de passe doit contenir au moins une lettre majuscule.";
        }
        if (!preg_match('/[a-z]/', $password)) {
            $_SESSION['ERREUR'][] = "Le mot de passe doit contenir au moins une lettre minuscule.";
        }
        if (!preg_match('/\d/', $password)) {
            $_SESSION['ERREUR'][] = "Le mot de passe doit contenir au moins un chiffre.";
        }
        if (!preg_match('/[\W_]/', $password)) {
            $_SESSION['ERREUR'][] = "Le mot de passe doit contenir au moins un caractère spécial.";
        }

        if (!empty($_SESSION['ERREUR'])) {
            return $_SESSION['ERREUR'];
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (users_name, users_email, users_password, roles_id) 
                  VALUES (:username, :email, :password, :roles)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':password', $hashed_password, PDO::PARAM_STR);
        $stmt->bindValue(':roles', 2, PDO::PARAM_INT); // 2 est un ID de rôle par défaut
        $stmt->execute();

        $_SESSION['SUCCESS'] = 'Utilisateur enregistré avec succès.';
        return $_SESSION['SUCCESS'];
    }
}
}
