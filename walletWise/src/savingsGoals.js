
$(document).ready(function() {
    
    $('.newGoal').hide();
    $('.newGoalBtn').click(function() {
        $('.newGoal').show();
    });  
    

    document.addEventListener("DOMContentLoaded", function () {
        // 1️⃣ Selezioniamo il <canvas>
        const ctx = document.getElementById("goalChart");
    
        if (!ctx) {
            console.error("⚠️ ERRORE: Canvas non trovato!");
            return;
        }
    
        // 2️⃣ Dati del goal (Esempio: Risparmiati 500 su 1000)
        const savedAmount = 500; // Cambia con il valore effettivo
        const goalAmount = 1000;
    
        // Calcoliamo il progresso
        const remaining = goalAmount - savedAmount;
    
        // 3️⃣ Creiamo il grafico a torta
        new Chart(ctx, {
            type: "doughnut",
            data: {
                labels: ["Risparmiati", "Mancanti"],
                datasets: [{
                    data: [savedAmount, remaining], 
                    backgroundColor: ["#4CAF50", "#E0E0E0"], // Verde per i soldi risparmiati
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: "60%", // Effetto ciambella
                plugins: {
                    legend: {
                        position: "bottom"
                    }
                }
            }
        });
    
        console.log("Grafico creato con successo!");
    });
    

});