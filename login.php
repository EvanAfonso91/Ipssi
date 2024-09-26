<?php

require_once 'controller/User.php';
require_once 'controller/Security.php';

$security = new Security;

$security->alreadyConnected();

$user = new User($pdo);

    $user->login();
    $user->register();

?>
<link rel="stylesheet" href="src/css/login.css">
<main class="login">
    <div class="login-wrap">
        <div class="login-html">
            <input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab">Sign In</label>
            <input id="tab-2" type="radio" name="tab" class="sign-up"><label for="tab-2" class="tab">Sign Up</label>
            <div class="login-form">
                <form action="" method="POST">
                    <div class="sign-in-htm">
                        <div class="group">
                            <label for="user" class="label">Nom d'utilisateur</label>
                            <input type='text' required="required" name='user' class='input' autocomplete="off">
                        </div>
                        <div class="group">
                            <label for="pass" class="label">Mot de passe</label>
                            <input id="pass" required="required" name="pass" type="password" class="input" data-type="password">
                        </div>
                        <?php if (isset($_SESSION['ERROR'])): ?>
                            <div class="group"><?php echo $_SESSION['ERROR']; ?></div>
                        <?php endif; ?>
                        <div class="group">
                            <input type="submit" class="button" value="Sign In">
                        </div>
                    </div>
                </form>
                <form action="" method="POST">
                    <div class="sign-up-htm">
                        <div class="group">
                            <label for="user" class="label">Nom d'utilisateur</label>
                            <input type='text' required="required" name='register-user' class='input' autocomplete="off">
                        </div>
                        <div class="group">
                            <label for="user" class="label">Email</label>
                            <input type='email' required="required" name='register-email' class='input' autocomplete="on">
                        </div>
                        <div class="group">
                            <label for="pass" class="label">Mot de passe</label>
                            <input type='text' required="required" name='register-pass' class='input' data-type="password">
                        </div>
                        <div class="group">
                            <label for="pass" class="label">Confirmer le mot de passe</label>
                            <input type='text' required="required" name='confirmPassword' class='input' data-type="password">
                        </div>
                        <?php if (isset($_SESSION['ERROR'])): ?>
                            <div class="group"><?php echo $_SESSION['ERROR']; ?></div>
                        <?php endif; ?>
                        <div class="group">
                            <input type="submit" class="button" value="Sign Up">
                        </div>
                    </div>
            </div>
        </div>
    </div>
    </form>
</main>