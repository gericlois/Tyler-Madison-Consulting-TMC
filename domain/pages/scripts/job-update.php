<?php
session_start();
include "../../../pages/includes/connection.php";

if (isset($_GET['id']) && isset($_GET['status'])) {
    $job_id = intval($_GET['id']);
    $status = $_GET['status'];

    $sql = "UPDATE jobpostings SET status = ? WHERE job_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $job_id);

    if ($stmt->execute()) {
        $admin_id = $_SESSION['admin_id']; // Assuming admin ID is stored in session
        $action = "Job Posting Status Updated";
        $activity_description = "Updated job posting ID {$job_id}, status '{$status}'.";

        $activity_stmt = $conn->prepare("INSERT INTO activity (user_id, action, description) VALUES (?, ?, ?)");
        $activity_stmt->bind_param("iss", $admin_id, $action, $activity_description);
        $activity_stmt->execute();
        $activity_stmt->close();
        
        header("Location: ../jobs.php?success=StatusUpdated"); 
    } else {
        header("Location: ../jobs.php?error=UpdateFailed"); 
    }

    $stmt->close();
}
$conn->close();
?>
