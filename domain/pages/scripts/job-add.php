<?php
// Start the session at the beginning of the file
session_start();

include "../../../pages/includes/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the admin ID is set in the session
    if (isset($_SESSION['admin_id'])) {
        $admin_id = $_SESSION['admin_id']; // Assuming the admin's ID is stored in the session
    } else {
        // Handle the case when admin_id is not set (you can redirect, show an error, or handle it accordingly)
        header("Location: ../login.php?error=AdminNotLoggedIn");
        exit();
    }

    $title = $_POST['title'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $salary = $_POST['salary'];
    $posted_by = $_POST['posted_by'];
    $deadline = $_POST['deadline']; 
    $job_type = $_POST['job_type'];
    $schedule = $_POST['schedule'];
    $skills = $_POST['skills'];
    $status = "1"; 

    $formatted_deadline = date('Y-m-d H:i:s', strtotime($deadline));

    $stmt = $conn->prepare("INSERT INTO jobpostings 
        (title, job_type, schedule, skills, end_at, description, location, salary, posted_by, status, posted_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

    $stmt->bind_param("sssssssdis", $title, $job_type, $schedule, $skills, $formatted_deadline, $description, $location, $salary, $posted_by, $status);

    if ($stmt->execute()) {
        $action = "Job Posting Added";
        $activity_description = "Added a new job posting titled '$title' with location '$location' and salary '$salary'.";

        $activity_stmt = $conn->prepare("INSERT INTO activity (user_id, action, description) VALUES (?, ?, ?)");
        $activity_stmt->bind_param("iss", $admin_id, $action, $activity_description);

        $activity_stmt->execute();
        $activity_stmt->close();

        header("Location: ../jobs.php?success=JobAdded");
        exit();
    } else {
        header("Location: ../jobs.php?error=JobNotAdded");
        exit();
    }

    $stmt->close();
}
?>
