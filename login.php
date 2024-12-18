<?php
session_start();
include 'db.php';  // Include the database connection

$error = ''; // To store error message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use a prepared statement to fetch the user data from the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            // Redirect to dashboard
            header('Location: dashboard.php');
            exit;
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css"> <!-- External CSS -->
    <style>
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block; /* Places the label above the input field */
            margin-bottom: 5px;
        }

        .password-container {
            position: relative;
            display: block;
            width: 100%;
        }

        .password-container input[type="password"] {
            width: 100%;
            padding-right: 50px; /* Add padding to avoid overlap with the toggle text */
        }

        .password-container .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
            font-size: 14px;
            font-weight: bold;
        }

        .password-container .toggle-password:hover {
            color: #555; /* Change color on hover for better UX */
        }
    </style>
</head>
<body>
    <header>
        <h1>Login</h1>
    </header>

    <section class="login-form">
        <div class="container">
            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <div class="password-container">
                        <input type="password" id="password" name="password" required>
                        <span class="toggle-password" onclick="togglePassword()">Show</span>
                    </div>
                </div>

                <!-- Error message display -->
                <?php if (!empty($error)) { ?>
                    <div class="error-message">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php } ?>

                <button type="submit" class="btn primary">Login</button>
            </form>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 Task Management System</p>
    </footer>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const toggleText = document.querySelector(".toggle-password");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleText.textContent = "Hide"; // Change to "Hide" when visible
            } else {
                passwordInput.type = "password";
                toggleText.textContent = "Show"; // Change back to "Show" when hidden
            }
        }
    </script>
</body>
</html>
