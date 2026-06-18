﻿<?php
session_start();
include 'DBConn.php';

// Security Gate
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminLogin.php");
    exit();
}

$admin_name = isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : 'Admin';

// CRUD Form Processors
$admin_notification = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['crud_action'])) {
    if ($_POST['crud_action'] == 'add_clothing') {
        $brand = mysqli_real_escape_string($conn, $_POST['brand']);
        $desc = mysqli_real_escape_string($conn, $_POST['description']);
        $price = floatval($_POST['price']);
        // Handle image upload
        $image_url = 'images/placeholder.jpg';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = "uploads/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $image_url = $target_dir . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $image_url);
        }

        // Create CLOTHING table if it doesn't exist
        $create_table = "CREATE TABLE IF NOT EXISTS CLOTHING (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            brand VARCHAR(100) NOT NULL,
            description TEXT NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            image_url VARCHAR(255) DEFAULT 'images/placeholder.jpg',
            status VARCHAR(20) DEFAULT 'pending',
            seller_id INT(6),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        mysqli_query($conn, $create_table);

        $sql = "INSERT INTO CLOTHING (brand, description, price, image_url, status) 
                VALUES ('$brand', '$desc', '$price', '$image_url', 'pending')";
        if (mysqli_query($conn, $sql)) {
            $admin_notification = "✅ Successfully added new clothing listing.";
        } else {
            $admin_notification = "❌ Error: " . mysqli_error($conn);
        }
    }

    if ($_POST['crud_action'] == 'delete_user') {
        $user_id = intval($_POST['user_id']);
        // Prevent admin from deleting themselves
        if ($user_id == $_SESSION['admin_id']) {
            $admin_notification = "⚠️ You cannot delete your own admin account!";
        } else {
            $sql = "DELETE FROM Customer WHERE id = $user_id";
            if (mysqli_query($conn, $sql)) {
                $admin_notification = "✅ User record purged successfully.";
            } else {
                $admin_notification = "❌ Error deleting user: " . mysqli_error($conn);
            }
        }
    }

    if ($_POST['crud_action'] == 'send_message') {
        $receiver_id = intval($_POST['receiver_id']);
        $message = mysqli_real_escape_string($conn, $_POST['message']);

        // Create messages table if it doesn't exist
        $create_msg_table = "CREATE TABLE IF NOT EXISTS Messages (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            sender_id INT(6) NOT NULL,
            receiver_id INT(6) NOT NULL,
            message TEXT NOT NULL,
            is_read BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        mysqli_query($conn, $create_msg_table);

        $sql = "INSERT INTO Messages (sender_id, receiver_id, message) 
                VALUES ({$_SESSION['admin_id']}, $receiver_id, '$message')";
        if (mysqli_query($conn, $sql)) {
            $admin_notification = "✅ Message dispatched successfully.";
        } else {
            $admin_notification = "❌ Error sending message: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Executive Admin Hub | Past Times</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root { --forest: #1d3c34; --gold: #a68a64; --cream: #f9f7f2; }
        * { box-sizing: border-box; }
        body { font-family: 'Montserrat', sans-serif; background: #f0eae1; margin: 0; padding: 0; display: flex; }
        .sidebar { width: 260px; background: var(--forest); color: white; height: 100vh; padding: 30px 20px; box-sizing: border-box; position: fixed; overflow-y: auto; }
        .sidebar h2 { font-family: 'Playfair Display', serif; color: var(--gold); margin-top: 0; }
        .sidebar .admin-name { color: var(--gold); font-weight: 600; }
        .sidebar a { color: var(--gold); text-decoration: none; font-weight: bold; }
        .sidebar a:hover { text-decoration: underline; }
        .sidebar hr { border-color: rgba(255,255,255,0.1); margin: 20px 0; }
        .sidebar .nav-link { display: block; padding: 10px 0; color: rgba(255,255,255,0.7); text-decoration: none; }
        .sidebar .nav-link:hover { color: white; }
        .main-content { margin-left: 260px; padding: 40px; width: calc(100% - 260px); box-sizing: border-box; }
        .dashboard-section { background: white; padding: 30px; margin-bottom: 30px; box-shadow: 5px 5px 15px rgba(0,0,0,0.05); border-radius: 8px; }
        h1 { font-family: 'Playfair Display', serif; color: var(--forest); }
        h3 { font-family: 'Playfair Display', serif; color: var(--forest); border-bottom: 2px solid var(--cream); padding-bottom: 10px; margin-top: 0; }
        .grid-split { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
        input, textarea, select { width: 100%; padding: 10px; margin-bottom: 12px; border: 1px solid #ccc; box-sizing: border-box; border-radius: 4px; font-family: inherit; }
        textarea { min-height: 80px; resize: vertical; }
        .btn-action { background: var(--forest); color: white; border: none; padding: 12px 20px; cursor: pointer; text-transform: uppercase; font-weight: bold; font-size: 0.75rem; border-radius: 4px; transition: background 0.3s; }
        .btn-action:hover { background: #2a5a4e; }
        .btn-danger { background: #d9534f; }
        .btn-danger:hover { background: #c9302c; }
        .btn-gold { background: var(--gold); }
        .btn-gold:hover { background: #8f7a4e; }
        .notification { background: var(--forest); color: white; padding: 15px 20px; margin-bottom: 20px; font-weight: 600; font-size: 0.85rem; border-radius: 4px; }
        .notification.error { background: #d9534f; }
        .notification.success { background: var(--forest); }
        .file-input-wrapper { margin-bottom: 12px; }
        .file-input-wrapper label { display: block; margin-bottom: 5px; font-weight: 600; color: #555; font-size: 0.9rem; }
        @media (max-width: 768px) {
            body { flex-direction: column; }
            .sidebar { width: 100%; height: auto; position: relative; }
            .main-content { margin-left: 0; width: 100%; padding: 20px; }
            .grid-split { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Past Times</h2>
    <p>Signed in as: <strong class="admin-name"><?php echo htmlspecialchars($admin_name); ?></strong></p>
    <hr>
    <a href="adminDashboard.php" class="nav-link">📊 Dashboard</a>
    <a href="submit_clothing.php" class="nav-link" style="color: white;">👕 Manage Inventory</a>
    <a href="adminLogin.php" class="nav-link">⚙️ Settings</a>
    <hr>
    <a href="logout.php" style="color: var(--gold); text-decoration: none; font-weight: bold;">🚪 Secure Logout →</a>
</div>

<div class="main-content">
    <h1>👕 Inventory Management Hub</h1>
    
    <?php if (!empty($admin_notification)):
        $class = strpos($admin_notification, '✅') !== false ? 'success' : (strpos($admin_notification, '❌') !== false ? 'error' : '');
        ?>
        <div class="notification <?php echo $class; ?>"><?php echo $admin_notification; ?></div>
    <?php endif; ?>

    <div class="grid-split">
        <!-- Feature Area: Add Clothing -->
        <div class="dashboard-section">
            <h3>➕ Add New Stock Listing</h3>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="crud_action" value="add_clothing">
                <input type="text" name="brand" placeholder="Brand Name" required>
                <textarea name="description" placeholder="Garment Description (e.g., color, size, condition)" required></textarea>
                <input type="number" step="0.01" name="price" placeholder="Price (ZAR)" required>
                <div class="file-input-wrapper">
                    <label>Upload Image:</label>
                    <input type="file" name="image" accept="image/*">
                </div>
                <button type="submit" class="btn-action">📦 Publish Inventory Item</button>
            </form>
        </div>

        <!-- Feature Area: Delete Users -->
        <div class="dashboard-section">
            <h3>🗑️ User Control Database</h3>
            <p style="color: #666; font-size: 0.9rem;">Enter the User ID to permanently delete their account.</p>
            <form method="POST">
                <input type="hidden" name="crud_action" value="delete_user">
                <input type="number" name="user_id" placeholder="Enter Target User ID" required min="1">
                <button type="submit" class="btn-action btn-danger">⚠️ Purge Account Record</button>
            </form>
        </div>
    </div>

    <!-- Feature Area: Communication -->
    <div class="dashboard-section">
        <h3>💬 Quality Control Communication Desk</h3>
        <p style="color: #666; font-size: 0.9rem;">Send messages to buyers and sellers regarding delivery and condition verification.</p>
        <form method="POST">
            <input type="hidden" name="crud_action" value="send_message">
            <input type="number" name="receiver_id" placeholder="Target User ID (Buyer or Seller)" required min="1">
            <textarea name="message" placeholder="Type your message here..." required></textarea>
            <button type="submit" class="btn-action btn-gold">✉️ Dispatch Message</button>
        </form>
    </div>
</div>

</body>
</html>