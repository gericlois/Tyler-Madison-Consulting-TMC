<?php

session_start();
include "../connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']); // Get username input
    $password = $_POST['password'];

    // Check if username exists
    $stmt = $conn->prepare("SELECT user_id, password_hash, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password, $role);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['role'] = $role;

            // Redirect to dashboard based on role
            if ($role == 'admin') {
                header("Location: ../admin/dashboard.php");
            } elseif ($role == 'employee') {
                header("Location: ../employee/dashboard.php");
            } else {
                header("Location: ../dashboard.php");
            }
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

