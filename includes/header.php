<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_lifetime' => 86400, // 1 day
        'cookie_secure' => true,    // HTTPS only
        'cookie_httponly' => true, // Prevent JavaScript access
        'cookie_samesite' => 'Strict', // SameSite protection
    ]);
}

// Display alert messages if set
if (isset($_SESSION['alert_message'])): ?>
    <div class="alert alert-<?php echo $_SESSION['alert_type']; ?> alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['alert_message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['alert_message'], $_SESSION['alert_type']); ?>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/final%20project/assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Course Management System</title>
</head>
<body>

