<!DOCTYPE html>
<html>
<head>
    <title>Manage Zones</title>
</head>
<body>
    <h1>Manage Zones</h1>
    
    <!-- Add Zone Form -->
    <form method="post" action="manage_zones.php">
        <input type="hidden" name="action" value="add">
        <div><label>Zone ID: <input type="number" name="zone_id" required></label></div>
        <div><label>Zone Name: <input type="text" name="zone_name" required></label></div>
        <div><label>Max Spots: <input type="number" name="total_spots" required></label></div>
        <div><label>Rate: <input type="text" name="rate" required></label></div>
        <div><label>Zone Fee: <input type="text" name="zone_fee" required pattern="\d+(\.\d{2})?" title="Decimal format (e.g., 10.00)"></label></div>
        <div><button type="submit">Add Zone</button></div>
    </form>
    
    <!-- Remove Zone Form -->
    <form method="post" action="manage_zones.php">
        <input type="hidden" name="action" value="remove">
        <div><label>Zone ID: <input type="number" name="zone_id" required></label></div>
        <div><button type="submit">Remove Zone</button></div>
    </form>

    <!-- Update Spots Form -->
    <form method="post" action="manage_zones.php">
        <input type="hidden" name="action" value="update_spots">
        <div><label>Zone ID: <input type="number" name="zone_id" required></label></div>
        <div><label>Max Spots: <input type="number" name="total_spots" required></label></div>
        <div><button type="submit">Update Number of Spots</button></div>
    </form>

    <!-- Update Rate Form -->
    <form method="post" action="manage_zones.php">
        <input type="hidden" name="action" value="update_rate">
        <div><label>Zone ID: <input type="number" name="zone_id" required></label></div>
        <div><label>Rate: <input type="text" name="rate" required pattern="\d+(\.\d{2})?" title="Decimal format (e.g., 10.00)"></label></div>
        <div><button type="submit">Update Rate</button></div>
    </form>



    <?php
    //Configuring the database
    $servername = "localhost";
    $username = "phpuser"; //Database username
    $password = "phpwd"; //Database password
    $dbname = "parking_system"; //Database name

    //Creates database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    //Checking the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //Checks if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $action = $_POST['action'];

        if ($action == 'add') {
            $zoneID = $_POST["zone_id"];
            $zoneName = $_POST["zone_name"];
            $maxSpots = $_POST["total_spots"];
            $rate = $_POST["rate"];
            $zoneFee = $_POST["zone_fee"];
        
           //Precompile SQL before execution and linking variables to placeholders
            $stmt = $conn->prepare("INSERT INTO Zones (zone_ID, zone_name, total_spots, rate, zone_fee) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("isidd", $zoneID, $zoneName, $maxSpots, $rate, $zoneFee);
        
            //Executes statement and checks for errors
            if($stmt->execute()) {
                echo "<p>Zone added successfully.</p>";
            } else {
                echo "<p>Error adding zone: " . $stmt->error . "</p>";
            }
        
            //Close statement
            $stmt->close();
        } elseif ($action == 'remove') {
            $zoneId = $_POST["zone_id"];

            //Precompile SQL before execution and linking variables to placeholders for deletion
            $stmt = $conn->prepare("DELETE FROM Zones WHERE zone_ID = ?");
            $stmt->bind_param("i", $zoneId);

            //Executes statement and checks for errors
            if($stmt->execute()) {
                echo "<p>Zone removed successfully.</p>";
            } else {
                echo "<p>Error removing zone: " . $stmt->error . "</p>";
            }

            //Close the statement
            $stmt->close();
        } elseif ($action == 'update_spots') {
            $zoneID = $_POST["zone_id"];
            $maxSpots = $_POST["total_spots"];

            //Precompile SQL before execution and linking variables to placeholders for spot update
            $stmt = $conn->prepare("UPDATE Zones SET total_spots = ? WHERE zone_ID = ?");
            $stmt->bind_param("ii", $maxSpots, $zoneID);

            //Executes statement and checks for errors
            if($stmt->execute()) {
              echo "<p>Spots updated successfully.</p>";
            } else {
              echo "<p>Error updating spots: " . $stmt->error . "</p>";
            }

            //Close the statement
            $stmt->close();
        } elseif ($action == 'update_rate') {
            $zoneID = $_POST["zone_id"];
            $rate = $_POST["rate"];

            //Precompile SQL before execution and linking variables to placeholders for  rate update
            $stmt = $conn->prepare("UPDATE Zones SET rate = ?, zone_fee = ? WHERE zone_ID = ?");
            $stmt->bind_param("ddi", $rate, $rate, $zoneID);

            //Executes statement and checks for errors
            if($stmt->execute()) {
              echo "<p>Rate updated successfully.</p>";
            } else {
              echo "<p>Error updating rate: " . $stmt->error . "</p>";
            }

            //Close the statement
            $stmt->close();
          }

    }

    //Close connection
    $conn->close();
    ?>
</body>
</html>
