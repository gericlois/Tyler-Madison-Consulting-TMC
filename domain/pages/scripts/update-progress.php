
<?php
include "../../../pages/includes/connection.php";


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["app_id"], $_POST["progress"])) {
    $app_id = intval($_POST["app_id"]);
    $progress = intval($_POST["progress"]);

    // Prepare the SQL statement to prevent SQL injection
    $sql = "UPDATE jobapplications SET progress = ? WHERE jobapplication_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $progress, $app_id);

    if ($stmt->execute()) {
        $admin_id = $_SESSION['admin_id']; // Assuming admin ID is stored in session
        $action = "Job Application Updated";
        $activity_description = "Updated  Job Application ID {$app_id}, progress '{$progress}'.";
    
        $activity_stmt = $conn->prepare("INSERT INTO activity (user_id, action, description) VALUES (?, ?, ?)");
        $activity_stmt->bind_param("iss", $admin_id, $action, $activity_description);
        $activity_stmt->execute();
        $activity_stmt->close();
        
        echo "Success";
    } else {
        echo "Error updating progress: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request";
}

?>