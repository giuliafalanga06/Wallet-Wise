<?php
session_start();

// Controlla se l'utente è loggato
if (!isset($_SESSION["username"])) {
    header("Location: ../login/login.html");
    exit();
}

// $monthlyIncome = 3500.00;
// $monthlyExpenses = 1850.25;

// $userId = $_SESSION["user_id"]; 

// $host = 'ftp.walletwise.altervista.org';  
// $dbname = 'my_walletwise';  
// $username = 'walletwise';  
// $password = '';

// try {
//     $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
//     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//     $stmt = $pdo->prepare("SELECT balance, last_update FROM user_balance WHERE user_id = ?");
//     $stmt->execute([$userId]);
//     $userData = $stmt->fetch(PDO::FETCH_ASSOC);

//     if ($userData) {
//         $balance = $userData['balance'];
//         $lastUpdate = $userData['last_update'];

//         if (strtotime($lastUpdate) < strtotime("first day of this month")) {
//             $balance += $monthlyIncome;
//             $balance -= $monthlyExpenses;
//             $stmt = $pdo->prepare("UPDATE user_balance SET balance = ?, last_update = ? WHERE user_id = ?");
//             $stmt->execute([$balance, date("Y-m-d"), $userId]);

//             echo "Saldo aggiornato per il mese!";
//         } else {
//             echo "Il saldo è già stato aggiornato questo mese.";
//         }
//     } else {
//        // echo "Utente non trovato.";
//     }
// } catch (PDOException $e) {
//     echo "Errore nel recupero dei dati: " . $e->getMessage();
//     exit();
// }




?>
       

<!DOCTYPE html>
   <html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <!-----JQUERY ----->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

      <!-----REMIXICONS ----->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css">

      <!-----CSS ----->
      <link rel="stylesheet" href="../styles/home.css">
      <link rel="stylesheet" href="styles/home.css">
      <title>Walletwise | Home</title>
   </head>
   <body>
      <!-----HEADER ----->
      <header class="header" id="header">
         <div class="header__container">
            <a href="#" class="header__logo">
                <img id="logo" src="../images/un_logo_con_W_W.png" alt="logo">
               <span>Wallet Wise</span>
            </a>
            
            <button class="header__toggle" id="header-toggle">
               <i class="ri-menu-line"></i>
            </button>
         </div>
      </header>

      <!-----SIDEBAR ----->
      <nav class="sidebar" id="sidebar">
         <div class="sidebar__container">
            <div class="sidebar__user">
               <div>
                  <img  class="sidebar__img" src="../images/un_logo_con_W_W.png" alt="user">
               </div>
   
               <div class="sidebar__info">
                  <h3><?php echo htmlspecialchars($_SESSION["username"]); ?></h3>
                  <span><?php echo htmlspecialchars($_SESSION["email"])?? '' ;?></span>
               </div>
            </div>

            <div class="sidebar__content">
               <div>
                  <h3 class="sidebar__title">MANAGE</h3>

                  <div class="sidebar__list">
                     <a href="#" id="home" class="sidebar__link active-link" data-section = "home">
                        <i class="ri-pie-chart-2-fill"></i> 
                        <span>Home</span>
                     </a>
                     
                     <a id="wallet" class="sidebar__link " href="../wallet/walletHtml.php" data-section = "wallet">
                        <i class="ri-wallet-3-fill"></i>
                        <span>My Wallet</span>
                     </a>

                     <a id="recentTransactions" class="sidebar__link"   href="../transaction/transactionHtml.php"data-section = "transactions">
                        <i class="ri-arrow-up-down-line"></i>
                        <span>Recent Transactions</span>
                     </a>

                     <a id="goals" class="sidebar__link "  href="../savingsGoals/savingsGoalsHtml.php" data-section = "goals">
                        <i class="ri-archive-drawer-fill"></i>
                        <span>Savings goals</span>
                     </a>

                     <a id="investment" class="sidebar__link "  href="../investment/investmentHtml.php" data-section = "investment">
                        <i class="ri-line-chart-fill"></i>
                        <span>Investment</span>
                     </a>
                  </div>
               </div>

               <div>
                  <h3 class="sidebar__title">SETTINGS</h3>

                  <div class="sidebar__list">
                     <a href="#" class="sidebar__link" >
                        <i class="ri-settings-3-fill"></i>
                        <span>Settings</span>
                     </a>


                     <a href="#" class="sidebar__link">
                        <i class="ri-notification-2-fill"></i>
                        <span>Notifications</span>
                     </a>
                  </div>
               </div>
            </div>

            <div class="sidebar__actions">
               <button>
                  <i class="ri-moon-clear-fill sidebar__link sidebar__theme" id="theme-button">
                     <span>Theme</span>
                  </i>
               </button>

                  <button class='sidebar__link' onclick="window.location.href='../login/logout.php'">
                    <i class="ri-logout-box-r-fill"></i>
                    <span>Log Out</span>
                  </button>
            </div>
         </div>
      </nav>

      <!-----MAIN ----->
      <main class="main container" id="main">
         <div class="section home">
         <?php include 'php/homebanking.php';?>
            <h2>Home</h2>
            <br>
               <div class="content">
                  <div class="left-div">
                     <div class="top-left">
                     <p>SALDO : €<?php echo number_format($balance, 2); ?></p>
                     </div>
                     <div class="bottom-left">
                        <?php echo $valori; ?>  
                     </div>
                  </div>
                  <div class="right-div">
                     <p>Grafici spese e guadagni del mese</p>
                  </div>
                  
               </div>
         </div>
      </main>

      
      <!-----MAIN JS ----->
      <script src="../src/home.js"></script>
   </body>
</html>