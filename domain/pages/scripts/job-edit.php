<?php
session_start();
include "../../../pages/includes/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input
    if (!isset($_POST['job_id']) || empty($_POST['job_id'])) {
        header("Location: ../jobs.php?error=NoJobID");
        exit();
    }

    $job_id = intval($_POST['job_id']);
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $location = trim($_POST['location']);
    $salary = floatval($_POST['salary']);
    $deadline = $_POST['deadline'];
    $job_type = trim($_POST['job_type']);
    $schedule = trim($_POST['schedule']);
    $skills = trim($_POST['skills']);

    $formatted_deadline = date('Y-m-d H:i:s', strtotime($deadline));

    // Update job details in database
    $sql = "UPDATE jobpostings 
            SET title=?, description=?, location=?, salary=?, end_at=?, job_type=?, schedule=?, skills=?
            WHERE job_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssissssi", $title, $description, $location, $salary, $formatted_deadline, $job_type, $schedule, $skills, $job_id);

    if ($stmt->execute()) {
        header("Location: ../jobs.php?success=JobUpdated");
    } else {
        header("Location: ../jobs.php?error=UpdateFailed");
    }
    exit();
}
?>
