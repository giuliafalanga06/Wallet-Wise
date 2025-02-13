<?php
    

    //se il tasto submit viene cliccato
    if (isset($_POST['submit'])) {
        //connessione al database
        require 'dbConnection.php';
        //prendo i dati inseriti dall'utente
        $userId = $_SESSION['userId'];
        $goalName = $_POST['goalName'];
        $goalDescription = $_POST['goalDescription'];
        $goalAmount = $_POST['goalAmount'];
        $targetDate = $_POST['targetDate'];
        //inserisco i dati nel database
        $sql = "INSERT INTO DrawerFund ( goal, Description, startdate) VALUES ( '$goalAmount','$goalDescription',  '$targetDate')";
        $result = $conn->query($sql);
        //se l'inserimento è andato a buon fine
        if ($result) {
            echo "Goal saved successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        $conn->close();
    }
?>
