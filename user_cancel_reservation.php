<!DOCTYPE html>
<html>
<head>
    <title>Cancel a Reservation</title>
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
            $servername = "localhost"; //Server name
            $username = "phpuser"; //Database username
            $password = "phpwd"; //Database password
            $dbname = "PARKING_SYSTEM"; //Database name

            //Creating a connection to the database
            $conn = new mysqli($servername, $username, $password, $dbname);

            //Checking the connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            //Cleaning input to make sure data is safe before using for database
            $confirmation_number = $conn->real_escape_string($_POST['confirmation_number']);

            //Getting the reservation date
            $sqlFetchDate = "SELECT event_date FROM Reservations WHERE Confirmation_number = ?";
            $stmtFetchDate = $conn->prepare($sqlFetchDate);
            $stmtFetchDate->bind_param("i", $confirmation_number);
            $stmtFetchDate->execute();
            $resultFetchDate = $stmtFetchDate->get_result();

            if ($resultFetchDate->num_rows > 0) {
                $row = $resultFetchDate->fetch_assoc();
                $reservationDate = new DateTime($row['event_date']);
                $currentDate = new DateTime();
                $currentDate->modify('+2 day');

                if ($currentDate > $reservationDate) {
                    echo "<p style='color: red;'>Cannot cancel reservation. Cancellations must be made at least 3 days in advance.</p>";
                } else {
                    //SQL to cancel a reservation
                    $sqlCancel = "UPDATE Reservations SET status = FALSE WHERE Confirmation_number = ?";
                    $stmtCancel = $conn->prepare($sqlCancel);
                    $stmtCancel->bind_param("i", $confirmation_number);
                    if ($stmtCancel->execute()) {
                        echo "<p>Reservation cancelled successfully.</p>";
                    } else {
                        echo "<p>Error canceling reservation: " . $stmtCancel->error . "</p>";
                    }
                    $stmtCancel->close();
                }
                $stmtFetchDate->close();
            } else {
                echo "<p>Invalid confirmation number.</p>";
            }

            //Close connection
            $conn->close();
        }
        ?>
    </div>
</div>
</body>
</html>
