
$(document).ready(function() {
    
    $('.newGoal').hide();
    $('.newGoalBtn').click(function() {
        $('.newGoal').show();
    }); 
    $('.moreDetailsBtn').click(function(){
        $(this).siblings('.goal-details').slideToggle();
     }); 
    const coursesData = {  
        datasets: [{ 
            data: [30, 70], 
            backgroundColor: ['red', 'green'], 
        }], 
    }; 

    const config = { 
        type: 'doughnut', 
        data: coursesData, 
    }; 
    const ctx = document.getElementById( 
        'coursesDoughnutChart').getContext('2d'); 
        
    new Chart(ctx, config); 
});