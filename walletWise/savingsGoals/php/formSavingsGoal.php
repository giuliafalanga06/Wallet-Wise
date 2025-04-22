<?php


//------COLLEGAMENTO AL DATABASE ------
//sostituire con funzione
session_start();
        include realpath(__DIR__ . "/../../../walletWise/connectDB.php");
        
        $pdo = pdoConnection();
        
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



//------INSERIMENTO DEL NEW SAVINGS GOAL NEL DATABASE ------$goal = $_POST['goalAmount'];
            $description = "";
            $startDate = "";
            $name ="";
            $monthAmount ="";
            $goal = "";

        if (isset($_POST['submit'])) {
            inputControl($pdo);
        
            exit();
        }

        function insertIcon($pdo): string{
            
            //calcolo dell'id massimo di savingsGoal nel database
            $sql = "SELECT MAX(id) FROM SavingsGoal";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $id = $stmt->fetchColumn();
            if (isset($_POST['submit'])) {
                $dir = getcwd();
                if(is_dir( $dir)){
                    $iconDir = opendir( $dir);
                    $tmpName = $_FILES['icon']['tmp_name'];
                    $explode = explode(".", $_FILES['icon']['name']);
                    $ext = end($explode);
                    $name = ($id+1).'.'.$ext;
                    $path = "../../images/" . $name;
                    move_uploaded_file($tmpName, $path);
                    echo 'tmp: '.$tmpName.'<br>path: '.$path.'<br>ext:'.$ext.'<br>name: '.$name;
                }
            }
            closeDir($iconDir);
            return $name;
        }


        function inputControl($pdo){
            
            // Recupero i dati dal form
            $goal = $_POST['goalAmount'];
            $description = $_POST['description'];
            $startDate = $_POST['startDate'];
            $name = $_POST['name'];
            $monthAmount = $_POST['monthAmount'];
            $icon = $_POST['icona'];
            
            
            

            // Variabile per tenere traccia degli errori
            $errors = [];
            $form = [];

            // Controllo che tutti i campi obbligatori siano stati compilati
            if (empty($goal))
                $errors['goal'] = "Il campo 'Goal Amount' è obbligatorio.";

            if (empty($description)) 
                $errors['description'] = "Il campo 'Description' è obbligatorio.";

            if (empty($startDate)) {
                $errors['startDate'] = "Il campo 'Start Date' è obbligatorio.";
                $form['startDate'] = $startDate;
            } else {
                $today = new DateTime(); // Data odierna
                $selectedDate = new DateTime($startDate); // Data selezionata dall'utente
                
                // Verifica che la data sia nel futuro (>= oggi)
                if ($selectedDate < $today) {
                    $errors['startDate'] = "La data di inizio deve essere uguale o successiva a oggi.";
                }
            }

            if (empty($name)) 
                $errors['name'] = "Il campo 'Name' è obbligatorio.";
            if (empty($monthAmount)) 
                $errors['monthAmount'] = "Il campo 'Month Amount' è obbligatorio.";
            if (empty($icon)) 
                $errors['icona'] = "Il campo 'icona' è obbligatorio.";

            // Controllo che 'Month Amount' sia minore di 'Goal Amount'
            if (!empty($goal) && !empty($monthAmount) && $monthAmount >= $goal) 
                $errors['monthAmount'] = "Il campo 'Month Amount' deve essere minore di 'Goal Amount'.";
            

            // Se ci sono errori, li mostro
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    echo "<p style='color: red;'>$error</p>";
                }
            }
            else{
                
                $endDate = date('Y-m-d', strtotime($startDate . ' + ' . ceil($goal/$monthAmount) . ' months'));
            
                try {
                    $sql = "INSERT INTO SavingsGoal (Goal, Description, StartDate, EndDate, Name, monthAmount, Icon, CardId) VALUES (:goal, :description, :startDate, :endDate, :name, :monthAmount, :icon, :cardId)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':goal', $goal);
                    $stmt->bindParam(':description', $description);
                    $stmt->bindParam(':startDate', $startDate);
                    $stmt->bindParam(':endDate', $endDate);
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':monthAmount', $monthAmount);
                    $stmt->bindParam(':icon', $icon);
                    $stmt->bindParam(':cardId', $_SESSION['idCard']);
                    $stmt->execute();
                    //echo "Dati inseriti correttamente!";
                    
                } catch (Exception $e) {
                    echo "Errore: " . $e->getMessage();
                }
                header("Location: ../savingsGoalsHtml.php");
            }
        }

        
?>