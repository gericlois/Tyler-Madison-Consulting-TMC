<?php
session_start();
include "includes/connection.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT resume_path FROM employees WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($resume_path);
$stmt->fetch();
$stmt->close();

if (empty($resume_path)) {
    die("No resume uploaded.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Resume</title>
</head>
<body>
    <iframe src="<?php echo htmlspecialchars($resume_path); ?>" width="100%" height="600px"></iframe>
</body>
</html>
