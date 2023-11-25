<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
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
            <h1>Admin Dashboard</h1>
        </div>
        <div class="nav">
            <a href="manage_users.php">Manage Users</a>
            <a href="generate_report.php">View Reports</a>
            <a href="manage_zones.php">Manage Zones</a>
            <a href="view_zones.php">View Zones</a>
            <a href="user_logout.php">Logout</a> <!-- Logout functionality -->
        </div>
        <div class="content">
            <h2>Welcome, Admin!</h2>
            <p>This is the admin dashboard. From here, you can manage users, view reports, adjust zones, and more.</p>
        </div>
    </div>
</body>
</html>
