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
        header("Location: ../users.php?success=StatusUpdated"); 
    } else {
        header("Location: ../users.php?error=UpdateFailed"); 
    }

    $stmt->close();
}
$conn->close();
?>
