<?php
session_start();

// Connessione al database
include realpath( "../../walletWise/connectDB.php");
        
        $pdo = pdoConnection();
        
        if (!$pdo) {
            die("Errore di connessione al database.");
        } else {
            echo "Connessione al database riuscita!<br>";
        }

        var_dump($_POST);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
        $email = trim($_POST["email"] ?? '');
        $pwd = $_POST["password"] ?? '';
        $_SESSION["email"] = $email;
        
        if (empty($email) || empty($pwd)) {
            $_SESSION['loginError'] = "Both fields are required!";
            header("Location: ./login.php");
        } else {
            $sql = "SELECT u.id as id, u.name as name, u.password as password, u.account_activation_hash as account_activation_hash, c.Id as idCard 
                FROM usertables as u, Card as c 
                WHERE c.UserId = u.id
                AND email = :email";
            
            $iban = $row['Iban'];
            $balance = $row['Balance'];
            $expirationDate = new DateTime($row['Expiration']);  
            $expirationMonthYear = $expirationDate->format('m/y');


            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $_SESSION['password'] = $row["password"];
            $_SESSION['idCard'] = $row['idCard'];
            
            if (!$row || hash("sha256", $pwd)!= $row["password"]) {
                $_SESSION['loginError'] = "Wrong credentials supplied!";
                header("Location: ./login.php");
            } else if ($row["account_activation_hash"] != null) {
                $_SESSION['loginError'] = "Wrong credentials supplied!";
                header("Location: ./login.php");
            }
            else {
                $_SESSION["username"] = $row["name"];
                $_SESSION["surname"] = $row["surname"];
                $_SESSION["email"] = $email;
                $_SESSION["id"] = $row["id"];
                
                header("Location: ../home/homebankingHtml.php");
                exit();
            }
        }
   
    
}

// Visualizza l'errore tramite alert se presente
if ($error_message) {
    echo "<script>alert('$error_message'); window.location.href = 'login.html';</script>";
}

// Chiusura della connessione al database
$pdo = null;


?>
