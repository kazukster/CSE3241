<!DOCTYPE html>
<html>
<head>
    <title>Login and Sign Up Page</title>
</head>
<body>
    <h2>Login</h2>
    <form method="post">
        <label for="username_id_login">Username ID:</label>
        <input type="text" id="username_id_login" name="username_id" required><br>
        <label for="password_login">Password:</label>
        <input type="password" id="password_login" name="password" required><br>
        <input type="submit" name="login" value="Login">
    </form>

    <h2>Sign Up</h2>
    <form method="post">
        <label for="username_id_signup">Username ID:</label>
        <input type="text" id="username_id_signup" name="username_id_signup" required><br>
        <label for="cellphone_signup">Cellphone:</label>
        <input type="text" id="cellphone_signup" name="cellphone_signup" required><br>
        <label for="password_signup">Password:</label>
        <input type="password" id="password_signup" name="password_signup" required><br>
        <label for="password_confirm">Confirm Password:</label>
        <input type="password" id="password_confirm" name="password_confirm" required><br>
        <input type="submit" name="signup" value="Sign Up">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $servername = "localhost";
        $dbusername = "phpuser"; // Replace with your DB username
        $dbpassword = "phpwd"; // Replace with your DB password
        $dbname = "PARKING_SYSTEM"; // Replace with your DB name

        // Create database connection
        $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if (isset($_POST['login'])) {
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

                    // Set session variable
                    session_start();
                    $_SESSION['Username_ID'] = $username_id;
                    
                    // Redirect to a user-specific page
                    header("Location: user_page.php");
                    exit;
                } else {
                    echo "Invalid username or password.";
                }
            }
        }elseif (isset($_POST['signup'])) {
            $username_id = $conn->real_escape_string($_POST['username_id_signup']);
            $password = $_POST['password_signup'];
            $password_confirm = $_POST['password_confirm'];
        
            // Check if the cellphone key exists in the POST data
            if (!isset($_POST['cellphone_signup'])) {
                echo "Cellphone number is required.";
                exit; // Stop the script if the cellphone number is not provided
            }
        
            $cellphone = $conn->real_escape_string($_POST['cellphone_signup']);

            // Check if the two passwords match
            if ($password !== $password_confirm) {
                echo "Passwords do not match.";
            } else {
                // Check if username or cellphone already exists
                $checkUser = $conn->prepare("SELECT * FROM users WHERE Username_ID = ? OR Cellphone = ?");
                $checkUser->bind_param("ss", $username_id, $cellphone);
                $checkUser->execute();
                $result = $checkUser->get_result();
                if ($result->num_rows > 0) {
                    echo "Username or cellphone already exists.";
                } else {
                    // Insert new user into the database
                    $insertUser = $conn->prepare("INSERT INTO users (Username_ID, Cellphone, Password) VALUES (?, ?, ?)");
                    $insertUser->bind_param("sss", $username_id, $cellphone, $password); // In a real-world scenario, password should be hashed
                    if ($insertUser->execute()) {
                        echo "User registered successfully.";
                    } else {
                        echo "Error registering user: " . $insertUser->error;
                    }
                    $insertUser->close();
                }
                $checkUser->close();
            }
        }
        $conn->close();
    }
    ?>
</body>
</html>
