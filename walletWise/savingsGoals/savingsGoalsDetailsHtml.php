
<?php
session_start();

// Controlla se l'utente è loggato
if (!isset($_SESSION["username"])) {
   header("Location: ../login/login.php");
   exit();
}


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

   $checkedTravel = ($icon == 'travel.png') ? 'checked' : '';
   $checkedCar = ($icon == 'car.png') ? 'checked' : '';
   $checkedPeople = ($icon == 'people.png') ? 'checked' : '';
   $checkedFood = ($icon == 'food.png') ? 'checked' : '';
   $checkedPc = ($icon == 'pc.png') ? 'checked' : '';
   $checkedHome = ($icon == 'home.png') ? 'checked' : '';
   $checkedGames = ($icon == 'games.png') ? 'checked' : '';
   $checkedBooks = ($icon == 'books.png') ? 'checked' : '';

   
   $sql = "SELECT * FROM SavingsTransactions WHERE GoalId = '$id';";
   $stmt = $pdo->prepare($sql);
   $stmt->execute();
   $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

   if(empty($rows)){
         $valori = "<p style='text-align: center;'>No transactions found for this goal.</p>";
   } else {
      foreach ($rows as $row) {
         $amount = $row['Amount'];
         $date = $row['TransactionDate'];
         $valori .= "<tr><td>$date</td><td>$amount</td></tr>";
     }
     $valori = "<table><tr><th>Date</th><th>Amount</th></tr>$valori</table>";
   }
   

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
      <link rel=\"stylesheet\" href=\"https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=arrow_back_ios\" />
      <title>Walletwise | Savings Goal Detail</title>
   </head>
   <body>
";
if (isset($_SESSION['goal_modified'])) {
    $html .= '
        <div class="success-banner" id="successBanner">
          <i class="ri-checkbox-circle-fill"></i>
          Saving goals successfully modified
        </div>
        <script>
          document.addEventListener("DOMContentLoaded", () => {
            const banner = document.getElementById("successBanner");
            setTimeout(() => banner.classList.add("active"), 100);
            setTimeout(() => {
              banner.classList.remove("active");
              setTimeout(() => banner.remove(), 500);
            }, 3000);
          });
        </script>
    ';
    unset($_SESSION['goal_modified']);
}

