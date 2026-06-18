<?php
session_start();
include 'DBConn.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM Customer WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password_hash'])) {
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_name'] = $row['fullname'];
            $_SESSION['user_id'] = $row['id'];
            header("Location: shop.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Email not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Login | Past Times</title>
    <style>
        body { font-family: Arial, sans-serif; background: #1d3c34; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .login-card { background: white; padding: 40px; width: 350px; border-radius: 8px; text-align: center; }
        h2 { color: #1d3c34; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; box-sizing: border-box; }
        button { background: #1d3c34; color: white; padding: 12px; width: 100%; border: none; cursor: pointer; }
        .error { color: red; }
        a { color: #1d3c34; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>Customer Login</h2>
        <?php if ($error)
            echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>