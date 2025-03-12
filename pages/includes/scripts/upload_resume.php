<?php
session_start();
include "../connection.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../login.php");
    exit();
  }


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["resume"])) {
    $upload_dir = "uploads/resumes/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_name = "resume_" . time() . "_" . $_SESSION['user_id'] . ".pdf";
    $target_file = $upload_dir . $file_name;

    if (move_uploaded_file($_FILES["resume"]["tmp_name"], $target_file)) {
        $sql = "UPDATE employees SET resume_path = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $target_file, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->close();
        header("Location: ../../profile.php?success=resume_uploaded");
    } else {
        header("Location: ../../profile.php?error=upload_failed");
    }
}
?>
