<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/final%20project/index.php">CMS</a>
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user_id']) && isset($_SESSION['role'])): ?>
                    <!-- General Links for Logged-in Users -->
                    <li class="nav-item"><a class="nav-link" href="/final%20project/pages/profile.php">Profile</a></li>

                    <!-- Admin Role Links -->
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <li class="nav-item"><a class="nav-link" href="/final%20project/pages/admin_dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="/final%20project/pages/manage_users.php">Users</a></li>
                        <li class="nav-item"><a class="nav-link" href="/final%20project/pages/manage_courses.php">Courses</a></li>

                    <!-- Instructor Role Links -->
                    <?php elseif ($_SESSION['role'] === 'instructor'): ?>
                        <li class="nav-item"><a class="nav-link" href="/final%20project/pages/instructor_dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="/final%20project/pages/manage_grades.php">Grades</a></li>

                    <!-- Student Role Links -->
                    <?php elseif ($_SESSION['role'] === 'student'): ?>
                        <li class="nav-item"><a class="nav-link" href="/final%20project/pages/student_dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="/final%20project/pages/view_courses.php">Courses</a></li>
                        <li class="nav-item"><a class="nav-link" href="/final%20project/pages/enrollments.php">Enrollments</a></li>
                        <li class="nav-item"><a class="nav-link" href="/final%20project/pages/assignments.php">Assignments</a></li>


                    <?php endif; ?>
                    <?php if (isset($_SESSION['user_id'])): ?>     <!-- Logout Option -->
                    <li class="nav-item"><a class="nav-link text-danger" href="/final%20project/pages/logout.php">Logout</a></li>
                    <?php endif; ?>


                <!-- Guest Links -->
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="/final%20project/pages/login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="/final%20project/pages/register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
