<?php
session_start();
include "../../../pages/includes/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if admin_id is set
    if (isset($_SESSION['admin_id'])) {
        $admin_id = $_SESSION['admin_id']; 
    } else {
        header("Location: ../login.php?error=AdminNotLoggedIn");
        exit();
    }

    // Get and sanitize POST data
$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$responsibilities = $_POST['responsibilities'] ?? '';
$skills = $_POST['skills'] ?? '';
$preferred_skills = $_POST['preferred_skills'] ?? '';
$education = $_POST['education'] ?? '';
$location = $_POST['location'] ?? '';
$duration = $_POST['duration'] ?? '';
$hours = $_POST['hours'] ?? '';
$start_date = $_POST['start_date'] ?? '';
$salary = $_POST['salary'] ?? '';
$job_type = $_POST['job_type'] ?? '';
$schedule = $_POST['schedule'] ?? '';
$posted_by = $_POST['posted_by'] ?? '';
$employer_id = $_POST['employer_id'] ?? '';
$status = "1";


    // Prepare insert statement
    $stmt = $conn->prepare("INSERT INTO jobpostings 
        (title, job_type, schedule, description, responsibilities, skills, preferred_skills, education, location, duration, hours, start_date, salary, posted_by, employer_id, status, posted_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

    // Bind parameters
    $stmt->bind_param(
        "sssssssssisdsiii",
        $title,
        $job_type,
        $schedule,
        $description,
        $responsibilities,
        $skills,
        $preferred_skills,
        $education,
        $location,
        $duration,
        $hours,
        $start_date,
        $salary,
        $posted_by,
        $employer_id,
        $status
    );

    if ($stmt->execute()) {
        // Log activity
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
