<!DOCTYPE html>
<html>
<head>
    <title>User Reservations</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .cancelled {
            color: red;
        }
        .active {
            color: green;
        }
    </style>
</head>
<body>
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
