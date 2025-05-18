<?php
session_start();
// Controlla se l'utente è loggato
if (!isset($_SESSION["username"])) {
    header("Location: ../login/login.php");
    exit();
}

if(isset($_SESSION['goal_modified'])) {
    echo '<div class="success-banner">Modifica avvenuta con successo!</div>';
    unset($_SESSION['goal_modified']);
}

include realpath(__DIR__ . '/../../../walletWise/connectDB.php');

$pdo = pdoConnection();
if (!$pdo) {
    die('Errore di connessione al database.');
} else {
    /*echo 'Connessione al database riuscita!<br>';*/
}

$id = $_GET['id'];
$description = "";
$startDate = "";
$name = "";
$monthAmount = "";
$goal = "";


$sql = "SELECT * FROM SavingsGoal WHERE Id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    die("Nessun record trovato.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    inputControl($pdo, $row, $id);
    exit();
}

function inputControl($pdo, $row, $id)
{

    // Recupero i dati dal form
    $goal = $_POST['goalAmount'];
    $description = $_POST['description'];
    $name = $_POST['name'];
    $monthAmount = $_POST['monthAmount'];
    $icon = $_POST['icona'];
    // Variabile per tenere traccia degli errori
    $errors = [];
    $form = [];

    // Controllo che tutti i campi obbligatori siano stati compilati
    if (empty($goal) || $goal < $row['CurrentAmount'])
        $errors['goal'] = "Il campo 'Goal Amount' è obbligatorio.";

    if (empty($description))
        $errors['description'] = "Il campo 'Description' è obbligatorio.";

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
    } else {
        $endDate = date('Y-m-d', strtotime($row['StartDate'] . ' + ' . ceil(($goal - $row['CurrentAmount']) / $monthAmount) . ' months'));

        try {
            $sql = "UPDATE SavingsGoal 
                        SET Goal = :goal,
                            Description = :description,
                            EndDate = :endDate,
                            Name = :name,
                            MonthAmount = :monthAmount,
                            Icon = :icon
                        WHERE ID = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':goal', $goal);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':endDate', $endDate);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':monthAmount', $monthAmount);
            $stmt->bindParam(':icon', $icon);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $_SESSION['goal_modified'] = true;
            
            header("Location: ../savingsGoalsDetailsHtml.php?id=" . $id);
            exit();
        } catch (Exception $e) {
            echo "Errore: " . $e->getMessage();
        }
    }
}
?>