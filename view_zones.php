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
        
        <!-- Form to select a single date or a date range -->
        <form method="post" action="view_zones.php">
            <!-- Single Date Selection -->
            <label for="single_date">Select Single Date:</label>
            <input type="date" id="single_date" name="single_date">

            <br><br>

            <!-- Date Range Selection -->
            <label for="start_date">Select Range of Dates: Start Date:</label>
            <input type="date" id="start_date" name="start_date">

            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date">

            <br><br>

            <button type="submit">Show Availability</button>
        </form>

        <?php
        // Function to get the next date
        function getNextDate($date) {
            return date('Y-m-d', strtotime($date . ' +1 day'));
        }

        // Function to display availability
        function displayAvailability($date, $conn) {
            $sqlZones = "SELECT zone_ID, zone_name, total_spots, rate FROM Zones";
            $resultZones = $conn->query($sqlZones);

            if ($resultZones && $resultZones->num_rows > 0) {
                echo "<h2>Availability for " . $date . "</h2>";
                echo "<table><tr><th>Zone Name</th><th>Total Spots</th><th>Spots Taken</th><th>Available Spots</th><th>Rate</th></tr>";

                while($zone = $resultZones->fetch_assoc()) {
                    // SQL to calculate spots taken for each zone
                    $sqlReservations = "SELECT COUNT(*) AS spots_taken FROM Reservations WHERE zone_id = " . $zone['zone_ID'] . " AND event_date = '" . $date . "' AND status = TRUE";
                    $resultReservations = $conn->query($sqlReservations);
                    $reservation = $resultReservations->fetch_assoc();

                    $spotsTaken = $reservation['spots_taken'];
                    $availableSpots = $zone['total_spots'] - $spotsTaken;

                    echo "<tr><td>" . htmlspecialchars($zone["zone_name"]) . "</td><td>" . $zone["total_spots"] . "</td><td>" . $spotsTaken . "</td><td>" . $availableSpots . "</td><td>" . $zone["rate"] . "</td></tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No zones found for " . $date . ".</p>";
            }
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Database configuration and connection
            $servername = "localhost";
            $username = "phpuser";
            $password = "phpwd";
            $dbname = "parking_system";

            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Check if single date or date range is provided
            if (isset($_POST['single_date']) && !empty($_POST['single_date'])) {
                $date = $_POST['single_date'];
                displayAvailability($date, $conn);
            } elseif (isset($_POST['start_date']) && isset($_POST['end_date']) && !empty($_POST['start_date']) && !empty($_POST['end_date'])) {
                $startDate = $_POST['start_date'];
                $endDate = $_POST['end_date'];

                // Iterate over each date in the range
                for ($date = $startDate; $date <= $endDate; $date = getNextDate($date)) {
                    displayAvailability($date, $conn);
                }
            } else {
                echo "<p>Please select a date or a range of dates.</p>";
            }

            // Close connection
            $conn->close();
        }
        ?>
    </div>
</body>
</html>
