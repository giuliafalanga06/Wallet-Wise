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
    <link rel='icon' type='image/png' sizes='32x32' href='../images/un_logo_con_W_W.png'>
   <!-----JQUERY ----->
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

   <!-----REMIXICONS ----->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css">

   <!-----CSS ----->
   <link rel="stylesheet" href="../styles/home.css">
   <link rel="stylesheet" href="../investment/style/styles.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
   
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

         <!-- sezione investimenti -->
         <main class="ss-main ss-container">
        <section class="ss-simulator-section">
            <div class="ss-simulator-card">
                <div class="ss-simulator-controls">
                    <div class="ss-input-group">
                        <label for="ss-initial-amount" class="ss-input-label">Deposito iniziale (€)</label>
                        <input type="range" id="ss-initial-amount" class="ss-range-input" min="100" max="10000" step="100" value="1000">
                        <div class="ss-value-display">€ <span id="ss-initial-amount-value" class="ss-value-number">1000</span></div>
                    </div>

                    <div class="ss-input-group">
                        <label for="ss-monthly-amount" class="ss-input-label">Deposito mensile (€)</label>
                        <input type="range" id="ss-monthly-amount" class="ss-range-input" min="0" max="1000" step="50" value="100">
                        <div class="ss-value-display">€ <span id="ss-monthly-amount-value" class="ss-value-number">100</span></div>
                    </div>

                    <div class="ss-input-group">
                        <label for="ss-duration" class="ss-input-label">Durata (anni)</label>
                        <input type="range" id="ss-duration" class="ss-range-input" min="1" max="30" step="1" value="5">
                        <div class="ss-value-display"><span id="ss-duration-value" class="ss-value-number">5</span> anni</div>
                    </div>

                    <div class="ss-input-group">
                        <label for="ss-risk-profile" class="ss-input-label">Profilo di rischio</label>
                        <select id="ss-risk-profile" class="ss-select-input">
                            <option value="conservative">Conservativo (2%)</option>
                            <option value="balanced" selected>Bilanciato (4%)</option>
                            <option value="aggressive">Aggressivo (6%)</option>
                        </select>
                    </div>
                  
                    <div class="ss-input-group">
                      <button id="ss-calculate-btn" class="ss-primary-btn">Calcola</button>
                    </div>
                    
                </div>

                <div class="ss-simulator-results">
                    <h3 class="ss-results-title">Proiezione del tuo risparmio</h3>
                    <div class="ss-chart-container">
                        <canvas id="ss-savings-chart" class="ss-chart"></canvas>
                    </div>
                    <div class="ss-summary">
                        <div class="ss-summary-item">
                            <span class="ss-summary-label">Totale versato</span>
                            <strong id="ss-total-deposited" class="ss-summary-value">€0</strong>
                        </div>
                        <div class="ss-summary-item">
                            <span class="ss-summary-label">Interessi guadagnati</span>
                            <strong id="ss-interest-earned" class="ss-summary-value">€0</strong>
                        </div>
                        <div class="ss-summary-item ss-highlight">
                            <span class="ss-summary-saldo">Saldo finale</span>
                            <strong id="ss-final-balance" class="ss-saldo-finale">€0</strong>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="inv-education-section">
            <h3 class="inv-subtitle">Guida agli Investimenti Automatici</h3>
            
            <div class="inv-education-tabs">
               <button class="inv-tab-btn active" data-tab="inv-basics">Concetti Base</button>
               <button class="inv-tab-btn" data-tab="inv-strategies">Strategie</button>
               <button class="inv-tab-btn" data-tab="inv-platforms">Piattaforme</button>
               <button class="inv-tab-btn" data-tab="inv-faq">FAQ</button>
            </div>

            <!-- Tab 1: Concetti Base -->
            <div id="inv-basics" class="inv-tab-content" style="display: block;">
               <h4><i class="ri-lightbulb-line"></i> Cosa sono gli Investimenti Automatici?</h4>
               <p>Sistemi che utilizzano algoritmi e tecnologie digitali per gestire portafogli con intervento umano minimo o nullo.</p>
               
               <div class="inv-types-grid">
                  <div class="inv-type-card">
                     <h5><i class="ri-calendar-line"></i> PAC (Piani di Accumulo)</h5>
                     <ul>
                        <li>Investimenti periodici di importi fissi</li>
                        <li>Dollar-cost averaging (media dei prezzi nel tempo)</li>
                        <li>Riduce l'impatto della volatilità</li>
                     </ul>
                  </div>
                  
                  <div class="inv-type-card">
                     <h5><i class="ri-cpu-line"></i> Trading Algoritmico</h5>
                     <ul>
                        <li>Operazioni ad alta frequenza</li>
                        <li>Sfrutta inefficienze di mercato</li>
                        <li>Principalmente per istituzioni</li>
                     </ul>
                  </div>
                  
                  <div class="inv-type-card">
                     <h5><i class="ri-share-line"></i> Copy Trading</h5>
                     <ul>
                        <li>Copia strategie di trader esperti</li>
                        <li>Trasparenza sui rendimenti</li>
                        <li>Possibilità di diversificare</li>
                     </ul>
                  </div>
               </div>
               
               <div class="inv-benefits-box">
                  <h5><i class="ri-medal-line"></i> Vantaggi Principali</h5>
                  <ul>
                     <li><strong>Accessibilità:</strong> investimenti minimi bassi</li>
                     <li><strong>Costi ridotti:</strong> commissioni inferiori</li>
                     <li><strong>Disciplina:</strong> niente decisioni emotive</li>
                     <li><strong>Efficienza:</strong> risparmio di tempo</li>
                     <li><strong>Diversificazione:</strong> portafogli globali</li>
                  </ul>
               </div>
            </div>

            <!-- Tab 2: Strategie -->
            <div id="inv-strategies" class="inv-tab-content">
               <h4><i class="ri-line-chart-line"></i> Strategie di Investimento</h4>
               
               <div class="inv-strategy-accordion">
                  <button class="inv-accordion-btn">Strategie Basate su Regole <i class="ri-arrow-down-s-line"></i></button>
                  <div class="inv-accordion-panel">
                     <ul>
                        <li><strong>Ribilanciamento periodico:</strong> riaggiustamento alle allocazioni target</li>
                        <li><strong>Momentum:</strong> acquisto di asset con performance recenti forti</li>
                        <li><strong>Value:</strong> focus su asset sottovalutati</li>
                        <li><strong>Dividend Growth:</strong> concentrazione su aziende con dividendi crescenti</li>
                     </ul>
                  </div>
                  
                  <button class="inv-accordion-btn">Strategie con Algoritmi Avanzati <i class="ri-arrow-down-s-line"></i></button>
                  <div class="inv-accordion-panel">
                     <ul>
                        <li><strong>Reti neurali:</strong> modelli complessi ispirati al funzionamento cerebrale</li>
                        <li><strong>NLP:</strong> analisi del sentiment da notizie e social</li>
                        <li><strong>Reinforcement Learning:</strong> algoritmi che migliorano con l'esperienza</li>
                        <li><strong>Ensemble Methods:</strong> combinazione di più modelli</li>
                     </ul>
                  </div>
               </div>
               
               <div class="inv-implementation-box">
                  <h5><i class="ri-checkbox-line"></i> Come Implementare</h5>
                  <ol>
                     <li>Definire obiettivi (rendimenti, rischio, orizzonte temporale)</li>
                     <li>Selezionare piattaforma (costi, funzionalità, affidabilità)</li>
                     <li>Configurazione iniziale (allocazione, frequenza)</li>
                     <li>Monitoraggio periodico</li>
                     <li>Ottimizzazione quando necessario</li>
                  </ol>
               </div>
            </div>

            <!-- Tab 3: Piattaforme -->
            <div id="inv-platforms" class="inv-tab-content">
               <h4><i class="ri-computer-line"></i> Piattaforme di Riferimento</h4>
               
               <div class="inv-platforms-table">
                  <table>
                     <thead>
                        <tr>
                           <th>Regione</th>
                           <th>Piattaforme</th>
                           <th>Caratteristiche</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td>Nord America</td>
                           <td>Betterment, Wealthfront, Robinhood</td>
                           <td>Robo-advisor avanzati, bassi costi</td>
                        </tr>
                        <tr>
                           <td>Europa</td>
                           <td>Nutmeg, Moneyfarm, Scalable Capital</td>
                           <td>Conforme a regolamentazioni UE</td>
                        </tr>
                        <tr>
                           <td>Italia</td>
                           <td>Fineco Advice, Tinaba, Moneyfarm</td>
                           <td>Servizi localizzati, supporto in italiano</td>
                        </tr>
                        <tr>
                           <td>Asia</td>
                           <td>StashAway, 8 Securities</td>
                           <td>Focus su mercati emergenti</td>
                        </tr>
                     </tbody>
                  </table>
               </div>
               
               <div class="inv-selection-criteria">
                  <h5><i class="ri-search-line"></i> Criteri di Selezione</h5>
                  <ul>
                     <li><strong>Costi:</strong> commissioni di gestione e transazione</li>
                     <li><strong>Asset disponibili:</strong> azioni, ETF, obbligazioni, etc.</li>
                     <li><strong>Interfaccia:</strong> usabilità e strumenti di analisi</li>
                     <li><strong>Sicurezza:</strong> regolamentazione e protezione fondi</li>
                     <li><strong>Supporto:</strong> assistenza clienti e risorse educative</li>
                  </ul>
               </div>
            </div>

            <!-- Tab 4: FAQ -->
            <div id="inv-faq" class="inv-tab-content">
               <h4><i class="ri-question-line"></i> Domande Frequenti</h4>
               
               <div class="inv-faq-accordion">
                  <button class="inv-accordion-btn">Gli investimenti automatici sono sicuri? <i class="ri-arrow-down-s-line"></i></button>
                  <div class="inv-accordion-panel">
                     <p>La sicurezza dipende dalla piattaforma scelta. Le piattaforme regolamentate offrono protezioni, ma tutti gli investimenti comportano rischi. Diversificare e comprendere la strategia sono fondamentali.</p>
                  </div>
                  
                  <button class="inv-accordion-btn">Qual è il capitale minimo richiesto? <i class="ri-arrow-down-s-line"></i></button>
                  <div class="inv-accordion-panel">
                     <p>Molte piattaforme permettono di iniziare con poche centinaia di euro, specialmente per i PAC. Alcune hanno addirittura nessun minimo per iniziare.</p>
                  </div>
                  
                  <button class="inv-accordion-btn">Come vengono tassati questi investimenti? <i class="ri-arrow-down-s-line"></i></button>
                  <div class="inv-accordion-panel">
                     <p>La tassazione varia per paese. In Italia, le plusvalenze sono tassate al 26% per i privati. Alcune piattaforme offrono report fiscali automatici.</p>
                  </div>
                  
                  <button class="inv-accordion-btn">Posso personalizzare la strategia? <i class="ri-arrow-down-s-line"></i></button>
                  <div class="inv-accordion-panel">
                     <p>Dipende dalla piattaforma. Alcune offrono strategie predefinite, altre permettono un alto grado di personalizzazione, inclusi fattori ESG e preferenze di rischio.</p>
                  </div>
               </div>
            </div>
         </section>

        
    </main>
         
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
   <script>
      // Gestione tab migliorata
      document.addEventListener('DOMContentLoaded', function() {
         const tabButtons = document.querySelectorAll('.inv-tab-btn');
         const tabContents = document.querySelectorAll('.inv-tab-content');
         
         tabButtons.forEach(button => {
            button.addEventListener('click', function() {
               const tabId = this.getAttribute('data-tab');
               
               // Rimuovi classe active da tutti i bottoni
               tabButtons.forEach(btn => btn.classList.remove('active'));
               // Nascondi tutti i contenuti
               tabContents.forEach(content => content.style.display = 'none');
               
               // Aggiungi classe active al bottone cliccato
               this.classList.add('active');
               // Mostra il contenuto correlato
               document.getElementById(tabId).style.display = 'block';
            });
         });
         
         // Gestione accordion
         const accButtons = document.querySelectorAll('.inv-accordion-btn');
         accButtons.forEach(button => {
            button.addEventListener('click', function() {
               this.classList.toggle('active');
               const panel = this.nextElementSibling;
               
               if (panel.style.maxHeight) {
                  panel.style.maxHeight = null;
               } else {
                  panel.style.maxHeight = panel.scrollHeight + 'px';
               }
            });
         });
      });
   </script>
</body>
</html>
