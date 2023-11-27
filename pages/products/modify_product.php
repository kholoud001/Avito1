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



/// Check if the form is submitted for modifying a product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifyProduct'])) {
    // Get form data
    $productIdToModify = $_POST['productIdToModify'];
    $newProductName = $_POST['newProductName'];
    $newDescription = $_POST['newDescription'];
    $newPrice = $_POST['newPrice'];
    $newImage = $_POST['newImage'];

    // Attempt to modify the product
    $modifyProductMessage = $tableCreator->modifyProduct(
        $productIdToModify,
        $newProductName,
        $newDescription,
        $newPrice,
        $newImage
    );

    // Output the result
    echo $modifyProductMessage;

    // Check if the modification was successful before redirecting
    if (strpos($modifyProductMessage, "successfully") !== false) {
        // Redirect to the product dashboard
        header('Location: product_dashboard.php');
        exit();
    }

    // If the modification was not successful, continue displaying the form



    // Refresh the product list after modifying a product
    $userProducts = $tableCreator->getProductsByUser($authenticatedUsername);

}

// Assuming $tableCreator is an instance of the TableCreator class
$id = $_GET['id'];
$row = $tableCreator->getProductByID($id);
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

    <h3 class="text-xl mt-2 mb-2">Modify Product</h3>

<!-- form fields for modifying a product -->
    <form method="post" action="modify_product.php" class="w-1/2 bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <input type="hidden" name="productIdToModify" value="<?php echo $row['product_id']; ?>">
        <div class="mb-4">
            <label for="newProductName" class="block text-gray-700 text-sm font-bold mb-2">New Product Name:</label>
            <input type="text" name="newProductName" id="newProductName" placeholder="New Product Name" value="<?php echo $row['product_name']; ?>" required
                   class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <div class="mb-4">
            <label for="newDescription" class="block text-gray-700 text-sm font-bold mb-2">New Description:</label>
            <textarea name="newDescription" id="newDescription" placeholder="New Description" required
                      class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?php echo $row['description']; ?></textarea>
        </div>
        <div class="mb-4">
            <label for="newPrice" class="block text-gray-700 text-sm font-bold mb-2">New Price:</label>
            <input type="text" name="newPrice" id="newPrice" placeholder="New Price" value="<?php echo $row['price']; ?>" required
                   class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <div class="mb-4">
            <label for="newImage" class="block text-gray-700 text-sm font-bold mb-2">New Image:</label>
            <input type="text" name="newImage" id="newImage" placeholder="New Image" value="<?php echo $row['image']; ?>"
                   class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <div class="flex items-center justify-between">
            <input type="submit" name="modifyProduct" value="Modify" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded focus:outline-none focus:shadow-outline">
        </div>
    </form>
    <a href="product_dashboard.php" class="text-blue-500 hover:underline mb-4 inline-block" > Return to dashboard </a>

<?php
//print_r($row); ?>

</body>
</html>