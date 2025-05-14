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
    $sql = "SELECT Name, CurrentAmount FROM SavingsGoal WHERE Id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $goal = $stmt->fetch(PDO::FETCH_ASSOC);
    $name = $goal['Name'];
    $currentAmount = $goal['CurrentAmount'];
    $sql = "DELETE FROM SavingsGoal WHERE Id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $sql = "DELETE FROM SavingsTransactions WHERE GoalId = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $sql = "INSERT INTO Transactions (
                            Description, Creditor, Income, TransactionDate, CardId, credit
                        ) VALUES (?, ?, ?, ?, ?, ?)";

                $stmt = $pdo->prepare($sql);
                $reason = "Elimination of savings goals: " . $name;
                $stmt->execute([$reason, $_SESSION['id'], $currentAmount, date('Y-m-d'), $_SESSION['idCard'], 1]);

    header("Location: ../savingsGoalsHtml.php");


    exit();
?>