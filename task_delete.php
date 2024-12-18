<?php
session_start();
include 'db.php';  // Include the database connection

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}

if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];

    // Delete the task from the database using a prepared statement
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $task_id, $_SESSION['user_id']);
    $stmt->execute();

    header('Location: dashboard.php');  // Redirect to dashboard after deletion
}
?>
