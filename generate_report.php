<!DOCTYPE html>
<html>
<head>
    <title>Generate Reports</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
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
        <h1>Generate Reports</h1>
        
        <!-- Form to select the date -->
        <form method="post" action="generate_report.php">
            <label for="report_date">Select Date for Report:</label>
            <input type="date" id="report_date" name="report_date" required>
            <button type="submit">Generate Report</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['report_date'])) {
            $reportDate = $_POST['report_date'];

            //Connecting to database
            $servername = "localhost";
            $username = "phpuser"; //Database username
            $password = "phpwd"; //Database password 
            $dbname = "parking_system"; //Database name

            $conn = new mysqli($servername, $username, $password, $dbname);

            //Checking the database connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            //SQL to get report data
            $sql = "SELECT Z.zone_name, Z.total_spots, COUNT(R.zone_id) AS reservations_made, Z.rate, SUM(R.total_fee) AS total_revenue
                    FROM Zones Z
                    LEFT JOIN Reservations R ON Z.zone_ID = R.zone_id AND R.event_date = ?
                    GROUP BY Z.zone_ID";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $reportDate);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                echo "<h2>Report for " . $reportDate . "</h2>";
                echo "<table><tr><th>Zone Name</th><th>Total Spots</th><th>Reservations Made</th><th>Rate</th><th>Total Revenue</th></tr>";

                while($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . htmlspecialchars($row["zone_name"]) . "</td><td>" . $row["total_spots"] . "</td><td>" . $row["reservations_made"] . "</td><td>" . $row["rate"] . "</td><td>" . $row["total_revenue"] . "</td></tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No report data found for " . $reportDate . ".</p>";
            }

            //Closing the connection and closing statement
            $stmt->close();
            $conn->close();
        }
        ?>
    </div>
</body>
</html>
