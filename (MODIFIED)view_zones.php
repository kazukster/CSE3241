<!DOCTYPE html>
<html>
<head>
    <title>View Zones</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #dddddd;
        }
        th, td {
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
<div class="container">
        <h1>Zone Listing</h1>
        
        <!-- Form to select a date -->
        <form method="post" action="view_zones.php">
            <label for="date">Select Date:</label>
            <input type="date" id="date" name="date" required>
            <button type="submit">Show Availability</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $selectedDate = $_POST['date'];

            // Database configuration
            $servername = "localhost";
            $username = "phpuser"; // your database username
            $password = "phpwd"; // your database password
            $dbname = "final"; // your database name

            // Create database connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // SQL to fetch total spots for each zone
            $sqlZones = "SELECT zone_ID, zone_name, total_spots, rate FROM Zones";
            $resultZones = $conn->query($sqlZones);

            if ($resultZones->num_rows > 0) {
                echo "<table><tr><th>Zone Name</th><th>Total Spots</th><th>Spots Taken</th><th>Available Spots</th><th>Rate</th></tr>";
                
                while($zone = $resultZones->fetch_assoc()) {
                    // SQL to calculate spots taken for each zone
                    $sqlReservations = "SELECT COUNT(*) AS spots_taken FROM Reservations WHERE zone_id = " . $zone['zone_ID'] . " AND event_date = '" . $selectedDate . "' AND status = TRUE";
                    $resultReservations = $conn->query($sqlReservations);
                    $reservation = $resultReservations->fetch_assoc();

                    $spotsTaken = $reservation['spots_taken'];
                    $availableSpots = $zone['total_spots'] - $spotsTaken;

                    echo "<tr><td>" . htmlspecialchars($zone["zone_name"]) . "</td><td>" . $zone["total_spots"] . "</td><td>" . $spotsTaken . "</td><td>" . $availableSpots . "</td><td>" . $zone["rate"] . "</td></tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No zones found.</p>";
            }

            // Close connection
            $conn->close();
        }
        ?>
    </div>
</body>
</html>
