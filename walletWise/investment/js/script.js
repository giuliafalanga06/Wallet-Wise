// Namespace per le funzionalità SmartSave
const SmartSave = (function() {
    // Variabili di stato
    let savingsChart = null;
    
    // Inizializzazione
    function init() {
        setupSliders();
        initChart();
        setupEventListeners();
        calculateSavings();
    }
    
    // Configurazione degli slider
    function setupSliders() {
        const initialAmountSlider = document.getElementById('ss-initial-amount');
        const monthlyAmountSlider = document.getElementById('ss-monthly-amount');
        const durationSlider = document.getElementById('ss-duration');
        
        const initialAmountValue = document.getElementById('ss-initial-amount-value');
        const monthlyAmountValue = document.getElementById('ss-monthly-amount-value');
        const durationValue = document.getElementById('ss-duration-value');
        
        initialAmountSlider.addEventListener('input', function() {
            initialAmountValue.textContent = this.value;
        });
        
        monthlyAmountSlider.addEventListener('input', function() {
            monthlyAmountValue.textContent = this.value;
        });
        
        durationSlider.addEventListener('input', function() {
            durationValue.textContent = this.value;
        });
    }
    
    // Inizializzazione del grafico
    function initChart() {
        const ctx = document.getElementById('ss-savings-chart').getContext('2d');
        savingsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [
                    {
                        label: 'Totale versato',
                        data: [],
                        borderColor: 'hsl(228, 8%, 56%)',
                        backgroundColor: 'hsla(228, 8%, 56%, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Saldo con interessi',
                        data: [],
                        borderColor: 'hsl(193, 100%, 55%)',
                        backgroundColor: 'hsla(193, 100%, 55%, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': €' + context.raw.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '€' + value;
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Configurazione degli event listeners
    function setupEventListeners() {
        document.getElementById('ss-calculate-btn').addEventListener('click', calculateSavings);
    }
    
    // Calcolo dei risparmi
    function calculateSavings() {
        const initialAmount = parseFloat(document.getElementById('ss-initial-amount').value);
        const monthlyAmount = parseFloat(document.getElementById('ss-monthly-amount').value);
        const duration = parseInt(document.getElementById('ss-duration').value);
        
        // Determina il tasso di interesse
        let interestRate;
        const riskProfile = document.getElementById('ss-risk-profile').value;
        
        switch(riskProfile) {
            case 'conservative':
                interestRate = 0.02; // 2%
                break;
            case 'balanced':
                interestRate = 0.04; // 4%
                break;
            case 'aggressive':
                interestRate = 0.06; // 6%
                break;
            default:
                interestRate = 0.04;
        }
        
        // Calcolo dei dati
        const months = duration * 12;
        const labels = [];
        const depositedData = [];
        const balanceData = [];
        
        let totalDeposited = initialAmount;
        let balance = initialAmount;
        
        // Mese 0 (deposito iniziale)
        labels.push('Oggi');
        depositedData.push(initialAmount);
        balanceData.push(initialAmount);
        
        for (let i = 1; i <= months; i++) {
            totalDeposited += monthlyAmount;
            balance += monthlyAmount;
            
            const monthlyInterestRate = Math.pow(1 + interestRate, 1/12) - 1;
            balance *= (1 + monthlyInterestRate);
            
            if (i % 12 === 0 || i === months) {
                const year = Math.floor(i / 12);
                labels.push(year > 0 ? `Anno ${year}` : `${i} mesi`);
                depositedData.push(totalDeposited);
                balanceData.push(balance);
            }
        }
        
        // Aggiornamento del grafico
        savingsChart.data.labels = labels;
        savingsChart.data.datasets[0].data = depositedData;
        savingsChart.data.datasets[1].data = balanceData;
        savingsChart.update();
        
        // Aggiornamento del riepilogo
        const interestEarned = balance - totalDeposited;
        
        document.getElementById('ss-total-deposited').textContent = '€' + totalDeposited.toFixed(2);
        document.getElementById('ss-interest-earned').textContent = '€' + interestEarned.toFixed(2);
        document.getElementById('ss-final-balance').textContent = '€' + balance.toFixed(2);
    }
    
    // Toggle per le info card
    function toggleInfo(id) {
        const infoContent = document.getElementById('ss-' + id + '-info');
        infoContent.classList.toggle('ss-active');
        
        const icon = infoContent.previousElementSibling.querySelector('.ss-toggle-icon');
        if (infoContent.classList.contains('ss-active')) {
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
        } else {
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
        }
    }
    
    // Esposizione delle funzioni pubbliche
    return {
        init: init,
        toggleInfo: toggleInfo
    };
})();

// Inizializzazione quando il DOM è pronto
document.addEventListener('DOMContentLoaded', function() {
    SmartSave.init();
});

// Funzione globale per il toggle (richiamata dall'HTML)
function ssToggleInfo(id) {
    SmartSave.toggleInfo(id);
}