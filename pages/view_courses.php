<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

// Fetch all courses and enrollment status
$user_id = $_SESSION['user_id'];
$sql = "SELECT c.id, c.name, c.description, c.capacity, 
        (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id) AS enrolled_count,
        (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id AND user_id = ?) AS is_enrolled
        FROM courses c";
$stmt_courses = $conn->prepare($sql);
$stmt_courses->bind_param("i", $user_id);
$stmt_courses->execute();
$result = $stmt_courses->get_result();

// Handle enrollment and deregistration
if (isset($_GET['action']) && isset($_GET['course_id'])) {
    $action = $_GET['action'];
    $course_id = $_GET['course_id'];

    if ($action === 'enroll') {
        // Enroll the student
        $enroll_sql = "INSERT INTO enrollments (user_id, course_id) VALUES (?, ?)";
        $stmt_enroll = $conn->prepare($enroll_sql);
        $stmt_enroll->bind_param("ii", $user_id, $course_id);
        if ($stmt_enroll->execute()) {
            $_SESSION['alert_message'] = "Enrollment successful!";
            $_SESSION['alert_type'] = "success";
        } else {
            $_SESSION['alert_message'] = "Error enrolling: " . $stmt_enroll->error;
            $_SESSION['alert_type'] = "danger";
        }
        $stmt_enroll->close();
    } elseif ($action === 'deregister') {
        // Deregister the student
        $deregister_sql = "DELETE FROM enrollments WHERE user_id = ? AND course_id = ?";
        $stmt_deregister = $conn->prepare($deregister_sql);
        $stmt_deregister->bind_param("ii", $user_id, $course_id);
        if ($stmt_deregister->execute()) {
            $_SESSION['alert_message'] = "Deregistration successful!";
            $_SESSION['alert_type'] = "success";
        } else {
            $_SESSION['alert_message'] = "Error deregistering: " . $stmt_deregister->error;
            $_SESSION['alert_type'] = "danger";
        }
        $stmt_deregister->close();
    }

    header("Location: view_courses.php");
    exit();
}

include '../includes/header.php';
include '../includes/navbar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Available Courses</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4">Available Courses</h1>

    <!-- Display Alerts -->
    <?php if (isset($_SESSION['alert_message'])): ?>
        <div class="alert alert-<?php echo htmlspecialchars($_SESSION['alert_type']); ?>">
            <?php echo htmlspecialchars($_SESSION['alert_message']); ?>
        </div>
        <?php unset($_SESSION['alert_message'], $_SESSION['alert_type']); ?>
    <?php endif; ?>

    <!-- Courses Table -->
    <table class="table table-bordered table-striped shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Capacity</th>
                <th>Enrolled</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['description']); ?></td>
                <td><?php echo htmlspecialchars($row['capacity']); ?></td>
                <td><?php echo htmlspecialchars($row['enrolled_count']); ?></td>
                <td>
                    <?php if ($row['is_enrolled'] > 0) { ?>
                        <a href="view_courses.php?action=deregister&course_id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to deregister from this course?');">Deregister</a>
                    <?php } elseif ($row['enrolled_count'] < $row['capacity']) { ?>
                        <a href="view_courses.php?action=enroll&course_id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Enroll</a>
                    <?php } else { ?>
                        <span class="badge bg-danger">Full</span>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
