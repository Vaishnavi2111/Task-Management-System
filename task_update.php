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
    
    // Fetch the task details using a prepared statement
    $stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $task_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $task = $result->fetch_assoc();
    } else {
        header('Location: dashboard.php');  // Task not found or user not authorized
        exit;
    }
}

if (isset($_POST['update_task'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category']; // Added category
    $priority = $_POST['priority']; // Added priority
    $status = $_POST['status']; // Correctly handle status as string

    // Update the task in the database with category and priority
    $stmt = $conn->prepare("UPDATE tasks SET title = ?, description = ?, category = ?, priority = ?, status = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ssssssi", $title, $description, $category, $priority, $status, $task_id, $_SESSION['user_id']); // Fixed status type
    $stmt->execute();

    header('Location: dashboard.php');  // Redirect to dashboard after update
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Update Task</title>
</head>
<body>
    <header>
        <h1>Update Task</h1>
    </header>
    <form action="task_update.php?task_id=<?php echo $task_id; ?>" method="POST">
        <label for="title">Task Title:</label>
        <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8'); ?>" required><br>

        <label for="description">Description:</label>
        <textarea name="description" id="description" required><?php echo htmlspecialchars($task['description'], ENT_QUOTES, 'UTF-8'); ?></textarea><br>

        <label for="category">Category:</label>
        <input type="text" name="category" id="category" value="<?php echo htmlspecialchars($task['category'], ENT_QUOTES, 'UTF-8'); ?>" required><br>

        <label for="priority">Priority:</label>
        <select name="priority" id="priority" required>
            <option value="Low" <?php if ($task['priority'] == 'Low') echo 'selected'; ?>>Low</option>
            <option value="Medium" <?php if ($task['priority'] == 'Medium') echo 'selected'; ?>>Medium</option>
            <option value="High" <?php if ($task['priority'] == 'High') echo 'selected'; ?>>High</option>
        </select><br>

        <label for="status">Status:</label>
        <select name="status" id="status" required>
            <option value="Pending" <?php echo ($task['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
            <option value="In Progress" <?php echo ($task['status'] == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
            <option value="Completed" <?php echo ($task['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
        </select><br>

        <button type="submit" name="update_task">Update Task</button>
    </form>
</body>
</html>
