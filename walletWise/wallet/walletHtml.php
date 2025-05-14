<?php
session_start();

// Controlla se l'utente è loggato
if (!isset($_SESSION["username"])) {
    header("Location: ../login/login.html");
    exit();
}
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
      <link rel='stylesheet' href='./css/wallet.css'>
      <link rel='stylesheet' href='../styles/input.css'>
      <link rel="stylesheet" href="../styles/home.css">
      <link rel="stylesheet" href="../styles/cards.css">
      
      
      <title>Walletwise | Wallet</title>
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
                  <span><?php echo htmlspecialchars($_SESSION["email"] ?? ''); ?></span>
               </div>
            </div>

            <div class="sidebar__content">
               <div>
                  <h3 class="sidebar__title">MANAGE</h3>

                  <div class="sidebar__list">
                     <a href="../home/homebankingHtml.php" id="home" class="sidebar__link " data-section = "home">
                        <i class="ri-pie-chart-2-fill"></i>
                        <span>Home</span>
                     </a>
                     
                     <a id="wallet" class="sidebar__link active-link" href="#" data-section = "wallet">
                        <i class="ri-wallet-3-fill"></i>
                        <span>My Wallet</span>
                     </a>

                     <a id="recentTransactions" class="sidebar__link "   href="../transaction/transactionHtml.php"data-section = "transactions">
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
      <div class='overlay'></div>
      <?php include 'php/card.php';?>
      <div class="section wallet">
            <h2>My Wallet</h2>
            <section class="cardsContainer">
               <div class="cardsWrapper">

                  <div class="cards">
                     <div class='credit-card' id="+ id +">
                        <div class='card-header'>
                           <div class='chip'></div>
                           <div class='logo'>Wise</div>
                        </div>
                        <div class='card-number'><?php echo $iban;?></div>
                        <div class='card-footer'>
                           <div class='card-holder'>
                              <span>Titolare</span>
                              <p> <?php echo$_SESSION['username'];?></p>
                           </div>
                           <div class='expires'>
                              <span>Scadenza</span>
                              <p><?php echo $expirationMonthYear ?></p>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </section>

         <div>
            <a class='newTransferBtn'>+ Instant bank transfer</a>
         </div>

         <div class='newTransfer'>
         <h3>Set a new instant bank transfer</h3>
         <br>
                        <form class='Transfer' method='post' action='php/formTransfer.php' enctype='multipart/form-data'>
               <div style='display: flex; gap: 1rem; align-items: flex-end;'>
                  <div class='form__div' style='flex: 1;'>
                     <input type='text' name='name' class='form__input' placeholder=' '>
                     <label class='form__label'>Beneficiary's Name</label>
                  </div>
                  <div class='form__div' style='flex: 1;'>
                     <input type='text' id='surname'  name='surname' class='form__input' placeholder=' '>
                     <label class='form__label'>Beneficiary's surname</label>
                  </div>
               </div>

      
               <div style='display: flex; gap: 1rem;'>
                  <div class='form__div' style='flex: 1;'>
                     <input type='text'  name='iban' class='form__input' placeholder=' '>
                     <label class='form__label'>Beneficiary's Iban</label>
                  </div>

                  <div class='form__div' style='flex: 1;'>
                     <input type='number' id='amount' name='amount' class='form__input' placeholder=' '>
                     <label class='form__label'>Amount</label>
                  </div>
               </div>

               <div style='display: flex; gap: 1rem;'>
                  <div class='form__div' style='flex: 1;'>
                  <input type='text' id='reason' name='reason' class='form__input' placeholder=' '>
                  <label class='form__label'>Reason for payment</label>
                  </div>
               </div>
               
               <div class="button-container">
                        <a class="CancelTransfer">Cancel</a>
                        <input type="submit" value="Save" name="submit" class="saveTransfer">
               </div>
            </form>
         </div>
      </div> 

      </main>
      
      <!-----MAIN JS ----->
      <script src="../src/home.js"></script>
      <script src="../src/card.js"></script>
      <script src="./js/wallet.js"></script>
   </body>
</html>
