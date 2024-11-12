<?php
session_start();
include 'config.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_name = $_POST['course_name'];
    $lecturer_id = $_POST['lecturer_id'];
    $course_description = $_POST['course_description'];

    // Insert new course into the database
    $stmt = $conn->prepare("INSERT INTO courses (name, lecturer_id, description) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $course_name, $lecturer_id, $course_description);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Course created successfully!";
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
    }
}

// Fetch all lecturers to assign them to a course
$lecturers = [];
$stmt = $conn->prepare("SELECT id, name FROM users WHERE role = 'lecturer'");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $lecturers[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Course</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Create Course</h2>

        <form action="create_course.php" method="POST">
            <div class="form-group">
                <label for="course_name">Course Name</label>
                <input type="text" class="form-control" id="course_name" name="course_name" required>
            </div>
            <div class="form-group">
                <label for="lecturer_id">Lecturer</label>
                <select class="form-control" id="lecturer_id" name="lecturer_id" required>
                    <?php foreach ($lecturers as $lecturer): ?>
                        <option value="<?= $lecturer['id'] ?>"><?= $lecturer['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="course_description">Course Description</label>
                <textarea class="form-control" id="course_description" name="course_description" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Create Course</button>
        </form>
    </div>
</body>
</html>
