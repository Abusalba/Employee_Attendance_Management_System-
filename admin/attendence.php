<?php 
include ("../config.php");
include("header.php");
include("layout.php");
include("navbar.php");
include("footer.php");
include("dashboard.php");

// Fetch all  from the database 
$stmt = $conn->prepare(
    " SELECT 
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
    ORDER BY attendance.date DESC
");
$stmt->execute();
$attendanceData = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Attendance Management Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Main Content Area -->
            <main class="col-12 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Attendance Management Dashboard</h1>
                </div>

                <!-- Filter Form -->
                <div class="mb-3">
                    <form class="form-inline">
                        <div class="form-group mr-2">
                            <label for="filterEmployeeId" class="mr-2">Employee ID</label>
                            <input type="text" class="form-control" id="filterEmployeeId" placeholder="Enter Employee ID">
                        </div>
                        <div class="form-group mr-2">
                            <label for="filterDate" class="mr-2">Date</label>
                            <input type="date" class="form-control" id="filterDate">
                        </div>
                        <div class="form-group mr-2">
                            <label for="filterStatus" class="mr-2">Status</label>
                            <select class="form-control" id="filterStatus">
                                <option value="">All</option>
                                <option value="Present">Present</option>
                                <option value="Absent">Absent</option>
                                <option value="Late">Late</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="filterAttendance()">Filter</button>
                        <button type="button" class="btn btn-secondary ml-2" onclick="resetFilters()">Reset</button>
                    </form>
                </div>

                <!-- Attendance Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="attendanceTable">
                        <thead class="thead-dark">
                            <tr>
                                <!-- <th>ID</th> -->
                                <th>Employee ID</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($attendanceData as $attendance): ?>
                            <tr>
                                <!-- <td><?php echo htmlspecialchars($attendance['id']);?></td> -->
                                <td><?php echo htmlspecialchars($attendance['emp_id']);?></td>
                                <td><?php echo htmlspecialchars($attendance['emp_name']);?></td>
                                <td><?php echo htmlspecialchars($attendance['emp_department']);?></td>
                                <td><?php echo htmlspecialchars($attendance['date']);?></td>
                                <td> <span class="badge badge-<?php 
                                                echo $attendance["status"] == "Present" ? "success" : 
                                                     ($attendance["status"] == "Absent" ? "warning": 
                                                     ($attendance["status"] == "Late" ? "secondary" :
                                                     ($attendance["status"] == "Leave" ? "danger" : "danger"))); ?> badge-status">
                                                <?php echo htmlspecialchars($attendance["status"]); ?>
                                            </span></td>
                                <td>
                                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editAttendanceModal">Edit</button>
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            
                            
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

   <!-- Edit Attendance Modal -->
<div class="modal fade" id="editAttendanceModal" tabindex="-1" aria-labelledby="editAttendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAttendanceModalLabel">Edit Attendance</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="employeeName">Employee Name</label>
                        <input type="text" class="form-control" id="employeeName" disabled>
                    </div>
                    <div class="form-group">
                        <label for="attendanceDate">Date</label>
                        <input type="date" class="form-control" id="attendanceDate">
                    </div>
                    <div class="form-group">
                        <label for="attendanceStatus">Status</label>
                        <select class="form-control" id="attendanceStatus">
                            <option value="Present">Present</option>
                            <option value="Absent">Absent</option>
                            <option value="Late">Late</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>


    <!-- Optional JavaScript; Bootstrap & jQuery libraries -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <script>
        function filterAttendance() {
            const empId = $('#filterEmployeeId').val().toLowerCase();
            const date = $('#filterDate').val();
            const status = $('#filterStatus').val();

            $('#attendanceTable tbody tr').each(function() {
                const rowEmpId = $(this).find('td:eq(1)').text().toLowerCase();
                const rowDate = $(this).find('td:eq(4)').text();
                const rowStatus = $(this).find('td:eq(5) .badge').text();

                if (
                    (empId === "" || rowEmpId.includes(empId)) &&
                    (date === "" || rowDate === date) &&
                    (status === "" || rowStatus === status)
                ) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        function resetFilters() {
            $('#filterEmployeeId').val('');
            $('#filterDate').val('');
            $('#filterStatus').val('');
            $('#attendanceTable tbody tr').show();
        }

        
    </script>
    <script>
    let currentEditRow = null; // Track the row being edited

    $(document).on('click', '.btn-primary[data-target="#editAttendanceModal"]', function () {
        currentEditRow = $(this).closest('tr');
        const empId = currentEditRow.find('td:eq(0)').text();
        const name = currentEditRow.find('td:eq(1)').text();
        const date = currentEditRow.find('td:eq(3)').text();
        const status = currentEditRow.find('td:eq(4) .badge').text();

        // Populate the modal fields
        $('#employeeName').val(name);
        $('#attendanceDate').val(date);
        $('#attendanceStatus').val(status);
        $('#editAttendanceModal').data('empId', empId); // Save empId in modal for update
    });

    $('#editAttendanceModal form').submit(function (e) {
        e.preventDefault();

        const empId = $('#editAttendanceModal').data('empId');
        const date = $('#attendanceDate').val();
        const status = $('#attendanceStatus').val();

        $.ajax({
            url: 'update_attendance.php',
            type: 'POST',
            data: { emp_id: empId, date: date, status: status },
            success: function (response) {
                const res = JSON.parse(response);

                if (res.success) {
                    // Update the table row visually
                    currentEditRow.find('td:eq(4) .badge')
                        .text(status)
                        .attr('class', `badge badge-${status === "Present" ? "success" : 
                            (status === "Absent" ? "warning" : 
                            (status === "Late" ? "secondary" : "danger"))} badge-status`);
                    
                    $('#editAttendanceModal').modal('hide');
                } else {
                    alert('Error: ' + res.message);
                }
            },
            error: function () {
                alert('An error occurred while updating attendance.');
            }
        });
    });

</script>

</body>
</html>
