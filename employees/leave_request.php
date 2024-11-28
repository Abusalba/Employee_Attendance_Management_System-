<?php
include '../config.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $emp_id = $_POST['emp_id'];
    $leave_type = $_POST['leave_type'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $status = $_POST['status']; 
    $reason = $_POST['reason'];

    $stmt = $conn->prepare("INSERT INTO leave_request(emp_id, leave_type,start_date,end_date,status,reason) VALUES(?,?,?,?,?,?)");
    $stmt->bind_param('ssssss',$emp_id,$leave_type,$start_date,$end_date,$status,$reason);
    $stmt->execute();
     header('Location: employee_dashboard.php');
    






}



?>