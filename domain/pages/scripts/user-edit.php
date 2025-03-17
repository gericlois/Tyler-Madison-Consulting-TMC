<?php
session_start();
include "../../../pages/includes/connection.php";

if (!isset($_GET['user_id']) || !isset($_GET['status'])) {
    header("Location: ../users.php?error=InvalidRequest");
    exit();
}

$user_id = intval($_GET['user_id']);
$status = $_GET['status'];

$valid_statuses = ['1', '2', '3']; // Allowed statuses
if (!in_array($status, $valid_statuses)) {
    header("Location: ../users.php?error=InvalidStatus");
    exit();
}

// Update user status
$sql = "UPDATE users SET status = ? WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $user_id);

if ($stmt->execute()) {
    header("Location: ../users.php?success=StatusUpdated");
} else {
    header("Location: ../users.php?error=UpdateFailed");
}

$stmt->close();
$conn->close();
?>
