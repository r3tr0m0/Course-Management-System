<?php
session_start();
include '../includes/db.php';

$reset_link = ''; // To store the reset link for direct display

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['alert_message'] = "Invalid email format.";
        $_SESSION['alert_type'] = "danger";
        header("Location: forgot_password.php");
        exit();
    }

    // Check if the user exists
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        try {
            // Generate a unique token
            $token = bin2hex(random_bytes(50));

            // Store the token in the database
            $sql = "INSERT INTO password_reset_tokens (user_id, token) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $user['id'], $token);
            $stmt->execute();

            // Generate the reset link
            $reset_link = "http://localhost/final%20project/pages/reset_password.php?token=$token";

            $_SESSION['alert_message'] = "Password reset link has been generated below.";
            $_SESSION['alert_type'] = "success";
        } catch (Exception $e) {
            $_SESSION['alert_message'] = "An error occurred. Please try again.";
            $_SESSION['alert_type'] = "danger";
        }
    } else {
        $_SESSION['alert_message'] = "Email not found.";
        $_SESSION['alert_type'] = "danger";
    }
}

include '../includes/header.php';
include '../includes/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Centered Form Styling */
        .form-container {
            max-width: 400px; /* Restrict the width */
            margin: 0 auto; /* Center horizontally */
            margin-top: 150px; /* Space from the top */
            padding: 20px;
            border-radius: 8px;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center; /* Center align text */
        }

        /* Reset Link Styling */
        .reset-link-container {
            margin-top: 20px;
            padding: 15px;
            background-color: #e9f7fe; /* Light blue background */
            border: 1px solid #b3d8f5; /* Slightly darker blue border */
            border-radius: 8px; /* Rounded corners */
            text-align: center; /* Center the text */
        }

        .reset-link-container a {
            color: #007bff; /* Link color */
            font-weight: bold; /* Bold text */
            word-wrap: break-word; /* Ensure long links wrap within the container */
            text-decoration: none; /* Remove underline */
        }

        .reset-link-container a:hover {
            text-decoration: underline; /* Add underline on hover */
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Forgot Password</h2>
    <?php if (isset($_SESSION['alert_message'])): ?>
        <div class="alert alert-<?php echo htmlspecialchars($_SESSION['alert_type']); ?>">
            <?php echo htmlspecialchars($_SESSION['alert_message']); ?>
        </div>
        <?php unset($_SESSION['alert_message'], $_SESSION['alert_type']); ?>
    <?php endif; ?>
    
    <?php if (!empty($reset_link)): ?>
        <div class="reset-link-container">
            <strong>Reset Link:</strong> 
            <a href="<?php echo $reset_link; ?>" target="_blank"><?php echo $reset_link; ?></a>
        </div>
    <?php endif; ?>

    <form method="POST" action="forgot_password.php">
        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Request Reset</button>
    </form>
</div>
</body>
</html>
<?php include '../includes/footer.php'; ?>
