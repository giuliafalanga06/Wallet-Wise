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
    echo "<script>alert('Connessione fallita: " . $conn->connect_error . "'); window.location.href = 'login.html';</script>";
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
        }
    }
}

// Visualizza l'errore tramite alert se presente
if ($error_message) {
    echo "<script>alert('$error_message'); window.location.href = 'login.html';</script>";
}

// Chiusura della connessione al database
$conn->close();
?>
