<?php
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the user is logged in and has the 'instructor' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'instructor') {
    header("Location: login.php");
    exit();
}

include '../includes/db.php'; // Database connection

// Fetch the instructor's courses
$user_id = $_SESSION['user_id'];
$sql = "SELECT c.id, c.name, c.description 
        FROM courses c 
        JOIN course_instructors ci ON c.id = ci.course_id 
        WHERE ci.instructor_id = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Failed to prepare statement: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Failed to execute query: " . $stmt->error);
}

$courses = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Instructor Dashboard</title>
</head>
<body>
    <!-- Include the header and navbar -->
    
    <div class="container">
        <h2 class="text-center"></h2>
        <h1 class="text-center">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <p class="text-center">Below is a list of your assigned courses. Select an option to manage grades or view enrolled students.</p>

        <!-- List of Courses -->
        <div class="mt-4">
            <h3>My Courses</h3>
            <?php if (!empty($courses)): ?>
                <ul class="list-group">
                    <?php foreach ($courses as $course): ?>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?php echo htmlspecialchars($course['name']); ?></strong><br>
                                    <span class="text-muted"><?php echo htmlspecialchars($course['description']); ?></span>
                                </div>
                                <div>
                                    <a href="manage_grades.php?course_id=<?php echo urlencode($course['id']); ?>" class="btn btn-primary btn-sm">Manage Grades</a>
                                    <a href="view_students.php?course_id=<?php echo urlencode($course['id']); ?>" class="btn btn-secondary btn-sm">View Students</a>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-danger">No courses assigned to you yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Include the footer -->
    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
