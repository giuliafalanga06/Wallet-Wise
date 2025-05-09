
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
    <title>Walletwise | Sign Up</title>
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
            <p class="subtitle">Sign up</p>
        </div>
    
        <div class="login-form">
           
        <h1 class="form__title">Sign up</h1>
        <form action="processSignUp.php" method="post">
                <div class="form__div">
                    <input type="text" name="name" class="form__input" placeholder=" " value="<?php echo $_SESSION['signUp']['name'] ?? ''; unset($_SESSION['signUp']['name'])?>" >
                    <label class="form__label">Name</label>
                    
                </div>
                <?php  
                    echo $_SESSION['signUp']['error']['name'] ?? ''; 
                    unset($_SESSION['signUp']['error']['name']);
                ?>

                <div class="form__div">
                    <input type="text"  name="surname" class="form__input" placeholder=" " value="<?php echo $_SESSION['signUp']['surname'] ?? ''; unset($_SESSION['signUp']['surname'])?>" >
                    <label class="form__label">Surname</label>
                    
                </div>
                <?php  
                    echo $_SESSION['signUp']['error']['surname'] ?? ''; 
                    unset($_SESSION['signUp']['error']['surname']);
                ?>
                <div class="form__div">
                    <input type="text" name="email" class="form__input" placeholder=" " value="<?php echo $_SESSION['signUp']['email'] ?? ''; unset($_SESSION['signUp']['email']) ?>" >
                    <label class="form__label">Email</label>
                    
                </div>
                <?php  
                    echo $_SESSION['signUp']['error']['email'] ?? ''; 
                    unset($_SESSION['signUp']['error']['email']);
                ?>
                <div class="form__div">
                    <input type="password" name="password" class="form__input" placeholder=" " >
                    <label class="form__label">Password</label>
                
                </div>
                <?php  
                    echo $_SESSION['signUp']['error']['password'] ?? ''; 
                    unset($_SESSION['signUp']['error']['password']);
                ?>
                <div class="form__div">
                    <input type="password" name="password_confirmation" class="form__input" placeholder=" " >
                    <label class="form__label">Confirm password</label>
                    
                </div>
                <?php  
                    echo $_SESSION['signUp']['error']['passwordConfirmation'] ?? ''; 
                    unset($_SESSION['signUp']['error']['passwordConfirmation']);
                ?>
                <span class="form_error">
                <?php  
                    echo $_SESSION['signUp']['registration'] ?? ''; 
                    unset($_SESSION['signUp']['registration']);
                ?>
                </span>
                <button type="submit" class="btn btn-primary">
                    <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                        <polyline points="10 17 15 12 10 7"></polyline>
                        <line x1="15" y1="12" x2="3" y2="12"></line>
                    </svg>
                    Sign Up
                </button>
            </form>  
            <div class="login-footer">
            <p>Hai già un account <a href="login.php">LogIn</a></p>
        </div>
    </div>
</body>
</html>