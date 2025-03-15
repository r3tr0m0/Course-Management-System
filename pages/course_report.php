<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

// Handle search query
$search = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';

// Fetch courses with enrollment counts
$sql = "SELECT c.name, c.description, c.capacity,
        (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id) AS enrolled_count
        FROM courses c
        WHERE c.name LIKE ? OR c.description LIKE ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $search, $search);
$stmt->execute();
$result = $stmt->get_result();

include '../includes/header.php'; 
include '../includes/navbar.php'; 
?>

<div class="container mt-4">
    <h1 class="mb-4">Course Report</h1>

    <!-- Alert System -->
    <?php if (isset($_SESSION['alert_message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['alert_type']; ?> alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['alert_message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['alert_message'], $_SESSION['alert_type']); ?>
    <?php endif; ?>

    <!-- Search Form -->
    <form method="GET" action="course_report.php" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search courses" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <!-- Course Table -->
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Course Name</th>
                <th>Description</th>
                <th>Capacity</th>
                <th>Enrolled Students</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo $row['capacity']; ?></td>
                    <td><?php echo $row['enrolled_count']; ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">No courses found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="admin_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>

<?php include '../includes/footer.php'; ?>
