<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../../vendor/autoload.php';
if (!function_exists('sendEmail')) {
function sendEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.example.com'; // Replace with your SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your_email@example.com'; // Your email
        $mail->Password   = 'your_password'; // Your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Sender
        $mail->setFrom('your_email@example.com', 'Your Website');

        // Recipient
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

}

?>
