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
   <title>Walletwise | Savings Goals</title>
</head>

<body>
   <?php if (isset($_SESSION['goal_added'])): ?>
   <div class="success-banner" id="successBanner">
      <i class="ri-checkbox-circle-fill"></i>
      Saving goals added successfully
   </div>
   <script>
      document.addEventListener('DOMContentLoaded', () => {
         const banner = document.getElementById('successBanner');
         setTimeout(() => banner.classList.add('active'), 100);
         setTimeout(() => {
            banner.classList.remove('active');
            setTimeout(() => banner.remove(), 500);
         }, 3000);
      });
   </script>
   <?php unset($_SESSION['goal_added']); ?>
   <?php endif; ?>

      <?php if (isset($_SESSION['goal_deleted'])): ?>
   <div class="success-banner" id="successBanner">
      <i class="ri-checkbox-circle-fill"></i>
      Saving goals successfully deleted 
   </div>
   <script>
      document.addEventListener('DOMContentLoaded', () => {
         const banner = document.getElementById('successBanner');
         setTimeout(() => banner.classList.add('active'), 100);
         setTimeout(() => {
            banner.classList.remove('active');
            setTimeout(() => banner.remove(), 500);
         }, 3000);
      });
   </script>
   <?php unset($_SESSION['goal_deleted']); ?>
   <?php endif; ?>



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
               <img class='sidebar__img' src='../images/un_logo_con_W_W.png' alt='user'>
            </div>

            <div class='sidebar__info'>
               <h3>
                  <?php echo htmlspecialchars($_SESSION["username"]); ?>
               </h3>
               <span>
                  <?php echo htmlspecialchars($_SESSION["email"] ?? ''); ?>
               </span>
            </div>
         </div>

         <div class='sidebar__content'>
            <div>
               <h3 class='sidebar__title'>MANAGE</h3>

               <div class='sidebar__list'>
                  <a href='../home/homebankingHtml.php' id='home' class='sidebar__link ' data-section='home'>
                     <i class='ri-pie-chart-2-fill'></i>
                     <span>Home</span>
                  </a>

                  <a id='wallet' class='sidebar__link ' href='../wallet/walletHtml.php' data-section='wallet'>
                     <i class='ri-wallet-3-fill'></i>
                     <span>My Wallet</span>
                  </a>

                  <a id='recentTransactions' class='sidebar__link ' href='../transaction/transactionHtml.php'
                     data-section='transactions'>
                     <i class='ri-arrow-up-down-line'></i>
                     <span>Recent Transactions</span>
                  </a>

                  <a id='goals' class='sidebar__link active-link' href='#' data-section='goals'>
                     <i class='ri-archive-drawer-fill'></i>
                     <span>Savings goals</span>
                  </a>

                  <a id="investment" class="sidebar__link " href="../investment/investmentHtml.php"
                     data-section="investment">
                     <i class="ri-line-chart-fill"></i>
                     <span>Investment</span>
                  </a>
               </div>
            </div>

            <div>
               <h3 class='sidebar__title'>SETTINGS</h3>

               <div class='sidebar__list'>
                  <a href='#' class='sidebar__link'>
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

            <button class='sidebar__link' onclick="window.location.href='../login/logout.php'">
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

            <?php include 'php/savingsGoals.php'; ?>

            <div class='goalsList'>
               <?php echo $valori; ?>
            </div>

            <div class='newGoal'>
               <h3>Set a new Savings Goal</h3>
               <br>
               <?php $valori ?>
               <input type="hidden" id="cardBalance" value="<?php echo $balance; ?>">

               <form class='goalForm' id='newGoalForm' method='post' action='php/formSavingsGoal.php'
                  enctype='multipart/form-data'>
                  <div style='display: flex; gap: 1rem; align-items: flex-start;'>
                     <div style='flex: 1;'>
                        <div class='form__div'>
                           <input type='text' name='name' class='form__input' placeholder=' '>
                           <label class='form__label'>Goal Name</label>
                        </div>
                        <div class="error-container" data-field="name"></div>
                     </div>
                     <div style='flex: 1;'>
                        <div class='form__div'>
                           <input type='date' id='startDate' name='startDate' class='form__input'>
                           <label class='form__label'>Start Date</label>
                        </div>
                        <div class="error-container" data-field="startDate"></div>
                     </div>
                  </div>

                  <div style='display: flex; gap: 1rem;'>
                     <div style='flex: 1;'>
                        <div class='form__div'>
                           <input type='text' name='description' class='form__input' placeholder=' '>
                           <label class='form__label'>Goal Description</label>
                        </div>
                        <div class="error-container" data-field="description"></div>
                     </div>
                  </div>

                  <div style='display: flex; gap: 1rem;'>
                     <div style='flex: 1;'>
                        <div class='form__div'>
                           <input type='number' id='goalAmount' name='goalAmount' class='form__input' placeholder=' '
                              step="0.01" min="0">
                           <label class='form__label'>Goal Amount</label>
                        </div>
                        <div class="error-container" data-field="goalAmount"></div>
                     </div>

                     <div style='flex: 1;'>
                        <div class='form__div'>
                           <input type='number' id='monthAmount' name='monthAmount' class='form__input' placeholder=' '
                              step="0.01" min="0">
                           <label class='form__label'>Month Amount</label>
                        </div>
                        <div class="error-container" data-field="monthAmount"></div>
                     </div>
                  </div>

                  <div class="icone">
                     <label>
                        <input type="radio" name="icona" value="travel.png">
                        <img src="../images/icone/travel.png" alt="Icona travel">
                     </label>
                     <label>
                        <input type="radio" name="icona" value="car.png">
                        <img src="../images/icone/car.png" alt="Icona car">
                     </label>
                     <label>
                        <input type="radio" name="icona" value="people.png">
                        <img src="../images/icone/people.png" alt="Icona people">
                     </label>
                     <label>
                        <input type="radio" name="icona" value="food.png">
                        <img src="../images/icone/food.png" alt="Icona food">
                     </label>
                     <label>
                        <input type="radio" name="icona" value="pc.png">
                        <img src="../images/icone/pc.png" alt="Icona pc">
                     </label>
                     <label>
                        <input type="radio" name="icona" value="home.png">
                        <img src="../images/icone/home.png" alt="Icona home">
                     </label>
                     <label>
                        <input type="radio" name="icona" value="games.png">
                        <img src="../images/icone/games.png" alt="Icona games">
                     </label>
                     <label>
                        <input type="radio" name="icona" value="books.png">
                        <img src="../images/icone/books.png" alt="Icona books">
                     </label>
                  </div>
                  <div class="error-container" data-field="icona"></div>

                  <div class="button-container">
                     <a class="CancelGoal">Cancel</a>
                     <a name="submit" class="saveGoal">Save</a>
                  </div>

                  <!-- Campo nascosto per il valore del saldo della carta -->
                  <input type="hidden" id="cardBalance" value="1000">
               </form>
            </div>


         </div>

      </div>



   </main>

   <!-----MAIN JS ----->
   <script src='../src/home.js'></script>
   <script src='js/savingsGoals.js'></script>
   <script src='js/savingsGoalsForm.js'></script>
   <!-----MAIN PHP ----->

</body>

</html>