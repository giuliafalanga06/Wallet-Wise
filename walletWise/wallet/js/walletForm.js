$(document).ready(function() {
    $(".saveTransfer").on('click', function(e) {
        e.preventDefault();
        
        // Pulisci tutti gli errori precedenti
        $('.error-container').html('');
        
        // Recupera i valori
        const formData = {
            name: $('input[name="name"]').val().trim(),
            surname: $('input[name="surname"]').val().trim(),
            iban: $('input[name="iban"]').val().trim(),
            amount: parseFloat($('input[name="amount"]').val()),
            reason: $('input[name="reason"]').val().trim(),
            balance: parseFloat($('#cardBalance').val())
        };

        // Validazione
        let errors = {};
        
        if (!formData.name) errors.name = "Name is required";
        else if (!/^[a-zA-Z\s]+$/.test(formData.name)) errors.name = "Only letters and spaces allowed";

        if (!formData.surname) errors.surname = "Surname is required";
        else if (!/^[a-zA-Z\s]+$/.test(formData.surname)) errors.surname = "Only letters and spaces allowed";

        if (!formData.iban) errors.iban = "IBAN is required";
        else if (!/^[a-zA-Z0-9]+$/.test(formData.iban)) errors.iban = "Only alphanumeric characters";
        else if (formData.iban.length < 15) errors.iban = "Minimum 15 characters required";

        if (isNaN(formData.amount)) errors.amount = "Must be a number";
        else if (formData.amount <= 0) errors.amount = "Amount must be positive";
        else if (formData.amount > formData.balance) errors.amount = `Insufficient balance (max ${formData.balance})`;

        if (!formData.reason) errors.reason = "Reason is required";

        // Mostra errori
        Object.keys(errors).forEach(field => {
            $(`[data-field="${field}"]`).html(`<div class="error-message">${errors[field]}</div>`);
        });

        // Se non ci sono errori, invia il form
        if (Object.keys(errors).length === 0) {
            $('.Transfer').submit();
        }
    });


    $('.cancelTransfer').on('click', function() {
         $('.error-container').html('');
    });
});