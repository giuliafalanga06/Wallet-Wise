<?php 
    session_start();
    $_SESSION = array();
    session_destroy();
    header("Location: https://walletwise.altervista.org/walletWise/");
    exit();
?>