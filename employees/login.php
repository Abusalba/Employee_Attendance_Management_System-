<?php
require '../config.php';
session_start();  // Start the session

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emp_email = trim($_POST['emp_email']);
    $password = trim($_POST['password']);

    if (empty($emp_email) || empty($password)) {
        $message = "Please fill in both fields.";
    } else {
        // Prepare SQL query with error handling
        $stmt = $conn->prepare("SELECT id, password FROM employee WHERE emp_email = ?");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);  // Output the error message if prepare fails
        }

        $stmt->bind_param("s", $emp_email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $hashed_password);
            $stmt->fetch();
            // var_dump($password);
            // var_dump($hashed_password);
            // var_dump(password_verify($password, $hashed_password));
            // Verify password
            if (password_verify($password, $hashed_password)) {
                $_SESSION['id'] = $id;
                $_SESSION['emp_email'] = $emp_email;
                header("Location: employee_dashboard.php");
                exit();
            } else {
                $message = "Incorrect password.";
            }
        } else {
            $message = "Username not found.";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h3 class="text-center mb-4">Employee Login</h3>
        <?php if (!empty($message)) { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php } ?>
        <form method="post" action="login.php">
            <div class="form-group">
                <label for="emp_email">Email:</label>
                <input type="email" name="emp_email" id="emp_email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

