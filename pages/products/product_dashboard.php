<?php
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

// Get products associated with the authenticated user
$userProducts = $tableCreator->getProductsByUser($authenticatedUsername);




// Check if the form is submitted for deleting a product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteProduct'])) {
    // Get form data
    $productIdToDelete = $_POST['productIdToDelete'];

    // Attempt to delete the product
    $deleteProductMessage = $tableCreator->deleteProduct($productIdToDelete);

    // Output the result
    echo $deleteProductMessage;

    // Refresh the product list after deleting a product
    $userProducts = $tableCreator->getProductsByUser($authenticatedUsername);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Display</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-8">

<h2 class="text-2xl mb-4">Welcome, <?php echo $authenticatedUsername; ?>!</h2>

<!-- Add link/button to modify user details -->
<a href="../users/modify_user.php" class="text-blue-500 hover:underline mb-4 inline-block">Modify User Details</a>

<!-- Add link/button to sign out -->
<a href="../users/logout.php" class="text-red-500 hover:underline mb-4 inline-block">Log Out</a>

<?php if (!empty($userProducts)) : ?>
    <h3 class="text-xl mb-2">Your Products:</h3>
    <table class="w-full border-collapse border border-gray-300">
        <thead>
        <tr class="bg-gray-100">
            <th class="py-2 px-4 border">Product Name</th>
            <th class="py-2 px-4 border">Description</th>
            <th class="py-2 px-4 border">Price</th>
            <th class="py-2 px-4 border">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($userProducts as $product) : ?>
            <tr class="border">
                <td class="py-2 px-4 border"><?php echo $product['product_name']; ?></td>
                <td class="py-2 px-4 border"><?php echo $product['description']; ?></td>
                <td class="py-2 px-4 border"><?php echo $product['price']; ?></td>
                <td class="py-2 px-12 border flex items-center">
                    <!-- Form fields for modifying a product -->
                    <!--<form method="post" action="modify_product.php" class="ml-2">
                        <input type="hidden" name="productIdToModify" value="<?php echo $product['product_id']; ?>">
                        <input type="submit" name="modifyProduct" value="Modify" class="bg-blue-500 text-white px-4 py-2 rounded">
                    </form>-->
                    <a href="modify_product.php?id=<?php echo $product['product_id']; ?>" class="bg-blue-500 text-white px-4 py-2 rounded">Modify</a>

                    <!-- Form fields for deleting a product -->
                    <form method="post" action="product_dashboard.php" class="ml-2">
                        <input type="hidden" name="productIdToDelete" value="<?php echo $product['product_id']; ?>">
                        <input type="submit" name="deleteProduct" value="Delete" class="bg-red-500 text-white px-4 py-2 rounded">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else : ?>
    <p class="text-xl">You have no products added yet.</p>
<?php endif; ?>

<!-- "Add Product" button to navigate to the form page -->
<a href="add_product.php" class="absolute top-0 right-0 m-4 text-white bg-green-500 px-4 py-2 rounded">Add Product</a>
</body>
</html>

