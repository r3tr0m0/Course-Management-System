<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

include '../includes/db.php'; // Include database connection

// Initialize variables
$user_id = $_SESSION['user_id'];
$enrolled_courses = 0;
$assignments_count = 0;
$announcements = [];

// Fetch enrolled courses count
$sql_courses = "SELECT COUNT(*) AS enrolled_courses FROM enrollments WHERE user_id = ?";
$stmt_courses = $conn->prepare($sql_courses);
if ($stmt_courses) {
    $stmt_courses->bind_param("i", $user_id);
    $stmt_courses->execute();
    $result_courses = $stmt_courses->get_result();
    $enrolled_courses = $result_courses->fetch_assoc()['enrolled_courses'] ?? 0;
    $stmt_courses->close();
} else {
    die("Error preparing courses query: " . $conn->error);
}

// Fetch assignments count
$sql_assignments = "SELECT COUNT(*) AS assignments_count 
                    FROM assignments a
                    JOIN enrollments e ON a.course_id = e.course_id
                    WHERE e.user_id = ?";
$stmt_assignments = $conn->prepare($sql_assignments);
if ($stmt_assignments) {
    $stmt_assignments->bind_param("i", $user_id);
    $stmt_assignments->execute();
    $result_assignments = $stmt_assignments->get_result();
    $assignments_count = $result_assignments->fetch_assoc()['assignments_count'] ?? 0;
    $stmt_assignments->close();
} else {
    die("Error preparing assignments query: " . $conn->error);
}

// Fetch recent announcements
$sql_announcements = "SELECT a.title, a.content, c.name AS course_name, a.posted_at 
                      FROM announcements a
                      JOIN courses c ON a.course_id = c.id
                      JOIN enrollments e ON e.course_id = c.id
                      WHERE e.user_id = ?
                      ORDER BY a.posted_at DESC
                      LIMIT 5";
$stmt_announcements = $conn->prepare($sql_announcements);
if ($stmt_announcements) {
    $stmt_announcements->bind_param("i", $user_id);
    $stmt_announcements->execute();
    $result_announcements = $stmt_announcements->get_result();
    $announcements = $result_announcements->fetch_all(MYSQLI_ASSOC);
    $stmt_announcements->close();
} else {
    die("Error preparing announcements query: " . $conn->error);
}

include '../includes/header.php'; 
include '../includes/navbar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1 class="text-center">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <p class="text-center">Here is an overview of your activities and quick actions you can take.</p>

        <!-- Overview Section -->
        <div class="row text-center">
            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2><?php echo htmlspecialchars($enrolled_courses); ?></h2>
                        <p>Enrolled Courses</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2><?php echo htmlspecialchars($assignments_count); ?></h2>
                        <p>Assignments</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2>70%</h2>
                        <p>Average Progress</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Announcements Section -->
        <div class="mt-5">
            <h3 class="text-center">Recent Announcements</h3>
            <?php if (!empty($announcements)): ?>
                <ul class="list-group mt-3">
                    <?php foreach ($announcements as $announcement): ?>
                        <li class="list-group-item">
                            <h5><?php echo htmlspecialchars($announcement['title']); ?></h5>
                            <p><?php echo htmlspecialchars($announcement['content']); ?></p>
                            <small class="text-muted">Course: <?php echo htmlspecialchars($announcement['course_name']); ?> | Date: <?php echo htmlspecialchars(date("F j, Y, g:i A", strtotime($announcement['posted_at']))); ?></small>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-muted text-center">No announcements available.</p>
            <?php endif; ?>
        </div>

        <!-- Quick Links Section -->
        <div class="text-center mt-4">
            <a href="view_courses.php" class="btn btn-primary btn-lg">View Courses</a>
            <a href="enrollments.php" class="btn btn-secondary btn-lg">My Enrollments</a>
            <a href="assignments.php" class="btn btn-success btn-lg">My Assignments</a>
            <a href="grades.php" class="btn btn-info btn-lg">My Grades</a>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
