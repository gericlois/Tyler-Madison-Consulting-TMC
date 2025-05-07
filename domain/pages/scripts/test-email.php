<?php

require __DIR__ . '/../../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.secureserver.net';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'administrator@tylermadisonconsulting.com';
    $mail->Password   = 'YOUR_PASSWORD_HERE';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('administrator@tylermadisonconsulting.com', 'Tyler Madison');
    $mail->addAddress('youremail@example.com'); // Change this

    $mail->isHTML(true);
    $mail->Subject = 'Test Email';
    $mail->Body    = 'This is a test.';

    $mail->send();
    echo "Email sent successfully.";
} catch (Exception $e) {
    echo "Mailer Error: " . $mail->ErrorInfo;
    error_log("Email send failed: " . $mail->ErrorInfo);
    exit();
}

