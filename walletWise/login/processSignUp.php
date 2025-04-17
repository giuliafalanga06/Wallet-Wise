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

include realpath(__DIR__ . "/../../walletWise/connectDB.php");
$pdo = pdoConnection();

// Verifica se l'email esiste già
$sql_check = "SELECT COUNT(*) FROM usertables WHERE email = :email";
$stmt_check = $pdo->prepare($sql_check);
$stmt_check->bindParam(':email', $email, PDO::PARAM_STR);
$stmt_check->execute();

if ($stmt_check->fetchColumn() > 0) {
    die("Email already registered. Please use a different email address.");
}

$password_hash = hash("sha256",$_POST["password"]);

$activation_token = bin2hex(random_bytes(16));
$activation_token_hash = hash("sha256", $activation_token);


$sql = "INSERT INTO usertables (name, surname, email, password, account_activation_hash)
        VALUES (:name, :surname, :email, :password_hash, :account_activation_hash)";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':name', $name, PDO::PARAM_STR);
$stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->bindParam(':password_hash', $password_hash, PDO::PARAM_STR);
$stmt->bindParam(':account_activation_hash', $activation_token_hash, PDO::PARAM_STR);

if ($stmt->execute()) {
    $to = $email;
    $subject = "Account Activation - WalletWise";
    $message = "
        <html>
        <head>
            <title>Account Activation</title>
        </head>
        <body>
            <p>Hello {$name},</p>
            <p>Thank you for registering at WalletWise. Please click the link below to activate your account:</p>
            <a href='https://www.walletwise.altervista.org/walletWise/login/activate.php?token={$activation_token}'>Activate Account</a>
            <p>If you did not register, please ignore this email.</p>
        </body>
        </html>
    ";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: no-reply@tuosito.com" . "\r\n";

    // Send the email
    if (mail($to, $subject, $message, $headers)) {
        echo "Registration successful! Please check your email to activate your account.";
    } else {
        echo "Error: Unable to send activation email.";
    }

    createCard($email, $pdo);
} else {
    echo "Error: Could not register the user.";
}
function createCard($email, $pdo) {
    // Step 1: Find the user by email
    $sql = "SELECT id FROM usertables WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$row) {
        echo "User not found!";
        return;
    }

    $userId = $row['id'];
    
    try {
        $sql = "INSERT INTO Card (Iban, Balance, Expiration, UserId) VALUES (:iban, :balance, :expiration, :userId)";
        $stmt = $pdo->prepare($sql);

        do {
            $iban = randomIban(); 
        } while (ibanExists($iban, $pdo)); 
        
        $balance = 500000;  
        $expiration = todayMoreFiveYears(); 
        $stmt->bindParam(':iban', $iban, PDO::PARAM_STR);
        $stmt->bindParam(':balance', $balance, PDO::PARAM_INT);
        $stmt->bindParam(':expiration', $expiration, PDO::PARAM_STR);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        
        $stmt->execute();
        
        echo "Card created successfully!";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

function todayMoreFiveYears() {
    $today = new DateTime(); 
    $today->modify('+5 years'); 
    return $today->format('Y-m-d'); 
}

function randomIban() {
    $countryCode = "IT"; 
    $checkDigits = rand(10, 99); 
    $bankCode = str_pad(rand(1, 9999), 5, '0', STR_PAD_LEFT); 
    $branchCode = str_pad(rand(1, 9999), 5, '0', STR_PAD_LEFT); 
    $accountCode = str_pad(rand(1000000000, 9999999999), 10, '0', STR_PAD_LEFT); 

    $iban = $countryCode . $checkDigits . $bankCode . $branchCode . $accountCode;
    return $iban;
}

function ibanExists($iban, $pdo) {
    $sql = "SELECT COUNT(*) FROM Card WHERE Iban = :iban";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':iban', $iban, PDO::PARAM_STR);
    $stmt->execute();
    
    $count = $stmt->fetchColumn();
    return $count > 0;
}

?>