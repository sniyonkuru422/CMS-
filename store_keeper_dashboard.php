<?php
session_start();
include("auth.php");// Include the authentication functions

checkLogin();// Check if the user is logged in
requireAnyRole(["STORE_KEEPER", "COMPANY_ADMIN", "PROJECT_MANAGER", "SUPERVISOR"]);// Restrict access to STORE_KEEPER role only


include("database/connect.php"); // Include the database connection file
include("dashboard_layout.php"); // Include the common dashboard layout

if ($_SESSION['role'] !== 'Store_Keeper') {
    //die("Access denied. You do not have permission to view this page.");
    header("Location: login.html");
    exit;
}
?>

<h2>Store Keeper Dashboard</h2>

<p>Welcome, Store Keeper! Here you can manage inventory, track material usage, and monitor supply levels.</p>

<a href="inventory.php">Manage Inventory</a><br>
<a href="material-usage.php">Material Usage</a><br>
<a href="supply-levels.php">Supply Levels</a><br>
<a href="Equipment-Management.php">Manage Equipment</a><br>
<a href="issue_reporting_materials.php">Report Material Issues</a><br>
<a href="login.php">Back</a>
<a href="logout.php">Logout</a>