<?php
session_start();

include("auth.php");
checkLogin();

requireAnyRole([
    "SUPER_ADMIN"
]);

include("database/connect.php"); // Include the database connection file

// Check if the user is logged in and is a SUPER_ADMIN
if (!isset($_SESSION["user_id"])){
    header("Location: login.html");
    exit();
}
if ($_SESSION["role"] !== "SUPER_ADMIN") {
    echo ("Access denied. You do not have permission to view this page.");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Super Admin Dashboard</title>
</head>
<body>
    <h1>Super Admin Dashboard</h1>
    <p>Welcome, Super Admin!</p>
    <ul>
        <li><a href="create_company.php">Create   A Company</a></li>
        <li><a href="create_company_admin.php">Create Company Admin</a></li>
        <li><a href="view_companies.php">View Companies</a></li>
        <li><a href="view_company_admins.php">View Company Admins</a></li>
        <li><a href="view_users.php">View Users</a></li>
    </ul>
    <img src ="images/Buildng_Plans_Image.png" alt="Super Admin Dashboard Image" style="width:100%; max-width:600px; margin-top:20px;">

    <a href="register_company.html">Register Company</a><br><br>
    <a class="back" href="index.html">Back To The Main Dashboard</a><br><br>
    <a href="logout.php">Logout</a>
</body>
</html>
