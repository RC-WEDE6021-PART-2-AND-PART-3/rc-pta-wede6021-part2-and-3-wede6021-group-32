<?php
session_start();
include 'DBConn.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: adminLogin.php");
    exit();
}

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM Customer WHERE id = $id");
$customer = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $status = $_POST['status'];
    $role = $_POST['role'];

    $sql = "UPDATE Customer SET fullname='$fullname', email='$email', status='$status', role='$role' WHERE id=$id";

    if (!empty($_POST['password'])) {
        $pass_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "UPDATE Customer SET fullname='$fullname', email='$email', password_hash='$pass_hash', status='$status', role='$role' WHERE id=$id";
    }

    if (mysqli_query($conn, $sql)) {
        header("Location: adminDashboard.php");
        exit();
    } else {
        $error = "Update failed: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Customer</title>
    <style>
        body { font-family: Arial; background: #eef2f7; padding: 20px; }
        .form-container { max-width: 500px; margin: auto; background: white; padding: 30px; border-radius: 8px; }
        input, select { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; box-sizing: border-box; }
        button { background: #1d3c34; color: white; padding: 12px; width: 100%; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Update Customer</h2>
        <?php if (isset($error))
            echo "<p style='color:red'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="fullname" value="<?php echo $customer['fullname']; ?>" required>
            <input type="email" name="email" value="<?php echo $customer['email']; ?>" required>
            <input type="password" name="password" placeholder="New Password (leave blank to keep current)">
            <select name="status">
                <option value="pending" <?php echo ($customer['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="verified" <?php echo ($customer['status'] == 'verified') ? 'selected' : ''; ?>>Verified</option>
            </select>
            <select name="role">
                <option value="customer" <?php echo ($customer['role'] == 'customer') ? 'selected' : ''; ?>>Customer</option>
                <option value="admin" <?php echo ($customer['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
            </select>
            <button type="submit">Update Customer</button>
        </form>
    </div>
</body>
</html>