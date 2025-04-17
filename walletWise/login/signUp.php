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
                    <input type="text" name="name" class="form__input" placeholder=" " required>
                    <label class="form__label">Name</label>
                </div>

                <div class="form__div">
                    <input type="text" name="surname" class="form__input" placeholder=" " required>
                    <label class="form__label">Surname</label>
                </div>
                <div class="form__div">
                    <input type="email" name="email" class="form__input" placeholder=" " required>
                    <label class="form__label">Email</label>
                </div>
                <div class="form__div">
                    <input type="password" name="password" class="form__input" placeholder=" " required>
                    <label class="form__label">Password</label>
                </div>

                <div class="form__div">
                    <input type="password" name="password_confirmation" class="form__input" placeholder=" " required>
                    <label class="form__label">Password</label>
                </div>
                
                <button type="submit" name="signup" class="form__button">Sign Up</button>
            </form>
            <a href="login.php"><button class="form__toggle">Already have an account? Log in</button></a>
        </div>
    </body>
</html>