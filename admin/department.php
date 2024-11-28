<?php
include("../config.php");
include("header.php");
include("layout.php");
include("navbar.php");
include("footer.php");
include("dashboard.php");

// Fetch department-wise employee details
$stmt = $conn->prepare("SELECT
        emp_department, 
        GROUP_CONCAT(CONCAT(emp_name, ' (', emp_id, ')') SEPARATOR ', ') AS employee_list,
        COUNT(emp_id) AS total_employees
    FROM 
        employee
    GROUP BY 
        emp_department
    ORDER BY 
        emp_department ASC
");
$stmt->execute();
$result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees by Department</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Employee Details by Department</h1>

        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Department</th>
                    <th>Total Employees</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['emp_department']); ?></td>
                        <td><?php echo htmlspecialchars($row['total_employees']); ?></td>
                        <td>
                            <button class="btn btn-primary btn-sm" type="button" data-toggle="collapse" data-target="#details<?php echo htmlspecialchars($row['emp_department']); ?>" aria-expanded="false" aria-controls="details<?php echo htmlspecialchars($row['emp_department']); ?>">
                                View Employees
                            </button>
                            <div class="collapse mt-2" id="details<?php echo htmlspecialchars($row['emp_department']); ?>">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Employee ID</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $employees = explode(', ', $row['employee_list']);
                                        foreach ($employees as $employee):
                                            // Split each employee entry into name and ID
                                            preg_match('/(.+) \((.+)\)/', $employee, $matches);
                                            $name = $matches[1] ?? 'Unknown';
                                            $id = $matches[2] ?? 'Unknown';
                                        ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($name); ?></td>
                                                <td><?php echo htmlspecialchars($id); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Optional Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    
</body>
</html>
