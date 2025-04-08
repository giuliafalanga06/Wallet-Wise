<?php
//se il nome e la data di inizio sono settate
    // if (!isset($_POST['monthAmount']) && isset($_POST['startDate']) && isset($_POST['goalAmount'])) {
    //     $date = $_POST['startDate'];
    //     $endDateMonth = $_POST['goalAmount']/ $_POST['monthAmount'];
    //     date_add($date,date_interval_create_from_date_string($endDateMonth." months"));
    // }


    //se il tasto submit viene cliccato
    // if (isset($_POST['submit'])) {
    //     //connessione al database
    //     include(realpath(__DIR__ . "/../../../walletWise/connectDB.php"));
    //     $pdo = pdoConnection();

    //     //prendo i dati inseriti dall'utente
    //     $userId = $_SESSION['userId'];
    //     $goalName = $_POST['name'];
    //     $goalDescription = $_POST['description'];
    //     $goalAmount = $_POST['amount'];
    //     $targetDate = $_POST['date'];
    //     //inserisco i dati nel database
    //     try {
    //         $sql = "INSERT INTO DrawerFund (goal, Description, startdate) VALUES (:goalAmount, :goalDescription, :targetDate)";
    //         $stmt = $pdo->prepare($sql);
    //         $stmt->bindParam(':goalAmount', $goalAmount);
    //         $stmt->bindParam(':goalDescription', $goalDescription);
    //         $stmt->bindParam(':targetDate', $targetDate);
    //         $stmt->execute();
    //         echo "Dati inseriti correttamente!";
    //     } catch (Exception $e) {
    //         echo "Errore: " . $e->getMessage();
    //     }
    // } 
    
    /*$sql = 'SELECT * FROM DrawerFund ';
        //$sql = "INSERT INTO DrawerFund ( goal, Description, startdate) VALUES ( $goalAmount,$goalDescription,  $targetDate)";
        $conn = mysqli_connect('localhost','walletwise','','my_walletwise');
        $query = mysqli_query($conn, $sql);

        $rows = mysqli_fetch_all($query);

        $valori = '';
        foreach ($rows as $row) {
            $valori.= $row['description'];
        }*/


//------COLLEGAMENTO AL DATABASE ------
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
        /*echo "Connesso al database: " . $dbName . "<br>";*/

//------SELEZIONE DEI SAVINGS GOALS INSERITI NEL DATABASE ------   
session_start();
        try {
            $sql = "SELECT * FROM SavingsGoal where CardId = :CardId ORDER BY Id;";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':CardId', $_SESSION["idCard"], PDO::PARAM_STR);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $valori = '';

            foreach ($rows as $row) {
                $name = $row['Name'];
                $goal = $row['Goal'];
                $id = $row['Id'];
                $icon = $row['icon'];
                $valori .= "
                            <a href='savingsGoalsDetails.php?id=$id'>
                                <div class='singleGoal'>
                                    <img src='https://walletwise.altervista.org/walletWise/images/$icon'>
                                    <h3 class='goal-name'>$name</h3>   
                                </div>
                            </a>
                ";
            }



        } catch (Exception $e) {
            echo "Errore: " . $e->getMessage();
        }

        error_reporting(E_ALL);
        ini_set('display_errors', 1);
?>
