<?php
session_start();
include("database/connect.php"); // Include the database connection file

//Check if logged in / not logged in -> redirect to login page
if (!isset($_SESSION["user_id"])) {
   header("Location: login.html");
    exit();
}
// Restrict/ allow/ give access to COMPANY_ADMIN only
if ($_SESSION['role'] !== "COMPANY_ADMIN") {
    die("Access denied. You do not have permission to access this page.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link rel="stylesheet" href="CSS/style.css"> <!-- This helps to Link this file with CSS file -->

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-image: url('construction_management_system/images/manage-users.jpg') !important;
            background-size: cover !important;
            background-position: center !important;
            background-repeat: no-repeat !important;
            background-attachment: fixed !important;
            min-height: 100vh !important;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            margin: 50px auto;
            border-radius: 30px;
            max-height: 530px;
            background: rgba(74, 228, 143, 0.85);
            box-shadow: 0 0 10px rgba(8, 8, 8, 0.3);
            background-filter: blur(10px);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .menu a {
            display: block;
            padding: 15px;
            margin: 10px 0;
            background-color: #2c3e50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }
        .menu a:hover {
            background-color: #34495e;
        }
    </style>
</head>

<body class="manage-users-page" style="background-image: url('images/manage-users1.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat; background-attachment: fixed; min-height: 100vh;">
    
    <div class="container-dashboard">  <!-- Contains a form for better styling -->
        <h2>Manage Staff Users</h2>
        <div class="menu">
            <a href="create_staff.php">Create Staff User</a>
            <a href="view_users.php">View All Staff Users</a>
            <a href="edit_user.php">Edit Staff User</a>
            <a href="view_users.php">Delete Staff User</a>

        </div>

        <br>

        <a href="company_admin_dashboard.php" style="display:block; text-align:center; margin-top:20px;">
            <button style="padding:10px 20px; background-color:#2980b9; color:white; border:none; border-radius:5px;">
                Back To The Company Admin Dashboard
            </button>
        </a>

    </div>
</body>
</html>