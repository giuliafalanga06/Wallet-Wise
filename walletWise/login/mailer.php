
<?php

require_once __DIR__ . "/src/libs/PHPMailer/PHPMailer.php";
require_once __DIR__ . "/../libs/PHPMailer/SMTP.php";
require_once __DIR__ . "/../libs/PHPMailer/Exception.php";

// Usa la libreria PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

// $mail->SMTPDebug = SMTP::DEBUG_SERVER;

$mail->isSMTP();
$mail->SMTPAuth = true;

$mail->Host = "smtp.example.com";
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;
$mail->Username = "your-user@example.com";
$mail->Password = "your-password";

$mail->isHtml(true);

return $mail;
?>