$(document).ready(function () {
  $(".newTransfer").hide();
  $(".overlay").hide();

  // Mostra la modale e l'overlay al click del bottone
  $(".newTransferBtn").click(function () {
    $(".newTransfer").show();
    $(".overlay").show();
  });

  $(".CancelTransfer").click(function () {
    $(".newTransfer").hide();
    $(".overlay").hide();
  });

  
});
