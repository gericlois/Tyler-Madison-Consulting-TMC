<?php
session_start();
include "../../../pages/includes/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form inputs
    $name = trim($_POST["name"]);
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $phone = trim($_POST["phone"]);
    $email = trim($_POST["email"]);
    $description = trim($_POST["description"]);
    $link_facebook = trim($_POST["link_facebook"]);
    $link_linkedin = trim($_POST["link_linkedin"]);
    $link_instagram = trim($_POST["link_instagram"]);
    $status = (int)$_POST["status"];
    $admin_id = $_SESSION['admin_id'];

    // Check if email already exists
    $check_email_stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $check_email_stmt->bind_param("s", $email);
    $check_email_stmt->execute();
    $check_email_stmt->store_result();
    if ($check_email_stmt->num_rows > 0) {
        $check_email_stmt->close();
        header("Location: ../employers-add.php?error=EmailDuplicate");
        exit();
    }
    $check_email_stmt->close();

    // Hash the password
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Set default values for required user fields
    $first_name = $name;
    $last_name = "Employer";
    $address = "N/A";
    $birthday = "2000-01-01";
    $role = "employer";
    $user_status = 1;

    // Handle profile picture upload
    $profile_picture = null;
    if (!empty($_FILES["profile_picture"]["name"])) {
        $target_dir = "../uploads/employers/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        $profile_picture = basename($_FILES["profile_picture"]["name"]);
        $target_file = $target_dir . $profile_picture;
        move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);
    }

    // Begin transaction
    $conn->begin_transaction();
    try {
        // Insert into users table
        $user_sql = "INSERT INTO users (first_name, last_name, email, phone, address, birthday, username, password_hash, role, status, created_at)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $user_stmt = $conn->prepare($user_sql);
        $user_stmt->bind_param("sssssssssi", $first_name, $last_name, $email, $phone, $address, $birthday, $username, $password_hash, $role, $user_status);
        $user_stmt->execute();
        $user_id = $conn->insert_id;
        $user_stmt->close();

        // Insert into employers table
        $emp_sql = "INSERT INTO employers (user_id, name, profile_picture, location, description, email, phone, link_facebook, link_linkedin, link_instagram, status, created_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $emp_stmt = $conn->prepare($emp_sql);
        $emp_stmt->bind_param("isssssssssi", $user_id, $name, $profile_picture, $username, $description, $email, $phone, $link_facebook, $link_linkedin, $link_instagram, $status);
        $emp_stmt->execute();
        $emp_stmt->close();

        // Log activity
        $action = "Employer Added";
        $activity_description = "Added employer '{$name}' (User ID: {$user_id}).";
        $act_stmt = $conn->prepare("INSERT INTO activity (user_id, action, description) VALUES (?, ?, ?)");
        $act_stmt->bind_param("iss", $admin_id, $action, $activity_description);
        $act_stmt->execute();
        $act_stmt->close();

        $conn->commit();
        header("Location: ../employers.php?success=EmployerAdded");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    $conn->close();
}
?>
