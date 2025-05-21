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

    // Sanitize and get POST data
    $title = $_POST['title'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $salary = $_POST['salary'];
    $posted_by = $_POST['posted_by']; // This should be the admin_id or employer_id who posted
    $deadline = $_POST['deadline'];
    $job_type = $_POST['job_type'];
    $schedule = $_POST['schedule'];
    $skills = $_POST['skills'];
    $status = "1";

    // New: get employer_id from POST
    $employer_id = $_POST['employer_id'];

    // Format deadline to MySQL datetime
    $formatted_deadline = date('Y-m-d H:i:s', strtotime($deadline));

    // Prepare statement with employer_id
    $stmt = $conn->prepare("INSERT INTO jobpostings 
        (title, job_type, schedule, skills, end_at, description, location, salary, posted_by, employer_id, status, posted_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

    // Bind parameters (string, string, string, string, string, string, string, double, int, int, string)
$stmt->bind_param(
    "sssssssdiis",
    $title,
    $job_type,
    $schedule,
    $skills,
    $formatted_deadline,
    $description,
    $location,
    $salary,
    $posted_by,
    $employer_id,
    $status
);


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
