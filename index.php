<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <form method="post">
        <label for="username_id">Username ID:</label>
        <input type="text" id="username_id" name="username_id" required><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" value="Login">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $servername = "localhost";
        $dbusername = "phpuser"; // Replace with your DB username
        $dbpassword = "phpwd"; // Replace with your DB password
        $dbname = "PARKING_SYSTEM"; // Replace with your DB name

        $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $username_id = $conn->real_escape_string($_POST['username_id']);
        $password = $_POST['password'];

        // Check for admin login
        if ($username_id === 'admin' && $password === 'admin123') {
            // Redirect to admin-specific page
            header("Location: admin_page.php");
            exit;
        } else {
            // For regular users, check against the database
            $sql = "SELECT * FROM users WHERE Username_ID = '$username_id' AND Password = '$password'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Redirect to a user-specific page
                header("Location: user_page.php");
                exit;
            } else {
                echo "Invalid username or password.";
            }
        }
        $conn->close();
    }
    ?>
</body>
</html>

