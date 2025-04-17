$(document).ready(function() {
    // Nascondi inizialmente la modale e l'overlay
    $('.newGoal').hide();
    $('.overlay').hide();

    // Mostra la modale e l'overlay al click del bottone
    $('.newGoalBtn').click(function() {
        $('.newGoal').show();
        $('.overlay').show();
    }); 

 
    
    $('.CancelGoal').click(function() {
        $('.newGoal').hide();
        $('.overlay').hide();
    }); 

  
    const goal = $('.goalAmount')[0] ||100;
    goalTxt = goal.textContent;
    const currentAmount = $('.currentAmount')[0]|| 50;
    currentAmountTxt = currentAmount.textContent;
    // Calcola i dati per il grafico
    const coursesData = {  
        datasets: [{ 
            data: [100 - (currentAmountTxt / goalTxt * 100), currentAmountTxt / goalTxt * 100], 
            backgroundColor: ['hsla(193, 86.10%, 33.90%, 0.18)','hsl(193, 86%, 34%)'], 
        }], 
    }; 
    // Configurazione del grafico
    const config = { 
        type: 'doughnut', 
        data: coursesData, 
    }; 

    // Seleziona il contesto del grafico
    const ctx = $('.coursesDoughnutChart')[0];

    // Crea il grafico
    if (ctx) {
        new Chart(ctx, config);
    }

    // Gestione del nuovo goal
    $('.newGoalBtn').click( function(){
        $('.newGoal').style.display = 'block';
       $('.overlay').style.display = 'block';
    });

    // Calcolare la data di fine
    // const form = document.querySelector('form');
    // if (form) {
    //     form.addEventListener('submit', function(event) {
    //         if (!document.querySelector('#monthAmount').value && document.querySelector('#startDate').value && document.querySelector('#goalAmount').value) {
    //             const startDate = new Date(document.querySelector('#startDate').value);
    //             const endDateMonth = parseInt(document.querySelector('#goalAmount').value) / parseInt(document.querySelector('#monthAmount').value);
    //             startDate.setMonth(startDate.getMonth() + endDateMonth);
    //             document.querySelector('#endDate').value = startDate.toISOString().split('T')[0];
    //         }
    //     });
    // }
});
