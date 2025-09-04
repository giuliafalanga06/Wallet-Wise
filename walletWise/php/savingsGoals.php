<?php
// filepath: /C:/Users/uni/Documents/GitHub/Wallet-Wise/php/savingsGoals.php

// Connessione al database


$servername = "ftp.walletwise.altervista.org";
$username = "walletwise";
$password = "Walletwise1!";
$dbname = "my_walletwise";

// Crea la connessione
$conn = new mysqli($servername, $username, $password, $dbname);

// Controlla la connessione
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Se il tasto submit viene cliccato
var_dump($_POST);
if (isset($_POST['submit'])) {
    var_dump($_POST);
    // Prendi i dati inseriti dall'utente
    $userId = $_SESSION['userId'];
    $goalName = $_POST['goalName'];
    $goalDescription = $_POST['goalDescription'];
    $goalAmount = $_POST['goalAmount'];
    $targetDate = $_POST['targetDate'];

    // Debug: verifica i valori delle variabili
    echo "User ID: $userId<br>";
    echo "Goal Name: $goalName<br>";
    echo "Goal Description: $goalDescription<br>";
    echo "Goal Amount: $goalAmount<br>";
    echo "Target Date: $targetDate<br>";

    // Prepara e esegui la query di inserimento
    $sql = "INSERT INTO DrawerFund (goal, Description, startdate) VALUES ('$goalAmount', '$goalDescription', '$targetDate')";
    if ($conn->query($sql) === TRUE) {
        echo "Goal saved successfully";
    } else {
        echo "Error: " . $conn->error;
    }

    // Chiudi la connessione
    $conn->close();
}
?>