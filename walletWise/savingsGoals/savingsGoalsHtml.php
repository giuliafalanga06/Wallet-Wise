<?php
session_start();

// Controlla se l'utente è loggato
if (!isset($_SESSION["username"])) {
   header("Location: ../login/login.php");
   exit();
}
?>
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
                  <h3><?php echo htmlspecialchars($_SESSION["username"]); ?></h3>
                  <span><?php echo htmlspecialchars($_SESSION["email"] ?? ''); ?></span>
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

                  <button class='sidebar__link' onclick="window.location.href='../index.html'">
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
            <h2>Savings goals</h2>
            <div>
               <span class='newGoalBtn btn'>
                  +
                  Savings goal
               </span>

               <script src='https://cdn.jsdelivr.net/npm/chart.js'></script>

               <?php include 'php/savingsGoals.php';?>
               
               <div class='goalsList'>
                  <?php echo $valori; ?>
               </div>

               <div class='newGoal'>
                  <h3>Set a new Savings Goal</h3>
                  <br>
                  <?php $valori ?>
                  <form class='goalForm' method='post' action='php/formSavingsGoal.php' enctype='multipart/form-data'>

                     <div style='display: flex; gap: 1rem; align-items: flex-end;'>

                        <div class='form__div' style='flex: 1; max-width: 30%;'>
                           <input type='text' name='name' class='form__input' placeholder=' '>
                           <label class='form__label'>Goal Name</label>
                           
                        </div>
                        
                        

                        <div class='form__div' style='flex: 2; max-width: 70%;'>
                           <input type='text'  name='description' class='form__input' placeholder=' '>
                           <label class='form__label'>Goal Description</label>
                        </div>
                     </div>
               
                     <div style='display: flex; gap: 1rem;'>
                        <div class='form__div' style='flex: 1;'>
                           <input type='number' id='goalAmount' name='goalAmount' class='form__input' placeholder=' '>
                           <label class='form__label'>Goal Amount</label>
                        </div>

                        <div class='form__div' style='flex: 1;'>
                           <input type='number' id='monthAmount' name='monthAmount' class='form__input' placeholder=' '>
                           <label class='form__label'>Month Amount</label>
                        </div>
               
                        
                     </div>

                     <div style='display: flex; gap: 1rem;'>
                        <div class='form__div' style='flex: 1;'>
                           <input type='date' id='startDate'  name='startDate' class='form__input'>
                           <label class='form__label'>Start Date</label>
                        </div>
               
                        <div class='form__div' style='flex: 1;'>
                           <input type='file'  name='icon' value='' class='form__input'>
                           <label class='form__label'>Icon</label>
                        </div>
                     </div>
                     <div class="button-container">
                        <a class="CancelGoal">Cancel</a>
                        <input type="submit" value="Save" name="submit" class="saveGoal">
                     </div>
                  </form>
               </div>
              
                 
            </div>
            
         </div>



      </main>
      
      <!-----MAIN JS ----->
      <script src='../src/home.js'></script>
      <script src='js/savingsGoals.js'></script>

      <!-----MAIN PHP ----->

   </body>
</html>