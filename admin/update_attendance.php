<?php
include("../config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emp_id = $_POST['emp_id'] ?? null;
    $date = $_POST['date'] ?? null;
    $status = $_POST['status'] ?? null;

    // Debugging statements
    if (!$emp_id || !$date || !$status) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE attendance SET status = ? WHERE emp_id = ? AND date = ?");
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare statement: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param("sis", $status, $emp_id, $date);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Attendance updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Query execution failed: ' . $stmt->error]);
    }
}
?>
