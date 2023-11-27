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
            <p>You have opted for option 1: <br> <br>
            1. We will display all zones that still have available spots, their number of available spots, and the rates for the date you enter. <br>
            </p>
        </div>
    </div>
    
    <h2>Please view your choices and select the zone you would like to use by entering the zone ID (finalizing your reservation)!</h2>


<?php 
$servername = "localhost";
$username = "phpuser";
$password = "phpwd";
$dbname = "PARKING_SYSTEM";
$conn = new mysqli($servername, $username, $password, $dbname);
$selectedEventName = $_SESSION['selectedEvent'];

// Query to get available zones for the selected event
    $sql = "SELECT z.zone_ID, z.zone_name, (z.total_spots - COUNT(r.Confirmation_number)) AS available_spots, z.rate,
        MAX(zd.distance_miles) AS distance_miles
        FROM Zones z
        LEFT JOIN Reservations r ON z.zone_ID = r.zone_id
        LEFT JOIN ZoneEventsDistances zd ON z.zone_ID = zd.zone_ID
        LEFT JOIN Events e ON zd.event_ID = e.event_ID
        WHERE e.event_name = '$selectedEventName'
        GROUP BY z.zone_ID, z.zone_name, z.total_spots, z.rate";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Display available zones
        echo "<h2>Available Zones for Event: $selectedEventName</h2>";
        echo "<table border='1'>
                <tr>
                    <th>Zone ID</th>
                    <th>Zone Name</th>
                    <th>Available Spots</th>
                    <th>Rate</th>
                    <th>Distance (miles)</th>
                </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['zone_ID']}</td>
                    <td>{$row['zone_name']}</td>
                    <td>{$row['available_spots']}</td>
                    <td>{$row['rate']}</td>
                    <td>{$row['distance_miles']}</td>
                  </tr>";

            // Reservation form
            echo "<h2>Make Reservation</h2>";
            echo "<form method='post' action=''>
                    <input type='hidden' name='selected_zone' value='{$row['zone_ID']}'>
                    <input type='hidden' name='username' value='{$_SESSION['Username_ID']}'>
                    <input type='hidden' name='phone' value='{$_SESSION['Cellphone']}'>
                    <button type='submit'>Make Reservation</button>
                  </form>";

            // Process reservation submission
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $selectedZone = $_POST["selected_zone"];
                $username = $_POST["username"];
                $phone = $_POST["phone"];

                // Query to get the rate for the selected zone
                $zoneQuery = "SELECT rate FROM Zones WHERE zone_ID = '$selectedZone'";
                $zoneResult = $conn->query($zoneQuery);

                if ($zoneResult->num_rows > 0) {
                    $zoneRow = $zoneResult->fetch_assoc();
                    $zoneRate = $zoneRow['rate'];

                    // Calculate total fee
                    $totalFee = $zoneRate;

                    // Insert reservation into the database
                    $insertSql = "INSERT INTO Reservations (user_name, Cellphone, zone_id, event_date, status, total_fee)
                                  VALUES ('$username', '$phone', '$selectedZone', NOW(), true, $totalFee)";

                    if ($conn->query($insertSql) === TRUE) {
                        // Display confirmation number
                        $confirmationNumber = $conn->insert_id;
                        echo "<p>Reservation successful! Your confirmation number is: $confirmationNumber</p>";
                    } else {
                        echo "Error: " . $insertSql . "<br>" . $conn->error;
                    }
                } else {
                    echo "Error: Unable to retrieve zone rate.";
                }
            }
        }

        echo "</table>";
    } else {
        echo "<p>No available zones for event: $selectedEventName.</p>";
    }

    $conn->close();


?>
</body>
</html>
