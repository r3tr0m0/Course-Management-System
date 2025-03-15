<?php
session_start();
include 'includes/header.php';
include 'includes/navbar.php';

// Check if the user is logged in and retrieve their role and username
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
?>

<!-- Welcome Banner -->
<div class="container mt-5">
    <div class="row align-items-center bg-center">
        <div class="col-md-12 text-center text-white">
            <?php if (!$role): ?>
                <!-- Guest View -->
                <h1 class="mb-4">Welcome to the Course Management System</h1>
                <p class="lead">Manage courses, enrollments, and grades seamlessly with our intuitive platform.</p>
                <div class="mt-4">
                    <a href="pages/login.php" class="btn btn-primary btn-lg">Login</a>
                    <a href="pages/register.php" class="btn btn-secondary btn-lg">Register</a>
                </div>
            <?php elseif ($role === 'admin'): ?>
                <!-- Admin View -->
                <h1 class="mb-4">Welcome, Admin <?php echo htmlspecialchars($username); ?>!</h1>
                <p class="lead">Manage the system, users, and courses with ease.</p>
                <div class="mt-4">
                    <a href="pages/admin_dashboard.php" class="btn btn-primary btn-lg">Go to Dashboard</a>
                    <a href="pages/manage_users.php" class="btn btn-secondary btn-lg">Manage Users</a>
                </div>
            <?php elseif ($role === 'instructor'): ?>
                <!-- Instructor View -->
                <h1 class="mb-4">Welcome, Instructor <?php echo htmlspecialchars($username); ?>!</h1>
                <p class="lead">Manage your courses, students, and grades effortlessly.</p>
                <div class="mt-4">
                    <a href="pages/instructor_dashboard.php" class="btn btn-primary btn-lg">View My Courses</a>
                    <a href="pages/manage_grades.php" class="btn btn-secondary btn-lg">Manage Grades</a>
                </div>
            <?php elseif ($role === 'student'): ?>
                <!-- Student View -->
                <h1 class="mb-4">Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
                <p class="lead">Browse and enroll in courses, and track your progress.</p>
                <div class="mt-4">
                    <a href="pages/student_dashboard.php" class="btn btn-primary btn-lg">My Dashboard</a>
                    <a href="pages/view_courses.php" class="btn btn-secondary btn-lg">View Courses</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Features Section -->
<?php if (!$role): ?>
<div class="container mt-5">
    <h2 class="text-center mb-4">Features</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Admin Panel</h5>
                    <p class="card-text">Manage users, courses, and generate reports with ease.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Instructor Tools</h5>
                    <p class="card-text">View enrolled students and assign grades effortlessly.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Student Portal</h5>
                    <p class="card-text">Browse and enroll in courses, and track your grades.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
