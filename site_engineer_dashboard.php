<?php
//session_start();

include("auth.php"); // Include the authentication functions
checkLogin(); // Check if the user is logged in
requireRole('SITE_ENGINEER'); // Restrict access to SITE_ENGINEER role only

include("database/connect.php"); // Include the database connection file

//Check if user is logged in, if not redirect to login page
if (!isset($_SESSION["user_id"])){
    header("Location: login.html");
    exit();
}
// Allow only SITE_ENGINEER to access this page, if not redirect to login page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'SITE_ENGINEER') {
    //die("Access denied. You do not have permission to view this page.");
    header("Location: login.html");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Site Engineer Dashboard</title>
</head>

<body>
<h1>Site Engineer Dashboard</h1>

<img src="images/site_Engineers1.PNG" width="100%" height="180" style="object-fit: cover; border-radius: 10px;">
<p>Welcome, Site Engineer! Here you can manage your projects, materials, and expenses.</p>

<a href="project_management.php">Update Projects Progress</a><br>
<a href="reporting_&_Monitoring.php">Provide Projects Reports</a><br>
<a href="Daily-Activity-Recording.php">Daily Activities</a><br>
<a href="issue_reporting.php">Report Issues</a><br>
<a href="material_request.php">Request Materials</a><br>
<a href="login.html">Back</a>

</body>
</html>
