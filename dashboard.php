<?php
session_start();
include 'db.php';  // Include the database connection

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch user's tasks
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM tasks WHERE user_id='$user_id'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>
    <header>
        <div class="container">
            <h1>Task Management System</h1>
            <nav>
                <ul>
                    <li><a href="logout.php">Logout</a></li>
                    <li>
                        <a href="delete_account.php" onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">Delete Account</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="intro">
        <div class="container">
            <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
            <p>Your Task Management Dashboard</p>
        </div>
    </section>

    <section class="tasks">
        <div class="container">
            <h3>Your Tasks</h3>
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['title']; ?></td>
                            <td><?php echo $row['description']; ?></td>
                            <td><?php echo $row['category']; ?></td>
                            <td class="priority-<?php echo strtolower($row['priority']); ?>">
                                <?php echo $row['priority']; ?>
                            </td>
                            <td><?php echo $row['status']; ?></td>
                            <td><?php echo $row['created_at']; ?></td>
                            <td>
                                <a href="task_update.php?task_id=<?php echo $row['id']; ?>">Edit</a> |
                                <a href="task_delete.php?task_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this task?');">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="create-task">
    <div class="container">
        <form action="task_create.php" method="POST">
            <h3>Create a New Task</h3> <!-- Heading moved inside form -->
            <label for="title">Task Title:</label>
            <input type="text" name="title" id="title" required><br>

            <label for="description">Description:</label>
            <textarea name="description" id="description" required></textarea><br>

            <label for="category">Category:</label>
            <input type="text" name="category" id="category" required><br>

            <label for="priority">Priority:</label>
            <select name="priority" id="priority" required>
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
            </select><br>

            <button type="submit" name="create_task">Create Task</button>
        </form>
    </div>
    </section>




    <footer>
        <p>&copy; 2024 Task Management System</p>
    </footer>
</body>
</html>
