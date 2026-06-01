<?php
//session_start();

include("auth.php");
checkLogin();

requireAnyRole([
    "COMPANY_ADMIN"
]);

include("dashboard_layout.php");// Include the common dashboard layout
include("database/connect.php"); // Include the database connection file

//If not logged in -> redirect to login page
if (!isset($_SESSION["user_id"])) {
   header("Location: login.html");
    exit();
}

// Restrict/ give access to COMPANY_ADMIN only
if ($_SESSION['role'] !== "COMPANY_ADMIN") {
    //die("Role mismatch. Access denied.");
    header("Location: login.html");
    exit();
}

$role = $_SESSION["role"];

?>


<!DOCTYPE html>
<html>
<head>
    <title>Company Admin Dashboard</title>
<style>
    body{margin:0; font-family: Arial;background:f4f6f9;}
    .sidebar {
        width: 250px; background: #2c3e50; color: white; height: 100vh; position: fixed; padding: 20px; overflow: auto;
    }
    .sidebar a {
        display: block; color: white; text-decoration: none; margin: 4px 0; padding:8px;font-size:16px;
    }
    .sidebar a:hover {
        background: #34495e; border-radius: 4px;
    }
    .topbar {
        margin-left: 270px; background: #8af16b; padding: 10px; box-shadow: 0 2px 5px #ccc;
    }
    .content {
        margin-left: 270px; padding: 20px;
    }
    .card {
        background: #fff; padding: 20px;
        border-radius: 8px; box-shadow: 0 0 10px #ccc;
        margin: 20px;
    }
    
</style>
</head>
<body>

    <!--Topbar to avoid breaking even if something is missing-->
    <div class="topbar" style="display:flex; justify-content:space-between;">
        <div style="background: #3498db; color:white; padding:5px 10px; border-radius:5px;">
            <!-- Line below Displays the logged-in user's name and role -->
            Logged in as: <b><?= $_SESSION["username"] ?? 'User' ?></b> (<?= $role ?>)
        </div>
    
    </div>
 
    <div class="content">
        <h2>Dashboard</h2>
        <p>Here you can get an overview of your construction projects, manage materials, track worker attendance, and monitor expenses. Use the links on the left to navigate through different sections of the dashboard.</p>
    </div>
</body>
</html>
