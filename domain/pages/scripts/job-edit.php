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
    $salary = trim($_POST['salary']); // Now text, not float
    $job_type = trim($_POST['job_type']);
    $schedule = trim($_POST['schedule']);
    $skills = trim($_POST['skills']);
    $start_date = trim($_POST['start_date']);
    $duration = trim($_POST['duration']);
    $hours = trim($_POST['hours']);
    $responsibilities = trim($_POST['responsibilities']);
    $preferred_skills = trim($_POST['preferred_skills']);
    $education = trim($_POST['education']);

    // Updated SQL to include new fields, removed 'deadline'
    $sql = "UPDATE jobpostings 
            SET title=?, description=?, location=?, salary=?, job_type=?, schedule=?, skills=?, start_date=?, duration=?, hours=?, responsibilities=?, preferred_skills=?, education=?
            WHERE job_id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssssssssssi",
        $title,
        $description,
        $location,
        $salary,
        $job_type,
        $schedule,
        $skills,
        $start_date,
        $duration,
        $hours,
        $responsibilities,
        $preferred_skills,
        $education,
        $job_id
    );

    if ($stmt->execute()) {
        $admin_id = $_SESSION['admin_id'] ?? null;
        if ($admin_id) {
            $action = "Job Posting Updated";
            $activity_description = "Updated job posting ID {$job_id} with title '{$title}'.";

            $activity_stmt = $conn->prepare("INSERT INTO activity (user_id, action, description) VALUES (?, ?, ?)");
            $activity_stmt->bind_param("iss", $admin_id, $action, $activity_description);
            $activity_stmt->execute();
            $activity_stmt->close();
        }

        header("Location: ../jobs.php?success=JobUpdated");
        exit();
    } else {
        header("Location: ../jobs.php?error=UpdateFailed");
        exit();
    }
}
?>
