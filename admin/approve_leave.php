<?php
include("../config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $leaveId = intval($_POST['id']); // Sanitize input

    // Update the leave request status to "Approved"
    $stmt = $conn->prepare("UPDATE leave_request SET status = 'Approved' WHERE id = ?");
    $stmt->bind_param("i", $leaveId);

    if ($stmt->execute()) {
        echo "Success"; // Response for success
    } else {
        http_response_code(500); // Internal Server Error
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    http_response_code(400); // Bad Request
    echo "Invalid request.";
}
?>
