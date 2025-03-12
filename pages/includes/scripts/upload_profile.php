<?php
session_start();
include "../connection.php";

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: ../../login.php");
    exit();
  }


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profile_picture"])) {
    $user_id = $_SESSION["user_id"]; // Get the logged-in user's ID
    $target_dir = "uploads/profile_pictures/";

    // Ensure the upload directory exists
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_name = basename($_FILES["profile_picture"]["name"]);
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_extensions = ["jpg", "jpeg", "png", "gif"];

    // Validate file type
    if (!in_array($file_ext, $allowed_extensions)) {
        die("Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.");
    }

    // Generate unique file name
    $new_file_name = "profile_" . $user_id . "_" . time() . "." . $file_ext;
    $target_file = $target_dir . $new_file_name;

    // Check file size (limit to 2MB)
    if ($_FILES["profile_picture"]["size"] > 2 * 1024 * 1024) {
        die("File size exceeds 2MB limit.");
    }

    // Move file to the target directory
    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
        echo "File uploaded to: " . $target_file . "<br>";

        // Check if the user exists in the employees table
        $check = $conn->prepare("SELECT user_id FROM employees WHERE user_id = ?");
        $check->bind_param("i", $user_id);
        $check->execute();
        $check->store_result();
        
        if ($check->num_rows == 0) {
            die("Error: User ID not found in the employees table.");
        }
        $check->close();

        // Update profile picture path in the employees table
        $stmt = $conn->prepare("UPDATE employees SET profile_picture = ? WHERE user_id = ? AND (profile_picture IS NULL OR profile_picture != ?)");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("sis", $target_file, $user_id, $target_file);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION["profile_picture"] = $target_file; // Update session variable
                header("Location: ../../profile.php?upload_success"); // Redirect to profile page
                exit();
            } else {
                die("Database update failed. No changes made.");
            }
        } else {
            die("Execute failed: " . $stmt->error);
        }

        $stmt->close();
    } else {
        die("Error uploading file.");
    }
} else {
    die("Invalid request.");
}

$conn->close();
?>
