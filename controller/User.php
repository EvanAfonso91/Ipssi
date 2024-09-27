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

    // Méthode pour gérer la connexion d'un user
    public function login()
    {
        // Vérifie si les champs 'user' et 'pass' ont été envoyés via POST
        if (isset($_POST['user']) && isset($_POST['pass'])) {
            $username = $_POST['user'];
            $password = $_POST['pass'];

            // Requête SQL pour récupérer les informations de l'utilisateur
            $query = "SELECT u.users_id, u.users_name, u.users_password, r.roles_name, u.roles_id 
                      FROM users AS u 
                      JOIN roles AS r ON r.roles_id = u.roles_id 
                      WHERE u.users_name = :user";

            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(':user', $username, PDO::PARAM_STR);
            $stmt->execute();
            $users = $stmt->fetch(); // Récupère le résultat de la requête

            // Vérifie si un utilisateur est trouvé et si le mot de passe correspond
            if ($users && password_verify($password, $users['users_password'])) {
                session_start();
                $_SESSION['user'] = $users; // Stocke les informations de l'utilisateur dans la session
                $_SESSION['SUCCESS'] = 'Connexion réussie!';
                header('location: index.php');
                return $_SESSION;
            } else {
                $_SESSION['ERROR'] = "Nom d'utilisateur ou mot de passe incorrect";
                header('Location: login.php');
                return $_SESSION['ERROR'];
            }
        }
    }

    // Méthode pour gérer l'inscription d'un nouveau user
    public function register()
    {
        $_SESSION['ERREUR'] = []; // Initialise un tableau pour stocker les erreurs

        // Vérifie si tous les champs requis pour l'enregistrement ont été envoyés via POST
        if (isset($_POST['register-user']) && isset($_POST['register-email']) && isset($_POST['register-pass']) && isset($_POST['confirmPassword'])) {

            $username = htmlspecialchars($_POST['register-user']);
            $email = $_POST['register-email'];
            $password = $_POST['register-pass'];
            $confirmPassword = $_POST['confirmPassword'];

            // Vérifie si le nom d'utilisateur est vide
            if (empty($username)) {
                $_SESSION['ERREUR'][] = "Le nom d'utilisateur ne peut pas être vide.";
            }
            // Vérifie si l'email est valide
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['ERREUR'][] = "L'adresse e-mail n'est pas valide.";
            }

            // Vérifie si les mots de passe correspondent
            if ($password !== $confirmPassword) {
                $_SESSION['ERREUR'][] = "Les mots de passe ne correspondent pas.";
            }

            // Vérifie les contraintes de mot de passe par rapport aux recommandations de la CNIL
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

            // Si des erreurs existent les retourner sans continuer l'enregistrement
            if (!empty($_SESSION['ERREUR'])) {
                return $_SESSION['ERREUR'];
            }

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Requête SQL pour insérer un nouvel utilisateur
            $query = "INSERT INTO users (users_name, users_email, users_password, roles_id) 
                      VALUES (:username, :email, :password, :roles)";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR); 
            $stmt->bindValue(':password', $hashed_password, PDO::PARAM_STR); 
            $stmt->bindValue(':roles', 2, PDO::PARAM_INT); 
            $stmt->execute();

            $_SESSION['SUCCESS'] = 'Utilisateur enregistré avec succès.';
            return $_SESSION['SUCCESS'];
        }
    }
}
