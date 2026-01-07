<?php
session_start();
include "../connection.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["cover_letter"])) {
    $upload_dir = "uploads/cover_letters/";  // Use correct relative path from script location

    // Create directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // File name: cover_letter_userid_timestamp.pdf
    $file_name = "cover_letter_" . time() . "_" . $_SESSION['user_id'] . ".pdf";
    $target_file = $upload_dir . $file_name;    

    // Debugging
    if (!is_writable($upload_dir)) {
        die("Upload directory is not writable.");
    }
    if (!file_exists($_FILES["cover_letter"]["tmp_name"])) {
        die("File upload failed. Temp file missing.");
    }

    // Move uploaded file
    if (move_uploaded_file($_FILES["cover_letter"]["tmp_name"], $target_file)) {
        $db_target_path = "uploads/cover_letters/" . $file_name; // Save relative path in the database

        $sql = "UPDATE employees SET cover_letter_path = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $db_target_path, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->close();

        header("Location: ../../profile.php?success=cover_letter_uploaded");
    } else {
        header("Location: ../../profile.php?error=upload_failed");
    }
}
?>
