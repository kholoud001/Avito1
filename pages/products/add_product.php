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


// Check if the form is submitted for adding a new product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addProduct'])) {
    // Get form data
    $productName = $_POST['productName'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_POST['image'];

    // Attempt to add a new product
    $addProductMessage = $tableCreator->addProduct($authenticatedUsername, $productName,$description, $price, $image);
    // Output the result
    echo $addProductMessage;

    // Refresh the product list after adding a product
    /** @var TYPE_NAME $userProducts */
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
<!-- Form to add new product -->
    <h3 class="text-xl mt-2 mb-2">Add New Product</h3>
<form method="post" action="add_product.php" class="max-w-md bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <label for="productName" class="block text-sm font-medium text-gray-600">Product Name:</label>
    <input type="text" name="productName" required class="mt-1 p-2 border rounded w-full focus:outline-none focus:shadow-outline">

    <label for="description" class="block text-sm font-medium text-gray-600 mt-4">Description:</label>
    <textarea name="description" required class="mt-1 p-2 border rounded w-full focus:outline-none focus:shadow-outline"></textarea>

    <label for="price" class="block text-sm font-medium text-gray-600 mt-4">Price:</label>
    <input type="text" name="price" required class="mt-1 p-2 border rounded w-full focus:outline-none focus:shadow-outline">

    <label for="image" class="block text-sm font-medium text-gray-600 mt-4">Image:</label>
    <input type="text" name="image" class="mt-1 p-2 border rounded w-full focus:outline-none focus:shadow-outline">

    <input type="submit" name="addProduct" value="Add Product" class="mt-4 bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded focus:outline-none focus:shadow-outline">
</form>


<a href="product_dashboard.php" class="text-blue-500 hover:underline mb-4 inline-block"> Return to dashboard </a>
</body>
</html>