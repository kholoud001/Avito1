<?php

// Include the TableCreator class
require_once '../../config/tables.php';
require_once '../../config/config.php';

// Create an instance of the TableCreator class
$tableCreator = new TableCreator($conn);

// Check if the form is submitted for adding a new regular user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addRegularUser'])) {
    // Get form data
    $newUsername = $_POST['newUsername'];
    $newEmail = $_POST['newEmail'];
    $newPassword = $_POST['newPassword'];
    $newPhone = $_POST['newPhone'];

    // Attempt to add a new regular user
    $addUserMessage = $tableCreator->createRegularUser($newUsername, $newEmail, $newPassword, $newPhone);

    // Output the result
    echo $addUserMessage;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="p-8">

<h3 class="mt-8 mb-4 text-xl">Add New Regular User</h3>
<form method="post" action="admin_add.php" class="w-full max-w-md bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <!-- Add form fields for new regular user -->
    <label for="newUsername" class="block text-sm font-medium text-gray-600">Username:</label>
    <input type="text" name="newUsername" required class="w-full px-4 py-2 mb-2 border focus:outline-none focus:shadow-outline">

    <label for="newEmail" class="block text-sm font-medium text-gray-600">Email:</label>
    <input type="email" name="newEmail" required class="w-full px-4 py-2 mb-2 border focus:outline-none focus:shadow-outline">

    <label for="newPassword" class="block text-sm font-medium text-gray-600">Password:</label>
    <input type="password" name="newPassword" required class="w-full px-4 py-2 mb-2 border focus:outline-none focus:shadow-outline">

    <label for="newPhone" class="block text-sm font-medium text-gray-600">Phone:</label>
    <input type="text" name="newPhone" required class="w-full px-4 py-2 mb-2 border focus:outline-none focus:shadow-outline">

    <input type="submit" name="addRegularUser" value="Add User" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded focus:outline-none focus:shadow-outline">
</form>

<a href="admin_dashboard.php" class="text-blue-500 hover:underline inline-block"> Return to dashboard  </a>
<a href="admin_logout.php" class=" text-red-500 hover:underline">Logout</a>

</body>
</html>
