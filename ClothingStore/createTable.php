<?php
include 'DBConn.php';

// Drop existing table
mysqli_query($conn, "DROP TABLE IF EXISTS Customer");

// Create Customer table
$sql = "CREATE TABLE Customer (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    status VARCHAR(20) DEFAULT 'pending',
    role VARCHAR(20) DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql)) {
    echo "Table 'Customer' created successfully!<br>";

    // Insert admin user (change password)
    $admin_pass = password_hash("AdminPass123", PASSWORD_DEFAULT);
    $insert = "INSERT INTO Customer (fullname, email, password_hash, status, role) 
               VALUES ('Admin User', 'admin@clothingstore.co.za', '$admin_pass', 'verified', 'admin')";

    if (mysqli_query($conn, $insert)) {
        echo "Admin user created. Email: admin@clothingstore.co.za, Password: AdminPass123";
    }
} else {
    echo "Error: " . mysqli_error($conn);
}
?>s