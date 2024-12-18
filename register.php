<?php
session_start();
include 'db.php'; // Include the database connection

$error = ''; // Initialize the error variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email']; // Get the email from the form
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure password hashing

    // Check if the user already exists
    $check_user_sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($check_user_sql);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // User already exists
        $error = "Username or Email is already taken!";
    } else {
        // Insert new user into the database
        $insert_sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("sss", $username, $email, $password);
        
        if ($stmt->execute()) {
            // Registration successful, redirect to login page
            $_SESSION['message'] = "Registration successful! Please login.";
            header('Location: login.php');
            exit;
        } else {
            // If insert fails
            $error = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
            padding-right: 50px; /* Add padding to avoid overlap with the text */
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
        <h1>Register</h1>
    </header>

    <section class="register-form">
        <div class="container">
            <form action="register.php" method="POST">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <div class="password-container">
                        <input type="password" id="password" name="password" required>
                        <span class="toggle-password" onclick="togglePassword()">Show</span>
                    </div>
                </div>

                <!-- Error message -->
                <?php if (!empty($error)) { ?>
                    <div class="error-message">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php } ?>

                <button type="submit" class="btn primary">Register</button>
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
