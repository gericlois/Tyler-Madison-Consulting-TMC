<?php
session_start();
include "../../../pages/includes/connection.php";

// Validate GET parameters
if (!isset($_GET['id']) || !isset($_GET['status'])) {
    die("Invalid request.");
}

$testimonial_id = intval($_GET['id']);
$new_status = intval($_GET['status']);

// Update testimonial status
$sql = "UPDATE testimonials SET status = ? WHERE testimonial_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $new_status, $testimonial_id);

if ($stmt->execute()) {
    header("Location: ../settings.php?success=StatusUpdated");
    exit();
} else {
    echo "Error updating status: " . $conn->error;
}

$stmt->close();
$conn->close();
?>