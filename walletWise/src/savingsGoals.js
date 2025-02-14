
$(document).ready(function() {
    
    $('.newGoal').hide();
    $('.newGoalBtn').click(function() {
        $('.newGoal').show();
    });  
    const coursesData = { 
        labels: ['Python', 'JavaScript', 
            'Java', 'C++', 'Data Structures'], 
        datasets: [{ 
            data: [30, 20, 15, 10, 25], 
            backgroundColor: ['#FF6384', '#36A2EB', 
                '#FFCE56', '#4CAF50', '#9C27B0'], 
        }], 
    }; 

    const config = { 
        type: 'doughnut', 
        data: coursesData, 
        options: { 
            plugins: { 
                title: { 
                    display: true, 
                    text: 'GeeksforGeeks Courses Distribution', 
                }, 
            }, 
        }, 
    }; 
    const ctx = document.getElementById( 
        'coursesDoughnutChart').getContext('2d'); 
        
    new Chart(ctx, config); 
});