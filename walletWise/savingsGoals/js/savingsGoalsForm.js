$(document).ready(function() {

    const today = new Date();
  const todayFormatted = today.toISOString().split('T')[0];
  $('#startDate').attr('min', todayFormatted);
  
  // Gestione submit del form
  $('[name="submit"]').on('click', function() {
    
    // Pulisce tutti i messaggi di errore precedenti
    $('.error-container').empty();
    
    let errors = {};
    const goal = parseFloat($('[name="goalAmount"]').val());
    const description = $('[name="description"]').val().trim();
    const startDate = $('[name="startDate"]').val();
    const name = $('[name="name"]').val().trim();
    const monthAmount = parseFloat($('[name="monthAmount"]').val());
    const icon = $('[name="icona"]:checked').val();
    const balance = parseFloat($('#cardBalance').val());
    
    // Validazione
    if (isNaN(goal) || goal <= 0) {
      errors.goalAmount = "The 'Goal Amount' field is required and must be positive.";
    } else if (goal > balance / 3) {
      errors.goalAmount = "'Goal Amount' must be less than one third of the card balance.";
    }
    
    if (!description) {
      errors.description = "The 'Description' field is required.";
    }
    
    if (!startDate) {
      errors.startDate = "The 'Start Date' field is required.";
    } else {
      const today = new Date();
      today.setHours(0, 0, 0, 0);
      const selected = new Date(startDate);
      selected.setHours(0, 0, 0, 0);
      if (selected < today) {
        errors.startDate = "The start date must be today or later.";
      }
    }
    
    if (!name) {
      errors.name = "The 'Name' field is required.";
    }
    
    if (isNaN(monthAmount) || monthAmount <= 0) {
      errors.monthAmount = "The 'Month Amount' field is required and must be positive.";
    } else {
      if (monthAmount >= goal) {
        errors.monthAmount = "'Month Amount' must be less than 'Goal Amount'.";
      } else if (monthAmount > balance) {
        errors.monthAmount = "'Month Amount' must be less than the card balance.";
      }
    }
    
    if (!icon) {
      errors.icona = "You must select an icon.";
    }
    
    // Mostra i messaggi di errore nei container appropriati
    for (let field in errors) {
      $(`.error-container[data-field="${field}"]`).html(
        `<div class="error-message">${errors[field]}</div>`
      );
    }
    
    // Se non ci sono errori, invia il form manualmente
    if (Object.keys(errors).length === 0) {
      console.log("Form valido, invio in corso...");
       $('.goalForm').submit(); // Usa this.submit() per inviare il form corrente
    }
  });
  
});