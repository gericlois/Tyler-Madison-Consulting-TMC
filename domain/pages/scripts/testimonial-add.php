<?php
session_start();
include "../../../pages/includes/connection.php"; // Adjust path as needed

// Validate form data
if (
    isset($_POST['name']) &&
    isset($_POST['company']) &&
    isset($_POST['email']) &&
    isset($_POST['rate']) &&
    isset($_POST['testimonial'])
) {
    $name = trim($_POST['name']);
    $company = trim($_POST['company']);
    $email = trim($_POST['email']);
    $rate = intval($_POST['rate']);
    $testimonial = trim($_POST['testimonial']);
    $status = isset($_POST['status']) ? intval($_POST['status']) : 1; // Default to display

    // Prepare SQL
    $stmt = $conn->prepare("INSERT INTO testimonials (name, company, email, rate, testimonial, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssisi", $name, $company, $email, $rate, $testimonial, $status);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: ../settings.php?success=TestimonialAdded");
        exit();
    } else {
        $stmt->close();
        $conn->close();
        header("Location: ../settings.php?error=InsertFailed");
        exit();
    }
} else {
    header("Location: ../settings.php?error=IncompleteData");
    exit();
}
