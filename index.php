<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Display</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<!-- Navbar -->
<nav class="bg-blue-600 p-4 text-white">
    <div class="flex items-center">
        <!-- Logo -->
        <img src="logo.png" alt="Logo" class="h-12 w-16mr-2">

        <!-- Sign up and Sign in links -->
        <div class="ml-auto">
            <a href="pages/users/signup.php" class="hover:underline">Sign Up</a>
            <span class="mx-2">|</span>
            <a href="pages/users/signin.php" class="hover:underline">Sign In</a>
        </div>
    </div>
</nav>

<!-- Content -->
<div class="container mx-auto p-8">

    <?php

    // Include the TableCreator class
    require_once 'config/config.php';
    require_once 'config/tables.php';

    $tableCreator = new TableCreator($conn);

    $userProducts = $tableCreator->getProductsWithCreators();

    // Check if there are any products
    if (!empty($userProducts)) {
        foreach ($userProducts as $product) {
            ?>
            <div class="border p-4 mb-4">
                <!-- Product Image -->
                <img src="<?php echo $product['image']; ?>" alt="Product Image" class="w-full h-80 object-cover mb-2">

                <!-- Product Name -->
                <p class="text-lg font-bold mb-2"><?php echo $product['product_name']; ?></p>

                <!-- Product Price -->
                <p class="text-green-500 font-bold mb-2"><?php echo $product['price']; ?></p>

                <!-- Creator Name -->
                <p class="text-gray-500">Created by: <?php echo $product['creator_name']; ?></p>
            </div>
            <?php
        }
    } else {
        // No products found
        echo "<p>No products available.</p>";
    }
    ?>

</div>
<!-- Footer -->
<footer class="bg-gray-800 text-white text-center py-4">
    &copy; 2023 Avito. All rights reserved.
</footer>
</body>
</html>
