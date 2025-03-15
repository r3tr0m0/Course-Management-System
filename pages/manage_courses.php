<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

// Handle course deletion
if (isset($_GET['delete'])) {
    $course_id = $_GET['delete'];
    $sql_delete = "DELETE FROM courses WHERE id = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("i", $course_id);

    if ($stmt->execute()) {
        $_SESSION['alert_message'] = "Course deleted successfully!";
        $_SESSION['alert_type'] = "success";
    } else {
        $_SESSION['alert_message'] = "Error deleting course: " . $stmt->error;
        $_SESSION['alert_type'] = "danger";
    }
    $stmt->close();
    header("Location: manage_courses.php");
    exit();
}

// Fetch all courses
$sql = "SELECT * FROM courses";
$result = $conn->query($sql);

include '../includes/header.php'; 
include '../includes/navbar.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Courses</title>
    <link rel="stylesheet" href="../assets/css/style.css">
   
</head>
<body>
    <div class="container">
        <h1>Manage Courses</h1>

        <!-- Display alert message -->
        <?php if (isset($_SESSION['alert_message'])): ?>
            <div class="alert alert-<?php echo htmlspecialchars($_SESSION['alert_type']); ?>">
                <?php echo htmlspecialchars($_SESSION['alert_message']); ?>
            </div>
            <?php unset($_SESSION['alert_message'], $_SESSION['alert_type']); ?>
        <?php endif; ?>

        <div class="text-end mb-3">
            <a href="add_course.php" class="btn btn-primary">Add New Course</a>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Capacity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td><?php echo htmlspecialchars($row['capacity']); ?></td>
                    <td>
                        <a href="edit_course.php?id=<?php echo urlencode($row['id']); ?>" class="btn btn-secondary btn-sm">Edit</a>
                        <a href="manage_courses.php?delete=<?php echo urlencode($row['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this course?');">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php include '../includes/footer.php'; ?>
