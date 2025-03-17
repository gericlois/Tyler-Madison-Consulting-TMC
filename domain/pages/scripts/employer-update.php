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
        header("Location: ../employers.php?success=StatusUpdated"); 
    } else {
        header("Location: ../employers.php?error=UpdateFailed"); 
    }

    $stmt->close();
}
$conn->close();
?>
