<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to signin.php after logout
header("Location: admin_signin.php");
exit();
