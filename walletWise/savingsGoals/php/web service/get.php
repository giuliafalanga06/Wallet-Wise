<?php
    include realpath(__DIR__ . "/../../../walletWise/connectDB.php");
    $pdo = pdoConnection();
    
    if (!$pdo) {
        die("Errore di connessione al database.");
    } else {
        /*echo "Connessione al database riuscita!<br>";*/
    }
    $sql = "SELECT DATABASE()";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $dbName = $stmt->fetchColumn();

    $input = file_get_contents("php://input") ;

    if(isempty($input))
        $sql = "SELECT * FROM SavingsGoal ORDER BY Id;";
    else{
        $id = json_decode($input, true);
        $sql = "SELECT * FROM SavingsGoal WHERE SavingsGoal.Id = $id;";
    }
  
    try {
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC)
        $data = json_encode($rows); 
        if(!$rows) {
            http_response_code(200);
            echo "{error : 'richiesta non consentita'}" ;
            }
        else echo $data;    

    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }

    error_reporting(E_ALL);
    ini_set('display_errors', 1);


?>
