<?php
session_start();
include "../connection.php";

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: ../../login.php");
    exit();
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get form data
    $employee_id = $_POST['employee_id'];
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $position = trim($_POST['position']);
    $user_id = $_SESSION['user_id'];
    
    // Validate inputs
    if (empty($first_name) || empty($last_name) || empty($email) || empty($phone) || empty($address) || empty($position)) {
        header("Location: ../../profile-edit.php?user_id=" . $user_id . "&error=empty_fields");
        exit();
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../../profile-edit.php?user_id=" . $user_id . "&error=invalid_email");
        exit();
    }
    
    // Validate phone number (basic validation - numbers, spaces, dashes, parentheses)
    if (!preg_match("/^[0-9\s\-\(\)\+]+$/", $phone)) {
        header("Location: ../../profile-edit.php?user_id=" . $user_id . "&error=invalid_phone");
        exit();
    }
    
    // Check if email already exists for another user
    $check_email = $conn->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
    $check_email->bind_param("si", $email, $user_id);
    $check_email->execute();
    $check_email->store_result();
    
    if ($check_email->num_rows > 0) {
        $check_email->close();
        header("Location: ../../profile-edit.php?user_id=" . $user_id . "&error=email_exists");
        exit();
    }
    $check_email->close();
    
    // Begin transaction
    $conn->begin_transaction();
    
    try {
        // Update users table
        $sql_users = "UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, address = ? WHERE user_id = ?";
        $stmt_users = $conn->prepare($sql_users);
        
        if (!$stmt_users) {
            throw new Exception("Failed to prepare users update statement");
        }
        
        $stmt_users->bind_param("sssssi", $first_name, $last_name, $email, $phone, $address, $user_id);
        
        if (!$stmt_users->execute()) {
            throw new Exception("Failed to update users table");
        }
        
        $stmt_users->close();
        
        // Update employees table
        $sql_employees = "UPDATE employees SET position = ? WHERE user_id = ?";
        $stmt_employees = $conn->prepare($sql_employees);
        
        if (!$stmt_employees) {
            throw new Exception("Failed to prepare employees update statement");
        }
        
        $stmt_employees->bind_param("si", $position, $user_id);
        
        if (!$stmt_employees->execute()) {
            throw new Exception("Failed to update employees table");
        }
        
        $stmt_employees->close();
        
        // Commit transaction
        $conn->commit();
        
        // Update session variables
        $_SESSION['first_name'] = $first_name;
        $_SESSION['last_name'] = $last_name;
        $_SESSION['email'] = $email;
        
        // Redirect with success message
        header("Location: ../../profile.php?success=profile_updated");
        exit();
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        
        // Log error (optional)
        error_log("Profile Update Error: " . $e->getMessage());
        
        // Redirect with error message
        header("Location: ../../profile-edit.php?user_id=" . $user_id . "&error=update_failed");
        exit();
    }
    
} else {
    // If not POST request, redirect to profile
    header("Location: ../../profile.php");
    exit();
}

$conn->close();
?>