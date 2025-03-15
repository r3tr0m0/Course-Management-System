<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include '../includes/db.php'; // Include database connection
include '../includes/header.php'; 
include '../includes/navbar.php'; 

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete user
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "User deleted successfully!";
    } else {
        echo "Error deleting user: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: manage_users.php");
    exit();
}

include '../includes/footer.php'; 
?>
