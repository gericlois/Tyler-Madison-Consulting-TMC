<?php
session_start();
include "../connection.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../login.php");
    exit();
  }
  $user_id = $_SESSION["user_id"];

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if (!empty($_POST["cover_letter"])) {
          $cover_letter = trim($_POST["cover_letter"]);
  
          // Prevent SQL injection & XSS
          $cover_letter = htmlspecialchars($cover_letter, ENT_QUOTES, 'UTF-8');
  
          // Check if user already has a cover letter
          $check_query = "SELECT cover_letter FROM employees WHERE user_id = ?";
          $stmt = $conn->prepare($check_query);
          $stmt->bind_param("i", $user_id);
          $stmt->execute();
          $stmt->store_result();
  
          if ($stmt->num_rows > 0) {
              // Update existing cover letter
              $update_query = "UPDATE employees SET cover_letter = ? WHERE user_id = ?";
              $stmt = $conn->prepare($update_query);
              $stmt->bind_param("si", $cover_letter, $user_id);
          } else {
              // Insert new cover letter (if somehow the user does not exist in employees)
              $insert_query = "INSERT INTO employees (user_id, cover_letter) VALUES (?, ?)";
              $stmt = $conn->prepare($insert_query);
              $stmt->bind_param("is", $user_id, $cover_letter);
          }
  
          if ($stmt->execute()) {
              header("Location: ../../profile.php?uploadcoverletter_success");
          } else {
              header("Location: ../../profile.php?uploadcoverletter_error");
          }
  
          $stmt->close();
      } else {
          header("Location: ../../profile.php?uploadcoverletter_empty");
      }
  }
  
  $conn->close();
  exit();