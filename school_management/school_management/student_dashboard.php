<?php
session_start();
if ($_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit;
}

include 'config.php';

// Fetch student's enrolled classes
$student_id = $_SESSION['user_id'];
$classes_result = $conn->query("SELECT * FROM classes WHERE id IN (SELECT class_id FROM students WHERE user_id = $student_id)");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Student Dashboard</h2>
    <h3>Your Enrolled Classes</h3>
    <ul class="list-group">
        <?php while ($class = $classes_result->fetch_assoc()): ?>
        <li class="list-group-item">
            <?php echo $class['class_name']; ?> 
            <a href="view_grades.php?class_id=<?php echo $class['id']; ?>" class="btn btn-success btn-sm float-right ml-2">View Grades</a>
            <a href="view_attendance.php?class_id=<?php echo $class['id']; ?>" class="btn btn-info btn-sm float-right">View Attendance</a>
        </li>
        <?php endwhile; ?>
    </ul>
</div>
</body>
</html>
