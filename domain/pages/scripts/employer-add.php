<?php
include "../../../pages/includes/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $location = $_POST["location"];
    $description = $_POST["description"];
    $link_facebook = $_POST["link_facebook"];
    $link_linkedin = $_POST["link_linkedin"];
    $link_instagram = $_POST["link_instagram"];
    $status = $_POST["status"]; 

    // Handle profile picture upload
    $profile_picture = "";
    if (!empty($_FILES["profile_picture"]["name"])) {
        $target_dir = "../uploads/employers/";  // Ensure this folder exists
        $profile_picture = basename($_FILES["profile_picture"]["name"]);
        $target_file = $target_dir . $profile_picture;
        move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);
    }

    // Prepare SQL statement
    $query = "INSERT INTO employers (name, profile_picture, location, description, link_facebook, link_linkedin, link_instagram, status, created_at) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("sssssssi", $name, $profile_picture, $location, $description, $link_facebook, $link_linkedin, $link_instagram, $status);
        if ($stmt->execute()) {
            header("Location: ../employers.php?success=EmployerAdded");  // Redirect on success ✅
            exit();  // Ensure script stops execution after redirection
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Database error: " . $conn->error;
    }

    $conn->close();
}
?>