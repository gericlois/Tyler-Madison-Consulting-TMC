<?php
session_start();
include "../../../pages/includes/connection.php";

if (!isset($_SESSION["admin_id"])) {
    die("Unauthorized access.");
}

if (isset($_GET['id']) && isset($_GET['status'])) {
    $vlog_id = intval($_GET['id']);
    $new_status = intval($_GET['status']);

    $stmt = $conn->prepare("UPDATE vlogs SET status = ? WHERE vlogs_id = ?");
    $stmt->bind_param("ii", $new_status, $vlog_id);

    if ($stmt->execute()) {
        // Redirect back with success message
        header("Location: ../settings.php?success=StatusUpdated");
        exit();
    } else {
        echo "Error updating vlog status.";
    }

    $stmt->close();
} else {
    echo "Invalid parameters.";
}

$conn->close();
?>
