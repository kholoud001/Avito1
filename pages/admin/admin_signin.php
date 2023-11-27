<?php
session_start();

// Include the TableCreator class
require_once '../../config/tables.php';
require_once '../../config/config.php';

// Create an instance of the TableCreator class
$tableCreator = new TableCreator($conn);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['signin'])) {
        // Get form data
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Attempt to authenticate the admin user
        $authenticated = $tableCreator->authenticateAdmin($username, $password);

        if ($authenticated) {
            // Successful signin, you can redirect to a dashboard or perform other actions
            //echo "Admin Signin successful!";
            header('Location: admin_dashboard.php');
            exit();
        } else {
            // Failed signin, display an error message
            $errorMessage = "Invalid username or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Signup and Signin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="bg-white p-8 rounded-md shadow-md w-1/3">

    <h2 class="text-2xl mb-4 text-center">Admin Signin</h2>

    <?php if (isset($errorMessage)) : ?>
        <p class="text-red-500 mb-4"><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <form method="post" action="admin_signin.php" class="space-y-4">
        <div>
            <label for="username" class="block text-sm font-medium text-gray-600">Username:</label>
            <input type="text" name="username" required class="w-full px-4 py-2 border rounded-md">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-600">Password:</label>
            <input type="password" name="password" required class="w-full px-4 py-2 border rounded-md">
        </div>

        <button type="submit" name="signin" class="w-full bg-blue-500 text-white px-4 py-2 rounded-md">Sign In</button>
    </form>

</div>

</body>
</html>
