<?php

$htmlContent = file_get_contents('homebanking.html');
include 'pho/savingsGoals.php';
echo $htmlContent;
?>