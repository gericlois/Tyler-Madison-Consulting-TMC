<?php
session_start();
include "../../../pages/includes/connection.php";

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    die("Unauthorized access.");
}

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']); // Optional descriptive name
    $type = trim($_POST['type']);
    $user_id = intval($_POST['user_id']);
    $uploaded_by = intval($_POST['uploaded_by']);
    $job_id = isset($_POST['job_id']) ? intval($_POST['job_id']) : null; // Optional job_id

    // File upload validation
    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        die("File upload failed.");
    }

    $fileTmpPath = $_FILES['file']['tmp_name'];
    $fileOriginalName = basename($_FILES['file']['name']);
    $fileSize = $_FILES['file']['size'];
    $fileType = mime_content_type($fileTmpPath);
    $allowedTypes = [
        'application/pdf',
        'image/jpeg',
        'image/png',
        'text/plain',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];

    if (!in_array($fileType, $allowedTypes)) {
        die("Invalid file type.");
    }

    if ($fileSize > 5 * 1024 * 1024) { // 5MB limit
        die("File is too large. Max size is 5MB.");
    }

    // Create a unique filename
    $fileExtension = pathinfo($fileOriginalName, PATHINFO_EXTENSION);
    $newFileName = uniqid('file_', true) . '.' . $fileExtension;

    // Prepare upload directory - ../uploads relative to this script
    $uploadDir = realpath(__DIR__ . '/../uploads');
    if ($uploadDir === false) {
        // Directory doesn't exist, create it
        $uploadDir = __DIR__ . '/../uploads';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        // Resolve to absolute path
        $uploadDir = realpath($uploadDir);
    }

    $destination = $uploadDir . DIRECTORY_SEPARATOR . $newFileName;

    // Move the uploaded file
    if (!move_uploaded_file($fileTmpPath, $destination)) {
        die("Error moving uploaded file.");
    }

    // Save relative directory path for DB (relative to your web root)
    $directory = 'uploads/' . $newFileName;

    // Prepare SQL based on whether job_id is set
    if ($job_id !== null) {
        $stmt = $conn->prepare("INSERT INTO files (name, type, user_id, job_id, uploaded_by, directory) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("ssiiss", $newFileName, $type, $user_id, $job_id, $uploaded_by, $directory);
    } else {
        $stmt = $conn->prepare("INSERT INTO files (name, type, user_id, uploaded_by, directory) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("ssiis", $newFileName, $type, $user_id, $uploaded_by, $directory);
    }

    if ($stmt->execute()) {
        // Redirect appropriately - you may want to redirect based on job or user
        if ($job_id !== null) {
            header("Location: ../jobs-profile.php?id=$job_id&upload_success=1");
        } else {
            header("Location: ../employers-profile.php?id=$user_id&upload_success=1");
        }
        exit();
    } else {
        echo "Database error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
