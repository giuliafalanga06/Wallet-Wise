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
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      <!-----REMIXICONS ----->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css">

      <!-----CSS ----->
      <link rel="stylesheet" href="../styles/home.css">
      <link rel="stylesheet" href="styles/home.css">
      <title>Walletwise | Home</title>
       <style>

        #transactionsChart {
            margin-top: 20px;
        }
    </style>

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
                     <p>Balance : €<?php echo number_format($balance, 2); ?></p>
                     </div>
                     <div class="bottom-left">
                        <?php echo $valori; ?>  
                     </div>
                  </div>
                  <div class="right-div">
                       <div class="container">
                           <h1 style="text-align: center;">Balance Trend<?php echo htmlspecialchars($cardId); ?></h1>
                           <canvas id="transactionsChart"></canvas>
                        </div>

                                 <script>
                                    // Dati passati direttamente da PHP a JavaScript
                                    const transactions = <?php echo $transactionsJson; ?>;
                                    console.log(transactions);
                                    // Preparazione dati per il grafico
                                    const dates = transactions.map(t => t.Date);
                                    const balances = transactions.map(t => parseFloat(t.NewBalance));
                                    
                                    if (transactions.length === 0 ) {
                                       document.getElementById('transactionsChart').parentElement.innerHTML =   '<p class="no-data">No transactions available</p>';
                                    } 
                                    else{
                                    // Creazione grafico
                                       const ctx = document.getElementById('transactionsChart').getContext('2d');
                                       const transactionsChart = new Chart(ctx, {
                                             type: 'line',
                                             data: {
                                                labels: dates,
                                                datasets: [{
                                                   label: 'Balance (€)',
                                                   data: balances,
                                                   borderColor: 'hsl(193, 86%, 34%)',
                                                   tension: 0.1,
                                                   fill: true
                                                }]
                                             },
                                             options: {
                                                responsive: true,
                                                plugins: {
                                                   title: {
                                                         display: true,
                                                         text: 'Last 10 transactions',
                                                         font: {
                                                            size: 15
                                                         }
                                                   },
                                                   tooltip: {
                                                         callbacks: {
                                                            label: function(context) {
                                                               return `Balance: €${context.parsed.y.toFixed(2)}`;
                                                            }
                                                         }
                                                   }
                                                },
                                                scales: {
                                                   y: {
                                                         beginAtZero: false,
                                                         ticks: {
                                                            callback: function(value) {
                                                               return `€${value}`;
                                                            }
                                                         }
                                                   },

                                                   y: {
                                                      ticks: { maxTicksLimit: dates.length } // Forza solo 5 etichette sull'asse X
                                                   },
                                                }
                                             }
                                          
                                       });
                                    }
                                 </script>
                  </div>
                  
               </div>
         </div>
      </main>

      
      <!-----MAIN JS ----->
      <script src="../src/home.js"></script>
   </body>
</html>