<?php
session_start();
include 'DBConn.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm = trim($_POST['confirm_password']);

    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $check = mysqli_query($conn, "SELECT * FROM Customer WHERE email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Email already registered.";
        } else {
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO Customer (fullname, email, password_hash, status, role) 
                    VALUES ('$fullname', '$email', '$pass_hash', 'pending', 'customer')";

            if (mysqli_query($conn, $sql)) {
                $success = "Registration successful! Please wait for admin verification.";
            } else {
                $error = "Registration failed: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register | Past Times</title>
    <style>
        body { font-family: Arial; background: #1d3c34; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .register-card { background: white; padding: 40px; width: 400px; border-radius: 8px; }
        h2 { color: #1d3c34; text-align: center; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; box-sizing: border-box; }
        button { background: #1d3c34; color: white; padding: 12px; width: 100%; border: none; cursor: pointer; }
        .error { color: red; text-align: center; }
        .success { color: green; text-align: center; }
        a { color: #1d3c34; display: block; text-align: center; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="register-card">
        <h2>Create Account</h2>
        <?php if ($error)
            echo "<p class='error'>$error</p>"; ?>
        <?php if ($success)
            echo "<p class='success'>$success</p>"; ?>
        <form method="POST">
            <input type="text" name="fullname" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Register</button>
        </form>
        <a href="login.php">Already have an account? Login</a>
    </div>
</body>
</html>