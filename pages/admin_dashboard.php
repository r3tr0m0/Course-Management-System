<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

// Fetch counts for statistics
$total_users = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];
$total_courses = $conn->query("SELECT COUNT(*) AS count FROM courses")->fetch_assoc()['count'];
$total_enrollments = $conn->query("SELECT COUNT(*) AS count FROM enrollments")->fetch_assoc()['count'];

// Fetch user counts by role
$user_roles = $conn->query("SELECT role, COUNT(*) AS count FROM users GROUP BY role");

include '../includes/header.php'; 
include '../includes/navbar.php'; 
?>

<div class="container mt-4">
    <h1 class="mb-4">Welcome, Admin <?php echo $_SESSION['username']; ?>!</h1>
    <div class="row">
        <!-- System Overview -->
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Total Users</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $total_users; ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Total Courses</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $total_courses; ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Total Enrollments</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $total_enrollments; ?></h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Users by Role -->
    <h3 class="mt-4">Users by Role</h3>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Role</th>
                <th>Count</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($role = $user_roles->fetch_assoc()) { ?>
            <tr>
                <td><?php echo ucfirst($role['role']); ?></td>
                <td><?php echo $role['count']; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Quick Links -->
    <h3 class="mt-4">Quick Links</h3>
    <div class="list-group">
        <a href="manage_users.php" class="list-group-item list-group-item-action">Manage Users</a>
        <a href="manage_courses.php" class="list-group-item list-group-item-action">Manage Courses</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
