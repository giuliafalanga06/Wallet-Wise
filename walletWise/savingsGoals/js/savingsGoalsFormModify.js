$(document).ready(function() {


  // Gestione submit del form
  $("[name='submitModify']").on('click', function() {
    
    // Pulisce tutti i messaggi di errore precedenti
    $('.error-container').empty();
    
    let errors = {};
    const goal = parseFloat($("[name='goalAmount']").val());
    const description = $('[name="description"]').val().trim();
    const name = $('[name="name"]').val().trim();
    const monthAmount = parseFloat($('[name="monthAmount"]').val());
    const icon = $('[name="icona"]:checked').val();
    const balance = parseFloat($('#cardBalance').val());
    
    // Validazione
    if (isNaN(goal) || goal <= 0) {
      errors.goalAmount = "The 'Goal Amount' field is required and must be positive.";
    } 
    
    if (!description) {
      errors.description = "The 'Description' field is required.";
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
       $('.goalFormModify').submit(); // Usa this.submit() per inviare il form corrente
    }
  });
  
});