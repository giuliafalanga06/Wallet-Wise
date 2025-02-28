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
        try {
            $sql = "SELECT * FROM SavingsGoal;";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $valori = '';
            foreach ($rows as $row) {
                if (!isset($row['Description'])) {
                    echo "Colonna 'description' non trovata!";
                }

                $description = $row['Description'];
                $goal = $row['Goal'];
                $valori .= "<div class='singleGoal'>
                                <img src='../images/image-plane.jpg'>
                                <h3 class='goal-name'>$description</h3>   

                            <!--    <div class='goal-progress'>
                                    <canvas  class='coursesDoughnutChart' style='width: 50px;'></canvas>
                                </div>
                             <button class='saveGoal moreDetailsBtn'>More Details</button>-->
                        
                            <!-- 
                            <div class='goal-details' style='display: none; margin-top: 10px;'> 
                                <p><strong>End Date:</strong> 31/12/2025</p>
                                <p><strong>Total Saved:</strong> $5,000</p>
                                <p><strong>Target Amount:</strong> $20,000</p>
                            </div> 
                            -->
                  </div>";
            }



        } catch (Exception $e) {
            echo "Errore: " . $e->getMessage();
        }

        error_reporting(E_ALL);
        ini_set('display_errors', 1);


?>
