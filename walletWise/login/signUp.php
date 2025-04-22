<?php
    session_start();

?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel='stylesheet' href='styles/login.css'>
    </head>

    <body>
        <div class="form-container" id="signup-form">
            <h1 class="form__title">Sign Up</h1>
            <form action="processSignUp.php" method="post">
                <div class="form__div">
                    <input type="text" value="<?php echo  $name;?>" name="name" class="form__input" placeholder=" " >
                    <label class="form__label">Name</label>
                    
                </div>
                <?php  
                    echo $_SESSION['signUp']['name'] ?? ''; 
                    unset($_SESSION['signUp']['name']);
                ?>

                <div class="form__div">
                    <input type="text"  name="surname" class="form__input" placeholder=" " >
                    <label class="form__label">Surname</label>
                    
                </div>
                <?php  
                    echo $_SESSION['signUp']['surname'] ?? ''; 
                    unset($_SESSION['signUp']['surname']);
                ?>
                <div class="form__div">
                    <input type="text" name="email" class="form__input" placeholder=" " >
                    <label class="form__label">Email</label>
                    
                </div>
                <?php  
                    echo $_SESSION['signUp']['email'] ?? ''; 
                    unset($_SESSION['signUp']['email']);
                ?>
                <div class="form__div">
                    <input type="password" name="password" class="form__input" placeholder=" " >
                    <label class="form__label">Password</label>
                
                </div>
                <?php  
                    echo $_SESSION['signUp']['password'] ?? ''; 
                    unset($_SESSION['signUp']['password']);
                ?>
                <div class="form__div">
                    <input type="password" name="password_confirmation" class="form__input" placeholder=" " >
                    <label class="form__label">Password</label>
                    
                </div>
                <?php  
                    echo $_SESSION['signUp']['passwordConfirmation'] ?? ''; 
                    unset($_SESSION['signUp']['passwordConfirmation']);
                ?>
                <span class="form_error">
                <?php  
                    echo $_SESSION['signUp']['registration'] ?? ''; 
                    unset($_SESSION['signUp']['registration']);
                ?>
                </span>
                <button type="submit" name="signup" class="form__button">Sign Up</button>
            </form>
            <a href="login.php"><button class="form__toggle">Already have an account? Log in</button></a>
        </div>
    </body>
</html>