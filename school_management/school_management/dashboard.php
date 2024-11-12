<?php
session_start();
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Welcome, <?= $_SESSION['username'] ?>!</h2>
        <p>Role: <?= ucfirst($role) ?></p>

        <?php if ($role == 'admin'): ?>
            <a href="admin_dashboard.php" class="btn btn-primary">Admin Dashboard</a>
        <?php elseif ($role == 'lecturer'): ?>
            <a href="create_assignment.php" class="btn btn-primary">Create Assignment</a>
        <?php elseif ($role == 'student'): ?>
            <a href="view_assignments.php" class="btn btn-primary">View Assignments</a>
        <?php endif; ?>
    </div>
</body>
</html>
