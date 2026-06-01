<?php
//session_start();
include("auth.php");// Include the authentication functions

checkLogin();// Check if the user is logged in
requireAnyRole(["SUPERVISOR"]);// Restrict access to SUPERVISOR role only

include("dashboard_layout.php"); // Include the common dashboard layout
include("database/connect.php"); // Include the database connection file

if ($_SESSION['role'] !== 'SUPERVISOR') {
    //die("Access denied. You do not have permission to view this page.");
    header("Location: login.html");
    exit;
}
?>

<h1>Supervisor Dashboard</h1>

<p>Welcome, Supervisor! Here you can oversee project progress, manage worker attendance, and monitor daily activities.</p>

<div class="sidebar">
        <h2>Hello Our Supervisor</h2>
        
        <p>Here as Supervisor! You are allowed to view the following: </p>
        <p>Daily Activities</p>
        <p>View Reports</p>
        <p>Worker Attendance</p>
        <p>View Material Issues</p>
        

        <?php if($_SESSION['role'] == 'SUPERVISOR'): ?>
        
        <?php endif; //Show attendance link only to Supervisors?>
        
        <div>
        <a href="logout.php">
            <button style="background:Green; color:white; border:none; padding:10px 30px; border-radius:10px;">
                Logout
            </button>
        </a>
    </div>
</div>
        <!-- Below there is where you can find the links on the top-->

<a href="Worker-Attendance-Monitoring.php">Record Worker Attendance</a><br><br>
<a href="Daily_Activity_Recording.php">Monitor Daily Tasks</a><br><br>
<a href="Reporting_&_Monitoring.php">View Store keeper's Reports</a><br><br>
<a href="issue_reporting_materials.php">View Material Issues</a><br><br>
<a href="Submit_field_updates.php">Submit Field Updates</a><br><br>
<a href="login.html">Go Back</a><br><br>
