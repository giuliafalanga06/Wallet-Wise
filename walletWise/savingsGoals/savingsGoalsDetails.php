<?php 

include realpath(__DIR__ . "/../../walletWise/connectDB.php");
try {
    $pdo = new PDO("mysql:host=localhost;dbname=my_walletwise", 'walletwise', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Attiva la gestione errori
} catch (PDOException $e) {
    die("Errore di connessione: " . $e->getMessage()); // Mostra l'errore
}

if (!$pdo) {
    die("Errore di connessione al database.");
} else {
    /*echo "Connessione al database riuscita!<br>";*/
}
$sql = "SELECT DATABASE()";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$dbName = $stmt->fetchColumn();
/*echo "Connesso al database: " . $dbName . "<br>";*/

//------SELEZIONE DEI SAVINGS GOALS INSERITI NEL DATABASE ------   

    $id = $_GET['id'];
    $sql = "SELECT * FROM SavingsGoal WHERE Id = '$id';";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $name = $rows[0]['Name'];
   $startDate = $rows[0]['StartDate'];
    $endDate = $rows[0]['EndDate'];
   // $name = $rows[0]['MonthAmount'];
   // $name = $rows[0]['GoalAmount'];

    $valori = '';


$html = "

<body>

    <p>$name</p>
    <p>$startDate</p>
    <p>$endDate</p>
</body>

";

echo $html;

?>
