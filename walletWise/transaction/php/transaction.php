<?php
//
//------COLLEGAMENTO AL DATABASE ------
        include realpath(__DIR__ . "/../../../walletWise/connectDB.php");
        session_start();

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

        if (!isset($_SESSION["username"])) {
            header("Location: ../login/login.html");
            exit();
        }

        $pdo = pdoConnection();

        try {

            // Prepara la query
         $sql = "
            SELECT *
            FROM Transactions
            WHERE CardId = :CardId
            ORDER BY TransactionDate DESC; ";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':CardId', $_SESSION["idCard"], PDO::PARAM_STR);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $valori = '';

            //prendi nome e cognome di creditore e debitore
           
            foreach ($rows as $row) {
                $description = $row['Description'];
                $creditor = $row['Creditor'];
                $debitor = $row['Debitor'];
                $income = $row['Income'];
                $date = $row['TransactionDate'];
                $credit = $row['credit'];
                //se creditor/debitor non è vuoto prendi il nome e cognome
                if (!empty($creditor)) $creditorData = dataUser($creditor, $pdo);
                    
                if(!empty($debitor)) $debitorData = dataUser($debitor, $pdo);
                

                if($credit == 1)
                    $color = 'green';
                else
                    $color = 'red';

                $valori .= "<tr>
                            <td>$description</td>
                            <td>$creditorData</td>
                            <td>$debitorData</td>
                            <td style='color:$color'>$income</td>
                            <td>$date</td>
                        </tr>";
                
            }

             $valori = "<table><tr><th>Description</th><th>Creditor</th><th>Debitor</th><th>Income</th><th>Date</th></tr>$valori</table>";
        } catch (Exception $e) {
            echo "Errore: " . $e->getMessage();
        }

        error_reporting(E_ALL);
        ini_set('display_errors', 1);


        function dataUser($id, $pdo) {
             
            $sql = "SELECT * FROM usertables WHERE id = :Id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':Id', $id, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['name'] . ' ' . $row['surname'];
        }
?>
