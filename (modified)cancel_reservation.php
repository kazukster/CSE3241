if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $servername = "localhost"; // Replace with your server details
            $username = "phpuser"; // Your DB username
            $password = "phpwd"; // Your DB password
            $dbname = "PARKING_SYSTEM"; // Your DB name

            // Create database connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Sanitize input
            $confirmation_number = $conn->real_escape_string($_POST['confirmation_number']);

            // Fetch the reservation date for the given confirmation number
            $sqlDateCheck = "SELECT event_date FROM Reservations WHERE Confirmation_number = ?";
            $stmtDateCheck = $conn->prepare($sqlDateCheck);
            $stmtDateCheck->bind_param("i", $confirmation_number);
            $stmtDateCheck->execute();
            $resultDateCheck = $stmtDateCheck->get_result();

            if ($resultDateCheck->num_rows > 0) {
                $row = $resultDateCheck->fetch_assoc();
                $reservationDate = new DateTime($row['event_date']);
                $currentDate = new DateTime();
                $currentDate->modify('+3 day');

                if ($currentDate > $reservationDate) {
                    // Cancellation not allowed as it's less than 3 days in advance
                    echo "<p style='color: red;'>Cancellation not allowed. Reservations must be canceled at least 3 days in advance.</p>";
                } else {
                    // Proceed with cancellation
                    $sqlCancel = "UPDATE Reservations SET status = FALSE WHERE Confirmation_number = ?";
                    $stmtCancel = $conn->prepare($sqlCancel);
                    $stmtCancel->bind_param("i", $confirmation_number);

                    if ($stmtCancel->execute()) {
                        echo "<p>Reservation cancelled successfully.</p>";
                    } else {
                        echo "<p>Error cancelling reservation: " . $stmtCancel->error . "</p>";
                    }

                    $stmtCancel->close();
                }

                $stmtDateCheck->close();
            } else {
                echo "<p>Invalid confirmation number.</p>";
            }

            // Close connection
            $conn->close();
        }
        ?>
