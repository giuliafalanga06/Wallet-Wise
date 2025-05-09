// Aspetta che il documento sia completamente caricato
document.addEventListener('DOMContentLoaded', function() {
    // Elementi del DOM per il simulatore
    const calculateBtn = document.getElementById('calculate-btn');
    const resultsDiv = document.getElementById('results');
    const finalAmountSpan = document.getElementById('final-amount');
    const totalInvestedSpan = document.getElementById('total-invested');
    const totalGainSpan = document.getElementById('total-gain');
    const totalReturnSpan = document.getElementById('total-return');
    const yearlyDataTbody = document.getElementById('yearly-data');
    
    // Elementi per il grafico TradingView
    const showChartBtn = document.getElementById('showChartBtn');
    const chartOverlay = document.getElementById('chartOverlay');
    const closeChartBtn = document.getElementById('closeChartBtn');
    const marketSelector = document.getElementById('marketSelector');
    
    // Riferimento al canvas del grafico
    const chartCanvas = document.getElementById('investment-chart');
    
    // Variabili per memorizzare l'istanza del grafico
    let investmentChart = null;
    
    // Configurazione iniziale
    setupAccordion();
    setupMarketSelector();
    
    // Event listeners
    calculateBtn.addEventListener('click', performCalculation);
    showChartBtn.addEventListener('click', showTradingViewChart);
    closeChartBtn.addEventListener('click', hideTradingViewChart);
    chartOverlay.addEventListener('click', function(e) {
        if (e.target === chartOverlay) {
            hideTradingViewChart();
        }
    });
    
    /**
     * Configura il comportamento degli elementi accordion
     */
    function setupAccordion() {
        const accordionHeaders = document.querySelectorAll('.inv-accordion-header');
        
        accordionHeaders.forEach(header => {
            header.addEventListener('click', function() {
                // Toggle active class
                const accordionItem = this.parentElement;
                accordionItem.classList.toggle('active');
                
                // Chiudi tutti gli altri elementi
                const allItems = document.querySelectorAll('.inv-accordion-item');
                allItems.forEach(item => {
                    if (item !== accordionItem) {
                        item.classList.remove('active');
                    }
                });
            });
        });
    }
    
    /**
     * Configura il selettore del mercato per TradingView
     */
    function setupMarketSelector() {
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

        markets.forEach((m, i) => {
            const opt = document.createElement("option");
            opt.value = m.symbol;
            opt.text = m.name;
            marketSelector.appendChild(opt);
        });
    }
    
    /**
     * Mostra il grafico TradingView in overlay
     */
    function showTradingViewChart() {
        chartOverlay.classList.add('show');
        loadTradingViewChart();
    }
    
    /**
     * Nasconde il grafico TradingView
     */
    function hideTradingViewChart() {
        chartOverlay.classList.remove('show');
    }
    
    /**
     * Carica il grafico TradingView
     */
    function loadTradingViewChart() {
        const selectedSymbol = marketSelector.value;
        document.getElementById("tradingview_chart").innerHTML = "";

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
    
    /**
     * Esegue il calcolo dell'investimento automatico
     */
    function performCalculation() {
        // Ottieni i valori dal form
        const initialAmount = parseFloat(document.getElementById('initial-amount').value) || 0;
        const monthlyContribution = parseFloat(document.getElementById('monthly-contribution').value) || 0;
        const years = parseInt(document.getElementById('years').value) || 1;
        const expectedReturnPercent = parseFloat(document.getElementById('expected-return').value) || 0;
        const investmentFrequency = document.getElementById('investment-frequency').value;
        
        // Validazione input
        if (years < 1 || years > 50) {
            showError('Il periodo di investimento deve essere compreso tra 1 e 50 anni.');
            return;
        }
        
        if (expectedReturnPercent < 0 || expectedReturnPercent > 30) {
            showError('Il rendimento atteso deve essere compreso tra 0% e 30%.');
            return;
        }
        
        // Converti il rendimento annuo in decimale
        const annualReturn = expectedReturnPercent / 100;
        
        // Calcola la frequenza di investimento
        let contributionsPerYear;
        switch (investmentFrequency) {
            case 'monthly':
                contributionsPerYear = 12;
                break;
            case 'quarterly':
                contributionsPerYear = 4;
                break;
            case 'biannual':
                contributionsPerYear = 2;
                break;
            case 'annual':
                contributionsPerYear = 1;
                break;
            default:
                contributionsPerYear = 12;
        }
        
        // Calcola il contributo per periodo
        const contributionPerPeriod = monthlyContribution * (12 / contributionsPerYear);
        
        // Calcola il rendimento per periodo
        const returnPerPeriod = Math.pow(1 + annualReturn, 1 / contributionsPerYear) - 1;
        
        // Esegui la simulazione
        const results = simulateInvestment(
            initialAmount,
            contributionPerPeriod,
            contributionsPerYear,
            years,
            returnPerPeriod
        );
        
        // Mostra i risultati
        displayResults(results);
    }
    
    /**
     * Simula un investimento automatico nel tempo
     */
    function simulateInvestment(initialAmount, contributionPerPeriod, periodsPerYear, years, returnPerPeriod) {
        let balance = initialAmount;
        let totalContributions = initialAmount;
        const yearlyData = [];
        const monthlyData = [];
        
        // Calcola i valori mensili per il grafico
        for (let month = 0; month <= years * 12; month++) {
            if (month === 0) {
                monthlyData.push({
                    month: month,
                    balance: balance,
                    contributions: totalContributions
                });
                continue;
            }
            
            // Verifica se è il momento di aggiungere un contributo
            if (month % (12 / periodsPerYear) === 0) {
                balance += contributionPerPeriod;
                totalContributions += contributionPerPeriod;
            }
            
            // Applica il rendimento mensile
            balance *= (1 + (returnPerPeriod / (12 / periodsPerYear)));
            
            // Salva i dati mensili
            monthlyData.push({
                month: month,
                balance: balance,
                contributions: totalContributions
            });
            
            // Salva i dati annuali
            if (month % 12 === 0 && month > 0) {
                const year = month / 12;
                yearlyData.push({
                    year: year,
                    balance: balance,
                    contributions: totalContributions,
                    gain: balance - totalContributions,
                    returnPercentage: ((balance - totalContributions) / totalContributions) * 100
                });
            }
        }
        
        // Aggiungi l'ultimo anno se non è un multiplo esatto
        const lastMonth = years * 12;
        if (lastMonth % 12 !== 0) {
            yearlyData.push({
                year: years,
                balance: monthlyData[lastMonth].balance,
                contributions: monthlyData[lastMonth].contributions,
                gain: monthlyData[lastMonth].balance - monthlyData[lastMonth].contributions,
                returnPercentage: ((monthlyData[lastMonth].balance - monthlyData[lastMonth].contributions) / 
                                  monthlyData[lastMonth].contributions) * 100
            });
        }
        
        return {
            finalBalance: balance,
            totalContributions: totalContributions,
            totalGain: balance - totalContributions,
            totalReturnPercentage: ((balance - totalContributions) / totalContributions) * 100,
            yearlyData: yearlyData,
            monthlyData: monthlyData
        };
    }
    
    /**
     * Mostra i risultati della simulazione
     */
    function displayResults(results) {
        // Mostra la sezione dei risultati
        resultsDiv.classList.remove('hidden');
        resultsDiv.classList.add('show');
        
        // Aggiorna i valori riassuntivi
        finalAmountSpan.textContent = formatCurrency(results.finalBalance);
        totalInvestedSpan.textContent = formatCurrency(results.totalContributions);
        totalGainSpan.textContent = formatCurrency(results.totalGain);
        totalReturnSpan.textContent = formatPercentage(results.totalReturnPercentage);
        
        // Popola la tabella dei dati annuali
        populateYearlyTable(results.yearlyData);
        
        // Crea o aggiorna il grafico
        createOrUpdateChart(results.monthlyData);
        
        // Scorri alla sezione dei risultati
        resultsDiv.scrollIntoView({ behavior: 'smooth' });
    }
    
    /**
     * Popola la tabella con i dati annuali
     */
    function populateYearlyTable(yearlyData) {
        // Pulisci la tabella
        yearlyDataTbody.innerHTML = '';
        
        // Aggiungi una riga per ogni anno
        yearlyData.forEach(data => {
            const row = document.createElement('tr');
            
            // Crea le celle
            const yearCell = document.createElement('td');
            yearCell.textContent = data.year;
            
            const contributionsCell = document.createElement('td');
            contributionsCell.textContent = formatCurrency(data.contributions);
            
            const balanceCell = document.createElement('td');
            balanceCell.textContent = formatCurrency(data.balance);
            
            const gainCell = document.createElement('td');
            gainCell.textContent = formatCurrency(data.gain);
            gainCell.classList.add(data.gain >= 0 ? 'positive-gain' : 'negative-gain');
            
            // Aggiungi le celle alla riga
            row.appendChild(yearCell);
            row.appendChild(contributionsCell);
            row.appendChild(balanceCell);
            row.appendChild(gainCell);
            
            // Aggiungi la riga alla tabella
            yearlyDataTbody.appendChild(row);
        });
    }
    
    /**
     * Crea o aggiorna il grafico dell'investimento
     */
    function createOrUpdateChart(monthlyData) {
        // Prepara i dati per il grafico
        const labels = monthlyData.map(data => {
            const years = Math.floor(data.month / 12);
            const months = data.month % 12;
            return years > 0 ? `${years}a ${months}m` : `${months}m`;
        });
        
        const balanceData = monthlyData.map(data => data.balance);
        const contributionsData = monthlyData.map(data => data.contributions);
        
        // Distruggi il grafico esistente se presente
        if (investmentChart) {
            investmentChart.destroy();
        }
        
        // Crea il nuovo grafico
        investmentChart = new Chart(chartCanvas, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Valore dell\'investimento',
                        data: balanceData,
                        borderColor: '#4CAF50',
                        backgroundColor: 'rgba(76, 175, 80, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Capitale investito',
                        data: contributionsData,
                        borderColor: '#2196F3',
                        backgroundColor: 'rgba(33, 150, 243, 0.1)',
                        borderDash: [5, 5],
                        tension: 0
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Tempo (anni e mesi)'
                        },
                        ticks: {
                            callback: function(value, index) {
                                // Mostra solo alcuni valori sull'asse X per evitare sovraffollamento
                                return index % Math.ceil(labels.length / 10) === 0 ? labels[index] : '';
                            }
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Valore (€)'
                        },
                        ticks: {
                            callback: function(value) {
                                return formatCurrency(value, false);
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + formatCurrency(context.raw);
                            }
                        }
                    },
                    legend: {
                        position: 'top'
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    }
    
    /**
     * Formatta un numero come valuta (Euro)
     */
    function formatCurrency(value, symbol = true) {
        return (symbol ? '€' : '') + new Intl.NumberFormat('it-IT', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(Math.round(value * 100) / 100);
    }
    
    /**
     * Formatta un numero come percentuale
     */
    function formatPercentage(value) {
        return new Intl.NumberFormat('it-IT', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
            style: 'percent',
            multiplier: 1
        }).format(value / 100);
    }
    
    /**
     * Mostra un messaggio di errore
     */
    function showError(message) {
        alert(message);
    }
    
    // Aggiungi stili CSS dinamici per i valori positivi/negativi
    const style = document.createElement('style');
    style.innerHTML = `
        .positive-gain { color: #4CAF50; }
        .negative-gain { color: #F44336; }
    `;
    document.head.appendChild(style);
});