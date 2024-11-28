<?php
include '../config.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are set and not empty
    if (isset($_POST['emp_id'], $_POST['emp_name'], $_POST['emp_email'], $_POST['password'], $_POST['emp_phone'], $_POST['emp_department'], $_POST['emp_role'], $_POST['emp_joining_date'])) {
        
        $emp_id = trim($_POST['emp_id']);
        $emp_name = trim($_POST['emp_name']);
        $emp_email = trim($_POST['emp_email']);
        $password = trim($_POST['password']);
        $emp_phone = trim($_POST['emp_phone']);
        $emp_department = trim($_POST['emp_department']);
        $emp_role = trim($_POST['emp_role']);
        $joining_date = trim($_POST['emp_joining_date']);

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Prepare and execute SQL statement
        if ($stmt = $conn->prepare("INSERT INTO employee (emp_id, emp_name, emp_email, password, emp_phone, emp_department, emp_role, emp_joining_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)")) {
            $stmt->bind_param('ssssssss', $emp_id, $emp_name, $emp_email, $hashed_password, $emp_phone, $emp_department, $emp_role, $joining_date);
            
            if ($stmt->execute()) {
                echo "Employee added successfully!";
                // Delay redirect to ensure successful insertion message is displayed
                header("Location: employees.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "All fields are required!";
    }
}

$conn->close();
?>
