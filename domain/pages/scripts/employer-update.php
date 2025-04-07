<?php
session_start();
include "../../../pages/includes/connection.php";

if (isset($_GET['id']) && isset($_GET['status'])) {
    $employer_id = intval($_GET['id']);
    $status = $_GET['status'];

    $sql = "UPDATE employers SET status = ? WHERE employer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $employer_id);

    if ($stmt->execute()) {
        $admin_id = $_SESSION['admin_id']; // Assuming admin ID is stored in session
        $action = "Employer Status Updated";
        $activity_description = "Updated Employer ID {$employer_id}, status '{$status}'.";

        $activity_stmt = $conn->prepare("INSERT INTO activity (user_id, action, description) VALUES (?, ?, ?)");
        $activity_stmt->bind_param("iss", $admin_id, $action, $activity_description);
        $activity_stmt->execute();
        $activity_stmt->close();
        
        header("Location: ../employers.php?success=StatusUpdated"); 
    } else {
        header("Location: ../employers.php?error=UpdateFailed"); 
    }

    $stmt->close();
}
$conn->close();
?>
