<?php
include("../config.php");

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are set and not empty
    if (isset(
        $_POST['id'],
        $_POST['emp_id'],
        $_POST['emp_name'],
        $_POST['emp_email'],
        $_POST['emp_phone'],
        $_POST['emp_department'],
        $_POST['emp_role'],
        $_POST['emp_joining_date']
    )) {

        $id =  $_POST['id'];
        $emp_id =  $_POST['emp_id'];
        $emp_name =  $_POST['emp_name'];
        $emp_email =  $_POST['emp_email'];
        $emp_phone =  $_POST['emp_phone'];
        $emp_department = $_POST['emp_department'];
        $emp_role = $_POST['emp_role'];
        $joining_date =  $_POST['emp_joining_date'];

        // Prepare SQL statement
        if ($stmt = $conn->prepare("UPDATE employee SET 
                              emp_id = ?,
                              emp_name = ?, 
                              emp_email = ?, 
                              emp_phone = ?, 
                              emp_department = ?, 
                              emp_role = ?, 
                              emp_joining_date = ? 
                              WHERE id = ?")) {
            // Bind parameters and execute statement
            $stmt->bind_param(
                'sssssssi',
                $emp_id,
                $emp_name,
                $emp_email,
                $emp_phone,
                $emp_department,
                $emp_role,
                $joining_date,
                $id
            );
            if ($stmt->execute()) {
                echo "Employee Update successfully!";
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
