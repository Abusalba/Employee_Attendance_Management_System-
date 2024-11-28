<?php
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['emp_id'], $_POST['date'], $_POST['status'], $_POST['check_in_time'], $_POST['check_out_time'])) {
        $emp_id = $_POST['emp_id'];
        $date = $_POST['date'];
        $status = $_POST['status'];
        $check_in_time = $_POST['check_in_time'];
        $check_out_time = $_POST['check_out_time'];

        // Check if emp_id exists in the employee table
        $check_stmt = $conn->prepare("SELECT id FROM employee WHERE emp_id = ?");
        $check_stmt->bind_param('i', $emp_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            // emp_id exists, proceed with insertion
            $stmt = $conn->prepare("INSERT INTO attendance (emp_id, date, status, check_in_time, check_out_time) VALUES (?, ?, ?,?, ?)");
            if ($stmt) {
                $stmt->bind_param('issss', $emp_id, $date, $status, $check_in_time, $check_out_time);
                if ($stmt->execute()) {
                    echo "Attendance marked successfully!";
                    header('Location: employee_dashboard.php');
                } else {
                    echo "Error executing query: " . $stmt->error;
                }
                $stmt->close();
            } else {
                die("Error preparing statement: " . $conn->error);
            }
        } else {
            echo "Error: Employee ID does not exist in the employee table.";
        }
        $check_stmt->close();
    } else {
        echo "Required form fields are missing.";
    }
} else {
    echo "Invalid request method.";
}
?>
