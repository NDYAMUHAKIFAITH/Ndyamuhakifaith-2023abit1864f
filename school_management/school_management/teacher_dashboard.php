<?php
session_start();
if ($_SESSION['role'] != 'teacher') {
    header("Location: login.php");
    exit;
}

include 'config.php';

// Fetch classes assigned to the teacher
$teacher_id = $_SESSION['user_id'];
$classes_result = $conn->query("SELECT * FROM classes WHERE teacher_id = $teacher_id");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Teacher Dashboard</h2>
    <h3>Your Classes</h3>
    <ul class="list-group">
        <?php while ($class = $classes_result->fetch_assoc()): ?>
        <li class="list-group-item">
            <?php echo $class['class_name']; ?> 
            <a href="attendance.php?class_id=<?php echo $class['id']; ?>" class="btn btn-info btn-sm float-right ml-2">Mark Attendance</a>
            <a href="grades.php?class_id=<?php echo $class['id']; ?>" class="btn btn-success btn-sm float-right">Manage Grades</a>
        </li>
        <?php endwhile; ?>
    </ul>
</div>
</body>
</html>
