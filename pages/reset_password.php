<?php
session_start();
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Validate the token
    $sql = "SELECT user_id FROM password_reset_tokens WHERE token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $token_data = $result->fetch_assoc();

    if ($token_data) {
        // Update the password
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_password, $token_data['user_id']);
        $stmt->execute();

        // Delete the token
        $sql = "DELETE FROM password_reset_tokens WHERE token = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();

        $_SESSION['alert_message'] = "Password updated successfully. You can now login.";
        $_SESSION['alert_type'] = "success";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['alert_message'] = "Invalid or expired token.";
        $_SESSION['alert_type'] = "danger";
    }
}

if (!isset($_GET['token'])) {
    die("Token not provided.");
}

$token = $_GET['token'];

include '../includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Centered Form Styling */
        .form-container {
            max-width: 400px;
            margin: 0 auto;
            margin-top: 100px;
            padding: 20px;
            border-radius: 8px;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }

        /* Alerts Styling */
        .alert {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 8px;
            font-weight: bold;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Form Buttons */
        button {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            font-weight: bold;
            color: #ffffff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Reset Password</h2>
    <?php if (isset($_SESSION['alert_message'])): ?>
        <div class="alert alert-<?php echo htmlspecialchars($_SESSION['alert_type']); ?>">
            <?php echo htmlspecialchars($_SESSION['alert_message']); ?>
        </div>
        <?php unset($_SESSION['alert_message'], $_SESSION['alert_type']); ?>
    <?php endif; ?>
    <form method="POST" action="reset_password.php">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
        <div class="mb-3">
            <label for="password" class="form-label">New Password:</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Reset Password</button>
    </form>
</div>
</body>
</html>
<?php include '../includes/footer.php'; ?>
