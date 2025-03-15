<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $capacity = (int)$_POST['capacity'];

    if (empty($name) || empty($description) || empty($capacity)) {
        $_SESSION['alert_message'] = "All fields are required!";
        $_SESSION['alert_type'] = "danger";
        header("Location: add_course.php");
        exit();
    }

    $sql = "INSERT INTO courses (name, description, capacity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $name, $description, $capacity);

    if ($stmt->execute()) {
        $_SESSION['alert_message'] = "Course added successfully!";
        $_SESSION['alert_type'] = "success";
        header("Location: manage_courses.php");
    } else {
        $_SESSION['alert_message'] = "Error adding course: " . $stmt->error;
        $_SESSION['alert_type'] = "danger";
        header("Location: add_course.php");
    }

    $stmt->close();
    $conn->close();
}

include '../includes/header.php'; 
include '../includes/navbar.php'; 
?>

<div class="container mt-4">
    <h1 class="mb-4">Add New Course</h1>

    <!-- Alert System -->
    <?php if (isset($_SESSION['alert_message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['alert_type']; ?> alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['alert_message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['alert_message'], $_SESSION['alert_type']); ?>
    <?php endif; ?>

    <!-- Course Form -->
    <form method="POST" action="add_course.php" class="p-4 border rounded bg-light">
        <div class="mb-3">
            <label for="name" class="form-label">Course Name</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Enter course name" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Course Description</label>
            <textarea name="description" id="description" class="form-control" rows="4" placeholder="Enter course description" required></textarea>
        </div>
        <div class="mb-3">
            <label for="capacity" class="form-label">Capacity</label>
            <input type="number" name="capacity" id="capacity" class="form-control" placeholder="Enter course capacity" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Course</button>
        <a href="manage_courses.php" class="btn btn-secondary">Back to Courses</a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
