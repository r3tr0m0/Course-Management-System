<?php
session_start();
include '../includes/header.php';
include '../includes/navbar.php'; // Ensure header is included before output
include '../includes/db.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if the user exists in the database
    $sql = "SELECT id, username, password, role FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $username, $hashed_password, $role);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
            // Store session variables
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;

            // Redirect based on role
            switch ($role) {
                case 'admin':
                    header("Location: admin_dashboard.php");
                    break;
                case 'instructor':
                    header("Location: instructor_dashboard.php");
                    break;
                case 'student':
                    header("Location: student_dashboard.php");
                    break;
            }
            exit();
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "No user found with this email.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <main>
        <div class="form-container">
            <h2>Login</h2>
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <form method="POST" action="login.php">
                <div class="mb-3">
                    <label>Email:</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Password:</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
            <p class="mt-3">
                <a href="forgot_password.php">Forgot Password?</a>
            </p>
        </div>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
