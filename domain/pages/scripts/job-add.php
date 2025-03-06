<?php
session_start();
include "../../../pages/includes/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $salary = $_POST['salary'];
    $posted_by = $_POST['posted_by'];
    $deadline = $_POST['deadline']; 
    $job_type = $_POST['job_type'];
    $schedule = $_POST['schedule'];
    $skills = $_POST['skills'];
    $status = "Active"; 

    $formatted_deadline = date('Y-m-d H:i:s', strtotime($deadline));

    $stmt = $conn->prepare("INSERT INTO jobpostings 
        (title, job_type, schedule, skills, end_at, description, location, salary, posted_by, status, posted_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

    $stmt->bind_param("sssssssdis", $title, $job_type, $schedule, $skills, $formatted_deadline, $description, $location, $salary, $posted_by, $status);

    if ($stmt->execute()) {
        header("Location: ../jobs.php?success=JobAdded");
        exit();
    } else {
        header("Location: ../jobs.php?error=JobNotAdded");
        exit();
    }

    $stmt->close();
}
?>
