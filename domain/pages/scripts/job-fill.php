<?php
session_start();
include "../../../pages/includes/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $job_id = $_POST['job_id'];
    $employee_id = $_POST['employee_id'];

    // 1. Update job status to "Filled" (status = 3)
    $updateJobSql = "UPDATE jobpostings SET status = 3 WHERE job_id = ?";
    $stmt1 = $conn->prepare($updateJobSql);
    $stmt1->bind_param("i", $job_id);

    // 2. Update the selected employee's application: progress = 6, status = 2 (Accepted)
    $updateApplicationSql = "UPDATE jobapplications SET progress = 6, status = 2 WHERE job_id = ? AND employee_id = ?";
    $stmt2 = $conn->prepare($updateApplicationSql);
    $stmt2->bind_param("ii", $job_id, $employee_id);

    if ($stmt1->execute() && $stmt2->execute()) {
        header("Location: ../jobs.php?success=JobFilled");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt1->close();
    $stmt2->close();
    $conn->close();
} else {
    header("Location: ../jobs.php");
    exit();
}
