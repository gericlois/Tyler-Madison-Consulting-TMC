<?php
session_start();
include "../../../pages/includes/connection.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $username = $_POST['username'];
    $password = trim($_POST['password']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $birthday = trim($_POST['birthday']);
    $role = trim($_POST['role']);

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Check if email already exists
    $check_email = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $check_email->store_result();

    if ($check_email->num_rows > 0) {
        $_SESSION['error'] = "Email already exists!";
        header("Location: ../users-add.php?error=EmailTaken");
        exit();
    }

    $check_email->close();

    // Insert user into the database
    $query = "INSERT INTO users (username, first_name, last_name, email, password_hash, phone, address, birthday, role, created_at) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssssss", $username, $first_name, $last_name, $email, $hashed_password, $phone, $address, $birthday, $role);
    $stmt->execute();
    $stmt->close();

    if ($stmt->execute()) {
        $admin_id = $_SESSION['admin_id']; // Assuming admin ID is stored in session
        $action = "User Added";
        $activity_description = "Added a new User, name '{$first_name} {$last_name}'.";
    
        $activity_stmt = $conn->prepare("INSERT INTO activity (user_id, action, description) VALUES (?, ?, ?)");
        $activity_stmt->bind_param("iss", $admin_id, $action, $activity_description);
        $activity_stmt->execute();
        $activity_stmt->close();
    }
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "User added successfully!";
        header("Location: ../users.php");
    } else {
        $_SESSION['error'] = "Error adding user. Please try again.";
        header("Location: ../users-add.php");
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../users.php");
    exit();
}
