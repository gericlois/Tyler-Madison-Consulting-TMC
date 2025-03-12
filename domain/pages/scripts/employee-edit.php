<?php
session_start();
include "../../../pages/includes/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $employee_id = $_POST['employee_id'];
    $user_id = $_POST['user_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $birthday = $_POST['birthday'];
    $username = $_POST['username'];
    $role = $_POST['role'];
    $position = $_POST['position'];

    // Database connection check
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Update users table
    $updateUser = "UPDATE users SET 
                    first_name = ?, 
                    last_name = ?, 
                    email = ?, 
                    phone = ?, 
                    address = ?, 
                    birthday = ?, 
                    username = ?, 
                    role = ?
                  WHERE user_id = ?";

    // Prepare statement
    $stmtUser = mysqli_prepare($conn, $updateUser);
    mysqli_stmt_bind_param($stmtUser, "ssssssssi", $first_name, $last_name, $email, $phone, $address, $birthday, $username, $role, $user_id);

    // Execute user update
    if (mysqli_stmt_execute($stmtUser)) {
        // Update employees table
        $updateEmployee = "UPDATE employees SET position = ? WHERE employee_id = ?";
        $stmtEmployee = mysqli_prepare($conn, $updateEmployee);
        mysqli_stmt_bind_param($stmtEmployee, "si", $position, $employee_id);
        
        if (mysqli_stmt_execute($stmtEmployee)) {
            // Redirect with success message
            header("Location: ../employees.php?success=EmployeeUpdated");
            exit();
        } else {
            echo "Error updating employees table: " . mysqli_error($conn);
        }
    } else {
        echo "Error updating users table: " . mysqli_error($conn);
    }

    // Close statements and connection
    mysqli_stmt_close($stmtUser);
    mysqli_stmt_close($stmtEmployee);
    mysqli_close($conn);
} else {
    // If accessed without POST request, redirect back
    header("Location: ../employees.php");
    exit();
}
?>
