<!-- add later -->
<html>
<head>
    <title>Viewing Reservation History</title>
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
        .content {
            margin-top: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .active {
            color: green;
        }
        .cancelled {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Adding a reservation</h1>
        </div>
        <div class="nav">
            <a href="user_add_reservation.php">Add a Reservation</a>
            <a href="user_cancel_reservation.php">Cancel a Reservation</a>
            <a href="user_reservation_history.php">View Reservation History</a>
            <a href="user_logout.php">Logout</a> 
        </div>
        <div class="content">
            <h2>Welcome, <?php session_start(); echo $_SESSION['Username_ID']; ?>!</h2>
            <p>This is where you can view your reservartion history, enter in either the phone number or a confirmation number from the user <br>
		you wish to view history for and it will be printed below! </p>
        </div>
    </div>
    <h2>User Reservations</h2>
    <form method="post">
        <label for="search">Enter Confirmation Number or Cellphone:</label>
        <input type="text" id="search" name="search" required>
        <input type="submit" name="submit" value="Show Reservations">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
        $servername = "localhost";
        $dbusername = "phpuser";
        $dbpassword = "phpwd";
        $dbname = "parking_system";

        // Create database connection
        $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $input = $conn->real_escape_string($_POST['search']);
        $isCellphone = strlen($input) == 10;

        // Initialize the variable to hold the cellphone number
        $cellphone = "";

        if ($isCellphone) {
            $cellphone = $input;
        } else {
            // It's a confirmation number, get the associated cellphone
            $confirm_sql = "SELECT Cellphone FROM Reservations WHERE Confirmation_number = ?";
            $confirm_stmt = $conn->prepare($confirm_sql);
            $confirm_stmt->bind_param("i", $input);
            $confirm_stmt->execute();
            $confirm_result = $confirm_stmt->get_result();
            if ($confirm_result->num_rows > 0) {
                $cellphone_row = $confirm_result->fetch_assoc();
                $cellphone = $cellphone_row['Cellphone'];
            } else {
                echo "No reservations found for the provided confirmation number.";
                $confirm_stmt->close();
                $conn->close();
                exit;
            }
            $confirm_stmt->close();
        }

        // Now query for all reservations using the cellphone number
        $sql = "SELECT Confirmation_number, user_name, Cellphone, zone_id, event_date, total_fee, status
                FROM Reservations
                WHERE Cellphone = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $cellphone);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<table><tr><th>Confirmation #</th><th>User Name</th><th>Cellphone</th><th>Zone Number</th><th>Date</th><th>Fee</th><th>Status</th></tr>";
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $status = $row["status"] ? "Active" : "Cancelled";
                $status_class = $row["status"] ? "active" : "cancelled";
                echo "<tr><td>".$row["Confirmation_number"]."</td><td>".$row["user_name"]."</td><td>".$row["Cellphone"]."</td><td>".$row["zone_id"]."</td><td>".$row["event_date"]."</td><td>".$row["total_fee"]."</td><td class=\"".$status_class."\">".$status."</td></tr>";
            }
            echo "</table>";
        } else {
            echo "No reservations found for the provided cellphone number.";
        }

        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>
