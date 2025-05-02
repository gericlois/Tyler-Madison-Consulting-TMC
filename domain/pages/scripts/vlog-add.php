<?php
session_start();
include "../../../pages/includes/connection.php";

if (!isset($_SESSION["admin_id"])) {
    die("Unauthorized access.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"]);
    $link = trim($_POST["link"]);
    $status = intval($_POST["status"]);

    if (empty($title) || empty($link)) {
        die("All fields are required.");
    }

    $stmt = $conn->prepare("INSERT INTO vlogs (title, link, status) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $title, $link, $status);

    if ($stmt->execute()) {
        header("Location: ../settings.php?success=VlogAdded");
        exit();
    } else {
        echo "Error saving vlog.";
    }

    $stmt->close();
    $conn->close();
}
?>
