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
            <a href="user_logout.php">Logout</a> <!-- Placeholder for logout functionality -->
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
    // Validate if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
        // Get the input date from the form
        $inputDate = $_POST["inputDate"];

        // Validate if the date is at least 1 day in advance
        $currentDate = date("Y-m-d");
        $nextDay = date('Y-m-d', strtotime($currentDate . ' +1 day'));

        if ($inputDate < $nextDay) {
            // Display error message
            echo '<p style="color: red;">Invalid date. Please enter a date at least 1 day in advance.</p>';
        } else {
            // If the date is valid, redirect to the new page
            session_start();
            $_SESSION['enteredDate'] = $inputDate;
            header("Location: user_add_reservation_by_date.php");
            exit();
        }
    }
    ?>
    <form action="" method="post">
        <label for="inputDate">Date:</label>
        <input type="date" id="inputDate" name="inputDate" required>
        <button type="submit">Submit</button>
    </form>

    <?php
// Validate if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get the input date from the form
    $inputDate = $_POST["inputDate"];

    // Validate if the date is at least 1 day in advance
    $currentDate = date("Y-m-d");
    $nextDay = date('Y-m-d', strtotime($currentDate . ' +1 day'));

    if ($inputDate < $nextDay) {
        // Redirect back to the form with an error message
        header("Location: index.php?error=1");
        exit();
    }

    // If the date is valid, redirect to the new page
    header("Location: new_page_given_date.php?date=" . urlencode($inputDate));
    exit();
}
?>
</body>
</html>
