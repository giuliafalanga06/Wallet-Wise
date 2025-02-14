<?php
    /*
    INSERT INTO DrawerFund (goal, Description, startdate) VALUES (goalAmount, goalDescription, targetDate)

    */

    //se il tasto submit viene cliccato
    if (isset($_POST['submit'])) {
        //connessione al database
        
    
        //prendo i dati inseriti dall'utente
        $userId = $_SESSION['userId'];
        $goalName = $_POST['name'];
        $goalDescription = $_POST['description'];
        $goalAmount = $_POST['amount'];
        $targetDate = $_POST['date'];
        //inserisco i dati nel database

   
    } 
    $sql = 'SELECT * FROM DrawerFund ';
        //$sql = "INSERT INTO DrawerFund ( goal, Description, startdate) VALUES ( $goalAmount,$goalDescription,  $targetDate)";
        $conn = mysqli_connect('localhost','walletwise','','my_walletwise');
        $query = mysqli_query($conn, $sql);

        $rows = mysqli_fetch_all($query);

        $valori = '';
        foreach ($rows as $row) {
            $valori.= $row['description'];
        }

?>
