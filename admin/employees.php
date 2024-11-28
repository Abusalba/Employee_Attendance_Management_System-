<?php
include("../config.php");
include("header.php");
include("layout.php");
include("navbar.php");
include("footer.php");
include("dashboard.php");




// Fetch all blog posts from the database
$stmt = $conn->prepare("SELECT * FROM employee");
$stmt->execute();
$result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

?>

<!----------------------------------------- HTML CODE ------------------------------------------------->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Employee Management Dashboard</h1>
            <!-- Add Employee Button -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEmployeeModal">
                Add Employee
            </button>
        </div>

        <!-- List of Employees Section -->
        <section id="listEmployeeSection">
            <h3>List of Employees</h3>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Department</th>
                            <th>Role</th>
                            <th>Joining Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['emp_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['emp_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['emp_email']); ?></td>
                                <td><?php echo htmlspecialchars($row['emp_phone']); ?></td>
                                <td><?php echo htmlspecialchars($row['emp_department']); ?></td>
                                <td><?php echo htmlspecialchars($row['emp_role']); ?></td>
                                <td><?php echo htmlspecialchars($row['emp_joining_date']); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="loadData(
        '<?php echo htmlspecialchars($row['id']); ?>', 
        '<?php echo htmlspecialchars($row['emp_id']); ?>', 
        '<?php echo htmlspecialchars($row['emp_name']); ?>', 
        '<?php echo htmlspecialchars($row['emp_email']); ?>', 
        '<?php echo htmlspecialchars($row['emp_phone']); ?>', 
        '<?php echo htmlspecialchars($row['emp_department']); ?>', 
        '<?php echo htmlspecialchars($row['emp_role']); ?>', 
        '<?php echo htmlspecialchars($row['emp_joining_date']); ?>'
    )">Edit</button>
                                    <a class="btn btn-sm btn-danger" href="delete.php?id=<?php echo $row['id']; ?>">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <!-- Edit Employee Modal -->
    <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEmployeeModalLabel">Edit Employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editEmployeeForm" action="edit.php" method="post">
                        <input type="hidden" id="employee_id" name="id">

                        <div class="form-group">
                            <label for="employeeId">Employee ID</label>
                            <input type="text" class="form-control" id="employeeId" placeholder="Enter Employee ID" name="emp_id">
                        </div>
                        <div class="form-group">
                            <label for="employeeName">Name</label>
                            <input type="text" class="form-control" id="employeeName" placeholder="Enter Employee Name" name="emp_name">
                        </div>
                        <div class="form-group">
                            <label for="employeeEmail">Email</label>
                            <input type="email" class="form-control" id="employeeEmail" placeholder="Enter Employee Email" name="emp_email">
                        </div>
                        <div class="form-group">
                            <label for="employeeEmail">Password</label>
                            <input type="password" class="form-control" id="employeeEmail" placeholder="Enter Employee Password" name="password">
                        </div>
                        <div class="form-group">
                            <label for="employeePhone">Phone Number</label>
                            <input type="tel" class="form-control" id="employeePhone" placeholder="Enter Phone Number" name="emp_phone">
                        </div>
                        <div class="form-group">
                            <label for="employeeDepartment">Department</label>
                            <select class="form-control" id="employeeDepartment" name="emp_department" required>
                                <option value="" disabled selected>Select Department</option>
                                <option value="HR">HR</option>
                                <option value="Computer Science">Computer Science</option>
                                <option value="Information Technology">Information Technology</option>
                                <option value="Finance">Finance</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="employeeRole">Role</label>
                            <input type="text" class="form-control" id="employeeRole" placeholder="Enter Role" name="emp_role">
                        </div>
                        <div class="form-group">
                            <label for="employeeJoiningDate">Joining Date</label>
                            <input type="date" class="form-control" id="employeeJoiningDate" placeholder="Select Joining Date" name="emp_joining_date">
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Employee Modal -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEmployeeModalLabel">Add Employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addEmployeeForm" action="add_employee.php" method="post">
                        <div class="form-group">
                            <label for="employeeId">Employee ID</label>
                            <input type="text" class="form-control" id="employeeId" placeholder="Enter Employee ID" name="emp_id">
                        </div>
                        <div class="form-group">
                            <label for="employeeName">Name</label>
                            <input type="text" class="form-control" id="employeeName" placeholder="Enter Employee Name" name="emp_name">
                        </div>
                        <div class="form-group">
                            <label for="employeeEmail">Email</label>
                            <input type="email" class="form-control" id="employeeEmail" placeholder="Enter Employee Email" name="emp_email">
                        </div>
                        <div class="form-group">
                            <label for="employeeEmail">Password</label>
                            <input type="password" class="form-control" id="employeeEmail" placeholder="Enter Employee Password" name="password">
                        </div>
                        <div class="form-group">
                            <label for="employeePhone">Phone Number</label>
                            <input type="tel" class="form-control" id="employeePhone" placeholder="Enter Phone Number" name="emp_phone">
                        </div>
                        <div class="form-group">
                            <label for="employeeDepartment">Department</label>
                            <select class="form-control" id="employeeDepartment" name="emp_department" onchange="handleDepartmentChange()" required>
                                <option value="" disabled selected>Select Department</option>
                                <option value="HR">HR</option>
                                <option value="Computer Science">Computer Science</option>
                                <option value="Information Technology">Information Technology</option>
                                <option value="Finance">Finance</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <!-- Text input for custom department -->
                        <div class="form-group" id="customDepartmentDiv" style="display: none;">
                            <label for="customDepartment">Custom Department</label>
                            <input type="text" class="form-control" id="customDepartment" placeholder="Enter Department" name="custom_department">
                        </div>
                        <div class="form-group">
                            <label for="employeeRole">Role</label>
                            <input type="text" class="form-control" id="employeeRole" placeholder="Enter Role" name="emp_role">
                        </div>
                        <div class="form-group">
                            <label for="employeeJoiningDate">Joining Date</label>
                            <input type="date" class="form-control" id="employeeJoiningDate" placeholder="Select Joining Date" name="emp_joining_date">
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary">Add Employee</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    

    <!-- Bootstrap JS libraries -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add this JavaScript code after your Bootstrap JS libraries
        function loadData(id, emp_id, emp_name, emp_email, emp_phone, emp_department, emp_role, emp_joining_date) {
            // Set values in the edit form
            document.getElementById('employee_id').value = id;
            document.getElementById('employeeId').value = emp_id;
            document.getElementById('employeeName').value = emp_name;
            document.getElementById('employeeEmail').value = emp_email;
            document.getElementById('employeePhone').value = emp_phone;
            document.getElementById('employeeDepartment').value = emp_department;
            document.getElementById('employeeRole').value = emp_role;
            document.getElementById('employeeJoiningDate').value = emp_joining_date;

            // Show the modal
            $('#editEmployeeModal').modal('show');
        }
    </script>
</body>

</html>