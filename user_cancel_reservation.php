<!-- add later -->
<!DOCTYPE html>
<html>
<head>
    <title>Adding a reservation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
        .header {
            background: #333;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }
        .nav {
            padding: 15px;
            background: #e3e3e3;
            margin-top: 10px;
        }
        .nav a {
            margin-right: 15px;
            text-decoration: none;
            color: #333;
        }
    </style>
</head>
<body>
<div class="container">
        <div class="header">
            <h1>Cancel a Reservation</h1>
        </div>
        <div class="nav">
            <a href="user_add_reservation.php">Add a Reservation</a>
            <a href="user_cancel_reservation.php">Cancel a Reservation</a>
            <a href="user_reservation_history.php">View Reservation History</a>
            <a href="user_logout.php">Logout</a>
        </div>
        <div class="content">
            <h2>Enter the Confirmation Number to Cancel Your Reservation</h2>
            <form method="post" action="user_cancel_reservation.php">
                <label for="confirmation_number">Confirmation Number:</label>
                <input type="text" id="confirmation_number" name="confirmation_number" required>
                <button type="submit">Cancel Reservation</button>
            </form>

            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $servername = "localhost"; // Replace with your server details
                $username = "phpuser"; // Your DB username
                $password = "phpwd"; // Your DB password
                $dbname = "PARKING_SYSTEM"; // Your DB name

                // Create database connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Sanitize input
                $confirmation_number = $conn->real_escape_string($_POST['confirmation_number']);

                // SQL to cancel a reservation
                $sql = "UPDATE Reservations SET status = FALSE WHERE Confirmation_number = ?";

                // Prepare and bind
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $confirmation_number);

                // Execute and check if successful
                if ($stmt->execute()) {
                    echo "<p>Reservation cancelled successfully.</p>";
                } else {
                    echo "<p>Error cancelling reservation: " . $stmt->error . "</p>";
                }

                // Close statement and connection
                $stmt->close();
                $conn->close();
            }
            ?>
        </div>
    </div>
</body>
</html>

