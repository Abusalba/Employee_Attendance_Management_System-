<?php
include("../config.php");

// Assuming you have a session for the logged-in employee
session_start();
$id = $_SESSION['emp_id']; // Fetch employee ID from session

// Fetch leave request data for the employee
$stmt = $conn->prepare("SELECT start_date, end_date, reason, status FROM leave_request WHERE id = ? ORDER BY start_date DESC");
$stmt->bind_param("s", $id);
$stmt->execute();
$leave_requests = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Calculate total leave days
$total_leave_days = 0;
foreach ($leave_requests as $request) {
    $start_date = new DateTime($request['start_date']);
    $end_date = new DateTime($request['end_date']);
    $interval = $start_date->diff($end_date);
    $total_leave_days += $interval->days + 1; // Including the start date
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Leave Requests</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">My Leave Requests</h2>
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
                            <td colspan="5" class="text-center">No leave requests found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
