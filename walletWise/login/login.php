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
    <title>Walletwise | Login</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="styles/login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="logo">
                <img src="../images/un_logo_con_W_W.png" alt="Logo"  class="logo-image">
            </div>
            <h1>Benvenuto</h1>
            <p class="subtitle">Accedi al tuo account</p>
        </div>
    
        <div class="login-form">
           
        <h1 class="form__title">Log In</h1>
        <form action="process.php" method="post">
            <div class="form__div">
                <input type="email" name="email" class="form__input" placeholder=" " value="<?php echo $_SESSION['email'] ?? ''; unset($_SESSION['email'])?>" >
                <label class="form__label">Email</label>
            </div>
            <div class="form__div">
                <input type="password" name="password" class="form__input" placeholder=" " >
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

                <button type="submit" class="btn btn-primary">
                    <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                        <polyline points="10 17 15 12 10 7"></polyline>
                        <line x1="15" y1="12" x2="3" y2="12"></line>
                    </svg>
                    Accedi
                </button>

            </form>
        <div class="login-footer">
            <p>Non hai un account? <a href="signUp.php">Registrati</a></p>
        </div>
    </div>
</body>
</html>