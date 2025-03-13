<?php 
function pdoConnection() {
    $servername = "localhost";
    $dbname = "my_walletwise";
    $DBusername = "walletwise";
    $DBpassword = "";

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $DBusername, $DBpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Attiva la gestione errori
        return $pdo;
    } catch (PDOException $e) {
        die("Errore di connessione: " . $e->getMessage()); // Mostra l'errore
    }
}

?>