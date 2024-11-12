<?php
session_start();
include 'config.php';

// Check if user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['assignment_id'])) {
    $assignment_id = $_GET['assignment_id'];
    $student_id = $_SESSION['user_id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Handle file upload
        $file = $_FILES['file'];

        // Define upload directory
        $upload_dir = "uploads/";
        $file_path = $upload_dir . basename($file['name']);

        // Check if file is uploaded successfully
        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            // Insert submission into the database
            $stmt = $conn->prepare("INSERT INTO assignment_submissions (assignment_id, student_id, file_path) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $assignment_id, $student_id, $file_path);

            if ($stmt->execute()) {
                $_SESSION['message'] = "Assignment submitted successfully!";
                header("Location: view_assignments.php");
                exit;
            } else {
                $_SESSION['message'] = "Error: " . $stmt->error;
            }
        } else {
            $_SESSION['message'] = "File upload failed!";
        }
    }
} else {
    header("Location: view_assignments.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Assignment</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Submit Assignment</h2>

        <form action="submit_assignment.php?assignment_id=<?= $assignment_id ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="file">Upload File</label>
                <input type="file" class="form-control" id="file" name="file" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit Assignment</button>
        </form>
    </div>
</body>
</html>
