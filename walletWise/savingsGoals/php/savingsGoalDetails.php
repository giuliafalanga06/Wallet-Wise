<?php
session_start();

// Controlla se l'utente è loggato
if (!isset($_SESSION["username"])) {
   header("Location: ../login/login.html");
   exit();
}

include realpath(__DIR__ . '/../../../walletWise/connectDB.php');

$pdo = pdoConnection();
if (!$pdo) {
    die('Errore di connessione al database.');
} else {
    /*echo 'Connessione al database riuscita!<br>';*/
}
$sql = 'SELECT DATABASE()';
$stmt = $pdo->prepare($sql);
$stmt->execute();
$dbName = $stmt->fetchColumn();
/*echo 'Connesso al database: ' . $dbName . '<br>';*/

//------SELEZIONE DEI SAVINGS GOALS INSERITI NEL DATABASE ------   

    $id = $_GET['id'];
    $sql = "SELECT * FROM SavingsGoal WHERE Id = '$id';";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $name = $rows[0]['Name'];
    $startDate = $rows[0]['StartDate'];
    $endDate = $rows[0]['EndDate'];
    $monthAmount = $rows[0]['MonthAmount'];
    $goalAmount = $rows[0]['Goal'];
    $icon =$rows[0]['icon']; 
    $description = $rows[0]['Description'];
    $valori = '';
    $currentAmount = $rows[0]['CurrentAmount'];
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];

    $sql = "SELECT * FROM SavingsTransactions WHERE GoalId = '$id';";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row) {
        $amount = $row['Amount'];
        $date = $row['TransactionDate'];
        $valori .= "<tr><td>$amount</td><td>$date</td></tr>";
    }
    $valori = "<table><tr><th>Amount</th><th>Date</th></tr>$valori</table>";
?>