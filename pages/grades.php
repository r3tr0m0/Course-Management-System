<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

$user_id = $_SESSION['user_id'];

// Fetch grades and assignments
$sql = "SELECT a.title AS assignment_title, 
               c.name AS course_name, 
               g.grade AS obtained_grade, 
               g.created_at AS graded_on
        FROM grades g
        JOIN assignments a ON g.enrollment_id = a.course_id   -- Adjust the column name for linking grades to assignments
        JOIN enrollments e ON g.enrollment_id = e.id
        JOIN courses c ON a.course_id = c.id
        WHERE e.user_id = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("i", $user_id);

if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
}

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
    <title>My Grades</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">My Grades</h1>
        <p class="text-center">Here are your grades for completed assignments.</p>

        <?php if (!empty($grades)): ?>
            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>Assignment</th>
                        <th>Course</th>
                        <th>Grade</th>
                        <th>Maximum Marks</th>
                        <th>Graded On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($grades as $grade): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($grade['assignment_title']); ?></td>
                            <td><?php echo htmlspecialchars($grade['course_name']); ?></td>
                            <td><?php echo htmlspecialchars($grade['obtained_grade']); ?></td>
                            <td><?php echo htmlspecialchars($grade['maximum_marks']); ?></td>
                            <td><?php echo htmlspecialchars(date("F j, Y, g:i A", strtotime($grade['graded_on']))); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted text-center">No grades available.</p>
        <?php endif; ?>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
