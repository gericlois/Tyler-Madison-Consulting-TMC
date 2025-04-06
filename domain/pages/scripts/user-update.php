<?php
session_start();
include "../../../pages/includes/connection.php";

if (isset($_GET['id']) && isset($_GET['status'])) {
    $user_id = intval($_GET['id']);
    $status = $_GET['status'];

    $valid_statuses = ['1', '2', '3'];
    if (!in_array($status, $valid_statuses)) {
        header("Location: ../users.php?error=InvalidStatus");
        exit();
    }

    $sql = "UPDATE users SET status = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $user_id);

    if ($stmt->execute()) {
        $admin_id = $_SESSION['admin_id']; // Assuming admin ID is stored in session
        $action = "User Status Updated";
        $activity_description = "Updated User Status: ID {$user_id}, status '{$status}'.";
    
        $activity_stmt = $conn->prepare("INSERT INTO activity (user_id, action, description) VALUES (?, ?, ?)");
        $activity_stmt->bind_param("iss", $admin_id, $action, $activity_description);
        $activity_stmt->execute();
        $activity_stmt->close();
    }

    if ($stmt->execute()) {
        header("Location: ../users.php?success=StatusUpdated"); 
    } else {
        header("Location: ../users.php?error=UpdateFailed"); 
    }

    $stmt->close();
}
$conn->close();
?>
