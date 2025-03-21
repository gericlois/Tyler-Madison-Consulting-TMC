<?php
session_start();
include "../connection.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["resume"])) {
    $upload_dir = "uploads/resumes/";  // Use correct relative path from script location

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_name = "resume_" . time() . "_" . $_SESSION['user_id'] . ".pdf";
    $target_file = $upload_dir . $file_name;    

    // Debugging
    if (!is_writable($upload_dir)) {
        die("Upload directory is not writable.");
    }
    if (!file_exists($_FILES["resume"]["tmp_name"])) {
        die("File upload failed. Temp file missing.");
    }

    if (move_uploaded_file($_FILES["resume"]["tmp_name"], $target_file)) {
        $db_target_path = "uploads/resumes/" . $file_name; // Save relative path in the database

        $sql = "UPDATE employees SET resume_path = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $db_target_path, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->close();

        header("Location: ../../profile.php?success=resume_uploaded");
    } else {
        header("Location: ../../profile.php?error=upload_failed");
    }
}
?>
