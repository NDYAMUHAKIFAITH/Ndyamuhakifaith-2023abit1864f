<?php
session_start();
include 'config.php';

// Check if user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit;
}

// Fetch assignments for courses the student is enrolled in
$student_id = $_SESSION['user_id'];
$assignments = [];

$stmt = $conn->prepare("SELECT a.id, a.title, a.description, a.deadline, c.name as course_name 
                        FROM assignments a
                        JOIN courses c ON a.course_id = c.id
                        JOIN enrollments e ON e.course_id = c.id
                        WHERE e.student_id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if any assignments exist
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $assignments[] = $row;
    }
} else {
    $_SESSION['message'] = "No assignments available for your enrolled courses.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Assignments</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Assignments</h2>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info">
                <?php echo $_SESSION['message']; ?>
                <?php unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

        <?php if (count($assignments) == 0): ?>
            <p>No assignments available.</p>
        <?php else: ?>
            <ul class="list-group">
                <?php foreach ($assignments as $assignment): ?>
                    <li class="list-group-item">
                        <h5><?= htmlspecialchars($assignment['title']) ?></h5>
                        <p><strong>Course:</strong> <?= htmlspecialchars($assignment['course_name']) ?></p>
                        <p><strong>Description:</strong> <?= htmlspecialchars($assignment['description']) ?></p>
                        <p><strong>Deadline:</strong> <?= htmlspecialchars($assignment['deadline']) ?></p>
                        <a href="submit_assignment.php?assignment_id=<?= $assignment['id'] ?>" class="btn btn-success">Submit Assignment</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</body>
</html>
