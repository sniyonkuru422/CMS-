<?php
//if (session_status() == PHP_SESSION_NONE) {
    //session_start();
//}
$role = $_SESSION["role"] ?? '';
?>

</DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial;
            margin: 0;
        }
        html, body {
            height: 100%;
            margin: 0;
        }
        .sidebar {
            width: 215px;
            background: #2c3e50;
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            padding-bottom: 50px;
            overflow: auto; /* This line  is to enable scrolling if content exceeds viewport height*/
        }
        .sidebar h2 {
            text-align: center;
        }
        .sidebar a{
            display: block;
            color: white;
            text-decoration: none;
            padding: 12px;
        }
        .sidebar a:hover {
            background: #34495e;
        }
        .main {
            margin-left: 230px;
            margin-right:40px;
            margin-bottom: 100px;
            padding: 10px 15px;
            background: #f4f4f4;
            min-height: 100vh;
            overflow: auto; /* This line is to enable scrolling if content exceeds viewport height*/
        }
         .topbar {
            background: red;
            padding: 10px;
            box-shadow: 0 2px 5px #c5c3c3;
            margin-bottom:30px;
        }
        .topbar-links {
            margin-top: 8px;
        }
        .topbar-links a {
            margin-right: 15px;
            color: #2c3e50;
            font-weight: 500;
        }
        .topbar-links a:hover {
            color: #2980b9;
        }
        .sedebar {
            scrollbar-width: thin;
        }
        .sidebar a:last-child {
            margin-bottom: 40px;
        }
        .sidebar {
            background: darkblue !important;
        }

    </style>
    <link rel="stylesheet" type="text/css" href="style.css">
    </head>

    <div class="sidebar <?php echo strtolower($_SESSION['role']) ; '';?>">

    <?php if($_SESSION['role'] == 'COMPANY_ADMIN'): ?>
        <a href="manage_users.php">Manage Users</a>
    <!-- Company Admin links -->

        <h2>Company Admin Dashboard</h2>
        <p>Welcome to Our Company Admin Dashboard! Where Company Admin can view the projects, materials, worker attendence, expenses, equipment, daily activities, and reports. and also be able to add a staff member and manage users.</p>
        <a href="company_admin_dashboard.php">Home</a>
        <a href="project-management.php">Projects</a>
        <a href="Material-management.php">Materials</a>
        <a href="Reporting_&_Monitoring.php">Reports</a>
        <a href="Expense-Management.php">Expenses</a>
        <a href="Equipment-Management.php">Equipment</a>
        <a href="Worker-Attendance-Monitoring.php">Worker Attendance</a>
        <a href="daily_activity_recording.php">Daily Activities</a>
       

        <?php elseif($_SESSION['role'] == 'PROJECT_MANAGER'): ?>
            <a href="project-management.php">Projects</a>
        
        <?php elseif($_SESSION['role'] == 'STORE_KEEPER'): ?>
            <a href="material-management.php">Materials</a> <!-- Show material management link only to Store Keepers -->
         

        <?php elseif($_SESSION['role'] == 'SUPERVISOR'): ?>
            <h2>Supervisor Dashboard</h2>

            <p>Here you can see the worker attendance information.</p><br>
            <p>And you can edit it as needed.</p><br>
            <p>And update the worker attendence</p>
        
        <a href="supervisor_dashboard.php" style="display:block; margin-top:15px;">
                <button style="
                    background:Green;
                    color:white;
                    border:none;
                    padding:10px 30px;
                    border-radius:15px;
                    width: 90%;
                    cursor:pointer;
                ">
                    Back to Supervisor Dashboard
                </button>
        </a>
            <?php endif;?> <!-- Show attendance link only to Supervisors -->
        
        <div>
        <a href="logout.php">
            <button style="background:Green; color:white; width: 90%; border:none; padding:10px 30px; border-radius:10px;">
                Logout
            </button>
        </a>
    </div>
    </div>

    <!-- This file serves as a common layout for all dashboards, providing 
     a consistent structure and navigation across different user roles. 
     Each specific dashboard (e.g., Supervisor, Site Engineer, Super Admin) 
     will include this layout and populate the main content area with role-specific information and functionalities. -->

    <div class="main">
        <div class="topbar">
            <!-- First Row -->
            <div class="topbar-welcome">
            <h2>Construction Management System</h2><br><br>

                WELCOME OUR, <?= $_SESSION['role'] ?> 
            </div><br><br>
         <!-- Second Row -->
            <div class="topbar-links">
                <?php if($_SESSION['role'] == 'COMPANY_ADMIN'):?>
                
                    <a href ="profile.php"><i class="fas fa-user"></i> My Profile</a> 
                    <a href ="budget_report.php"> Budget Report</a>
                    <a href="equipment_report.php">Equipment Report</a>
                    <a href="daily_activity_report.php">Daily Activity report</a>
                    <a href ="charts.php">Charts</a>
                <?php endif; //Show these links only to Company Admins?>

                <?php if($_SESSION['role'] == 'SUPERVISOR'):?>
                    <!-- <a href="daily_activities_report.php">Daily Activities Report</a> -->
                <?php endif; //Show these links only to Project Managers?>
           </div>
    </div>

</html>

