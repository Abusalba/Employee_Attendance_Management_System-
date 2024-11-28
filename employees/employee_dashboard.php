<?php
include '../config.php';
session_start();

$id = $_SESSION['id'];
$stmt = $conn->prepare("SELECT * FROM employee WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$employee_profile = $stmt->get_result()->fetch_assoc(); // Fetch a single employee record


// Fetch attendance data
$stmt2 = $conn->prepare("SELECT * FROM attendance WHERE emp_id=?");
$stmt2->bind_param("s", $employee_profile['emp_id']);
$stmt2->execute();
$attendance_list = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);


// Handle case when no attendance data is found
if (empty($attendance_list)) {
    $attendance_list = [];
}

// Initialize attendance summary
$attendance_summary = [
    'present' => 0,
    'absent' => 0,
    'late' => 0,
];

// Loop through attendance list to calculate summary
foreach ($attendance_list as $attendance) {
    if ($attendance['status'] == 'Present') {
        $attendance_summary['present']++;
    } elseif ($attendance['status'] == 'Absent') {
        $attendance_summary['absent']++;
    } elseif ($attendance['status'] == 'Late') {
        $attendance_summary['late']++;
    }
}


// Assuming you have a session for the logged-in employee
$id = $_SESSION['id']; // Session ID
$stmt = $conn->prepare("SELECT emp_id FROM employee WHERE id = ?");
$stmt->bind_param("i", $id); // Bind as integer
$stmt->execute();
$result = $stmt->get_result();

// Check if the employee record exists
if ($result->num_rows > 0) {
    $employee = $result->fetch_assoc(); // Fetch the employee record
    $emp_id = $employee['emp_id']; // Get the emp_id

    // Step 2: Fetch leave requests for the emp_id
    $stmt = $conn->prepare("SELECT start_date, end_date, reason, status FROM leave_request WHERE emp_id = ? ORDER BY start_date DESC");
    $stmt->bind_param("i", $emp_id); // Bind as integer
    $stmt->execute();
    $leave_requests = $stmt->get_result()->fetch_all(MYSQLI_ASSOC); // Fetch all leave requests

    // Output or use the fetched leave requests as needed
} else {
    echo "No employee found with the given session ID.";
}

// Close the statement
$stmt->close();

// Initialize variables for total leave days and the number of leave requests
$total_leave_days = 0;
$leave_request_count = 0;

