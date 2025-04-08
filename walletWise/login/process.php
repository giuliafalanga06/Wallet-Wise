<?php
session_start();

// Connessione al database
include realpath( "../../walletWise/connectDB.php");
        
        $pdo = pdoConnection();
        
        if (!$pdo) {
            die("Errore di connessione al database.");
        } else {
            echo "Connessione al database riuscita!<br>";
        }


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        // Sanificazione dell'input
        $email = trim($_POST["email"] ?? '');
        $pwd = $_POST["password"] ?? '';

        if (empty($email) || empty($pwd)) {
            $error_message = "Entrambi i campi sono obbligatori!";
        } else {
            // Preparazione della query per evitare SQL injection
            $sql = "SELECT id, name, password FROM usertables WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row || !password_verify($pwd, $row["password"])) {
                $error_message = "Credenziali errate!";
            } else {
                $_SESSION["username"] = $row["name"];
                $_SESSION["surname"] = $row["surname"];
                $_SESSION["email"] = $email;
                $_SESSION["id"] = $row["id"];
                header("Location: ../home/homebankingHtml.php");
                exit();
            }
        }
    } elseif (isset($_POST['signup'])) {
        // Sanificazione dell'input
        $name = trim($_POST["name"] ?? '');
        $email = trim($_POST["email"] ?? '');
        $pwd = $_POST["password"] ?? '';
        $hashedPwd = password_hash($pwd, PASSWORD_BCRYPT);

        if (empty($name) || empty($email) || empty($pwd)) {
            $error_message = "Tutti i campi sono obbligatori!";
        } else {
            // Verifica se l'email è già registrata
            $sql = "SELECT id FROM usertables WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $error_message = "Email già registrata!";
            } else {
                // Inserimento del nuovo utente
                $sql = "INSERT INTO usertables (name, email, password) VALUES (:name, :email, :password)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':password', $hashedPwd, PDO::PARAM_STR);
                if ($stmt->execute()) {
                    createCard($email, $pdo);
                    header("Location: login.html?success=1");
                    exit();
                } else {
                    $error_message = "Errore durante la registrazione.";
                }
            }
        }
    }
}

// Visualizza l'errore tramite alert se presente
if ($error_message) {
    echo "<script>alert('$error_message'); window.location.href = 'login.html';</script>";
}

// Chiusura della connessione al database
$pdo = null;

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
