<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete user from the database
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "User deleted successfully!";
        header("Location: manage_users.php");
        exit;
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
    }
} else {
    $_SESSION['message'] = "Invalid user ID!";
    header("Location: manage_users.php");
    exit;
}
?>
