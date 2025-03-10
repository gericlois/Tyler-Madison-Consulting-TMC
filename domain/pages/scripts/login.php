<?php

session_start();
include "../../../pages/includes/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check if fields are empty
    if (empty($username) || empty($password)) {
        header("Location: ../login.php?error=empty_fields");
        exit();
    }

    // Prepare and execute query
    $stmt = $conn->prepare("SELECT user_id, username, password_hash, first_name, last_name, role FROM users WHERE username = ? AND role = 'admin'");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Verify if admin exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $db_username, $db_password, $first_name, $last_name, $role);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $db_password)) {
            $_SESSION['admin_id'] = $user_id;
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name'] = $last_name;
            $_SESSION['username'] = $db_username;
            $_SESSION['role'] = $role; // Store role in session
            
            header("Location: ../index.php"); // Redirect to admin dashboard
            exit();
        } else {
            header("Location: ../login.php?error=invalid_password");
            exit();
        }
    } else {
        header("Location: ../login.php?error=admin_not_found");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
