<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

// Fetch users grouped by role
$sql = "SELECT role, username, email FROM users ORDER BY role, username";
$result = $conn->query($sql);

include '../includes/header.php'; 
include '../includes/navbar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Report</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>User Report</h1>
    <table border="1">
        <tr>
            <th>Role</th>
            <th>Username</th>
            <th>Email</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo ucfirst($row['role']); ?></td>
            <td><?php echo $row['username']; ?></td>
            <td><?php echo $row['email']; ?></td>
        </tr>
        <?php } ?>
    </table>
    <a href="admin_dashboard.php">Back to Dashboard</a>
</body>
</html>
<?php include '../includes/footer.php'; ?>