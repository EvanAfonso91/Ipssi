<?php

class Security
{
    

    public function isAdmin() {
        if ($_SESSION['user']['roles_name'] != 'Admin' || !isset($_SESSION['user'])) {
            header('Location: index.php');
        }
    }

    public function isConnected() {
        if (!isset($_SESSION['user'])) {
            header('Location: login.php');
        } 
    }
    
    public function alreadyConnected() {
        if (isset($_SESSION['user'])) {
            header('Location: index.php');
        }
    }

}