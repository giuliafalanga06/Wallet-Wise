<?php
session_start();

// Controlla se l'utente è loggato
if (!isset($_SESSION["username"])) {
   header("Location: ../login/login.php");
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
   <link rel="stylesheet" href="../styles/home.css">
   <link rel="stylesheet" href="../investment/style/styles.css">
   
   <title>Walletwise | Investments</title>
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
               <img class="sidebar__img" src="../images/un_logo_con_W_W.png" alt="user">
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
                  <a id="home" class="sidebar__link" href='../home/homebankingHtml.php' data-section="home">
                     <i class="ri-pie-chart-2-fill"></i> 
                     <span>Home</span>
                  </a>
                  
                  <a id="wallet" class="sidebar__link" href="../wallet/walletHtml.php" data-section="wallet">
                     <i class="ri-wallet-3-fill"></i>
                     <span>My Wallet</span>
                  </a>

                  <a id="recentTransactions" class="sidebar__link" href="../transaction/transactionHtml.php" data-section="transactions">
                     <i class="ri-arrow-up-down-line"></i>
                     <span>Recent Transactions</span>
                  </a>

                  <a id="goals" class="sidebar__link" href="../savingsGoals/savingsGoalsHtml.php" data-section="goals">
                     <i class="ri-archive-drawer-fill"></i>
                     <span>Savings goals</span>
                  </a>

                  <a id="investment" class="sidebar__link active-link" href="#" data-section="investment">
                     <i class="ri-line-chart-fill"></i>
                     <span>Investment</span>
                  </a>
               </div>
            </div>

            <div>
               <h3 class="sidebar__title">SETTINGS</h3>

               <div class="sidebar__list">
                  <a href="#" class="sidebar__link">
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

   <!-----MAIN CONTENT ----->
   <main class="main container inv-main-container" id="main">
      <div class="inv-section">
         <h2 class="inv-section-title">Investment Area</h2>
         
         <button id="showChartBtn" class="inv-button" style="margin-bottom: 20px;">
            <i class="ri-line-chart-line"></i> Show Market Chart
         </button>

         <!-- Overlay per il grafico TradingView -->
         <div class="market-chart-overlay" id="chartOverlay">
            <div class="market-chart-container">
               <section class="inv-stock-section">
                  <h3 class="inv-subtitle">Stock Market</h3>
                  <p class="inv-description">Select a market or stock to view the chart:</p>

                  <select id="marketSelector" onchange="loadChart()" style="padding: 8px; margin-top: 10px;"></select>

                  <div class="tradingview-widget-container">
                     <div id="tradingview_chart"></div>
                  </div>
                  
                  <button id="closeChartBtn" class="cancelChartBtn">Close Chart</button>
               </section>
            </div>
         </div>

         <!-- Sezione simulatore investimenti -->
         <section class="inv-simulator-section">
            <h3 class="inv-subtitle">AutoInvest Simulator</h3>
            <p class="inv-description">This educational platform will teach you the fundamentals of automatic investments, allowing you to simulate investment strategies without real risks.</p>
            
            <div class="inv-info-cards">
               <div class="inv-card">
                  <h4 class="inv-card-title">What are automatic investments?</h4>
                  <p class="inv-card-text">Automatic investments (or DCA - Dollar Cost Averaging) allow you to invest small amounts at regular intervals, reducing risk and taking advantage of dollar-cost averaging.</p>
               </div>
               <div class="inv-card">
                  <h4 class="inv-card-title">Benefits</h4>
                  <p class="inv-card-text">✓ Investment discipline<br>
                     ✓ Reduced timing risk<br>
                     ✓ Exploitation of market fluctuations<br>
                     ✓ Ability to start with small amounts</p>
               </div>
               <div class="inv-card">
                  <h4 class="inv-card-title">How it works</h4>
                  <p class="inv-card-text">1. Define a monthly budget<br>
                     2. Choose financial instruments<br>
                     3. Set investment frequency<br>
                     4. The system invests automatically</p>
               </div>
            </div>

            <form id="investment-form" class="inv-form">
               <div class="inv-form-group">
                  <label for="initial-amount" class="inv-label">Initial investment (€):</label>
                  <input type="number" id="initial-amount" class="inv-input" min="0" value="1000">
               </div>
               
               <div class="inv-form-group">
                  <label for="monthly-contribution" class="inv-label">Monthly contribution (€):</label>
                  <input type="number" id="monthly-contribution" class="inv-input" min="0" value="100">
               </div>
               
               <div class="inv-form-group">
                  <label for="years" class="inv-label">Investment period (years):</label>
                  <input type="number" id="years" class="inv-input" min="1" max="50" value="10">
               </div>
               
               <div class="inv-form-group">
                  <label for="expected-return" class="inv-label">Expected annual return (%):</label>
                  <input type="number" id="expected-return" class="inv-input" min="0" max="30" step="0.1" value="7">
               </div>
               
               <div class="inv-form-group">
                  <label for="investment-frequency" class="inv-label">Investment frequency:</label>
                  <select id="investment-frequency" class="inv-select">
                     <option value="monthly">Monthly</option>
                     <option value="quarterly">Quarterly</option>
                     <option value="biannual">Semiannual</option>
                     <option value="annual">Annual</option>
                  </select>
               </div>
               
               <button type="button" id="calculate-btn" class="inv-button">Calculate</button>
            </form>
            
            <div id="results" class="inv-results hidden">
               <h4 class="inv-results-title">Simulation Results</h4>
               <div class="inv-results-grid">
                  <div class="inv-result-item">
                     <span class="inv-result-label">Final amount:</span>
                     <span id="final-amount" class="inv-result-value">€0</span>
                  </div>
                  <div class="inv-result-item">
                     <span class="inv-result-label">Total invested:</span>
                     <span id="total-invested" class="inv-result-value">€0</span>
                  </div>
                  <div class="inv-result-item">
                     <span class="inv-result-label">Gain:</span>
                     <span id="total-gain" class="inv-result-value">€0</span>
                  </div>
                  <div class="inv-result-item">
                     <span class="inv-result-label">Total return:</span>
                     <span id="total-return" class="inv-result-value">0%</span>
                  </div>
               </div>
               
               <div class="inv-chart-container">
                  <canvas id="investment-chart" class="inv-canvas-chart"></canvas>
               </div>
               
               <table id="yearly-breakdown" class="inv-table">
                  <thead>
                     <tr>
                        <th>Year</th>
                        <th>Invested capital</th>
                        <th>Value</th>
                        <th>Gain</th>
                     </tr>
                  </thead>
                  <tbody id="yearly-data"></tbody>
               </table>
            </div>
         </section>

         <!-- Sezione educativa -->
         <section class="inv-education-section">
            <h3 class="inv-subtitle">Key Concepts to Understand</h3>
            
            <div class="inv-accordion">
               <div class="inv-accordion-item">
                  <button class="inv-accordion-header">Dollar-Cost Averaging (DCA)</button>
                  <div class="inv-accordion-content">
                     <p>The Dollar-Cost Averaging is a strategy where you invest fixed amounts at regular intervals, regardless of market price. This allows you to buy more shares when prices are low and fewer shares when prices are high, averaging the purchase cost over time.</p>
                     <p>Main benefits:</p>
                     <ul class="inv-list">
                        <li>Reduces the impact of market volatility</li>
                        <li>Eliminates the need for "perfect timing"</li>
                        <li>Promotes investment discipline</li>
                     </ul>
                  </div>
               </div>
               
               <div class="inv-accordion-item">
                  <button class="inv-accordion-header">Compound Interest</button>
                  <div class="inv-accordion-content">
                     <p>Compound interest is the process where the returns generated by your initial investment are added to the principal, generating further returns in turn. It is often referred to as the eighth wonder of the financial world.</p>
                     <p>An example: if you invest €1000 with an annual return of 7%, after one year you will have €1070. The next year, the 7% will apply to €1070, not just the initial €1000, and so on, creating a "snowball" effect.</p>
                  </div>
               </div>
            </div>
         </section>
      </div>
   </main>

   <!-----SCRIPTS ----->
   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
   <script src="../investment/js/script.js"></script>
   <script src="../src/home.js"></script>

   <script>
      // Gestione overlay del grafico
      const showChartBtn = document.getElementById('showChartBtn');
      const chartOverlay = document.getElementById('chartOverlay');
      const closeChartBtn = document.getElementById('closeChartBtn');
      
      showChartBtn.addEventListener('click', function() {
         chartOverlay.classList.add('show');
         loadChart(); // Ricarica il grafico ogni volta che viene aperto
      });
      
      closeChartBtn.addEventListener('click', function() {
         chartOverlay.classList.remove('show');
      });
      
      // Chiudi l'overlay cliccando fuori dal contenuto
      chartOverlay.addEventListener('click', function(e) {
         if (e.target === chartOverlay) {
            chartOverlay.classList.remove('show');
         }
      });
      
      // Array configurabile con tutti i titoli e mercati
      const markets = [
         { name: "Apple (AAPL)", symbol: "NASDAQ:AAPL" },
         { name: "Microsoft (MSFT)", symbol: "NASDAQ:MSFT" },
         { name: "Tesla (TSLA)", symbol: "NASDAQ:TSLA" },
         { name: "Nvidia (NVDA)", symbol: "NASDAQ:NVDA" },
         { name: "Amazon (AMZN)", symbol: "NASDAQ:AMZN" },
         { name: "Google (GOOGL)", symbol: "NASDAQ:GOOGL" },
         { name: "Meta (META)", symbol: "NASDAQ:META" },
         { name: "S&P 500 (SPY)", symbol: "NYSEARCA:SPY" },
         { name: "Nasdaq 100 (QQQ)", symbol: "NASDAQ:QQQ" },
         { name: "Dow Jones (DIA)", symbol: "NYSEARCA:DIA" },
         { name: "Russell 2000 (IWM)", symbol: "NYSEARCA:IWM" },
         { name: "ETF obbligazionario (AGG)", symbol: "NASDAQ:AGG" },
         { name: "Bitcoin (BTC)", symbol: "BINANCE:BTCUSDT" },
         { name: "Ethereum (ETH)", symbol: "BINANCE:ETHUSDT" },
         { name: "FTSE MIB (Italia)", symbol: "MIL:FTSEMIB" },
         { name: "DAX (Germania)", symbol: "XETR:DAX" },
         { name: "Nikkei 225 (Giappone)", symbol: "TVC:NI225" },
         { name: "Oro (Gold)", symbol: "TVC:GOLD" },
         { name: "Petrolio (WTI)", symbol: "TVC:USOIL" }
      ];

      // Popola il menu
      const selector = document.getElementById("marketSelector");
      markets.forEach((m, i) => {
         const opt = document.createElement("option");
         opt.value = m.symbol;
         opt.text = m.name;
         selector.appendChild(opt);
      });

      // Carica il grafico selezionato
      function loadChart() {
         const selectedSymbol = selector.value;
         document.getElementById("tradingview_chart").innerHTML = ""; // Pulisce vecchio grafico

         new TradingView.widget({
            "width": "100%",
            "height": 500,
            "symbol": selectedSymbol,
            "interval": "D",
            "timezone": "Europe/Rome",
            "theme": "light",
            "style": "1",
            "locale": "it",
            "toolbar_bg": "#f1f3f6",
            "enable_publishing": false,
            "withdateranges": true,
            "hide_side_toolbar": false,
            "allow_symbol_change": false,
            "container_id": "tradingview_chart"
         });
      }
   </script>
</body>
</html>