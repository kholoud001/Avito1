<?php
// Start the session
session_start();

// Include the TableCreator class
require_once '../../config/tables.php';
require_once '../../config/config.php';

// Check if the user is authenticated
if (!isset($_SESSION['username'])) {
    // If not authenticated, redirect to the sign-in page
    header('Location: signin.php');
    exit();
}

// Get the authenticated username
$authenticatedUsername = $_SESSION['username'];

// Create an instance of the TableCreator class
$tableCreator = new TableCreator($conn);

// Get the user details
$userDetails = $tableCreator->getUserDetails($authenticatedUsername);

// Check if the form is submitted for modifying user details
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifyUser'])) {
    // Get form data
    $newUsername = $_POST['newUsername'];
    $newPassword = $_POST['newPassword'];
    $newEmail = $_POST['newEmail'];
    $newPhone = $_POST['newPhone'];

    // Attempt to modify user details
    $modifyUserMessage = $tableCreator->modifyUser(
        $authenticatedUsername,
        $newUsername,
        $newPassword,
        $newEmail,
        $newPhone
    );

    // Output the result
    echo $modifyUserMessage;

    // Refresh the user details after modifying
    $userDetails = $tableCreator->getUserDetails($newUsername);
}

// Check if the form is submitted for deleting the account
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteAccount'])) {
    // Attempt to delete the user account
    $deleteAccountMessage = $tableCreator->deleteRegularUser($authenticatedUsername);

    // Output the result
    echo $deleteAccountMessage;

    // Redirect to the sign-in page after deleting the account
    header('Location: signin.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify User Details</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-8">

<h2 class="text-2xl mb-4">Modify User Details</h2>

<!-- Link to go back to the product dashboard -->
<a href="../products/product_dashboard.php" class="text-blue-500 hover:underline mb-4 inline-block">Back to Product Dashboard</a>

<!-- User details form -->
<form method="post" action="modify_user.php" class="w-1/2 bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <label for="newUsername" class="block mb-2 text-sm font-medium text-gray-600">New Username:</label>
    <input type="text" name="newUsername" value="<?php echo $userDetails['username']; ?>" required class="w-full px-4 py-2 mb-4 border focus:outline-none focus:shadow-outline">

    <label for="newPassword" class="block mb-2 text-sm font-medium text-gray-600">New Password:</label>
    <input type="password" name="newPassword" required class="w-full px-4 py-2 mb-4 border focus:outline-none focus:shadow-outline">

    <label for="newEmail" class="block mb-2 text-sm font-medium text-gray-600">New Email:</label>
    <input type="email" name="newEmail" value="<?php echo $userDetails['email']; ?>" required class="w-full px-4 py-2 mb-4 border focus:outline-none focus:shadow-outline">

    <label for="newPhone" class="block mb-2 text-sm font-medium text-gray-600">New Phone:</label>
    <input type="text" name="newPhone" value="<?php echo $userDetails['phone']; ?>" required class="w-full px-4 py-2 mb-4 border focus:outline-none focus:shadow-outline">

    <input type="submit" name="modifyUser" value="Modify Information" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded focus:outline-none focus:shadow-outline">
</form>


<!-- Form for deleting the account -->
<form method="post" action="modify_user.php" class="mt-4">
    <input type="hidden" name="usernameToDelete" value="<?php echo $authenticatedUsername; ?>">
    <input type="submit" name="deleteAccount" value="Delete Account" class="bg-red-500 text-white px-4 py-2 rounded">
</form>

</body>
</html>
