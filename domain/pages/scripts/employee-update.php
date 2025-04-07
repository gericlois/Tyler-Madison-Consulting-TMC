<?php
session_start();
include "../../../pages/includes/connection.php";

if (isset($_GET['id']) && isset($_GET['status'])) {
    $employee_id = intval($_GET['id']);
    $status = $_GET['status'];

    $sql = "UPDATE employees SET status = ? WHERE employee_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $employee_id);

    if ($stmt->execute()) {
        $admin_id = $_SESSION['admin_id']; // Assuming admin ID is stored in session
        $action = "Employee Status Updated";
        $activity_description = "Updated Employee ID {$employee_id}, status '{$status}'.";

        $activity_stmt = $conn->prepare("INSERT INTO activity (user_id, action, description) VALUES (?, ?, ?)");
        $activity_stmt->bind_param("iss", $admin_id, $action, $activity_description);
        $activity_stmt->execute();
        $activity_stmt->close();
        
        header("Location: ../employees.php?success=StatusUpdated"); 
    } else {
        header("Location: ../employees.php?error=UpdateFailed"); 
    }

    $stmt->close();
}
$conn->close();
?>
