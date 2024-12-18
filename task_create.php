<?php
session_start();
include 'db.php';  // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $priority = $_POST['priority'];
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO tasks (title, description, category, priority, user_id, status, created_at)
            VALUES ('$title', '$description', '$category', '$priority', '$user_id', 'Pending', NOW())";

    if ($conn->query($sql) === TRUE) {
        echo "Task created successfully!";
        header('Location: dashboard.php');
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

?>
