<?php
session_start();

// Connessione al database
$host = "localhost";
$user = "walletwise";
$password = "";
$dbname = "my_walletwise";
$conn = new mysqli($host, $user, $password, $dbname);

// Verifica connessione
if ($conn->connect_error) {
    echo "<script>alert('Connessione fallita: " . $conn->connect_error . "'); window.location.href = '../login/login.html';</script>";
    exit();
}

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        // Sanificazione dell'input
        $email = trim($_POST["email"] ?? '');
        $pwd = $_POST["password"] ?? '';

        if (empty($email) || empty($pwd)) {
            $error_message = "Entrambi i campi sono obbligatori!";
        } else {
            // Preparazione della query per evitare SQL injection
            $sql = "SELECT id, name, password FROM usertables WHERE email = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();

                if (!$row || !password_verify($pwd, $row["password"])) {
                    $error_message = "Credenziali errate!";
                } else {
                    $_SESSION["username"] = $row["name"];
                    $_SESSION["email"] = $email;
                    header("Location: ../home/homebankingHtml.php");
                    exit();
                }
            } else {
                $error_message = "Errore nella preparazione della query.";
            }
        }
    } elseif (isset($_POST['signup'])) {
        // Sanificazione dell'input
        $name = trim($_POST["name"] ?? '');
        $email = trim($_POST["email"] ?? '');
        $pwd = $_POST["password"] ?? '';
        $hashedPwd = password_hash($pwd, PASSWORD_BCRYPT);

        if (empty($name) || empty($email) || empty($pwd)) {
            $error_message = "Tutti i campi sono obbligatori!";
        } else {
            // Verifica se l'email è già registrata
            $sql = "SELECT id FROM usertables WHERE email = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $error_message = "Email già registrata!";
                } else {
                    // Inserimento del nuovo utente
                    $sql = "INSERT INTO usertables (name, email, password) VALUES (?, ?, ?)";
                    if ($stmt = $conn->prepare($sql)) {
                        $stmt->bind_param("sss", $name, $email, $hashedPwd);
                        if ($stmt->execute()) {
                            createCard($email, $conn);
                            header("Location: login.html?success=1");
                            exit();
                        } else {
                            $error_message = "Errore durante la registrazione.";
                        }
                    } else {
                        $error_message = "Errore nella preparazione della query di registrazione.";
                    }
                   
                }
            } else {
                $error_message = "Errore nella preparazione della query di verifica email.";
            }

            
            //Id, Iban, Balance, Expiration, UserId
        }
    }
}

// Visualizza l'errore tramite alert se presente
if ($error_message) {
    echo "<script>alert('$error_message'); window.location.href = 'login.html';</script>";
}

// Chiusura della connessione al database
$conn->close();


function createCard($email, $mysqli) {
    // Step 1: Find the user by email
    $sql = "SELECT id FROM usertables WHERE email = ?";
    $stmt = $mysqli->prepare($sql);
    if ($stmt === false) {
        echo "Error preparing SQL: " . $mysqli->error;
        return;
    }
    $userId = '';

    $stmt->bind_param('s', $email); 
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($userId);
    
    if ($stmt->num_rows > 0) {
        $stmt->fetch(); 
    } else {
        echo "User not found!";
        $stmt->close();
        return;
    }
    $stmt->close(); 
    
    try {
        $sql = "INSERT INTO Card (Iban, Balance, Expiration, UserId) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        
        if ($stmt === false) {
            echo "Error preparing SQL for inserting card: " . $mysqli->error;
            return;
        }
        
        do {
            $iban = randomIban(); 
        } while (ibanExists($iban, $mysqli)); 
        $balance = 500000;  
        $expiration = todayMoreFiveYears(); 
        $stmt->bind_param('sssi', $iban, $balance, $expiration, $userId);
        
        // Execute the statement
        $stmt->execute();
        
        echo "Card created successfully!";
        $stmt->close(); // Close the statement
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}


function todayMoreFiveYears() {
    $today = new DateTime(); 
    $today->modify('+5 years'); 
    return $today->format('Y-m-d'); 
}


function randomIban() {
    $countryCode = "IT"; // Codice del paese per l'Italia
    $checkDigits = rand(10, 99); // Due cifre casuali per il codice di controllo
    $bankCode = str_pad(rand(1, 9999), 5, '0', STR_PAD_LEFT); // Codice della banca (5 cifre)
    $branchCode = str_pad(rand(1, 9999), 5, '0', STR_PAD_LEFT); // Codice della filiale (5 cifre)
    $accountCode = str_pad(rand(1000000000, 9999999999), 10, '0', STR_PAD_LEFT); // Numero conto (10 cifre)

    // Combinazione di tutti i componenti
    $iban = $countryCode . $checkDigits . $bankCode . $branchCode . $accountCode;

    return $iban;
}

function ibanExists($iban, $conn) {
    // Prepariamo la query SQL per verificare se l'IBAN esiste
    $sql = "SELECT COUNT(*) FROM Card WHERE Iban = ?";
    $count = 0;
    // Prepariamo la query
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        // Se la preparazione della query fallisce, mostriamo l'errore di preparazione
        die("Errore nella preparazione della query: " . $conn->error);
    }

    // Lega il parametro (l'IBAN) alla query
    $stmt->bind_param("s", $iban);

    // Eseguiamo la query
    $stmt->execute();
    
    // Verifica se l'esecuzione è riuscita
    if ($stmt->error) {
        die("Errore nell'esecuzione della query: " . $stmt->error);
    }

    // Collega il risultato alla variabile $count
    $stmt->bind_result($count);
    
    // Otteniamo il risultato
    $stmt->fetch();
    
    // Restituisce true se l'IBAN esiste, altrimenti false
    return $count > 0;
}

?>
