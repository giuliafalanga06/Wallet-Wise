<?php


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
            echo "Connessione al database riuscita!<br>";
        }
        $sql = "SELECT DATABASE()";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $dbName = $stmt->fetchColumn();
        echo "Connesso al database: " . $dbName . "<br>";



//------INSERIMENTO DEL NEW SAVINGS GOAL NEL DATABASE ------

        if (isset($_POST['submit'])) {
            $goal = $_POST['goalAmount'];
            $description = $_POST['description'];
            $startDate = $_POST['startDate'];
            $name = $_POST['name'];
            $monthAmount = $_POST['monthAmount'];
            $endDate = date('Y-m-d', strtotime($startDate . ' + ' . ceil($goal/$monthAmount) . ' months'));
            
            $icon = insertIcon($pdo);
            try {
                $sql = "INSERT INTO SavingsGoal (Goal, Description, StartDate, EndDate, Name, monthAmount, Icon) VALUES (:goal, :description, :startDate, :endDate, :name, :monthAmount, :icon)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':goal', $goal);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':startDate', $startDate);
                $stmt->bindParam(':endDate', $endDate);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':monthAmount', $monthAmount);
                $stmt->bindParam(':icon', $icon);
                $stmt->execute();
                //echo "Dati inseriti correttamente!";
                
            } catch (Exception $e) {
                echo "Errore: " . $e->getMessage();
            }
            header("Location: ../savingsGoalsHtml.php");
            exit();
        }

        function insertIcon($pdo): string{
            if (isset($_POST['submit'])) {
                $dir = getcwd();
                if(is_dir( $dir)){
                    $iconDir = opendir( $dir);
                    $tmpName = $_FILES['icon']['tmp_name'];
                    $explode = explode(".", $_FILES['icon']['name']);
                    $ext = end($explode);
                    $name = $pdo->lastInsertId().'.'.'png';
                    $path = "images/" . $name;
                    move_uploaded_file($tmpName, $path);
                }
            }
            closeDir($iconDir);
            return $path;
        }
?>


