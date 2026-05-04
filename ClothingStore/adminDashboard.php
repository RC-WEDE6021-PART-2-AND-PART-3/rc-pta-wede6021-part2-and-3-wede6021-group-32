<?php
session_start();
include 'DBConn.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminLogin.php");
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    mysqli_query($conn, "DELETE FROM Customer WHERE id = $id");
    header("Location: adminDashboard.php");
    exit();
}

// Handle Verify
if (isset($_GET['verify'])) {
    $id = mysqli_real_escape_string($conn, $_GET['verify']);
    mysqli_query($conn, "UPDATE Customer SET status = 'verified' WHERE id = $id");
    header("Location: adminDashboard.php");
    exit();
}

// Get ALL customers (no filter - show everyone)
$result = mysqli_query($conn, "SELECT * FROM Customer ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | Past Times</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; padding: 20px; }
        .dashboard { max-width: 1400px; margin: 0 auto; background: white; border-radius: 12px; box-shadow: 0 2px 20px rgba(0,0,0,0.1); overflow: hidden; }
        .header { background: #1d3c34; color: white; padding: 20px 30px; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { font-size: 1.8rem; }
        .stats { display: flex; gap: 20px; padding: 20px 30px; background: #f9f7f2; border-bottom: 1px solid #ddd; }
        .stat-box { background: white; padding: 15px 25px; border-radius: 8px; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .stat-number { font-size: 2rem; font-weight: bold; color: #1d3c34; }
        .stat-label { color: #666; font-size: 0.85rem; margin-top: 5px; }
        .logout-btn { background: #c0392b; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: bold; }
        .logout-btn:hover { background: #a93226; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #1d3c34; color: white; padding: 15px; text-align: left; font-weight: 600; }
        td { padding: 12px 15px; border-bottom: 1px solid #eee; }
        tr:hover { background: #f9f7f2; }
        .status-pending { background: #e67e22; color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; display: inline-block; }
        .status-verified { background: #27ae60; color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; display: inline-block; }
        .role-badge { background: #3498db; color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.7rem; display: inline-block; }
        .btn { padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 0.8rem; margin: 0 3px; display: inline-block; }
        .btn-verify { background: #27ae60; color: white; }
        .btn-update { background: #3498db; color: white; }
        .btn-delete { background: #e74c3c; color: white; }
        .btn:hover { opacity: 0.8; }
        .empty-state { text-align: center; padding: 50px; color: #999; }
        @media (max-width: 768px) {
            th, td { padding: 8px; font-size: 0.75rem; }
            .btn { padding: 4px 8px; font-size: 0.7rem; }
            .stats { flex-direction: column; }
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <div class="header">
            <h1>?? Admin Control Center</h1>
            <a href="logout.php" class="logout-btn">?? Logout</a>
        </div>
        
        <?php
        // Calculate stats
        $total = mysqli_num_rows($result);
        $pending_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM Customer WHERE status='pending'"));
        $verified_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM Customer WHERE status='verified'"));

        // Reset result pointer (since we used it for stats)
        mysqli_data_seek($result, 0);
        ?>
        
        <div class="stats">
            <div class="stat-box">
                <div class="stat-number"><?php echo $total; ?></div>
                <div class="stat-label">Total Customers</div>
            </div>
            <div class="stat-box">
                <div class="stat-number" style="color: #e67e22;"><?php echo $pending_count; ?></div>
                <div class="stat-label">Pending Verification</div>
            </div>
            <div class="stat-box">
                <div class="stat-number" style="color: #27ae60;"><?php echo $verified_count; ?></div>
                <div class="stat-label">Verified Customers</div>
            </div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Role</th>
                    <th>Registered</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['fullname']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td>
                                <?php if ($row['status'] == 'pending'): ?>
                                    <span class="status-pending">? PENDING</span>
                                <?php else: ?>
                                    <span class="status-verified">? VERIFIED</span>
                                <?php endif; ?>
                            </td>
                            <td><span class="role-badge"><?php echo $row['role']; ?></span></td>
                            <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                            <td>
                                <?php if ($row['status'] == 'pending'): ?>
                                    <a href="adminDashboard.php?verify=<?php echo $row['id']; ?>" 
                                       class="btn btn-verify" 
                                       onclick="return confirm('Verify this customer?')">? Verify</a>
                                <?php endif; ?>
                                <a href="updateCustomer.php?id=<?php echo $row['id']; ?>" 
                                   class="btn btn-update">?? Update</a>
                                <a href="adminDashboard.php?delete=<?php echo $row['id']; ?>" 
                                   class="btn btn-delete" 
                                   onclick="return confirm('Delete <?php echo addslashes($row['fullname']); ?>? This cannot be undone!')">??? Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="empty-state">
                            No customers found. 
                            <a href="register.php" style="color: #1d3c34;">Register a new customer</a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>