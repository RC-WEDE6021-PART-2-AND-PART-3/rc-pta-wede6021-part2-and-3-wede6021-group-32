<?php
session_start();
include 'DBConn.php';

$error = "";
$debug_info = "";

// Check if admin user exists in database
$check_admin = mysqli_query($conn, "SELECT * FROM Customer WHERE role = 'admin'");
if (mysqli_num_rows($check_admin) == 0) {
    $debug_info = "⚠️ No admin user found. Please run createTable.php first.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Query to check admin credentials
    $sql = "SELECT * FROM Customer WHERE email = '$email' AND role = 'admin'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password_hash'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_name'] = $row['fullname'];
            $_SESSION['admin_id'] = $row['id'];
            header("Location: adminDashboard.php");
            exit();
        } else {
            $error = "❌ Invalid password. Please try again.";
        }
    } else {
        $error = "❌ Admin credentials not found. Please check your email or run createTable.php.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login | Past Times</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #1d3c34; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .login-card { background: white; padding: 40px; width: 400px; border-radius: 12px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
        h2 { color: #1d3c34; text-align: center; margin-bottom: 10px; font-family: 'Playfair Display', serif; font-size: 28px; }
        .subtitle { text-align: center; color: #666; font-size: 14px; margin-bottom: 25px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: 600; color: #333; margin-bottom: 5px; font-size: 14px; }
        input { width: 100%; padding: 12px 15px; border: 2px solid #ddd; border-radius: 8px; font-size: 16px; transition: border-color 0.3s; box-sizing: border-box; }
        input:focus { border-color: #1d3c34; outline: none; }
        button { background: #1d3c34; color: white; padding: 14px; width: 100%; border: none; border-radius: 8px; font-size: 16px; font-weight: bold; cursor: pointer; transition: background 0.3s; }
        button:hover { background: #2a5a4e; }
        .error { color: #d9534f; background: #fde8e8; padding: 12px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid #d9534f; }
        .debug { color: #856404; background: #fff3cd; padding: 12px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid #ffc107; font-size: 13px; }
        .links { text-align: center; margin-top: 20px; font-size: 14px; }
        .links a { color: #1d3c34; text-decoration: none; font-weight: 600; }
        .links a:hover { text-decoration: underline; }
        .credentials-box { background: #f0f0f0; padding: 15px; border-radius: 8px; margin: 15px 0; font-size: 14px; }
        .credentials-box strong { color: #1d3c34; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>👔 Admin Login</h2>
        <p class="subtitle">Secure access to the management dashboard</p>
        
        <?php if ($debug_info): ?>
            <div class="debug"><?php echo $debug_info; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="credentials-box">
            <strong>📧 Default Admin Credentials:</strong><br>
            Email: <strong>admin@clothingstore.co.za</strong><br>
            Password: <strong>Admin123</strong>
        </div>
        
        <form method="POST">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="admin@clothingstore.co.za" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit">🔐 Login</button>
        </form>
        <div class="links">
            <a href="index.php">← Back to Home</a>
        </div>
    </div>
</body>
</html>