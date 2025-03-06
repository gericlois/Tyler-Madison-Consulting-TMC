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
        header("Location: ../jobs.php?success=StatusUpdated"); 
    } else {
        header("Location: ../jobs.php?error=UpdateFailed"); 
    }

    $stmt->close();
}
$conn->close();
?>
