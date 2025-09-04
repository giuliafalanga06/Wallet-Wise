$(document).ready(function() {
  const radios = document.querySelectorAll('input[type="radio"][name="icona"]');
  let lastChecked = null;
    // Nascondi inizialmente la modale e l'overlay
    $('.newGoal').hide();
    $('.overlay').hide();
    $('.overlay2').hide();
    $('.deleteGoal').hide();

    // Mostra la modale e l'overlay al click del bottone
    $('.newGoalBtn').click(function() {
        $('.newGoal').show();
        $('.overlay').show();
    }); 

    $('.CancelGoal').click(function() {
      $('.newGoal').hide();
      $('.overlay').hide();
      radios.forEach(radio => {
        radio.checked = false;
      });
    }); 

    $('.CancelGoalDetails').click(function() {
      $('.modifyGoal').hide();
      $('.overlay2').hide();
    }); 
 
    $('.deleteGoalBtn').click(function (){
      $('.overlay2').show();
      $('.deleteGoal').show();
    });

    $('.notDeleteGoal').click(function (){
      $('.overlay2').hide();
      $('.deleteGoal').hide();
    });
    
    $('.modifyGoalBtn').click(function() {
      $('.modifyGoal').show();
      $('.overlay2').show();
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
        options: {
          responsive: true,
          maintainAspectRatio: false, // <-- fondamentale};
    } 
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
  
    radios.forEach(radio => {
      radio.addEventListener('click', removeCheckradio);
    });

    function removeCheckradio(){
       if (this === lastChecked) {
          this.checked = false;
          lastChecked = null;
        } else {
          lastChecked = this;
        }
      };
    
});
