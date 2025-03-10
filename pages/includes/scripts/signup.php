<?php
include "../connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $_POST['phone'];
    $position = $_POST['position'];

    try {
        // Start transaction
        $conn->autocommit(false);

        // Check if email already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            header("Location: ../../signup.php?error=emailtaken");
            exit();
        }

        // Insert into user table
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, username, password_hash, phone, role) VALUES (?, ?, ?, ?, ?, ?, 'employee')");
        $stmt->bind_param("ssssss", $first_name, $last_name, $email, $username, $password, $phone);
        $stmt->execute();

        // Get the last inserted user_id
        $user_id = $conn->insert_id;

        // Insert into employee table
        $stmt = $conn->prepare("INSERT INTO employees (user_id, position) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $position);
        $stmt->execute();

        // Commit transaction
        $conn->commit();

        // Redirect on success
        header("Location: ../../promp.php?success=AccountCreated");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        header("Location: ../../signup.php?error=" . urlencode($e->getMessage())); // Show actual error
        exit();
    }
    
}