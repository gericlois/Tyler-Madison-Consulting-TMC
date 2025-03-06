<?php

session_start();
include "../connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']); // Get username input
    $password = $_POST['password'];

    // Query to check username and get employee position
    $stmt = $conn->prepare("
        SELECT u.user_id, u.username, u.password_hash, u.role, e.position
        FROM users u
        LEFT JOIN employees e ON u.user_id = e.user_id
        WHERE u.username = ?
    ");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $db_username, $hashed_password, $role, $position);
        $stmt->fetch();

        // Only allow employees to log in
        if ($role !== 'employee') {
            header("Location: ../../login.php?error=AccessDenied");
            exit();
        }

        // Verify password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $db_username; 
            $_SESSION['role'] = $role;
            $_SESSION['position'] = $position ?: 'Not Assigned'; 

            // Redirect employee to their dashboard
            header("Location: ../../index.php");
            exit();
        } else {
            header("Location: ../../login.php?error=IncorrectPassword");
            exit();
        }
    } else {
        header("Location: ../../login.php?error=AccountNotFound");
        exit();
    }

    $stmt->close();
}
?>
