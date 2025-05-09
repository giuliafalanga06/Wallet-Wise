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

    $id = $_GET['id'];
    $sql = "DELETE FROM SavingsGoal WHERE Id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $sql = "DELETE FROM SavingsTransactions WHERE GoalId = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header("Location: ../savingsGoalsHtml.php");
    exit();
?>