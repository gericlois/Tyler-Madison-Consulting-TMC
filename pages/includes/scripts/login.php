<?php
session_start();
include "../connection.php";

$username = $_POST['username'];
$password = $_POST['password'];
$role = $_GET['role'] ?? 'employee'; // 'employee' or 'employer'

if (!$role) {
    header("Location: ../../login.php?error=MissingRole");
    exit();
}

// Always query from users table
$query = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: ../../login.php?role=$role&error=AccountNotFound");
    exit();
}

$user = $result->fetch_assoc();

// Check if role matches
if ($user['role'] !== $role) {
    header("Location: ../../login.php?role=$role&error=AccountNotFound");
    exit();
}

// Verify password
if (password_verify($password, $user['password_hash'])) {
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username']; // <-- Add this line
    $_SESSION['role'] = $user['role'];

    // Redirect to appropriate dashboard
    if ($role === 'employee') {
        header("Location: ../../index.php");
    } elseif ($role === 'employer') {
        header("Location: ../../../domain/pages/index.php");
    }
    exit();
} else {
    header("Location: ../../login.php?role=$role&error=IncorrectPassword");
    exit();
}
