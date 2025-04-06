<?php
session_start();
include "../../../pages/includes/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    $sql = "UPDATE jobpostings 
            SET title=?, description=?, location=?, salary=?, end_at=?, job_type=?, schedule=?, skills=?
            WHERE job_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssissssi", $title, $description, $location, $salary, $formatted_deadline, $job_type, $schedule, $skills, $job_id);

    if ($stmt->execute()) {
        $admin_id = $_SESSION['admin_id']; // Assuming admin ID is stored in session
        $action = "Job Posting Updated";
        $activity_description = "Updated job posting ID {$job_id}, title '{$title}', location '{$location}', and salary '{$salary}'.";

        $activity_stmt = $conn->prepare("INSERT INTO activity (user_id, action, description) VALUES (?, ?, ?)");
        $activity_stmt->bind_param("iss", $admin_id, $action, $activity_description);
        $activity_stmt->execute();
        $activity_stmt->close();

        header("Location: ../jobs.php?success=JobUpdated");
    } else {
        header("Location: ../jobs.php?error=UpdateFailed");
    }
    exit();
}
?>
