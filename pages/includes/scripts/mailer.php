<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../../vendor/autoload.php';

function sendApplicationEmail($to, $fullName, $jobTitle)
{
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'tylermadisonconsulting@gmail.com';
        $mail->Password   = 'wasw nnca fquy chkb';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('tylermadisonconsulting@gmail.com', 'Tyler Madison Consulting');
        $mail->addAddress($to, $fullName);

        $mail->isHTML(true);
        $mail->Subject = "Application Received for $jobTitle";
        $mail->Body    = "
            <p>Dear <strong>$fullName</strong>,</p>
            <p>Thank you for applying for the <strong>$jobTitle</strong> position. We will review your application and follow up shortly.</p>
            <p>Best regards,<br>Tyler Madison Consulting</p>
        ";

        $mail->send();
        echo "Email sent!";
        return true;
    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";
        error_log("PHPMailer Error: " . $mail->ErrorInfo);
        return false;
    }
}
