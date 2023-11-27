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

    // Create an instance of the TableCreator class
    $tableCreator = new TableCreator($conn);

    // Attempt to authenticate the user
    $authenticated = $tableCreator->authenticateUser($username, $password);

    // Check if the authentication was successful
    if ($authenticated) {
        // Set user data in the session (you can adjust this based on your needs)
        $_SESSION['username'] = $username;

        // Redirect the user to a new page or perform any other actions
        header('Location: http://localhost/New_Avito/pages/products/product_dashboard.php');
        exit();
    } else {
        // Authentication failed, display an error message
        $errorMessage = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

<div class="w-1/3 p-8 bg-white shadow-md rounded-md">

    <img src="../../logo.png" alt="Logo" class="w-16 h-14 mx-auto mb-8">

    <h2 class="text-2xl mb-4 text-center">Sign In</h2>

    <?php if (isset($errorMessage)) : ?>
        <p class="text-red-500 mb-4"><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <form method="post" action="signin.php">
        <div class="mb-4">
            <label for="username" class="block text-sm font-medium text-gray-600">Username:</label>
            <input type="text" name="username" required class="w-full px-4 py-2 border rounded-md">
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-600">Password:</label>
            <input type="password" name="password" required class="w-full px-4 py-2 border rounded-md">
        </div>

        <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-md">Sign In</button>
    </form>

    <a href="../../index.php" class="text-blue-500 hover:underline flex items-center mb-4">
        Back to Home
    </a>

</div>

</body>
</html>
