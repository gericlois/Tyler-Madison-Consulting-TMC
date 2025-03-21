<?php
session_start();
include "../connection.php";
require_once 'mailer.php';

// Check if `id` (job ID) and `status` are set
if (!isset($_GET['id']) || !isset($_GET['status'])) {
    header("Location: ../../promp.php?error=InvalidRequest");
    exit();
}

$job_id = intval($_GET['id']);
$status = intval($_GET['status']);
$employee_id = $_SESSION['user_id'] ?? null; // Ensure user is logged in

if (!$employee_id) {
    header("Location: ../../login.php?error=NotLoggedIn");
    exit();
}

// Prevent duplicate applications
$sql_check = "SELECT * FROM jobapplications WHERE job_id = ? AND employee_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ii", $job_id, $employee_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    header("Location: ../../promp.php?error=AlreadyApplied");
    exit();
}

// Insert new application
$sql_apply = "INSERT INTO jobapplications (job_id, employee_id, status) VALUES (?, ?, ?)";
$stmt_apply = $conn->prepare($sql_apply);
$stmt_apply->bind_param("iii", $job_id, $employee_id, $status);

if ($stmt_apply->execute()) {
    // Fetch employer and job details
    $sql_employer = "SELECT u.email AS employer_email, u.first_name AS employer_name, 
                            j.title AS job_title, e.email AS employee_email, 
                            e.first_name AS employee_name
                     FROM jobpostings j
                     JOIN employers p ON j.employer_id = p.employer_id
                     JOIN users u ON p.employer_id = u.user_id
                     JOIN users e ON e.user_id = ?
                     WHERE j.job_id = ?";
    $stmt_employer = $conn->prepare($sql_employer);
    $stmt_employer->bind_param("ii", $employee_id, $job_id);
    $stmt_employer->execute();
    $result_employer = $stmt_employer->get_result();

    if ($row = $result_employer->fetch_assoc()) {
        $employer_email = $row['employer_email'];
        $employer_name = $row['employer_name'];
        $employee_email = $row['employee_email'];
        $employee_name = $row['employee_name'];
        $job_title = $row['job_title'];
        $admin_email = "admin@example.com";

        // Prepare email content
        $subject = "New Job Application: $job_title";
        $message = "<p>Dear $employer_name,</p>
                    <p>A new application has been submitted for the position: <strong>$job_title</strong>.</p>
                    <p>Applicant: <strong>$employee_name</strong></p>
                    <p>Please review the application and take the necessary action.</p>
                    <p>Best regards,<br>Your Hiring Platform</p>";

        // Send emails using PHPMailer
        sendEmail($employer_email, $subject, $message);

        // Notify Employee
        $message_employee = "<p>Dear $employee_name,</p>
                            <p>Your application for the job <strong>$job_title</strong> has been successfully submitted.</p>
                            <p>We will notify you once the employer reviews your application.</p>
                            <p>Best regards,<br>Your Hiring Platform</p>";
        sendEmail($employee_email, "Application Confirmation: $job_title", $message_employee);

        // Notify Admin
        $message_admin = "<p>Admin,</p>
                         <p>A new job application has been submitted.</p>
                         <p>Job Title: <strong>$job_title</strong></p>
                         <p>Applicant: <strong>$employee_name</strong></p>
                         <p>Employer: <strong>$employer_name</strong></p>
                         <p>Please monitor the process accordingly.</p>";
        sendEmail($admin_email, "New Job Application Alert", $message_admin);
    }

    header("Location: ../../promp.php?success=ApplicationSubmitted");
} else {
    header("Location: ../../promp.php?error=ApplicationFailed");
}
exit();

function sendEmail($to, $subject, $message) {
    global $mailer;
    $mailer->setFrom('noreply@yourwebsite.com', 'Your Hiring Platform');
    $mailer->addAddress($to);
    $mailer->Subject = $subject;
    $mailer->Body = $message;
    $mailer->isHTML(true);
    $mailer->send();
}
