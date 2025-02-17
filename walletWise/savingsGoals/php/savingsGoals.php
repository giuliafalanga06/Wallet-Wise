<?php
    /*
    INSERT INTO DrawerFund (goal, Description, startdate) VALUES (goalAmount, goalDescription, targetDate)

    */

    //se il tasto submit viene cliccato
    if (isset($_POST['submit'])) {
        //connessione al database
        include(realpath(__DIR__ . "/../../../walletWise/connectDB.php"));
        $pdo = pdoConnection();

        //prendo i dati inseriti dall'utente
        $userId = $_SESSION['userId'];
        $goalName = $_POST['name'];
        $goalDescription = $_POST['description'];
        $goalAmount = $_POST['amount'];
        $targetDate = $_POST['date'];
        //inserisco i dati nel database
        try {
            $sql = "INSERT INTO DrawerFund (goal, Description, startdate) VALUES (:goalAmount, :goalDescription, :targetDate)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':goalAmount', $goalAmount);
            $stmt->bindParam(':goalDescription', $goalDescription);
            $stmt->bindParam(':targetDate', $targetDate);
            $stmt->execute();
            echo "Dati inseriti correttamente!";
        } catch (Exception $e) {
            echo "Errore: " . $e->getMessage();
        }
    } 
    
    /*$sql = 'SELECT * FROM DrawerFund ';
        //$sql = "INSERT INTO DrawerFund ( goal, Description, startdate) VALUES ( $goalAmount,$goalDescription,  $targetDate)";
        $conn = mysqli_connect('localhost','walletwise','','my_walletwise');
        $query = mysqli_query($conn, $sql);

        $rows = mysqli_fetch_all($query);

        $valori = '';
        foreach ($rows as $row) {
            $valori.= $row['description'];
        }*/
        include(realpath(__DIR__ . "/../../../walletWise/connectDB.php"));
        $pdo = pdoConnection();

        try {
            $sql = "SELECT * FROM DrawerFund";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $valori = '';
            foreach ($rows as $row) {
                if (!isset($row['description'])) {
                    throw new Exception("Colonna 'description' non trovata!");
                }
                $valori .= $row['description'];
            }
            echo($valori);
            echo "Dati estratti correttamente: " . $valori;
        } catch (Exception $e) {
            echo "Errore: " . $e->getMessage();
        }

?>
