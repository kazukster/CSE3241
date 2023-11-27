<!-- add later--> 
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
            <p>This is where you will decide which method you will use to create your reservation, there are 2 options: <br>
            1. We will display all zones that still have available spots, their number of available spots, and the rates for the date you enter. <br>
            2. We will display the distance between each available zone and the venue you select, alongside the other information listed above. <br>
            Please decide which method you would like to pursue and use it's respective entry fields and click submit! <br> </p>
        </div>
    </div>
    
    <h2>Enter a Date (at least 1 day in advance of the current date)</h2>


<?php 
$servername = "localhost";
$username = "phpuser";
$password = "phpwd";
$dbname = "PARKING_SYSTEM";
$conn = new mysqli($servername, $username, $password, $dbname);

// Retrieve available zones for the selected date
$enteredDate = $_SESSION['enteredDate'];
$sql = "SELECT z.zone_id, z.zone_name, a.available_spots, a.rate 
        FROM Zones z
        JOIN Availability a ON z.zone_id = a.zone_id
        WHERE a.date = '$enteredDate' AND a.available_spots > 0";
$result = $conn->query($sql);

// Retrieve available spots for the selected date and zone
$enteredDate = $_SESSION['enteredDate'];
$sql = "SELECT z.zone_id, z.zone_name, z.total_spots, a.available_spots, a.rate 
        FROM Zones z
        LEFT JOIN (
            SELECT zone_id, SUM(num_spots) as reserved_spots
            FROM Reservations
            WHERE event_date = '$enteredDate'
            GROUP BY zone_id
        ) r ON z.zone_id = r.zone_id
        JOIN Availability a ON z.zone_id = a.zone_id
        WHERE a.date = '$enteredDate'";

$result = $conn->query($sql);

// Display available spots and their details
if ($result->num_rows > 0) {
    echo "<h2>Available Spots for $enteredDate</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Zone ID</th><th>Zone Name</th><th>Total Spots</th><th>Reserved Spots</th><th>Available Spots</th><th>Rate</th></tr>";
    while ($row = $result->fetch_assoc()) {
        $totalSpots = $row['total_spots'];
        $reservedSpots = $row['reserved_spots'] ?? 0;
        $availableSpots = $totalSpots - $reservedSpots;

        echo "<tr>";
        echo "<td>" . $row['zone_id'] . "</td>";
        echo "<td>" . $row['zone_name'] . "</td>";
        echo "<td>" . $totalSpots . "</td>";
        echo "<td>" . $reservedSpots . "</td>";
        echo "<td>" . $availableSpots . "</td>";
        echo "<td>" . $row['rate'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No available spots for $enteredDate</p>";
}

// Close the database connection
$conn->close();
?>

<!-- Add reservation form -->
<h2>Add Reservation</h2>
<form action="process_reservation.php" method="post">
    <input type="hidden" name="enteredDate" value="<?php echo $enteredDate; ?>">
    <label for="zoneId">Select Zone:</label>
    <select id="zoneId" name="zoneId" required>
        <?php
        // Display available spots in the dropdown menu
        $result = $conn->query($sql); // Re-run the query to fetch available spots
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['zone_id'] . "'>" . $row['zone_name'] . "</option>";
        }
        ?>
    </select>
    <label for="numSpots">Number of Spots:</label>
    <input type="number" id="numSpots" name="numSpots" required>
    <!-- Add more reservation details as needed -->
    <button type="submit">Submit Reservation</button>
</form>
</body>
</html>






