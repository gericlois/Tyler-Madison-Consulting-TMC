<?php
session_start();
include "../connection.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["cover_letter"])) {
    $upload_dir = "uploads/cover_letters/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Get file information
    $file_tmp = $_FILES["cover_letter"]["tmp_name"];
    $file_size = $_FILES["cover_letter"]["size"];
    $file_error = $_FILES["cover_letter"]["error"];
    $file_type = $_FILES["cover_letter"]["type"];
    
    // Get file extension
    $file_ext = strtolower(pathinfo($_FILES["cover_letter"]["name"], PATHINFO_EXTENSION));
    
    // Allowed file extensions
    $allowed_extensions = array('pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'gif');
    
    // Check for upload errors
    if ($file_error !== UPLOAD_ERR_OK) {
        header("Location: ../../profile.php?error=upload_error");
        exit();
    }
    
    // Validate file extension
    if (!in_array($file_ext, $allowed_extensions)) {
        header("Location: ../../profile.php?error=invalid_file_type");
        exit();
    }
    
    // Validate file size (max 5MB)
    $max_size = 5 * 1024 * 1024; // 5MB in bytes
    if ($file_size > $max_size) {
        header("Location: ../../profile.php?error=file_too_large");
        exit();
    }
    
    // Validate MIME type for security
    $allowed_mime_types = array(
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/gif'
    );
    
    if (!in_array($file_type, $allowed_mime_types)) {
        header("Location: ../../profile.php?error=invalid_mime_type");
        exit();
    }

    // Generate unique file name with proper extension
    $file_name = "cover_letter_" . time() . "_" . $_SESSION['user_id'] . "." . $file_ext;
    $target_file = $upload_dir . $file_name;

    // Check if upload directory is writable
    if (!is_writable($upload_dir)) {
        header("Location: ../../profile.php?error=directory_not_writable");
        exit();
    }
    
    // Check if temp file exists
    if (!file_exists($file_tmp)) {
        header("Location: ../../profile.php?error=temp_file_missing");
        exit();
    }

    // Move uploaded file
    if (move_uploaded_file($file_tmp, $target_file)) {
        $db_target_path = "uploads/cover_letters/" . $file_name;

        // Update database
        $sql = "UPDATE employees SET cover_letter_path = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $db_target_path, $_SESSION['user_id']);
        
        if ($stmt->execute()) {
            $stmt->close();
            header("Location: ../../profile.php?success=cover_letter_uploaded");
        } else {
            $stmt->close();
            // Delete uploaded file if database update fails
            unlink($target_file);
            header("Location: ../../profile.php?error=database_update_failed");
        }
    } else {
        header("Location: ../../profile.php?error=upload_failed");
    }
} else {
    header("Location: ../../profile.php?error=no_file_uploaded");
}
?>