$html .= "

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

                     <a id=\"investment\" class=\"sidebar__link \"  href=\"../investment/investmentHtml.php\" data-section = \"investment\">
                        <i class=\"ri-line-chart-fill\"></i>
                        <span>Investment</span>
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
      
      <div class='overlay2'></div>
      <div class='header-row'>
         <a href='savingsGoalsHtml.php' class='btnSGD'>❮  Savings Goals</a>
         <h1 class='goal-title'>$name</h1>
         <div class='button-container'>
            <a class='CancelGoalDetails deleteGoalBtn'>Delete</a>
            <a class='saveGoal modifyGoalBtn'>Modify</a>
         </div>
      </div>
       <br>
         <div class='section goals'> 
        
            <div>
               <script src='https://cdn.jsdelivr.net/npm/chart.js'></script>
              
               <?php include 'php/savingsGoals.php';?>
               
                <img src='https://walletwise.altervista.org/walletWise/images/icone/$icon' style=\"border-radius: 50%; width: 120px; height: 120px; margin-right: 10px;\">
               
                <div>                
                  <p>Description: $description</p>
                  <p>Start date: $startDate</p>
                  <p>End date: $endDate</p>

                  <p>Goal amount: <span class='goalAmount'>$goalAmount<span></p>
                  <p>Month amount: $monthAmount</p>
                  <p>Current amount: <span class='currentAmount'>$currentAmount</span></p>
                </div>

                <div><canvas class='coursesDoughnutChart'></canvas></div>
                
               
            </div>
            $valori
         </div>


         <div class='deleteGoal'>
            <span>Are you sure you want to delete the '$name' savings goal?</span>
            <div class=\"button-container-center\">   
               <a class=\"saveGoal notDeleteGoal\">Cancel</a>
               <a class=\"CancelGoalDetails\" href='php/deleteSavingsGoals.php?id=$id'>Delete</a>
            </div>
         </div>

         <div class='modifyGoal'>
                  <h3>Set a new Savings Goal</h3>
                  <br>
                  <form class='goalFormModify'id='modifyGoalForm' method='post' action='php/modifySavingsGoals.php?id=$id' enctype='multipart/form-data'>

               
                     <div style='display: flex; gap: 1rem; align-items: flex-start;'>
                        <div style='flex: 1;'>
                           <div class='form__div'>
                              <input type='text' name='name' class='form__input' placeholder=' ' value='$name'>
                              <label class='form__label'>Goal Name</label>
                           </div>
                           <div class=\"error-container\" data-field=\"name\"></div>
                        </div>
                        <div style='flex: 1;'>
                           <div class='form__div'>
                              <input type='date' id='startDate' name='startDate' class='form__input' placeholder=' ' value='$startDate' disabled>
                              <label class='form__label'>Start Date</label>
                           </div>
                           <div class=\"error-container\" data-field=\"startDate\"></div>
                        </div>
                     </div>
                     
                     <div style='display: flex; gap: 1rem;'>
                        <div style='flex: 1;'>
                           <div class='form__div'>
                              <input type='text' name='description' class='form__input' placeholder=' ' value='$description'>
                              <label class='form__label'>Goal Description</label>
                           </div>
                           <div class=\"error-container\" data-field=\"description\"></div>
                        </div>
                     </div>

                     <div style='display: flex; gap: 1rem;'>
                        <div style='flex: 1;'>
                           <div class='form__div'>
                              <input type='number' id='goalAmount' name='goalAmount' class='form__input' placeholder=' ' step=\"0.01\" min=\"0\" value='$goalAmount'>
                              <label class='form__label'>Goal Amount</label>
                           </div>
                           <div class=\"error-container\" data-field=\"goalAmount\"></div>
                        </div>

                        <div style='flex: 1;'>
                           <div class='form__div'>
                              <input type='number' id='monthAmount' name='monthAmount' class='form__input' placeholder=' ' step=\"0.01\" min=\"0\" value='$monthAmount'>
                              <label class='form__label'>Month Amount</label>
                           </div>
                           <div class=\"error-container\" data-field=\"monthAmount\"></div>
                        </div>
                     </div>

                     <div class=\"icone\">
                        <label>
                           <input type=\"radio\" name=\"icona\" value=\"travel.png\" $checkedTravel>
                           <img src=\"../images/icone/travel.png\" alt=\"Icona travel\">   
                        </label> 
                        <label>
                           <input type=\"radio\" name=\"icona\" value=\"car.png\" $checkedCar>
                           <img src=\"../images/icone/car.png\" alt=\"Icona car\">   
                        </label> 
                        <label>
                           <input type=\"radio\" name=\"icona\" value=\"people.png\" $checkedPeople>
                           <img src=\"../images/icone/people.png\" alt=\"Icona people\">
                        </label>
                        <label>
                           <input type=\"radio\" name=\"icona\" value=\"food.png\" $checkedFood>
                           <img src=\"../images/icone/food.png\" alt=\"Icona food\">   
                        </label> 
                        <label>
                           <input type=\"radio\" name=\"icona\" value=\"pc.png\" $checkedPc>
                           <img src=\"../images/icone/pc.png\" alt=\"Icona pc\">
                        </label>
                        <label>
                           <input type=\"radio\" name=\"icona\" value=\"home.png\" $checkedHome>
                           <img src=\"../images/icone/home.png\" alt=\"Icona home\">
                        </label>
                        <label>
                           <input type=\"radio\" name=\"icona\" value=\"games.png\" $checkedGames>
                           <img src=\"../images/icone/games.png\" alt=\"Icona games\">
                        </label>
                        <label>
                           <input type=\"radio\" name=\"icona\" value=\"books.png\" $checkedBooks>
                           <img src=\"../images/icone/books.png\" alt=\"Icona books\">
                        </label>
                     </div>
                     <div class=\"error-container\" data-field=\"icona\"></div>
                     
                     <div class=\"button-container\">
                         <a class=\"CancelGoalDetails\">Cancel</a>
                        <a  name='submitModify' class=\"saveGoal\">Save</a>
                     </div>
                     
                     <!-- Campo nascosto per il valore del saldo della carta -->
                     <input type=\"hidden\" id=\"cardBalance\" value=\"<?php echo $balance; ?>\">
                  </form>
               </div>
      </main>
      <!-----MAIN JS ----->
      <script src='js/savingsGoalsFormModify.js'></script>
      <script src='../src/home.js'></script>
      <script src='js/savingsGoals.js'></script>
      

      <!-----MAIN PHP ----->

   </body>
</html>
";

echo $html;

?>
