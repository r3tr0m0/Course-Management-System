<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

// Fetch course data
if (isset($_GET['id'])) {
    $course_id = $_GET['id'];
    $sql = "SELECT * FROM courses WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $course = $result->fetch_assoc();
    $stmt->close();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $course_id = $_POST['id'];
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $capacity = (int)$_POST['capacity'];

    $sql_update = "UPDATE courses SET name = ?, description = ?, capacity = ? WHERE id = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("ssii", $name, $description, $capacity, $course_id);

    if ($stmt->execute()) {
        echo "Course updated successfully! <a href='manage_courses.php'>Back to Courses</a>";
    } else {
        echo "Error updating course: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

include '../includes/header.php'; 
include '../includes/navbar.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Course</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Edit Course</h1>
    <form method="POST" action="edit_course.php">
        <input type="hidden" name="id" value="<?php echo $course['id']; ?>">
        <label>Name:</label><br>
        <input type="text" name="name" value="<?php echo $course['name']; ?>" required><br>
        <label>Description:</label><br>
        <textarea name="description" required><?php echo $course['description']; ?></textarea><br>
        <label>Capacity:</label><br>
        <input type="number" name="capacity" value="<?php echo $course['capacity']; ?>" required><br><br>
        <button type="submit">Update Course</button>
    </form>
</body>
</html>

<?php include '../includes/footer.php'; ?>