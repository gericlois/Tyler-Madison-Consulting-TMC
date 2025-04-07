<?php
session_start();
include "../../../pages/includes/connection.php";

if (!isset($_GET['job_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing job_id']);
    exit();
}

$job_id = intval($_GET['job_id']);

$sql = "SELECT ja.employee_id, CONCAT(u.first_name, ' ', u.last_name) AS employee_name
        FROM jobapplications ja
        JOIN employees e ON ja.employee_id = e.employee_id
        JOIN users u ON e.user_id = u.user_id
        WHERE ja.job_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();

$applicants = [];
while ($row = $result->fetch_assoc()) {
    $applicants[] = $row;
}

header('Content-Type: application/json');
echo json_encode($applicants);
