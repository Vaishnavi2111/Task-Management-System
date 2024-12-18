<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (isset($_POST['confirm_delete'])) {
    $user_id = $_SESSION['user_id'];

    // Delete tasks and account
    $conn->query("DELETE FROM tasks WHERE user_id='$user_id'");
    $conn->query("DELETE FROM users WHERE id='$user_id'");

    // Destroy session and redirect to index page
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    
    // Debugging log
    error_log("Redirecting to index.html"); 

    header('Location: /Task Management System/index.html'); // Redirect to index
    exit;
}

if (isset($_POST['cancel_delete'])) {
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Account</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <form method="POST">
        <h3>Are you sure you want to delete your account?</h3>
        <button type="submit" name="confirm_delete">Yes, Delete My Account</button>
        <button type="submit" name="cancel_delete">Cancel</button>
    </form>
</body>
</html>
