<?php

session_start();

// Include the TableCreator class
require_once '../../config/tables.php';
require_once '../../config/config.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];

    // Create an instance of the TableCreator class
    $tableCreator = new TableCreator($conn);

    // Attempt to create a regular user
    $message = $tableCreator->createRegularUser($username, $email, $password, $phone);

    // Check if the user was created successfully
    if (strpos($message, "successfully") !== false) {
        // Set user data in the session (you can adjust this based on your needs)
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;

        // Redirect the user to a new page or perform any other actions
        header('Location: signin.php');
        exit();
    } else {
        // Output the result
        echo $message;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="w-1/3 p-8 bg-white shadow-md rounded-md">

    <img src="../../logo.png" alt="Logo" class="w-16 h-14 mx-auto mb-6">


    <h2 class="text-2xl mb-2 text-center">Sign Up</h2>

    <form method="post" action="signup.php" class="space-y-4">
        <div>
            <label for="username" class="block text-sm font-medium text-gray-600">Username:</label>
            <input type="text" name="username" required class="w-full px-4 py-2 border rounded-md">
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-600">Email:</label>
            <input type="email" name="email" required class="w-full px-4 py-2 border rounded-md">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-600">Password:</label>
            <input type="password" name="password" required class="w-full px-4 py-2 border rounded-md">
        </div>

        <div>
            <label for="phone" class="block text-sm font-medium text-gray-600">Phone:</label>
            <input type="text" name="phone" required class="w-full px-4 py-2 border rounded-md">
        </div>

        <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-md">Sign Up</button>
    </form>
    <a href="../../index.php" class="text-blue-500 hover:underline flex items-center mb-4">
        Back to Home
    </a>

</div>

</body>
</html>
