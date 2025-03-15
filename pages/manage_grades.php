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

// Fetch students and their grades for the course
$sql = "SELECT u.id AS student_id, u.username, u.email, g.grade
        FROM users u
        JOIN enrollments e ON u.id = e.user_id
        LEFT JOIN grades g ON e.id = g.enrollment_id
        WHERE e.course_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();
$grades = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();

include '../includes/header.php';
include '../includes/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Grades</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Manage Grades</h1>
    <p class="text-center">
        <strong>Course:</strong> <?php echo htmlspecialchars($course['name']); ?><br>
        <strong>Description:</strong> <?php echo htmlspecialchars($course['description']); ?>
    </p>

    <?php if (!empty($grades)): ?>
        <form method="POST" action="save_grades.php">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Grade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($grades as $grade): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($grade['student_id']); ?></td>
                            <td><?php echo htmlspecialchars($grade['username']); ?></td>
                            <td><?php echo htmlspecialchars($grade['email']); ?></td>
                            <td>
                                <input type="text" name="grades[<?php echo $grade['student_id']; ?>]" value="<?php echo htmlspecialchars($grade['grade']); ?>" class="form-control">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">Save Grades</button>
        </form>
    <?php else: ?>
        <p class="text-danger text-center">No students or grades available for this course.</p>
    <?php endif; ?>
</div>
</body>
</html>
<?php include '../includes/footer.php'; ?>
