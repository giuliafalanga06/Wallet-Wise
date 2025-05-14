<?php
session_start();

// Controlla se l'utente è loggato
if (!isset($_SESSION["username"])) {
    header("Location: ../login/login.html");
    exit();
}
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
            $sql = "SELECT * FROM SavingsGoal where CardId = :CardId  AND NextTransactionDate >= CURDATE() AND month(NextTransactionDate) = month(CURDATE())ORDER BY Id;";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':CardId', $_SESSION["idCard"], PDO::PARAM_STR);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $valori = '';

            if (empty($rows)) {
                $valori = "<p class='no-goals'>You have no savings goals to pay for this month.</p>";
            } else {
                $valori = " <p>Savings goals to pay this month:</p><br>";
            }
            foreach ($rows as $row) {
                $name = $row['Name'];
                $goal = $row['Goal'];
                $id = $row['Id'];
                $monthAmount = $row['MonthAmount'];
                $icon = $row['icon'];
                $date = $row['NextTransactionDate'];
                $valori .= "
                            <a href='http://walletwise.altervista.org/walletWise/savingsGoals/savingsGoalsDetailsHtml.php?id=$id'>
                                <div class='nextTransaction'>
                                    <img src='https://walletwise.altervista.org/walletWise/images/icone/$icon'>
                                    <h3 class='goal-name'>$name</h3>  
                                    <p class='goal-date'>$date</p> 
                                    <p class='goal-amount'>€ $monthAmount</p>
                                </div>
                            </a>
                ";
            }
            // query per ottenere i dati
            $stmt = $pdo->prepare(" SELECT NewBalance, Date FROM logTransactions WHERE CardId = :cardId  ORDER BY  Id ASC LIMIT 10;
            ");
            $stmt->execute([':cardId' => $_SESSION["idCard"]]);
            $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // converti i dati in formato JSON per JavaScript
            $transactionsJson = json_encode($transactions);
            $_SESSION['transactions'] = $transactionsJson;

                    } catch (Exception $e) {
                        echo "Errore: " . $e->getMessage();
                    }
        ?>