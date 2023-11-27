<?php

require_once ("config.php");


class TableCreator
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
//User Table
    public function createUsersTable()
    {
        $sql_create_table_users = "
            CREATE TABLE IF NOT EXISTS Users (
                user_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(30) NOT NULL,
                password VARCHAR(100) NOT NULL,
                email VARCHAR(255) NOT NULL,
                role ENUM('admin', 'regular') NOT NULL,
                phone VARCHAR(15) NOT NULL
            )";

        if (!mysqli_query($this->conn, $sql_create_table_users)) {
            echo "Error creating Users table: " . mysqli_error($this->conn);
        }
    }
    //*************************   User Creation     **************************************/
    //user with role
    /**
     * @param $username
     * @param $email
     * @param $password
     * @param $phone
     * @param $role
     * @return string|void
     */
    public function createUserWithRole($username, $email, $password, $phone, $role)
    {
        // Validate input
        if (empty($username) || empty($email) || empty($password) || empty($phone) || empty($role)) {
            echo "Please fill in all fields.";
            exit();
        }

        // Validate email address
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email address.";
            exit();
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL statement
        $sql = "INSERT INTO Users (username, email, password, phone, role) VALUES (?, ?, ?, ?, ?)";

        // Use prepared statements to prevent SQL injection
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Error in preparing the statement: " . $this->conn->error);
        }

        // Bind parameters and execute the statement
        $stmt->bind_param("sssss", $username, $email, $hashedPassword, $phone, $role);
        $stmt->execute();

        // Check for success and return a message
        if ($stmt->affected_rows > 0) {
            return "User created successfully!";
        } else {
            return "Error creating user: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }

    //Create regular user

    /**
     * @param $username
     * @param $email
     * @param $password
     * @param $phone
     * @return string|void
     */
    public function createRegularUser($username, $email, $password, $phone)
    {
        return $this->createUserWithRole($username, $email, $password, $phone, 'regular');
    }


    //Add Admin
    /**
     * @param $username
     * @param $email
     * @param $password
     * @param $phone
     * @return string|void
     */
    public function addAdmin($username, $email, $password, $phone)
    {
        return $this->createUserWithRole($username, $email, $password, $phone, 'admin');
    }

    //******************************   Get admin     ***************************
    // Authenticate admin user
    /**
     * @param $username
     * @param $password
     * @return bool|void
     */
    public function authenticateAdmin($username, $password)
    {
        // Prepare the SQL statement
        $sql="SELECT * FROM Users WHERE username = ? AND role = 'admin'";
        // Use prepared statements to prevent SQL injection
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Error in preparing the statement: " . $this->conn->error);
        }
        // Bind parameters and execute the statement
        $stmt->bind_param("s", $username);
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Check if a row is returned
        if ($result->num_rows > 0) {
            $adminData = $result->fetch_assoc();
            // Verify the password
            if (password_verify($password, $adminData['password'])) {
                // Password is correct
                return true;
            }
        }
        // Close the statement
        $stmt->close();
        // If no matching admin is found or the password is incorrect
        return false;
    }

    //******************************   Get regular user     ***************************
    // Authenticate a regular user
    /**
     * @param $username
     * @param $password
     * @return bool|void
     */
    public function authenticateUser($username, $password)
    {
        // Prepare the SQL statement
        $sql = "SELECT * FROM Users WHERE username = ? AND role = 'regular'";

        // Use prepared statements to prevent SQL injection
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Error in preparing the statement: " . $this->conn->error);
        }
        // Bind parameters and execute the statement
        $stmt->bind_param("s", $username);
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Check if a row is returned
        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();
            // Verify the password
            if (password_verify($password, $userData['password'])) {
                // Password is correct
                return true;
            }
        }
        // Close the statement
        $stmt->close();
        // If no matching user is found or the password is incorrect
        return false;
    }

    //*******************************     getuser details      ****************************************
    /**
     * @param $username
     * @return void
     */
    public function getUserDetails($username)
    {
        // Prepare the SQL statement
        $sql = "SELECT * FROM Users WHERE username = ?";

        // Use prepared statements to prevent SQL injection
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Error in preparing the statement: " . $this->conn->error);
        }
        // Bind parameters and execute the statement
        $stmt->bind_param("s", $username);
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch user details
        $userDetails = $result->fetch_assoc();
        // Close the statement
        $stmt->close();
        return $userDetails;
    }


    //*****************      Get regular users with product counts        *****************************
    /**
     * @return void
     */
    public function getRegularUsersWithProductCounts()
    {
        // Prepare the SQL statement to join Users and Products tables
        $sql = "SELECT Users.username, COUNT(Products.product_id) AS product_count
                FROM Users
                LEFT JOIN Products ON Users.user_id = Products.creator_id
                WHERE Users.role = 'regular'
                GROUP BY Users.user_id";

        // Use prepared statements to prevent SQL injection
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Error in preparing the statement: " . $this->conn->error);
        }
        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch all regular users with product counts
        $usersWithProductCounts = $result->fetch_all(MYSQLI_ASSOC);

        // Close the statement
        $stmt->close();

        return $usersWithProductCounts;
    }


    //*************************   User display    **************************************/
    // Get regular users
    /**
     * @return array
     */
    public function getRegularUsers() {
        $users = array();


        $query = "SELECT * FROM Users WHERE  role = 'regular'";

        $result = $this->conn->query($query);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        return $users;
    }


    //*************************  Regular User update   **************************************/
    // Modify a regular user
    /**
     * @param $userId
     * @param $newUsername
     * @param $newEmail
     * @param $newPhone
     * @return string|void
     */
    public function modifyRegularUser($userId, $newUsername, $newEmail, $newPhone)
    {
        // Prepare the SQL statement
        $sql = "UPDATE Users SET username = ?, email = ?, phone = ? WHERE user_id = ? AND role = 'regular'";

        // Use prepared statements to prevent SQL injection
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Error in preparing the statement: " . $this->conn->error);
        }
        // Bind parameters and execute the statement
        $stmt->bind_param("sssi", $newUsername, $newEmail, $newPhone, $userId);
        $stmt->execute();

        // Check for success and return a message
        if ($stmt->affected_rows > 0) {
            return "Regular user modified successfully!";
        } else {
            return "Error modifying regular user: " . $stmt->error;
        }
        // Close the statement
        $stmt->close();
    }



    /////////////////////////////////////// For profile  /////////////////////////////////////////////
    ///
    /**
     * @param $authenticatedUsername
     * @param $newUsername
     * @param $newPassword
     * @param $newEmail
     * @param $newPhone
     * @return string|void
     */
    public function modifyUser($authenticatedUsername, $newUsername, $newPassword, $newEmail, $newPhone)
    {
        // Validate input, perform necessary checks

        // Hash the new password before storing it
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Prepare the SQL statement
        $sql = "UPDATE Users SET username = ?, password = ?, email = ?, phone = ? WHERE username = ?";

        // Use prepared statements to prevent SQL injection
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Error in preparing the statement: " . $this->conn->error);
        }

        // Bind parameters and execute the statement
        $stmt->bind_param("sssss", $newUsername, $hashedPassword, $newEmail, $newPhone, $authenticatedUsername);
        $stmt->execute();

        // Check if the update was successful
        if (!$stmt->affected_rows > 0) {
            $resultMessage = "User details modified successfully.";
        } else {
            $resultMessage = "Error modifying user details.";
        }

        // Close the statement
        $stmt->close();

        return $resultMessage;
    }



    //********************************    Get username     ********************************************
    // Get user ID by username
    /**
     * @param $username
     * @return mixed|void
     */
    public function getUserIdByUsername($username)
    {
        // Prepare the SQL statement
        $sql = "SELECT user_id FROM Users WHERE username = ?";

        // Use prepared statements to prevent SQL injection
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Error in preparing the statement: " . $this->conn->error);
        }

        // Bind parameters and execute the statement
        $stmt->bind_param("s", $username);
        $stmt->execute();

        // Bind the result variable
        $stmt->bind_result($userId);

        // Fetch the result
        $stmt->fetch();

        // Close the statement
        $stmt->close();

        return $userId;
    }


    //********************************  Regular User delete   **************************************/
    // Delete a regular user
    /**
     * @param $username
     * @return string|void
     */
    public function deleteRegularUser($username)
    {
        // Prepare the SQL statement
        $sql = "DELETE FROM Users WHERE username = ? AND role = 'regular'";

        // Use prepared statements to prevent SQL injection
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Error in preparing the statement: " . $this->conn->error);
        }

        // Bind parameters and execute the statement
        $stmt->bind_param("s", $username);
        $stmt->execute();

        // Check for success and return a message
        if ($stmt->affected_rows > 0) {
            return "Regular user deleted successfully!";
        } else {
            return "Error deleting regular user: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }





    //************************************* Product Table *********************************************//

    // Product Table
    public function createProductsTable()
    {
        $sql_create_table_products = "
            CREATE TABLE IF NOT EXISTS Products (
                product_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                product_name VARCHAR(50) NOT NULL,
                description TEXT,
                price DECIMAL(10,2) NOT NULL,
                image VARCHAR(255), 
                creator_id INT(6) UNSIGNED,
                FOREIGN KEY (creator_id) REFERENCES Users(user_id)
            )";

        if (!mysqli_query($this->conn, $sql_create_table_products)) {
            echo "Error creating Products table: " . mysqli_error($this->conn);
        }
    }

    //*********************************  Get products of user  ****************************************
    /**
     * Get products associated with a specific user
     *
     * @param string $authenticatedUsername
     * @return array
     */
    public function getProductsByUser($authenticatedUsername)
    {
        // Prepare the SQL statement
        $sql = "SELECT product_id, product_name, description, price 
            FROM Products 
            JOIN Users ON Products.creator_id = Users.user_id
            WHERE Users.username = ?";

        // Use prepared statements to prevent SQL injection
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Error in preparing the statement: " . $this->conn->error);
        }

        // Bind parameters and execute the statement
        $stmt->bind_param("s", $authenticatedUsername);
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch all products associated with the user
        $userProducts = $result->fetch_all(MYSQLI_ASSOC);

        // Close the statement
        $stmt->close();

        return $userProducts;
    }



    //***********************************     ADD PRODUCT   *********************************************
    // Add a new product for a specific user
    /**
     * @param $authenticatedUsername
     * @param $productName
     * @param $description
     * @param $price
     * @param $image
     * @return string
     */
    public function addProduct($authenticatedUsername, $productName, $description, $price, $image)
    {
        // Get user ID based on the authenticated username
        $userId = $this->getUserIdByUsername($authenticatedUsername);

        if (!$userId) {
            return "Error: User not found.";
        }
        // Prepare the SQL statement
        $sql = "INSERT INTO Products (creator_id, product_name, description, price, image) VALUES (?, ?, ?, ?, ?);";

        // Use prepared statements to prevent SQL injection
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return "Error in preparing the statement: " . $this->conn->error;
        }

        // Bind parameters and execute the statement
        $stmt->bind_param("dssds", $userId, $productName, $description, $price, $image);

        if (!$stmt->execute()) {
            $stmt->close();
            return "Error adding product: " . $stmt->error;
        }

        // Check for success and return a message
        if ($stmt->affected_rows > 0) {
            $stmt->close();
            return "Product added successfully!";
        } else {
            $stmt->close();
            return "No rows affected. Product may not have been added.";
        }

    }


    //************************************   Get PRODUCT Id   *********************************************

    // Function to get product by ID
    /**
     * @param $productID
     * @return string
     */
    public function getProductByID($productID) {
        $sql = "SELECT * FROM products WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);

        // Check for an error in preparing the statement
        if (!$stmt) {
            return "Error in preparing the statement: " . $this->conn->error;
        }

        // Bind parameter and execute the statement
        $stmt->bind_param("d", $productID);
        if (!$stmt->execute()) {
            $stmt->close();
            return "Error fetching product: " . $stmt->error;
        }

        // Get the result
        $result = $stmt->get_result();

        // Check if a row is returned
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $stmt->close();
            return $row;
        } else {
            $stmt->close();
            return "Product not found.";
        }
    }


    //*****************************     Modify product     *************************************************
    /**
     * @param $productId
     * @param $newProductName
     * @param $newDescription
     * @param $newPrice
     * @param $newImage
     * @return string
     */
    public function modifyProduct( $productId, $newProductName, $newDescription, $newPrice, $newImage)
    {
        $query = "UPDATE products SET product_name = ?, description = ?, price = ?, image = ? WHERE product_id = ?";
        $stmt = $this->conn->prepare($query);

        // Check for an error in preparing the statement
        if (!$stmt) {
            return "Error in preparing the statement: " . $this->conn->error;
        }

        // Bind parameters and execute the statement
        $stmt->bind_param("ssdsd", $newProductName, $newDescription, $newPrice, $newImage, $productId);

        // Assuming all input values are validated and sanitized appropriately
        if (!$stmt->execute()) {
            $stmt->close();
            return "Error modifying product: " . $stmt->error;
        }

        // Store the number of affected rows before closing the statement
        $affectedRows = $stmt->affected_rows;

        // Close the statement
        $stmt->close();

        // Check for success and return a message
        if ($affectedRows > 0) {
            return "Product modified successfully!";
        } else {
            return "No rows affected. Product may not have been modified.";
        }
    }



    //***********************************     Delete product      *************************************
    /**
     * @param $productIdToDelete
     * @return string
     */
    public function deleteProduct($productIdToDelete) {
        try {
            // Prepare and execute the SQL statement to delete the product
            $stmt = $this->conn->prepare("DELETE FROM products WHERE product_id = ?");
            $stmt->bind_param("d", $productIdToDelete);

            if ($stmt->execute()) {
                return "Product deleted successfully.";
            } else {
                return "Error deleting product: " . $stmt->error;
            }
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }



    //*********************** *****       Display products with user     ***  ******************************
    /**
     * @return array|string
     */
    public function getProductsWithCreators() {
        // Assume 'Products' and 'Users' are your table names

        // Select necessary columns from both tables
        $sql = "SELECT p.product_id, p.product_name, p.description, p.price, p.image, u.username AS creator_name
            FROM Products p
            JOIN Users u ON p.creator_id = u.user_id
            ORDER BY p.product_id DESC";

        $result = $this->conn->query($sql);

        if (!$result) {
            return "Error retrieving products: " . $this->conn->error;
        }

        $products = [];

        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        return $products;
    }
}


$tableCreator = new TableCreator($conn);
$tableCreator->createUsersTable();
$tableCreator->createProductsTable();


