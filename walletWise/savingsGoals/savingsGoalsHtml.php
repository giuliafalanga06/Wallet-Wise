
       

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
      <link rel="stylesheet" href="../styles/input.css">
      <link rel="stylesheet" href="../styles/goals.css">
      <link rel="stylesheet" href="../styles/home.css">
      <title>Responsive sidebar Menu | Dark/Light Mode - Bedimcode</title>
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
                  <h3>Sgaramella Antonio</h3>
                  <span>sgara06.anto@gmail.com</span>
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
                     
                     <a id="wallet" class="sidebar__link " href="../wallet/walletHtml.php" data-section = "wallet">
                        <i class="ri-wallet-3-fill"></i>
                        <span>My Wallet</span>
                     </a>

                     <a id="recentTransactions" class="sidebar__link "   href="../transaction/transactionHtml.php"data-section = "transactions">
                        <i class="ri-arrow-up-down-line"></i>
                        <span>Recent Transactions</span>
                     </a>

                     <a id="goals" class="sidebar__link active-link"  href="#" data-section = "goals">
                        <i class="ri-archive-drawer-fill"></i>
                        <span>Savings goals</span>
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

                  <button class="sidebar__link" onclick="window.location.href='../index.html'">
                    <i class="ri-logout-box-r-fill"></i>
                    <span>Log Out</span>
                  </button>
            </div>
         </div>
      </nav>

      <!-----MAIN ----->
      <main class="main container" id="main">

         <div class="section goals">
            <h2>Savings goals</h2>
            <div>
               <span class="newGoalBtn">
                  <button class="btn">+</button>
                  Savings goal
               </span>

               <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            
               <div class="goalsList">
                  <div class="singleGoal">
                     <h3 class="goal-name">Buy a New Caraaaaaaaaaaaa</h3>                     
                        <div class="goal-progress">
                           <canvas  id="coursesDoughnutChart" style="width: 50px;"></canvas>
                        </div>
                        <button class='saveGoal moreDetailsBtn'>More Details</button>
                        
                        <div class="goal-details" style="display: none; margin-top: 10px;">
                           <p><strong>End Date:</strong> 31/12/2025</p>
                           <p><strong>Total Saved:</strong> $5,000</p>
                           <p><strong>Target Amount:</strong> $20,000</p>
                        </div>
                  </div>
               </div>

               <div class="newGoal">
                  <h3>Set a new Savings Goal</h3>
               
                  <?php $valori ?>
                  <form class="goalForm" method="post" action="#">
                     <div style="display: flex; gap: 1rem; align-items: flex-end;">
                        <div class="form__div" style="flex: 1; max-width: 30%;">
                           <input type="text" name='name' class="form__input" placeholder=" ">
                           <label class="form__label">Goal Name</label>
                        </div>
               
                        <div class="form__div" style="flex: 2; max-width: 70%;">
                           <input type="text"  name='description' class="form__input" placeholder=" ">
                           <label class="form__label">Goal Description</label>
                        </div>
                     </div>
               
                     <div style="display: flex; gap: 1rem;">
                        <div class="form__div" style="flex: 1;">
                           <input type="number" name='amount' class="form__input" placeholder=" ">
                           <label class="form__label">Goal Amount</label>
                        </div>
               
                        <div class="form__div" style="flex: 1;">
                           <input type="date" name='date' class="form__input">
                           <label class="form__label">Target Date</label>
                        </div>
                     </div>
               
                     <input type="submit" value="Save" name="submit" class="saveGoal">
                  </form>
               </div>
              
                 
            </div>
            
         </div>



      </main>
      
      <!-----MAIN JS ----->
      <script src="../src/home.js"></script>
      <script src="../src/savingsGoals.js"></script>
   </body>
</html>