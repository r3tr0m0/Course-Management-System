<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include '../includes/db.php'; // Include database connection

// Fetch all users
$sql = "SELECT id, username, email, role FROM users";
$result = $conn->query($sql);

include '../includes/header.php'; 
include '../includes/navbar.php'; 

// Generate a reset password token when requested
if (isset($_GET['reset_id'])) {
    $user_id = $_GET['reset_id'];

    // Generate a unique token
    $token = bin2hex(random_bytes(50));

    // Insert token into the database
    $sql_token = "INSERT INTO password_reset_tokens (user_id, token) VALUES (?, ?) ON DUPLICATE KEY UPDATE token = ?";
    $stmt = $conn->prepare($sql_token);
    $stmt->bind_param("iss", $user_id, $token, $token);
    if ($stmt->execute()) {
        // Redirect to reset password page with token
        header("Location: reset_password.php?token=$token");
        exit();
    } else {
        $_SESSION['alert_message'] = "Failed to generate reset token.";
        $_SESSION['alert_type'] = "danger";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Users</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .manage-users-container {
            margin: 50px auto;
            max-width: 800px;
        }
        .table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            text-align: left;
        }
        .table th, .table td {
            padding: 10px;
            border: 1px solid #dee2e6;
        }
        .table th {
            background-color: #343a40;
            color: white;
        }
        .btn {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            background-color: #007bff;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #a71d2a;
        }
    </style>
</head>
<body>
    <div class="manage-users-container">
        <h1>Manage Users</h1>
        <?php if (isset($_SESSION['alert_message'])): ?>
            <div class="alert alert-<?php echo htmlspecialchars($_SESSION['alert_type']); ?>">
                <?php echo htmlspecialchars($_SESSION['alert_message']); ?>
            </div>
            <?php unset($_SESSION['alert_message'], $_SESSION['alert_type']); ?>
        <?php endif; ?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo ucfirst(htmlspecialchars($row['role'])); ?></td>
                    <td>
                        <a href="manage_users.php?reset_id=<?php echo $row['id']; ?>" class="btn">Reset Password</a>
                        <a href="delete_user.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php include '../includes/footer.php'; ?>
