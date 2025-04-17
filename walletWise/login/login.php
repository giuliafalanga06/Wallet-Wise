<?php
session_start();

// Controlla se l'utente è loggato
    if (isset($_SESSION["username"])) {
        header("Location: ../home/homebankingHtml.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="it">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='styles/login.css'>
    <title>Login</title>
</head>

<body>
    <div class="form-container" id="login-form">
        <h1 class="form__title">Log In</h1>
        <form action="process.php" method="post">
            <div class="form__div">
                <input type="email" name="email" class="form__input" placeholder=" " required>
                <label class="form__label">Email</label>
            </div>
            <div class="form__div">
                <input type="password" name="password" class="form__input" placeholder=" " required>
                <label class="form__label">Password</label>
            </div>
            <span class="form_error">
                <?php 
                    echo $_SESSION['loginError'] ?? ''; 
                    unset($_SESSION['loginError']);
                ?>
            </span>
            <span>
                <?php 
                    echo $_SESSION['successRegistration'] ?? '';
                    unset($_SESSION['successRegistration']);
                ?>
            </span>
            <button type="submit" name="login" class="form__button">Log In</button>
        </form>
        <a href="signUp.php"><button class="form__toggle">Don't have an account? Register</button></a>
    </div>


</body>

</html>