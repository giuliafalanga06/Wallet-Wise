<?php

if (empty($_POST["name"])) {
    die("Name is required");
}


if (empty($_POST["surname"])) {
    die("Name is required");
}

if ( ! filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    die("Valid email is required");
}

if (strlen($_POST["password"]) < 8) {
    die("Password must be at least 8 characters");
}

if ( ! preg_match("/[a-z]/i", $_POST["password"])) {
    die("Password must contain at least one letter");
}

if ( ! preg_match("/[0-9]/", $_POST["password"])) {
    die("Password must contain at least one number");
}

if ($_POST["password"] !== $_POST["password_confirmation"]) {
    die("Passwords must match");
}


$name = $_POST["name"];
$surname = $_POST["surname"];
$email = $_POST["email"];

$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

$activation_token = bin2hex(random_bytes(16));

$activation_token_hash = hash("sha256", $activation_token);


include realpath(__DIR__ . "/../../walletWise/connectDB.php");
$pdo = pdoConnection();

$sql = "INSERT INTO usertables (name, surname, email, password, account_activation_hash)
        VALUES (:name, :surname, :email, :password_hash, :account_activation_hash)";

// Prepara la query
$stmt = $pdo->prepare($sql);

// Bind dei parametri
$stmt->bindParam(':name', $name, PDO::PARAM_STR);
$stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->bindParam(':password_hash', $password_hash, PDO::PARAM_STR);
$stmt->bindParam(':account_activation_hash', $activation_token_hash, PDO::PARAM_STR);

// Esegui la query
if ($stmt->execute()) {
    // Invia l'email di attivazione
    $mail = require __DIR__ . "/mailer.php";

    $mail->setFrom("noreply@example.com");
    $mail->addAddress($email);
    $mail->Subject = "Account Activation";
    $mail->Body = <<<END
    Click <a href="http://example.com/activate-account.php?token=$activation_token">here</a> 
    to activate your account.
END;

    try {
        // Invia l'email
        $mail->send();
    } catch (Exception $e) {
        // Gestione degli errori nel caso l'email non venga inviata
        echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
        exit;
    }

    // Reindirizza alla pagina di successo
    header("Location: signup-success.html");
    exit;

} else {
    // Se c'è un errore nell'inserimento, mostriamo l'errore
    $errorInfo = $stmt->errorInfo();
    die("Error: " . $errorInfo[2]);
}
?>