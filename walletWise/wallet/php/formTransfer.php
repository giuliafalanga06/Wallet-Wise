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
   
    // Controllo che l'importo non superi il saldo disponibile
    $stmt = $pdo->prepare("SELECT Balance FROM Card WHERE Id = :cardId");
    $stmt->bindParam(':cardId', $_SESSION['idCard']);
    $stmt->execute();
    $balance = $stmt->fetchColumn();
    if ($amount > $balance) {
        $errors['amount'] = "Insufficient funds. Your balance is: " . $balance;
    }
    // Controllo che l'IBAN sia valido (lunghezza minima di 15 caratteri)
    if (strlen($iban) < 15) {
        $errors['iban'] = "Iban must be at least 15 characters long.";      
    } else {
        // Controllo che l'IBAN non sia già presente nel database
        $stmt = $pdo->prepare("SELECT c.UserId FROM Card as c WHERE Iban = :iban");
        $stmt->bindParam(':iban', $iban);
        $stmt->execute();

        if ($stmt->fetchColumn() == 0) {
            $errors['iban'] = "Iban does not exists in Walletwise.";
        }
        else{
            
            $userId = $stmt->fetchColumn();
            // Controllo che l'IBAN appartenga all'utente preso dalla form
            $stmt = $pdo->prepare("SELECT * FROM usertables WHERE id = :userId");
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if($name != $user['name'] || $surname != $user['surname']){
                $errors['iban'] = "Iban does not belong to the user.";
            }
        }
    }

    // Controllo che il nome e il cognome non contengano caratteri speciali
    if (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $errors['name'] = "Name can only contain letters and spaces.";
    }
    if (!preg_match("/^[a-zA-Z\s]+$/", $surname)) {
        $errors['surname'] = "Surname can only contain letters and spaces.";
    }
    //controllo che il motivo non contenga caratteri speciali
    if (!preg_match("/^[a-zA-Z0-9\s]+$/", $reason)) {
        $errors['reason'] = "Reason can only contain letters, numbers and spaces.";
    }
    // Controllo che l'importo sia un numero valido
    if (!is_numeric($amount)) {
        $errors['amount'] = "Amount must be a number."; 
    } else {    
        // Controllo che l'importo sia un numero positivo
        if ($amount <= 0) {
            $errors['amount'] = "Amount must be greater than zero.";
        }   
    }
    
    // Se ci sono errori, li mostro
    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    } else {
      

    //     try {
    //         // Inserimento nuovo obiettivo
    //         $sql = "INSERT INTO Transactions (
    //                     Goal, Description, StartDate, EndDate, Name,
    //                     monthAmount, Icon, CardId, CurrentAmount, NextTransactionDate
    //                 ) VALUES (
    //                     :goal, :description, :startDate, :endDate, :name,
    //                     :monthAmount, :icon, :cardId, :currentAmount, :nextTransactionDate
    //                 )";
                    
    //         $stmt = $pdo->prepare($sql);
    //         $stmt->bindParam(':goal', $goal);
    //         $stmt->bindParam(':description', $description);
    //         $stmt->bindParam(':startDate', $startDate);
    //         $stmt->bindParam(':endDate', $endDate);
    //         $stmt->bindParam(':name', $name);
    //         $stmt->bindParam(':monthAmount', $monthAmount);
    //         $stmt->bindParam(':icon', $icon);
    //         $stmt->bindParam(':cardId', $_SESSION['idCard']);
    //         $stmt->bindParam(':currentAmount', $currentAmount);
    //         $stmt->bindParam(':nextTransactionDate', $nextTransactionDate);
    //         $stmt->execute();

    //         // Recupera l'ID dell'obiettivo appena inserito
    //         $goalId = $pdo->lastInsertId();

    //         // Registra la prima transazione
    //         if ($startDate == date('Y-m-d')) {
    //             $insert = $pdo->prepare("INSERT INTO SavingsTransactions (GoalId, Amount, TransactionDate) VALUES (?, ?, ?)");
    //             $insert->execute([$goalId, $currentAmount, $startDate]);
    //         }            

    //         echo "Obiettivo creato con successo!";
    //         header("Location: ../savingsGoalsHtml.php");  // Redirect dopo successo
    //     } catch (Exception $e) {
    //         echo "Errore: " . $e->getMessage();
    //     }
    }
}
?>