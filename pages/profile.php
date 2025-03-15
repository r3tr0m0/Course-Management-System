<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../includes/db.php';
$user_id = $_SESSION['user_id'];

// Fetch user information
$sql = "SELECT username, email, role FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_username = trim($_POST['username']);
    $new_email = trim($_POST['email']);

    // Validation
    if (empty($new_username) || empty($new_email)) {
        $error = "Both fields are required.";
    } else {
        // Check if email is already taken by another user
        $check_email_sql = "SELECT id FROM users WHERE email = ? AND id != ?";
        $stmt = $conn->prepare($check_email_sql);
        $stmt->bind_param("si", $new_email, $user_id);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $error = "The email is already taken.";
        } else {
            // Update user details in the database
            $update_sql = "UPDATE users SET username = ?, email = ? WHERE id = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("ssi", $new_username, $new_email, $user_id);
            $stmt->execute();
            
            if ($stmt->affected_rows > 0) {
                // Success - update session variables and refresh user info
                $_SESSION['username'] = $new_username;
                $_SESSION['email'] = $new_email;
                $success = "Profile updated successfully.";
                // Update user array with the new data
                $user['username'] = $new_username;
                $user['email'] = $new_email;
            } else {
                $error = "No changes were made or something went wrong.";
            }
        }
        $stmt->close();
    }
}

$conn->close();

include '../includes/header.php';
include '../includes/navbar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Profile</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">My Profile</h1>
    <p class="text-center text-muted">View and manage your profile information.</p>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="card mx-auto mt-4" style="max-width: 600px;">
        <div class="card-body">
            <h5 class="card-title">User Details</h5>
            <ul class="list-group">
                <li class="list-group-item"><strong>Name:</strong> <?php echo htmlspecialchars($user['username']); ?></li>
                <li class="list-group-item"><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></li>
                <li class="list-group-item"><strong>Role:</strong> <?php echo ucfirst(htmlspecialchars($user['role'])); ?></li>
            </ul>
        </div>
    </div>

    <!-- Edit Profile Form -->
    <div class="card mx-auto mt-4" style="max-width: 600px;">
        <div class="card-body">
            <h5 class="card-title">Edit Profile</h5>
            <form action="profile.php" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <button type="submit" class="btn btn-success mt-3">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- Change Password Button -->
    <div class="text-center mt-4">
        <a href="reset_password.php" class="btn btn-warning">Change Password</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
