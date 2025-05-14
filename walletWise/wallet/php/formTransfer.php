<?php
//------COLLEGAMENTO AL DATABASE ------
session_start();
include realpath(__DIR__ . "/../../../walletWise/connectDB.php");

$pdo = pdoConnection();

if (!$pdo) {
    die("Errore di connessione al database.");
} else {
    echo "Connessione al database riuscita!<br>";
}
$sql = "SELECT DATABASE()";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$dbName = $stmt->fetchColumn();
echo "Connesso al database: " . $dbName . "<br>";

//------INSERIMENTO DEL NEW SAVINGS GOAL NEL DATABASE ------
if (isset($_POST['submit'])) {
    inputControl($pdo);
    exit();
}


// Funzione per la validazione dei dati
function inputControl($pdo) {
    // Recupero i dati dal form
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $iban = $_POST['iban'];
    $amount = $_POST['amount'];
    $reason = $_POST['reason'];
    

    // Variabile per tenere traccia degli errori
    $errors = [];
    $form = [];

    // Controllo che tutti i campi obbligatori siano stati compilati
    if (empty($name)) $errors['name'] = "Name is required.";
    if (empty($surname)) $errors['surname'] = "Surname is required.";
    if (empty($iban)) $errors['iban'] = "Iban is required.";
    if (empty($amount)) $errors['amount'] = "Amount is required.";
    if ($amount <= 0) $errors['amount'] = "Amount must be greater than zero.";
    if (empty($reason)) $errors['reason'] = "Reason is required.";

     // Controllo che l'IBAN non contenga caratteri speciali
    if (!preg_match("/^[a-zA-Z0-9]+$/", $iban)) {
        $errors['iban'] = "Iban can only contain letters and numbers.";
    }
    // Controllo che il nome e il cognome non siano vuoti
    // Controllo che il nome e il cognome non contengano caratteri speciali
    if (!preg_match("/^[a-zA-Z\s]+$/", $name))  $errors['name'] = "Name can only contain letters and spaces.";
    
    if (!preg_match("/^[a-zA-Z\s]+$/", $surname))   $errors['surname'] = "Surname can only contain letters and spaces.";
    
    //controllo che il motivo non contenga caratteri speciali
    if (!preg_match("/^[a-zA-Z0-9\s]+$/", $reason))  $errors['reason'] = "Reason can only contain letters, numbers and spaces.";
    
    // Controllo che l'importo sia un numero valido
    if (!is_numeric($amount)) 
        $errors['amount'] = "Amount must be a number.";  else {    
        // Controllo che l'importo sia un numero positivo
    if ($amount <= 0)  $errors['amount'] = "Amount must be greater than zero.";



   
    // Controllo che l'importo non superi il saldo disponibile
    $stmt = $pdo->prepare("SELECT Balance FROM Card WHERE Id = :idCard");
    $stmt->bindParam(':idCard', $_SESSION['idCard']);
    $stmt->execute();
    $balance = $stmt->fetchColumn();
    if ($amount > $balance) {
        $errors['amount'] = "Insufficient funds. Your balance is: " . $balance;
    }

    // Controllo che l'IBAN sia valido (lunghezza minima di 15 caratteri)
    if (strlen($iban) < 15) {
        $errors['iban'] = "Iban must be at least 15 characters long.";      
    } else {
        // Controllo che l'IBAN  sia già presente nel database
        $stmt = $pdo->prepare("SELECT c.UserId as UserId, c.Id as Id FROM Card as c WHERE Iban = :iban");
        $stmt->bindParam(':iban', $iban);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            $errors['iban'] = "Iban does not exists in Walletwise.";
        }
        else{
            
            $creditorId = $row['UserId'];

            $cardIdCreditor = $row['Id'];

            //controllo che l'iban sia dell'utente corretto.
            $stmt = $pdo->prepare("SELECT * FROM usertables WHERE id = :userId");
            $stmt->bindParam(':userId', $creditorId);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if($name != $user['name'] && $surname != $user['surname']){
                $errors['iban'] = "Iban does not belong to the user.";
            }

            $creditorEmail = $user['email'];
        }
    }

    //se creditore e debitore sono uguali
    if ($_SESSION['id'] == $creditorId) {
        $errors['iban'] = "You cannot transfer money to yourself.";
    }
   
        
    }
    
    // Se ci sono errori, li mostro
    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    } else {
    
    	try{

            $cardIdDebitor = $_SESSION["idCard"];
            $debitorId = $_SESSION["id"];
            $sql = "     INSERT INTO Transactions (
                        Description, Creditor, Debitor, Income, TransactionDate, CardId, credit
                        ) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);

        if ($stmt === false) die("Errore nella preparazione della query.");
        $stmt->execute([$reason, $creditorId, $debitorId, $amount, date('Y-m-d'), $cardIdDebitor, 0]);
        $stmt->execute([$reason, $creditorId, $debitorId, $amount, date('Y-m-d'), $cardIdCreditor, 1]);

        sendEmail($creditorEmail, $amount, $reason, $name, $surname);
         header("Location: ../walletHtml.php"); 
        }
        catch (Exception $e) {
           echo "Errore: " . $e->getMessage();
        }
      

    }
}

/**
 * Send an email to the creditor to notify him of the new transfer
 * @param string $creditorEmail Email of the creditor
 * @param float $amount Amount of the transfer
 * @param string $reason Reason of the transfer
 * @param string $Creditorname Name of the creditor
 * @param string $creditorSurname Surname of the creditor
 */
function sendEmail($creditorEmail, $amount, $reason, $creditorName, $creditorSurname) {
    $to = $creditorEmail;
    $subject = "New transfer received - Walletwise";
    $message = "
            <html>
            <head>
                <title>Account Activation</title>
            </head>
            <body>
                <p>Hello {$creditorName},</p>
                <p>You have received a new transfer from ".$_SESSION['username']." ". $_SESSION['surname']." of " . $amount . " euros for the reason: " . $reason."</p>
            </body>
            </html>
        ";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
        $headers .= "From: no-reply@walletwise.com" . "\r\n";  
    mail($to, $subject, $message, $headers);


    $to = $_SESSION['email'];
    $subject = "New transfer done - Walletwise";
        $message = "
            <html>
            <head>
                <title>Account Activation</title>
            </head>
            <body>
                <p>Hello {$_SESSION['username']},</p>
                <p>You have done a new transfer to ".$creditorName." ". $creditorSurname." of " . $amount . " euros for the reason: " . $reason."</p>
            </body>
            </html>
        ";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
        $headers .= "From: no-reply@walletwise.com" . "\r\n";      
    mail($to, $subject, $message, $headers);
}   
   
?>