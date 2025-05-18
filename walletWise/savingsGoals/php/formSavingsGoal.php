<?php
//------COLLEGAMENTO AL DATABASE ------
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


echo "POST data: ";
print_r($_POST);
echo "<br>";
//------INSERIMENTO DEL NEW SAVINGS GOAL NEL DATABASE ------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    inputControl($pdo);
    exit();
}



// Funzione per gestire il caricamento dell'icona
function insertIcon($pdo): string
{
    $sql = "SELECT MAX(id) FROM SavingsGoal";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $id = $stmt->fetchColumn();
    if (isset($_POST['submit'])) {
        $dir = getcwd();
        if (is_dir($dir)) {
            $iconDir = opendir($dir);
            $tmpName = $_FILES['icon']['tmp_name'];
            $explode = explode(".", $_FILES['icon']['name']);
            $ext = end($explode);
            $name = ($id + 1) . '.' . $ext;
            $path = "../../images/" . $name;
            move_uploaded_file($tmpName, $path);
            echo 'tmp: ' . $tmpName . '<br>path: ' . $path . '<br>ext:' . $ext . '<br>name: ' . $name;
        }
    }
    closeDir($iconDir);
    return $name;
}

// Funzione per la validazione dei dati
function inputControl($pdo)
{
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
        $today->setTime(0, 0, 0); // Azzeri l'orario
        $selectedDate = new DateTime($startDate); // Data selezionata dall'utente
        $selectedDate->setTime(0, 0, 0);
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


    //monthAmount deve essere minore del balance della carta
    $sql = "SELECT Balance FROM Card WHERE Id = :cardId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':cardId', $_SESSION["idCard"], PDO::PARAM_STR);
    $stmt->execute();
    $balance = $stmt->fetchColumn();

    if (!empty($monthAmount) && $monthAmount > $balance) {
        $errors['monthAmount'] = "Il campo 'Month Amount' deve essere minore del saldo della carta.";
    }
    //il campo 'Goal Amount' deve essere minore di 1/3 del balance della carta
    if (!empty($goal) && $goal > ($balance / 3)) {
        $errors['goal'] = "Il campo 'Goal Amount' deve essere minore di 1/3 del saldo della carta.";
    }
    // Se ci sono errori, li mostro
    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    } else {
        // Calcola la durata (mese finale) e la prima rata
        $rateIntere = floor($goal / $monthAmount);
        $residuo = $goal - ($rateIntere * $monthAmount);
        // Numero di mesi, considerando anche una rata parziale finale se c'è un residuo
        $mesi = $rateIntere + ($residuo > 0 ? 1 : 0);
        // Calcola la end date come un mese prima dell'ultima rata
        $endDate = date('Y-m-d', strtotime($startDate . ' + ' . ($mesi - 1) . ' months'));

        // se start date è now
        if ($startDate == date('Y-m-d')) {
            $currentAmount = $monthAmount;
            $nextTransactionDate = date('Y-m-d', strtotime($startDate . ' + 1 month'));
        } else {
            $nextTransactionDate = $startDate;
            $currentAmount = 0;
        }



        try {
            // Inserimento nuovo obiettivo
            $sql = "INSERT INTO SavingsGoal (
                        Goal, Description, StartDate, EndDate, Name,
                        monthAmount, Icon, CardId, CurrentAmount, NextTransactionDate
                    ) VALUES (
                        :goal, :description, :startDate, :endDate, :name,
                        :monthAmount, :icon, :cardId, :currentAmount, :nextTransactionDate
                    )";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':goal', $goal);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':startDate', $startDate);
            $stmt->bindParam(':endDate', $endDate);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':monthAmount', $monthAmount);
            $stmt->bindParam(':icon', $icon);
            $stmt->bindParam(':cardId', $_SESSION['idCard']);
            $stmt->bindParam(':currentAmount', $currentAmount);
            $stmt->bindParam(':nextTransactionDate', $nextTransactionDate);
            $stmt->execute();

            // Recupera l'ID dell'obiettivo appena inserito
            $goalId = $pdo->lastInsertId();

            // Registra la prima transazione
            if ($startDate == date('Y-m-d')) {
                $insert = $pdo->prepare("INSERT INTO SavingsTransactions (GoalId, Amount, TransactionDate, Credit, CardId) VALUES (?, ?, ?, ?, ?)");
                $insert->execute([$goalId, $currentAmount, $startDate, 0, $_SESSION['idCard']]);


                $sql = "INSERT INTO Transactions (
                            Description, Debitor, Income, TransactionDate, CardId, credit
                        ) VALUES (?, ?, ?, ?, ?, ?)";

                $stmt = $pdo->prepare($sql);
                $reason = "Payment savings goals: " . $name;
                $stmt->execute([$reason, $_SESSION['id'], $currentAmount, date('Y-m-d'), $_SESSION['idCard'], 0]);

            }

            echo "Obiettivo creato con successo!";
            $_SESSION['goal_added'] = true;
            header("Location: ../savingsGoalsHtml.php");
            exit();  // Redirect dopo successo
        } catch (Exception $e) {
            echo "Errore: " . $e->getMessage();
        }
    }
}
?>