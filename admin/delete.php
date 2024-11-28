<?php
include '../config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM employee WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: employees.php ");
        exit();
    } else {
        echo "No record found with the specified ID.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Error: ID parameter is missing in the request.";
}
?>
