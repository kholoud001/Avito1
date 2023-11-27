<?php
session_start();

// Include the TableCreator class
require_once '../../config/tables.php';
require_once '../../config/config.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Create an instance of the TableCreator class
    $tableCreator = new TableCreator($conn);

    // Determine which form was submitted (signup or signin)
    if (isset($_POST['signup'])) {
        // Attempt to create an admin user
        $message = $tableCreator->addAdmin($username, $email, $password, $phone);
        echo $message;
    }

    // Close the database connection
    $conn->close();
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

    <h2 class="text-2xl mb-4 text-center">Admin Signup</h2>

    <form method="post" action="admin_signup.php" class="space-y-4">
        <div>
            <label for="username" class="block text-sm font-medium text-gray-600">Username:</label>
            <input type="text" name="username" required class="w-full px-4 py-2 border rounded-md">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-600">Password:</label>
            <input type="password" name="password" required class="w-full px-4 py-2 border rounded-md">
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-600">Email:</label>
            <input type="email" name="email" required class="w-full px-4 py-2 border rounded-md">
        </div>

        <div>
            <label for="phone" class="block text-sm font-medium text-gray-600">Phone:</label>
            <input type="tel" name="phone" required class="w-full px-4 py-2 border rounded-md">
        </div>

        <button type="submit" name="signup" class="w-full bg-blue-500 text-white px-4 py-2 rounded-md">Sign Up</button>
    </form>

</div>

</body>
</html>
