
<?php
session_start();

// Controlla se l'utente è loggato
if (!isset($_SESSION["username"])) {
    header("Location: login.html");
    exit();
}
?>
<?php 

include realpath(__DIR__ . '/../../walletWise/connectDB.php');
$pdo = pdoConnection();
if (!$pdo) {
    die('Errore di connessione al database.');
} else {
    /*echo 'Connessione al database riuscita!<br>';*/
}
$sql = 'SELECT DATABASE()';
$stmt = $pdo->prepare($sql);
$stmt->execute();
$dbName = $stmt->fetchColumn();
/*echo 'Connesso al database: ' . $dbName . '<br>';*/

//------SELEZIONE DEI SAVINGS GOALS INSERITI NEL DATABASE ------   

    $id = $_GET['id'];
    $sql = "SELECT * FROM SavingsGoal WHERE Id = '$id';";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $name = $rows[0]['Name'];
    $startDate = $rows[0]['StartDate'];
    $endDate = $rows[0]['EndDate'];
    $monthAmount = $rows[0]['MonthAmount'];
    $goalAmount = $rows[0]['Goal'];
    $icon =$rows[0]['icon']; 
    $description = $rows[0]['Description'];
    $valori = '';
    $currentAmount = $rows[0]['CurrentAmount'];
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];

$html = "

<!DOCTYPE html>
   <html lang='en'>
   <head>
      <meta charset='UTF-8'>
      <meta name='viewport' content='width=device-width, initial-scale=1.0'>
      <link rel='icon' type='image/png' sizes='32x32' href='../images/un_logo_con_W_W.png'>
      <!-----JQUERY ----->
      <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js'></script>

      <!-----REMIXICONS ----->
      <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css'>

      <!-----CSS ----->
      <link rel='stylesheet' href='styles/savingGoalsDetails.css'>
      <link rel='stylesheet' href='../styles/input.css'>
      <link rel='stylesheet' href='styles/savingsGoals.css'>
      <link rel='stylesheet' href='../styles/home.css'>
      <title>Responsive sidebar Menu | Dark/Light Mode - Bedimcode</title>
   </head>
   <body>
      <!-----HEADER ----->
      <header class='header' id='header'>
         <div class='header__container'>
            <a href='#' class='header__logo'>
                <img id='logo' src='../images/un_logo_con_W_W.png' alt='logo'>
               <span>Wallet Wise</span>
            </a>
            
            <button class='header__toggle' id='header-toggle'>
               <i class='ri-menu-line'></i>
            </button>
         </div>
      </header>

      <!-----SIDEBAR ----->
      <nav class='sidebar' id='sidebar'>
         <div class='sidebar__container'>
            <div class='sidebar__user'>
               <div>
                  <img  class='sidebar__img' src='../images/un_logo_con_W_W.png' alt='user'>
               </div>
   
               <div class='sidebar__info'>
                  <h3>$username</h3>
                  <span>$email</span>
               </div>
            </div>

            <div class='sidebar__content'>
               <div>
                  <h3 class='sidebar__title'>MANAGE</h3>

                  <div class='sidebar__list'>
                     <a href='../home/homebankingHtml.php' id='home' class='sidebar__link ' data-section = 'home'>
                        <i class='ri-pie-chart-2-fill'></i>
                        <span>Home</span>
                     </a>
                     
                     <a id='wallet' class='sidebar__link ' href='../wallet/walletHtml.php' data-section = 'wallet'>
                        <i class='ri-wallet-3-fill'></i>
                        <span>My Wallet</span>
                     </a>

                     <a id='recentTransactions' class='sidebar__link '   href='../transaction/transactionHtml.php'data-section = 'transactions'>
                        <i class='ri-arrow-up-down-line'></i>
                        <span>Recent Transactions</span>
                     </a>

                     <a id='goals' class='sidebar__link active-link'  href='#' data-section = 'goals'>
                        <i class='ri-archive-drawer-fill'></i>
                        <span>Savings goals</span>
                     </a>
                  </div>
               </div>

               <div>
                  <h3 class='sidebar__title'>SETTINGS</h3>

                  <div class='sidebar__list'>
                     <a href='#' class='sidebar__link' >
                        <i class='ri-settings-3-fill'></i>
                        <span>Settings</span>
                     </a>


                     <a href='#' class='sidebar__link'>
                        <i class='ri-notification-2-fill'></i>
                        <span>Notifications</span>
                     </a>
                  </div>
               </div>
            </div>

            <div class='sidebar__actions'>
               <button>
                  <i class='ri-moon-clear-fill sidebar__link sidebar__theme' id='theme-button'>
                     <span>Theme</span>
                  </i>
               </button>

                  <button class='sidebar__link' onclick='window.location.href='../index.html''>
                    <i class='ri-logout-box-r-fill'></i>
                    <span>Log Out</span>
                  </button>
            </div>
         </div>
      </nav>

      <!-----MAIN ----->
      <main class='main container' id='main'>
      <div class='overlay'></div>
         <div class='section goals'>
            <div>
               <span class='newGoalBtn'>
                  <button class='btn'>+</button>
                  Savings goal
               </span>

               <script src='https://cdn.jsdelivr.net/npm/chart.js'></script>

               <?php include 'php/savingsGoals.php';?>
               
                <img src='https://walletwise.altervista.org/walletWise/images/$icon' style=\"border-radius: 50%; width: 100px; height: 100px;\">
                <h1>$name</h1>
                <p>Description: $description</p>
                <p>Start date: $startDate</p>
                <p>End date: $endDate</p>

                <p>Goal amount: <span class='goalAmount'>$goalAmount<span></p>
                <p>Month amount: $monthAmount</p>
                <p>Current amount: <span class='currentAmount'>$currentAmount</span></p>

                <canvas class='coursesDoughnutChart'></canvas>
                
                <button onclick=\"window.location.href='savingsGoalsHtml.php'\" class=\"btnSGD\">Savings Goals</button>

            </div>
         </div>
      </main>
      <!-----MAIN JS ----->
      <script src='../src/home.js'></script>
      <script src='js/savingsGoals.js'></script>

      <!-----MAIN PHP ----->

   </body>
</html>
";

echo $html;

?>
