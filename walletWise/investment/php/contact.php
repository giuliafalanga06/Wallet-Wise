<?php
/**
 * AutoInvest - Modulo di contatto
 * 
 * Questo script gestisce l'invio del modulo di contatto e la logica di validazione.
 */

// Inizializza le variabili
$name = $email = $subject = $message = '';
$nameError = $emailError = $subjectError = $messageError = '';
$formSuccess = false;

// Verifica se il form è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Processa i dati del form
    processForm();
}

/**
 * Processa i dati del modulo di contatto
 */
function processForm() {
    global $name, $email, $subject, $message;
    global $nameError, $emailError, $subjectError, $messageError;
    global $formSuccess;
    
    // Valida il nome
    if (empty($_POST["name"])) {
        $nameError = "Il nome è obbligatorio";
    } else {
        $name = sanitizeInput($_POST["name"]);
        if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
            $nameError = "Sono consentiti solo lettere e spazi";
        }
    }
    
    // Valida l'email
    if (empty($_POST["email"])) {
        $emailError = "L'indirizzo email è obbligatorio";
    } else {
        $email = sanitizeInput($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailError = "Formato email non valido";
        }
    }
    
    // Valida l'oggetto
    if (empty($_POST["subject"])) {
        $subjectError = "L'oggetto è obbligatorio";
    } else {
        $subject = sanitizeInput($_POST["subject"]);
        if (strlen($subject) < 3) {
            $subjectError = "L'oggetto deve contenere almeno 3 caratteri";
        }
    }
    
    // Valida il messaggio
    if (empty($_POST["message"])) {
        $messageError = "Il messaggio è obbligatorio";
    } else {
        $message = sanitizeInput($_POST["message"]);
        if (strlen($message) < 10) {
            $messageError = "Il messaggio deve contenere almeno 10 caratteri";
        }
    }
    
    // Se non ci sono errori, procedi con l'invio
    if (empty($nameError) && empty($emailError) && empty($subjectError) && empty($messageError)) {
        // In un ambiente di produzione, qui dovresti inviare l'email
        // Per ora, simuliamo l'invio con successo
        
        // Reset dei campi
        $name = $email = $subject = $message = '';
        
        // Imposta il flag di successo
        $formSuccess = true;
        
        // In un'applicazione reale:
        // sendEmail($name, $email, $subject, $message);
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
 * Funzione per inviare l'email (simulata)
 * @param string $name - Nome del mittente
 * @param string $email - Email del mittente
 * @param string $subject - Oggetto del messaggio
 * @param string $message - Testo del messaggio
 * @return bool - Esito dell'invio
 */
function sendEmail($name, $email, $subject, $message) {
    // In un'applicazione reale, qui utilizzeresti PHPMailer o la funzione mail()
    // Per esempio:
    
    /*
    $to = "supporto@autoinvest.it";
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    $emailBody = "
        <html>
        <head>
            <title>$subject</title>
        </head>
        <body>
            <h2>Nuovo messaggio da $name</h2>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Messaggio:</strong></p>
            <p>$message</p>
        </body>
        </html>
    ";
    
    return mail($to, $subject, $emailBody, $headers);
    */
    
    // Per ora, restituisci sempre true
    return true;
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
    <title>Contattaci - AutoInvest</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .error {
            color: #F44336;
            font-size: 0.85rem;
            margin-top: 5px;
            display: block;
        }
        
        .contact-form {
            max-width: 600px;
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
        
        .contact-info {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }
        
        .contact-info-item {
            flex: 1;
            min-width: 200px;
            padding: 1rem;
            margin: 0.5rem;
            background-color: #f9f9f9;
            border-radius: 8px;
            text-align: center;
        }
        
        .contact-info-item i {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
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
            <h2>Contattaci</h2>
            <p>Hai domande o feedback? Compila il modulo sottostante per metterti in contatto con il nostro team. Ti risponderemo il prima possibile.</p>
            
            <div class="contact-info">
                <div class="contact-info-item">
                    <i>📧</i>
                    <h3>Email</h3>
                    <p>info@autoinvest.it</p>
                </div>
                <div class="contact-info-item">
                    <i>📱</i>
                    <h3>Telefono</h3>
                    <p>+39 02 1234567</p>
                </div>
                <div class="contact-info-item">
                    <i>🏢</i>
                    <h3>Sede</h3>
                    <p>Via dell'Investimento 42, Milano</p>
                </div>
            </div>
            
            <?php if ($formSuccess): ?>
            <div class="success-message">
                <h3>Grazie per averci contattato!</h3>
                <p>Il tuo messaggio è stato inviato con successo. Ti risponderemo al più presto.</p>
                <p><a href="index.html">Torna alla home</a></p>
            </div>
            <?php else: ?>
            <div class="contact-form">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <label for="name">Nome completo:</label>
                        <input type="text" id="name" name="name" value="<?php echo $name; ?>">
                        <?php echo showError($nameError); ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo $email; ?>">
                        <?php echo showError($emailError); ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Oggetto:</label>
                        <input type="text" id="subject" name="subject" value="<?php echo $subject; ?>">
                        <?php echo showError($subjectError); ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Messaggio:</label>
                        <textarea id="message" name="message" rows="6"><?php echo $message; ?></textarea>
                        <?php echo showError($messageError); ?>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit">Invia messaggio</button>
                    </div>
                </form>
            </div>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2023 AutoInvest - Piattaforma didattica. Questo strumento è solo a scopo educativo e non costituisce consulenza finanziaria.</p>
        </div>
    </footer>
</body>
</html>