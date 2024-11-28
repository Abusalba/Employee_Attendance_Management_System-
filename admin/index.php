<?php
include ("../config.php");
include("header.php");
include("layout.php");
include("navbar.php");
include("footer.php");
include("dashboard.php");

// Get today's date
$today_date = date('Y-m-d');

// Fetch employee and attendance data
$stmt = $conn->prepare(
    "SELECT 
        employee.*, 
        attendance.date, 
        attendance.status, 
        attendance.check_in_time, 
        attendance.check_out_time
    FROM 
        employee
    LEFT JOIN 
        attendance 
    ON 
        employee.emp_id = attendance.emp_id
        WHERE attendance.date = ?
    ORDER BY attendance.date DESC
");
$stmt->bind_param('s',$today_date);  // if get result today date only then add 's' and bind param function ;
$stmt->execute();
$result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);


// Initialize attendance summary
$attendance_summary = [
    "total_employees" => 0,  // Total number of employees
    "total_attendance" => 0, // Total attendance records
    "present" => 0,
    "absent" => 0,
    "late" => 0,
];

// Fetch total employees count
$employeeStmt = $conn->prepare("SELECT COUNT(*) AS total FROM employee");
$employeeStmt->execute();
$totalEmployees = $employeeStmt->get_result()->fetch_assoc()['total'];
$attendance_summary['total_employees'] = $totalEmployees;

// Loop through attendance list to calculate summary
foreach ($result as $attendance) {
    $attendance_summary['total_attendance']++;
    if ($attendance['status'] == 'Present') {
        $attendance_summary['present']++;
    } elseif ($attendance['status'] == 'Absent') {
        $attendance_summary['absent']++;
    } elseif ($attendance['status'] == 'Late') {
        $attendance_summary['late']++;
    } 
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Main Content (Full-width) -->
            <main class="col px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Admin Dashboard</h1>
                </div>

                <!-- Dashboard Summary -->
                <div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Employees</h5>
                <p class="card-text" id="totalEmployees"><?php echo $attendance_summary['total_employees']; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Present Today</h5>
                <p class="card-text" id="presentToday"><?php echo $attendance_summary['present']; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning mb-3">
            <div class="card-body">
                <h5 class="card-title">Absent Today</h5>
                <p class="card-text" id="absent"><?php echo $attendance_summary['absent']; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-secondary mb-3">
            <div class="card-body">
                <h5 class="card-title">Late Today</h5>
                <p class="card-text" id="late"><?php echo $attendance_summary['late']; ?></p>
            </div>
        </div>
    </div>
</div>


                <!-- Employee Attendance Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Employee ID</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Date</th>
                                <th>Status</th>
                                
                            </tr>
                        </thead>
                        <tbody id="employeeTable">

                        <?php foreach($result as $row):?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']) ?></td>
                                <td><?php echo htmlspecialchars($row['emp_id']) ?></td>
                                <td><?php echo htmlspecialchars($row['emp_name']) ?></td>
                                <td><?php echo htmlspecialchars($row['emp_department']) ?></td>
                                <td><?php echo htmlspecialchars($row['date']) ?></td>
                                <td>
                                <span class="badge badge-<?php 
                                                echo $row["status"] == "Present" ? "success" : 
                                                     ($row["status"] == "Absent" ? "warning": 
                                                     ($row["status"] == "Late" ? "secondary" : "secondary")); ?> badge-status">
                                                <?php echo htmlspecialchars($row["status"]); ?>
                                            </span>
                                </td>
                            </tr>
                            <?php endforeach;?>
                            <!-- Additional rows as necessary -->
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>