<?php
    session_start();
    include realpath(__DIR__ . "/../../walletWise/connectDB.php");

    if(!isset($_GET["token"])){
        header("Location: ./login.php");
        return;
    }

    $token = $_GET["token"];

    $pdo = pdoConnection();

    if (!$pdo) {
        die("Errore di connessione al database.");
    } else {
        echo "Connessione al database riuscita!<br>";
    }

    $stmt = $pdo->prepare("SELECT * FROM usertables WHERE account_activation_hash = :token");

    $stmt->bindValue(":token", $token, PDO::PARAM_STR);

    $res = $stmt->execute();

    if(!$res){
        $_SESSION["errorLoginAuth"] = "Query fallita durante la verifica del token";
        header("Location: ./login.php");
        return;
    }

    $row = $stmt->fetch();

    if(!$row){
        $_SESSION["errorLoginAuth"] = "Token non valido";
        header("Location: ./login.php");
        return;
    }

    $stmt = $pdo->prepare("UPDATE usertables SET account_activation_hash = NULL WHERE account_activation_hash = :token");

    $stmt->bindValue(":token", $token, PDO::PARAM_STR);

    $res = $stmt->execute();

    if(!$res){
        $_SESSION["errorLoginAuth"] = "Query fallita durante la verifica del token";
        header("Location: ./login.php");
        return;
    }

    $_SESSION["successRegistration"] = "Verifica avvenuta con successo";
    header("Location: ./login.php");
    return;
?>