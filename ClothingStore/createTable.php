<?php
include 'DBConn.php';

// Drop existing table (optional - comment out if you don't want to lose data)
// mysqli_query($conn, "DROP TABLE IF EXISTS Customer");

// Create Customer table if not exists
$sql = "CREATE TABLE IF NOT EXISTS Customer (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    status VARCHAR(20) DEFAULT 'pending',
    role VARCHAR(20) DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql)) {
    echo "? Table 'Customer' ready!<br>";
} else {
    echo "? Error: " . mysqli_error($conn) . "<br>";
}

// Check if admin already exists
$check = mysqli_query($conn, "SELECT * FROM Customer WHERE email = 'admin@clothingstore.co.za'");
if (mysqli_num_rows($check) == 0) {
    // Insert admin user
    $admin_pass = password_hash("Admin123", PASSWORD_DEFAULT);
    $insert = "INSERT INTO Customer (fullname, email, password_hash, status, role) 
               VALUES ('Admin User', 'admin@clothingstore.co.za', '$admin_pass', 'verified', 'admin')";

    if (mysqli_query($conn, $insert)) {
        echo "? Admin user created.<br>";
        echo "?? Email: admin@clothingstore.co.za<br>";
        echo "?? Password: Admin123<br>";
    } else {
        echo "? Error creating admin: " . mysqli_error($conn) . "<br>";
    }
} else {
    // Update admin password just in case
    $admin_pass = password_hash("Admin123", PASSWORD_DEFAULT);
    mysqli_query($conn, "UPDATE Customer SET password_hash = '$admin_pass', status = 'verified', role = 'admin' WHERE email = 'admin@clothingstore.co.za'");
    echo "? Admin user updated.<br>";
    echo "?? Email: admin@clothingstore.co.za<br>";
    echo "?? Password: Admin123<br>";
}

// Show all users
echo "<hr><h3>?? Current Users:</h3>";
$result = mysqli_query($conn, "SELECT id, fullname, email, role, status FROM Customer");
echo "<table border='1' cellpadding='8'>";
echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Status</th></tr>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . $row['fullname'] . "</td>";
    echo "<td>" . $row['email'] . "</td>";
    echo "<td>" . $row['role'] . "</td>";
    echo "<td>" . $row['status'] . "</td>";
    echo "</tr>";
}
echo "</table>";
?>