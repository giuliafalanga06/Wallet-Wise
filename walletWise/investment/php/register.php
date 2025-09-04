<?php
/**
 * AutoInvest - Pagina di registrazione
 * 
 * Questo script gestisce la registrazione degli utenti alla piattaforma.
 */

// Inizializza le variabili
$username = $email = $password = $confirmPassword = '';
$usernameError = $emailError = $passwordError = $confirmPasswordError = '';
$registrationSuccess = false;

// Verifica se il form è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Processa i dati del form
    processRegistration();
}

/**
 * Processa i dati del modulo di registrazione
 */
function processRegistration() {
    global $username, $email, $password, $confirmPassword;
    global $usernameError, $emailError, $passwordError, $confirmPasswordError;
    global $registrationSuccess;
    
    // Valida lo username
    if (empty($_POST["username"])) {
        $usernameError = "Lo username è obbligatorio";
    } else {
        $username = sanitizeInput($_POST["username"]);
        if (!preg_match("/^[a-zA-Z0-9_]{4,20}$/", $username)) {
            $usernameError = "Lo username deve contenere solo lettere, numeri e underscore (4-20 caratteri)";
        }
        // In un'applicazione reale, dovresti verificare che lo username non sia già in uso
    }
    
    // Valida l'email
    if (empty($_POST["email"])) {
        $emailError = "L'indirizzo email è obbligatorio";
    } else {
        $email = sanitizeInput($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailError = "Formato email non valido";
        }
        // In un'applicazione reale, dovresti verificare che l'email non sia già in uso
    }
    
    // Valida la password
    if (empty($_POST["password"])) {
        $passwordError = "La password è obbligatoria";
    } else {
        $password = $_POST["password"];
        if (strlen($password) < 8) {
            $passwordError = "La password deve contenere almeno 8 caratteri";
        } elseif (!preg_match("/[A-Z]/", $password)) {
            $passwordError = "La password deve contenere almeno una lettera maiuscola";
        } elseif (!preg_match("/[a-z]/", $password)) {
            $passwordError = "La password deve contenere almeno una lettera minuscola";
        } elseif (!preg_match("/[0-9]/", $password)) {
            $passwordError = "La password deve contenere almeno un numero";
        }
    }
    
    // Valida la conferma della password
    if (empty($_POST["confirm_password"])) {
        $confirmPasswordError = "La conferma della password è obbligatoria";
    } else {
        $confirmPassword = $_POST["confirm_password"];
        if ($password !== $confirmPassword) {
            $confirmPasswordError = "Le password non corrispondono";
        }
    }
    
    // Se non ci sono errori, procedi con la registrazione
    if (empty($usernameError) && empty($emailError) && empty($passwordError) && empty($confirmPasswordError)) {
        // In un ambiente di produzione, qui dovresti salvare l'utente nel database
        // e gestire il processo di autenticazione
        
        // Per esempio:
        // $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        // saveUserToDatabase($username, $email, $hashedPassword);
        
        // Reset dei campi
        $username = $email = $password = $confirmPassword = '';
        
        // Imposta il flag di successo
        $registrationSuccess = true;
    }
}

/**
 * Pulisce e sanitizza l'input dell'utente
 * @param string $data - Dati da sanitizzare
 * @return string - Dati sanitizzati
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Visualizza un messaggio di errore per un campo
 * @param string $error - Messaggio di errore
 * @return string - HTML dell'errore
 */
function showError($error) {
    if (!empty($error)) {
        return "<span class='error'>$error</span>";
    }
    return "";
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione - AutoInvest</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .error {
            color: #F44336;
            font-size: 0.85rem;
            margin-top: 5px;
            display: block;
        }
        
        .registration-form {
            max-width: 500px;
            margin: 0 auto;
        }
        
        .success-message {
            background-color: #dff0d8;
            color: #3c763d;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .password-requirements {
            font-size: 0.85rem;
            color: #666;
            margin-top: 5px;
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .password-toggle {
            position: relative;
        }
        
        .password-toggle input {
            padding-right: 40px;
        }
        
        .password-toggle-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            padding: 0;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>AutoInvest</h1>
            <p class="tagline">Impara come funzionano gli investimenti automatici</p>
        </div>
    </header>

    <main class="container">
        <section>
            <h2>Registrazione</h2>
            <p>Crea un account per salvare le tue simulazioni e accedere a funzionalità avanzate.</p>
            
            <?php if ($registrationSuccess): ?>
            <div class="success-message">
                <h3>Registrazione completata con successo!</h3>
                <p>Il tuo account è stato creato. Ora puoi effettuare il login per accedere a tutte le funzionalità.</p>
                <p><a href="#" class="btn primary">Accedi ora</a> o <a href="index.html">torna alla home</a></p>
            </div>
            <?php else: ?>
            <div class="registration-form">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" value="<?php echo $username; ?>">
                        <?php echo showError($usernameError); ?>
                        <small class="password-requirements">Usa solo lettere, numeri e underscore (4-20 caratteri).</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo $email; ?>">
                        <?php echo showError($emailError); ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <div class="password-toggle">
                            <input type="password" id="password" name="password">
                            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password')">
                                👁️
                            </button>
                        </div>
                        <?php echo showError($passwordError); ?>
                        <small class="password-requirements">La password deve contenere almeno 8 caratteri, una lettera maiuscola, una minuscola e un numero.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Conferma password:</label>
                        <div class="password-toggle">
                            <input type="password" id="confirm_password" name="confirm_password">
                            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('confirm_password')">
                                👁️
                            </button>
                        </div>
                        <?php echo showError($confirmPasswordError); ?>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn primary">Registrati</button>
                    </div>
                </form>
                
                <div class="login-link">
                    <p>Hai già un account? <a href="#">Accedi qui</a></p>
                </div>
            </div>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2023 AutoInvest - Piattaforma didattica. Questo strumento è solo a scopo educativo e non costituisce consulenza finanziaria.</p>
        </div>
    </footer>

    <script>
        /**
         * Toglie visibilità alla password
         * @param {string} fieldId - ID del campo password
         */
        function togglePasswordVisibility(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
        }
    </script>
</body>
</html>