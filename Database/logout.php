<?php
// logout.php - Destroy the session and redirect to the role selection page
session_start();
session_unset();  // Remove all session variables
session_destroy();  // Destroy the session
header("Location: role_selection.php");  // Redirect to the role selection page
exit();
?>
