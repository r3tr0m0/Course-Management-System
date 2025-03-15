<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

// Fetch assignments for enrolled courses
$user_id = $_SESSION['user_id'];
$sql = "SELECT a.id, a.title, a.description, a.due_date, c.name AS course_name 
        FROM assignments a 
        JOIN enrollments e ON a.course_id = e.course_id 
        JOIN courses c ON a.course_id = c.id 
        WHERE e.user_id = ?
        ORDER BY a.due_date ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$assignments = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();

include '../includes/header.php';
include '../includes/navbar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Assignments</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">My Assignments</h1>
        <p class="text-center">Here are the assignments for your enrolled courses.</p>

        <!-- Sorting Options -->
        <div class="text-center mb-3">
            <a href="?sort=due_date" class="btn btn-outline-primary">Sort by Due Date</a>
            <a href="?sort=course_name" class="btn btn-outline-secondary">Sort by Course Name</a>
        </div>

        <?php if (!empty($assignments)): ?>
            <ul class="list-group mt-4">
                <?php foreach ($assignments as $assignment): ?>
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5><?php echo htmlspecialchars($assignment['title']); ?></h5>
                                <p><?php echo htmlspecialchars($assignment['description']); ?></p>
                                <small class="text-muted">Course: <?php echo htmlspecialchars($assignment['course_name']); ?> | Due: <?php echo htmlspecialchars(date("F j, Y, g:i A", strtotime($assignment['due_date']))); ?></small>
                            </div>
                            <div class="align-self-center">
                                <a href="submit_assignment.php?assignment_id=<?php echo urlencode($assignment['id']); ?>" class="btn btn-primary btn-sm">Submit</a>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="text-muted text-center">No assignments available.</p>
        <?php endif; ?>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
