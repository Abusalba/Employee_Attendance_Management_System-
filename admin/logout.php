<?php
// Start the session
session_start();

// Destroy all session data
session_unset();  // Unset all session variables
session_destroy(); // Destroy the session

// Redirect to login page or homepage
header("Location: login.php");
exit;
?>