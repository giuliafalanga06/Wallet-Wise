
$(document).ready(function() {
    
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
    const ctx = document.getElementsByClassName( 
        'coursesDoughnutChart').getContext('2d'); 
        
    new Chart(ctx, config); 
    document.querySelector('.newGoalBtn').addEventListener('click', () => {
        document.querySelector('.newGoal').style.display = 'block';
        document.querySelector('.overlay').style.display = 'block';
    });
    
    

   
});
