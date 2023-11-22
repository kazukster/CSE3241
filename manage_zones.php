<!DOCTYPE html>
<html>
<head>
    <title>Manage Zones</title>
    <style>
        /* Add your CSS styling here */
    </style>
</head>
<body>
    <h1>Manage Zones</h1>
    
    <!-- Add Zone Form -->
    <form method="post" action="manage_zones.php">
        <input type="hidden" name="action" value="add">
        <div><label>Zone Name: <input type="text" name="zone_name" required></label></div>
        <div><label>Max Spots: <input type="number" name="total_spots" required></label></div>
        <div><label>Rate: <input type="text" name="rate" required></label></div>
        <div><button type="submit">Add Zone</button></div>
    </form>
    
    <!-- Remove Zone Form -->
    <form method="post" action="manage_zones.php">
        <input type="hidden" name="action" value="remove">
        <div><label>Zone ID: <input type="number" name="zone_id" required></label></div>
        <div><button type="submit">Remove Zone</button></div>
    </form>
    
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

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $action = $_POST['action'];

        if ($action == 'add') {
            $zoneName = $_POST["zone_name"];
            $maxSpots = $_POST["total_spots"];
            $rate = $_POST["rate"];

            // Prepare SQL and bind parameters
            $stmt = $conn->prepare("INSERT INTO Zones (zone_name, total_spots, rate) VALUES (?, ?, ?)");
            $stmt->bind_param("sid", $zoneName, $maxSpots, $rate);

            // Execute statement and check for errors
            if($stmt->execute()) {
                echo "<p>Zone added successfully.</p>";
            } else {
                echo "<p>Error adding zone: " . $stmt->error . "</p>";
            }

            // Close statement
            $stmt->close();
        } elseif ($action == 'remove') {
            $zoneId = $_POST["zone_id"];

            // Prepare SQL and bind parameters for deletion
            $stmt = $conn->prepare("DELETE FROM Zones WHERE zone_ID = ?");
            $stmt->bind_param("i", $zoneId);

            // Execute statement and check for errors
            if($stmt->execute()) {
                echo "<p>Zone removed successfully.</p>";
            } else {
                echo "<p>Error removing zone: " . $stmt->error . "</p>";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
    ?>
</body>
</html>
