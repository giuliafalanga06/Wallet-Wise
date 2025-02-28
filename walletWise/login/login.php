<?php
session_start();

$config = [
    'db_engine' => 'mysql',
    'db_host' => 'ftp.walletwise.altervista.org',
    'db_name' => 'my_walletwise',
    'db_user' => 'walletwise',
    'db_password' => ''
];

try {
    $dsn = "{$config['db_engine']}:host={$config['db_host']};dbname={$config['db_name']};charset=utf8";
    $pdo = new PDO($dsn, $config['db_user'], $config['db_password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Connessione fallita: " . $e->getMessage());
}

function registerUser($pdo, $name, $email, $password) {
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);

    if ($stmt->rowCount() > 0) {
        return "Email già registrata.";
    }

    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
    if ($stmt->execute(['name' => $name, 'email' => $email, 'password' => $passwordHash])) {
        return "Registrazione avvenuta con successo!";
    } else {
        return "Errore nella registrazione.";
    }
}

function loginUser($pdo, $username, $password) {
    $stmt = $pdo->prepare("SELECT id, name, password FROM users WHERE name = :username");
    $stmt->execute(['username' => $username]);

    if ($stmt->rowCount() === 1) {
        $user = $stmt->fetch();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: ../home/homebankingHtml.php");
            exit();
        } else {
            return "Password errata.";
        }
    } else {
        return "Utente non trovato.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($action === 'signup') {
            $msg = registerUser($pdo, $name, $email, $password);
        } elseif ($action === 'login') {
            $msg = loginUser($pdo, $username, $password);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Registrazione</title>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap");

        :root {
            --first-color: #1A73E8;
            --second-color: #34A853;
            --input-color: #80868B;
            --border-color: #DADCE0;
            --body-font: "Roboto", sans-serif;
            --normal-font-size: 1rem;
            --small-font-size: .75rem;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: var(--body-font);
            font-size: var(--normal-font-size);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, var(--first-color), var(--second-color));
            background-attachment: fixed;
        }

        .form-container {
            width: 360px;
            padding: 3rem 2rem;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(92, 99, 105, 0.2);
            background: white;
            text-align: center;
        }

        .form__title {
            font-weight: 500;
            margin-bottom: 2rem;
            color: var(--first-color);
        }

        .form__div {
            position: relative;
            height: 52px;
            margin-bottom: 1.5rem;
        }

        .form__input {
            width: 100%;
            height: 100%;
            border: 2px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 1rem;
            outline: none;
            transition: 0.3s;
        }

        .form__input:focus {
            border-color: var(--first-color);
            box-shadow: 0 0 10px rgba(26, 115, 232, 0.5);
        }

        .form__button {
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(135deg, var(--first-color), var(--second-color));
            color: white;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: 0.3s;
        }

        .form__button:hover {
            box-shadow: 0 10px 36px rgba(0, 0, 0, 0.15);
        }

        .form__toggle {
            background: none;
            border: none;
            color: var(--first-color);
            cursor: pointer;
            font-size: var(--small-font-size);
            margin-top: 1rem;
        }

        .hidden {
            display: none;
        }

        .error {
            color: red;
            margin-top: 1rem;
        }
    </style>
</head>

<body>
    <div class="form-container" id="login-form">
        <h1 class="form__title">Log In</h1>
        <form method="POST" action="">
            <input type="hidden" name="action" value="login">
            <div class="form__div">
                <input type="text" class="form__input" name="username" placeholder="Username" required>
            </div>
            <div class="form__div">
                <input type="password" class="form__input" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="form__button">Log In</button>
        </form>
        <button class="form__toggle" onclick="toggleForms()">Non hai un account? Registrati</button>
    </div>

    <div class="form-container hidden" id="signup-form">
        <h1 class="form__title">Sign Up</h1>
        <form method="POST" action="">
            <input type="hidden" name="action" value="signup">
            <div class="form__div">
                <input type="text" class="form__input" name="name" placeholder="Nome" required>
            </div>
            <div class="form__div">
                <input type="email" class="form__input" name="email" placeholder="Email" required>
            </div>
            <div class="form__div">
                <input type="password" class="form__input" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="form__button">Sign Up</button>
        </form>
        <button class="form__toggle" onclick="toggleForms()">Hai già un account? Log In</button>
    </div>

    <?php if (isset($msg)) echo "<p class='error'>$msg</p>"; ?>

    <script>
        function toggleForms() {
            document.getElementById('login-form').classList.toggle('hidden');
            document.getElementById('signup-form').classList.toggle('hidden');
        }
    </script>
</body>

</html>

<?php $pdo = null; ?>
