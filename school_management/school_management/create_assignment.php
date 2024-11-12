<?php
session_start();
include 'config.php';

// Check if user is logged in and is a lecturer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'lecturer') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_id = $_POST['course_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $deadline = $_POST['deadline'];

    // Insert assignment into the database
    $stmt = $conn->prepare("INSERT INTO assignments (course_id, title, description, deadline) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $course_id, $title, $description, $deadline);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Assignment created successfully!";
        header("Location: create_assignment.php"); // Redirect to the same page to show the message
        exit;
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
    }
}

// Fetch courses that the lecturer teaches
$lecturer_id = $_SESSION['user_id'];
$courses = [];
$stmt = $conn->prepare("SELECT id, name FROM courses WHERE lecturer_id = ?");
$stmt->bind_param("i", $lecturer_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Assignment</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Create Assignment</h2>

        <!-- Display success or error message -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info">
                <?php echo $_SESSION['message']; ?>
                <?php unset($_SESSION['message']); // Clear the message after displaying it ?>
            </div>
        <?php endif; ?>

        <form action="create_assignment.php" method="POST">
            <div class="form-group">
                <label for="course_id">Course</label>
                <select class="form-control" id="course_id" name="course_id" required>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?= $course['id'] ?>"><?= $course['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="title">Assignment Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="deadline">Deadline</label>
                <input type="date" class="form-control" id="deadline" name="deadline" required>
            </div>
            <button type="submit" class="btn btn-primary">Create Assignment</button>
        </form>
    </div>
</body>
</html>
