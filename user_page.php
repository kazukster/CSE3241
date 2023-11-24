<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
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
            <h1>User Dashboard</h1>
        </div>
        <div class="nav">
            <a href="#">Add a Reservation</a>
            <a href="#">Cancel a Reservation</a>
            <a href="#">View Reservation History</a>
            <a href="logout.php">Logout</a> <!-- Placeholder for logout functionality -->
        </div>
        <div class="content">
            <h2>Welcome, ENTER USER NAME HERE!</h2>
            <p>This is the user dashboard. From here you can: add a reservation, cancel a reservation, and view reservation history.</p>
        </div>
    </div>
</body>
</html>