// Loop through leave requests to calculate total leave days and count requests
foreach ($leave_requests as $request) {
    $start_date = new DateTime($request['start_date']);
    $end_date = new DateTime($request['end_date']);
    $interval = $start_date->diff($end_date);
    $total_leave_days += $interval->days + 1; // Including the start date

    // Increment leave request count
    $leave_request_count++;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .dashboard-card {
            border-radius: 10px;
            transition: box-shadow 0.3s ease;
        }

        .dashboard-card:hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .badge-status {
            font-size: 0.9rem;
        }

        .profile-card {
            background: #f5f5f5;
            padding: 1.5rem;
            border-radius: 10px;
            height: 100%;
        }

        .attendance-card {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="h3 font-weight-bold">Employee Dashboard</h1>
            <!-- logout section in employee table -->
            <a class="nav-link menu-link collapsed btn btn-primary" href="logout.php">
                <i class="fa-solid fa-right-from-bracket"></i> <span data-key="t-attendece">Logout</span>
            </a>
        </div>

        <!-- Profile Overview and Mark Attendance Section -->
        <div class="row mb-4">
            <!-- Employee Profile Card -->
            <div class="col-lg-6">
                <div class="profile-card shadow-sm">
                    <h5>Welcome, <?php echo htmlspecialchars($employee_profile["emp_name"]); ?></h5>
                    <p>Position: <?php echo htmlspecialchars($employee_profile["emp_role"]); ?></p>
                    <p>Employee ID: <?php echo htmlspecialchars($employee_profile['emp_id']) ?></p>
                    <p>Email: <?php echo htmlspecialchars($employee_profile['emp_email']) ?></p>
                    <p>Department: <?php echo htmlspecialchars($employee_profile["emp_department"]); ?></p>
                </div>
            </div>

            <!-- Mark Attendance Card -->
            <div class="col-lg-6">
                <div class="card text-center attendance-card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3"><i class="fas fa-calendar-check mr-2"></i>Mark Attendance</h5>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#attendanceModal">Mark Attendance</button>
                        <button type="button" data-toggle="modal" class="btn btn-danger" data-target="#leaveRequestModal">Leave Request</button>
                    </div>

                </div>
            </div>
        </div>

        <!-- Attendance Summary Section -->
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card dashboard-card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-calendar-alt mr-2"></i>Attendance Summary</h5>
                        <div class="row text-center">
                            <div class="col">
                                <p class="mb-1">Present Days</p>
                                <span class="badge badge-success"><?php echo $attendance_summary["present"]; ?></span>
                            </div>
                            <div class="col">
                                <p class="mb-1">Absent Days</p>
                                <span class="badge badge-warning"><?php echo $attendance_summary["absent"]; ?></span>
                            </div>
                            <div class="col">
                                <p class="mb-1">Late Days</p>
                                <span class="badge badge-secondary"><?php echo $attendance_summary["late"]; ?></span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance List Section -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card dashboard-card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4"><i class="fas fa-list-alt mr-2"></i>Attendance List</h5>
                        <table class="table table-bordered text-center">
                            <thead class="thead-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Check-in Time</th>
                                    <th>Check-out Time</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php foreach ($attendance_list as $attendance): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($attendance["date"]); ?></td>
                                        <td>
                                            <span class="badge badge-<?php
                                                                        echo $attendance["status"] == "Present" ? "success" : ($attendance["status"] == "Absent" ? "warning" : ($attendance["status"] == "Late" ? "secondary"  : "danger")); ?> badge-status">
                                                <?php echo htmlspecialchars($attendance["status"]); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($attendance["check_in_time"]); ?></td>
                                        <td><?php echo htmlspecialchars($attendance["check_out_time"]); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mt-5">
    <h2 class="text-center mb-4">My Leave Requests</h2>
    <div class="row">
        <div class="col-md-12">
            <div class="card dashboard-card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-calendar-alt mr-2"></i>Leave Summary</h5>
                    <!-- Display Total Leave Days Taken and Number of Leave Requests -->
                    <p class="mb-2"><strong>Total Leave Days Taken:</strong> <?php echo $total_leave_days; ?> days</p>
                    <p class="mb-2"><strong>Number of Leave Requests:</strong> <?php echo $leave_request_count; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave Requests Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Reason</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($leave_requests)): ?>
                    <?php foreach ($leave_requests as $request): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($request['start_date']); ?></td>
                            <td><?php echo htmlspecialchars($request['end_date']); ?></td>
                            <td><?php echo htmlspecialchars($request['reason']); ?></td>
                            <td>
                                <span class="badge badge-<?php 
                                    echo $request['status'] == 'Approved' ? 'success' : 
                                         ($request['status'] == 'Rejected' ? 'danger' : 'warning'); ?>">
                                    <?php echo htmlspecialchars($request['status']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No leave requests found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


    </div>

    <!-- Attendance Modal -->
    <div class="modal fade" id="attendanceModal" tabindex="-1" aria-labelledby="attendanceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="attendanceModalLabel">Mark Attendance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="add_attendence.php" method="POST">
                        <div class="form-group">
                            <input type="hidden" name="emp_id" value="<?php echo $employee_profile['emp_id']; ?>"> <!-- Use dynamic employee ID  not ID-->
                            <label for="attendanceDate">Attendance Date</label>
                            <input type="date" class="form-control" id="attendanceDate" name="date" required>

                            <label for="attendanceStatus" class="mt-3">Attendance Status</label>
                            <select class="form-control" id="attendanceStatus" name="status" required onchange="toggleLeaveMessage()">
                                <option value="" disabled selected>Choose Status</option>
                                <option value="Present">Present</option>
                                <option value="Absent">Absent</option>
                                <option value="Late">Late</option> <!-- Late option now after Absent -->
                            </select>
                            <label for="checkInTime" class="mt-3">Check-in Time</label>
                            <input type="time" class="form-control" id="checkInTime" name="check_in_time" required>

                            <label for="checkOutTime" class="mt-3">Check-out Time</label>
                            <input type="time" class="form-control" id="checkOutTime" name="check_out_time" required>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4" name="submit">Submit Attendance</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave Request Modal -->
    <div class="modal fade" id="leaveRequestModal" tabindex="-1" aria-labelledby="leaveRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="leaveRequestModalLabel">Leave Request Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="leave_request.php" method="POST">
                        <!-- Leave Type -->
                        <div class="form-group">
                            <label for="leaveType">Leave Type</label>
                            <select class="form-control" id="leaveType" name="leave_type" required>
                                <option value="Sick Leave">Sick Leave</option>
                                <option value="Casual Leave">Casual Leave</option>
                                <option value="Maternity Leave">Maternity Leave</option>
                                <option value="Emergency Leave">Emergency Leave</option>
                            </select>
                        </div>
                        <input type="hidden" class="form-control" id="emp_id" name="emp_id" value="<?php echo $employee_profile['emp_id']; ?>" required>

                        <!-- Start Date -->
                        <div class="form-group">
                            <label for="startDate">Start Date</label>
                            <input type="date" class="form-control" id="startDate" name="start_date" required>
                        </div>

                        <!-- End Date -->
                        <div class="form-group">
                            <label for="endDate">End Date</label>
                            <input type="date" class="form-control" id="endDate" name="end_date" required>
                        </div>

                        <!-- Reason -->
                        <div class="form-group">
                            <label for="reason">Reason</label>
                            <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Provide a brief reason for the leave" required></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary btn-block">Submit Leave Request</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <!-- JavaScript for Attendance Submission and Show/Hide Leave Message -->
    <script>
        function toggleLeaveMessage() {
            const attendanceStatus = document.getElementById('attendanceStatus').value;
            const leaveMessageContainer = document.getElementById('leaveMessageContainer');
            leaveMessageContainer.style.display = attendanceStatus === 'Leave' ? 'block' : 'none';
        }
    </script>

    <!-- Optional Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>

</html>