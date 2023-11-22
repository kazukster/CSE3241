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
        <?php
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

        // SQL query to fetch zone data
        $sql = "SELECT zone_name, total_spots FROM Zones";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Start table and add header row
            echo "<table><tr><th>Zone Name</th><th>Max Spots</th></tr>";
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>" . htmlspecialchars($row["zone_name"]) . "</td><td>" . htmlspecialchars($row["total_spots"]) . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No zones found.</p>";
        }

        // Close connection
        $conn->close();
        ?>
    </div>
</body>
</html>
