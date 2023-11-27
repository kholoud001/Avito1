<?php
// Include the TableCreator class
require_once '../../config/tables.php';
require_once '../../config/config.php';

// Create an instance of the TableCreator class
$tableCreator = new TableCreator($conn);

// Check if the form is submitted for deleting a regular user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteRegularUser'])) {
    // Get form data
    $usernameToDelete = $_POST['usernameToDelete'];

    // Attempt to delete the regular user
    $deleteUserMessage = $tableCreator->deleteRegularUser($usernameToDelete);

    // Output the result
    echo $deleteUserMessage;
}

// Get regular users with product counts
$regularUsersWithCounts = $tableCreator->getRegularUsersWithProductCounts();
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

<h2 class="text-2xl mb-4">Admin Dashboard</h2>

<!-- Display regular users with product counts -->
<table class="border border-collapse">
    <tr>
        <th class="border py-2 px-4">Username</th>
        <th class="border py-2 px-4">Product Count</th>
        <th class="border py-2 px-4">Actions</th>
    </tr>
    <?php foreach ($regularUsersWithCounts as $user) : ?>
        <tr class="border">
            <td class="py-2 px-4 border"><?= $user['username']; ?></td>
            <td class="py-2 px-4 border"><?= $user['product_count']; ?></td>
            <td class="py-2 px-4 border flex items-center space-x-2">
                <!-- "Modify" button -->
                <a href="admin_modify.php" class="bg-blue-500 text-white px-4 py-2 rounded">Modify User</a>
                <!-- "Delete" button -->
                <form method="post" action="admin_dashboard.php" class="ml-2">
                    <input type="hidden" name="usernameToDelete" value="<?php echo $user['username']; ?>">
                    <button type="submit" name="deleteRegularUser" class="bg-red-500 text-white px-4 py-2 rounded">Delete User</button>
                </form>

            </td>
        </tr>
    <?php endforeach; ?>
</table>

<a href="admin_logout.php" class="mt-8 text-blue-500 hover:underline">Logout</a>
<!-- "Add User" button to navigate to the form page -->
<a href="admin_add.php" class="absolute top-0 right-0 m-4 text-white bg-green-500 px-4 py-2 rounded">Add User</a>
</body>

</html>
