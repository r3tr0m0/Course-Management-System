<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

// Fetch enrolled courses
$user_id = $_SESSION['user_id'];
$sql = "SELECT c.name, c.description, c.capacity 
        FROM courses c 
        JOIN enrollments e ON c.id = e.course_id 
        WHERE e.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$courses = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();

include '../includes/header.php';
include '../includes/navbar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Enrollments</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">My Enrollments</h1>
        <p class="text-center">Below are the courses you are enrolled in.</p>

        <?php if (!empty($courses)): ?>
            <ul class="list-group mt-4">
                <?php foreach ($courses as $course): ?>
                    <li class="list-group-item">
                        <strong><?php echo htmlspecialchars($course['name']); ?></strong><br>
                        <?php echo htmlspecialchars($course['description']); ?><br>
                        <small class="text-muted">Capacity: <?php echo htmlspecialchars($course['capacity']); ?></small>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="text-muted text-center">You are not enrolled in any courses.</p>
        <?php endif; ?>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
