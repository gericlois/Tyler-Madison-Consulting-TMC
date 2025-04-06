<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    die("You must be logged in to perform this action.");
}

include "../../../pages/includes/connection.php";

$job_id = $_POST['job_id'];
$employee_id = $_POST['employee_id'];
$comment = $_POST['comment'];

if (empty($comment)) {
    die("Comment cannot be empty.");
}

$stmt = $conn->prepare("INSERT INTO comments (comment, job_id, employee_id) VALUES (?, ?, ?)");
$stmt->bind_param("sii", $comment, $job_id, $employee_id);

if ($stmt->execute()) {
    $admin_id = $_SESSION['admin_id']; // Assuming admin ID is stored in session
    $action = "Added a Comment";
    $activity_description = "Added a Comment to Employee ID {$employee_id}, comment '{$comment}', job '{$job_id}'.";

    $activity_stmt = $conn->prepare("INSERT INTO activity (user_id, action, description) VALUES (?, ?, ?)");
    $activity_stmt->bind_param("iss", $admin_id, $action, $activity_description);
    $activity_stmt->execute();
    $activity_stmt->close();
}

if ($stmt->execute()) {
    header("Location: ../jobs-profile.php?id=" . $job_id . "&success=AddedComment");
    exit();
} else {
    echo json_encode(["status" => "error", "message" => "Error saving comment."]);
}

$stmt->close();
$conn->close();
?>
