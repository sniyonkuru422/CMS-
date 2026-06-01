<?php
//session_start();

include("auth.php");// Include the authentication functions
include("dashboard_layout.php");// Include the common dashboard layout

checkLogin();// Check if the user is logged in
requireAnyRole([
    "COMPANY_ADMIN",
    "PROJECT_MANAGER",
    
]);// Restrict access to PROJECT_MANAGER role only


include("database/connect.php"); // Include the database connection file

if (!isset($_SESSION["user_id"])){
    header("Location: login.html");
    exit();
}
//$conn = mysqli_connect($host, $username, $password, $dbname);This is also the connection code in the connect.php file, so we don't need to repeat it here.
$company_id = $_SESSION["company_id"];

//Queeriess
$result = $conn->query("SELECT SUM(total_cost) AS total FROM materials WHERE company_id = '$company_id'");
$expenses = $result ? $result->fetch_assoc()['total'] : 0;
$result = $conn->query("SELECT SUM(amount) AS total FROM expenses WHERE company_id = '$company_id'");
$materials = $result ? $result->fetch_assoc()['total'] : 0;
$result = $conn->query("SELECT SUM(budget) AS total FROM projects WHERE company_id = '$company_id'");
$projects = $result ? $result->fetch_assoc()['total'] : 0;
$result = $conn->query("SELECT COUNT(*) AS total FROM users WHERE company_id = '$company_id' AND role = 'STAFF'");
$staff = $result ? $result->fetch_assoc()['total'] : 0;
$result = $conn->query("SELECT COUNT(*) AS total FROM equipment WHERE company_id = '$company_id'");
$equipment = $result ? $result->fetch_assoc()['total'] : 0;
$result = $conn->query("SELECT COUNT(*) AS total FROM attendance WHERE company_id = '$company_id'");
$attendance = $result ? $result->fetch_assoc()['total'] : 0;
$result = $conn->query("SELECT COUNT(*) AS total FROM issues WHERE company_id = '$company_id'");
$issues = $result ? $result->fetch_assoc()['total'] : 0;

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Add charts (Reports)

$result = $conn->query("SELECT SUM(amount) AS total FROM expenses WHERE company_id = $company_id");

if (!$result) {
    die("SQL ERROR: " . $conn->error);
}

$row = $result->fetch_assoc();
$expenses = $row['total'] ?? 0;
$materials = $conn->query("SELECT SUM(total_cost) as total FROM materials WHERE company_id = $company_id")->fetch_assoc()["total"]?? 0;


//THIS IS USED TO BUILD ADVANCED CHART (Monthly Expenses Chart data)
//Group data by month
$result = $conn->query("SELECT MONTH(expense_date) as month, SUM(amount) as total FROM expenses WHERE company_id = $company_id GROUP BY MONTH(expense_date)");
if (!$result) {
    die("Query failed: " . $conn->error);
}
$data = $result->fetch_all(MYSQLI_ASSOC);

$months = [];
$totals = [];
foreach ($data as $row) {
    $months[] = $row['month'];
    $totals[] = $row['total'];
}

?>


<!DOCTYPE html>
<html>
<head>
<title>Reporting & Monitoring</title>
</head>
<body>
<h2>Reports & Monitoring</h2>
<img src ="images/reporting.png" width="100%" height = "180" style ="object-fit: cover; border-radius: 10px;">
<ul>
    <li>Total Expenses: <?= $expenses ?? 0 ?></li>
    <li>Total Material Cost: <?= $materials ?? 0 ?></li>
    <li>Total Project Budget: <?= $projects ?? 0 ?></li>
    <li>Total Staff: <?= $staff ?></li>
    <li>Total Equipment: <?= $equipment ?></li>
</ul>

<!-- Dsplay line charts for visual representation of data -->
<canvas id="expenseChart" width="400" height="200"></canvas>

<!-- JavaScript for Chart.js -->
<script>
new Chart(document.getElementById('expenseChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode($months) ?>,
        datasets: [{
            label: 'Monthly Expenses',
            data: <?= json_encode($totals) ?>,
            borderColor: 'rgba(75, 192, 192, 1)',
            fill: false
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

<img src="images/reports.png" alt="Chart.js Library">

<!-- Add Chart.js script -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<a href="generate_report.php" target="_blank">
    <button>Download PDF Detailed Report</button>
</a>

<a href="company_admin_dashboard.php">Back</a>
</body> 
</html>

