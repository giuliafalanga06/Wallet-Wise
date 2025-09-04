<?php
include realpath(__DIR__ . '/../../../walletWise/connectDB.php');
$pdo = pdoConnection();
    $sql = "SELECT * FROM Card WHERE UserId = :UserId";
 
    $stmt = $pdo->prepare($sql);           
    
    $stmt->bindParam(':UserId', $_SESSION["id"], PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $_SESSION['idCard'] = $row['Id'];
    $iban = $row['Iban'];
    $balance = $row['Balance'];
    $expirationDate = new DateTime($row['Expiration']);  // Crea l'oggetto DateTime
    $expirationMonthYear = $expirationDate->format('m/y');


?>