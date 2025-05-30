<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../../vendor/autoload.php';
include "../../../pages/includes/connection.php";
session_start();

// Validate
if (!isset($_GET['employee_id'])) {
    die("Invalid Request.");
}

$employee_id = intval($_GET['employee_id']);

// Get employee email and name
$sql = "SELECT u.email, u.first_name, u.last_name 
        FROM employees e 
        JOIN users u ON e.user_id = u.user_id 
        WHERE e.employee_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $email = $row['email'];
    $name = $row['first_name'] . ' ' . $row['last_name'];

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'tylermadisonconsulting@gmail.com';
        $mail->Password   = 'wasw nnca fquy chkb';
       $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
$mail->Port = 465;

        $mail->setFrom('tylermadisonconsulting@gmail.com', 'Tyler Madison Consulting');
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        $mail->Subject = "We value your feedback!";
        $mail->Body = "
            <p>Dear <strong>$name</strong>,</p>
            <p>Thank you for working with Tyler Madison Consulting. We would greatly appreciate your feedback.</p>
            <p>Please take a moment to fill out our short feedback form:</p>
            <p><a href='https://forms.gle/rmVsXVZNWsydFAx26' target='_blank'>Click here to submit feedback</a></p>
            <p>Best regards,<br>Tyler Madison Consulting</p>
        ";

        $mail->send();
        header("Location: ../employees.php?success=FeedbackRequestSent");
    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        error_log("Email send failed: " . $mail->ErrorInfo);
        exit();
    }
} else {
    header("Location: ../employees.php?error=NotFound");
}
exit;
