<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'instructor') {
    header("Location: login.php");
    exit();
}

include '../includes/db.php'; // Include database connection

if (!isset($_GET['course_id'])) {
    die("Course ID not provided.");
}

$course_id = intval($_GET['course_id']);

// Fetch course details
$sql = "SELECT name, description FROM courses WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$course_result = $stmt->get_result();
$course = $course_result->fetch_assoc();
$stmt->close();

// Fetch students enrolled in the course
$sql = "SELECT u.id, u.username, u.email 
        FROM users u
        JOIN enrollments e ON u.id = e.user_id
        WHERE e.course_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();
$students = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();

include '../includes/header.php';
include '../includes/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Students</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Students Enrolled</h1>
    <p class="text-center">
        <strong>Course:</strong> <?php echo htmlspecialchars($course['name']); ?><br>
        <strong>Description:</strong> <?php echo htmlspecialchars($course['description']); ?>
    </p>

    <?php if (!empty($students)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($student['id']); ?></td>
                        <td><?php echo htmlspecialchars($student['username']); ?></td>
                        <td><?php echo htmlspecialchars($student['email']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-danger text-center">No students enrolled in this course.</p>
    <?php endif; ?>
</div>
</body>
</html>
<?php include '../includes/footer.php'; ?>
