<?php
include("../config.php");
include("header.php");
include("layout.php");
include("navbar.php");
include("footer.php");
include("dashboard.php");

// Fetch leave request data by joining employee and leave_request tables
$stmt = $conn->prepare(
    "SELECT 
        leave_request.id AS leave_id, 
        employee.emp_id, 
        employee.emp_name, 
        employee.emp_department, 
        leave_request.start_date, 
        leave_request.end_date, 
        leave_request.status, 
        leave_request.reason
    FROM 
        leave_request
    INNER JOIN 
        employee 
    ON 
        leave_request.emp_id = employee.emp_id
    ORDER BY 
        leave_request.start_date DESC"
);


if (!$stmt) {
    die("SQL error: " . $conn->error); // Print the error if `prepare()` fails
}

$stmt->execute();
$leave_requests = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Request Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Leave Request Dashboard</h1>
        </div>

        <!-- List of Leave Requests Section -->
        <section id="leaveRequestSection">
            <h3>List of Leave Requests</h3>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <!-- <th>#</th> -->
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Leave Message</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($leave_requests as $leave): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($leave["emp_id"]); ?></td>
                                <td><?php echo htmlspecialchars($leave["emp_name"]); ?></td>
                                <td><?php echo htmlspecialchars($leave["emp_department"]); ?></td>
                                <td><?php echo htmlspecialchars($leave["start_date"]); ?></td>
                                <td><?php echo htmlspecialchars($leave["end_date"]); ?></td>
                                <td><?php echo htmlspecialchars($leave["status"]); ?></td>
                                <td><?php echo htmlspecialchars($leave["reason"]); ?></td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Leave Request Actions">
                                        <button type="button" class="btn btn-success btn-sm approve-btn" data-id="<?php echo $leave['leave_id']; ?>">Approve</button>
                                        <button type="button" class="btn btn-danger btn-sm reject-btn" data-id="<?php echo $leave['leave_id']; ?>">Reject</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>
        </section>
    </div>

    <!-- Bootstrap JS libraries -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(".approve-btn").click(function() {
            const leaveId = $(this).data("id"); // Get `id` from data attribute

            if (confirm("Are you sure you want to approve this leave request?")) {
                $.ajax({
                    url: "approve_leave.php",
                    type: "POST",
                    data: {
                        id: leaveId // Send `id` in the POST request
                    },
                    success: function(response) {
                        alert("Leave request approved successfully!");
                        location.reload(); // Refresh the page to update the status
                    },
                    error: function() {
                        alert("An error occurred while approving the leave request.");
                    }
                });
            }
        });
    </script>

    <script>
        $(".reject-btn").click(function() {
            const leaveId = $(this).data("id"); // Get `id` from data attribute

            if (confirm("Are you sure you want to reject this leave request?")) {
                $.ajax({
                    url: "reject_leave.php",
                    type: "POST",
                    data: {
                        id: leaveId // Send `id` in the POST request
                    },
                    success: function(response) {
                        alert("Leave request rejected successfully!");
                        location.reload(); // Refresh the page to update the status
                    },
                    error: function() {
                        alert("An error occurred while rejecting the leave request.");
                    }
                });
            }
        });
    </script>


</body>

</html>