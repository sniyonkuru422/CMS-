<?php

include("auth.php");

checkLogin();

requireRole('PROJECT_MANAGER');

include("database/connect.php");

include("dashboard_layout.php");


if (!isset($_SESSION['user_id'])) {

    header("Location: login.html");
    exit();

}

?>


<!DOCTYPE html>
<html>

<head>

<title>
Project Manager Dashboard
</title>

</head>


<body>


<h1>
Project Manager Dashboard
</h1><br>


<div style="
    width:100%;
    height:420px;
    overflow:hidden;
    border-radius:15px;
    margin-bottom:20px;
">

    <img src="images/construction1.jpeg"

    style="
    width:100%;
    height:100%;
    object-fit:cover;
    "> 

</div><br>



<p>
Welcome Project Manager! Here you can monitor projects,
update project progress, review budgets, coordinate teams,
and provide construction reports.
</p>


<br><br>


<div style="
    display:flex;
    gap:20px;
    flex-wrap:wrap;
    ">


<div style="
background: #3498db;
color:white;
padding:20px;
border-radius:15px;
width:200px;
">

<h3>
Projects
</h3>

<p>
Create a project
</p>

<a href="project-management.php"
style="color:white;">
Open
</a>

</div>


<div style="
background:#27ae60;
color:white;
padding:20px;
border-radius:15px;
width:200px;
">

<h3>
Reports
</h3>

<p>
View company's performance salary
</p>

<a href="Reporting_&_Monitoring.php"
style="color:white;">
Open
</a>

</div>


</div>


<br><br>


<a href="logout.php">

<button style="
width:30%;
background:red;
color:white;
border:none;
margin: left 20px;
padding:10px 20px;
border-radius:13px;
">

Logout

</button>

</a>


</body>

</html>