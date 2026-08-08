<?php

include("auth.php");

checkLogin();

requireAnyRole([
    "COMPANY_ADMIN",
    "PROJECT_MANAGER"
]);


include("database/connect.php");

include("dashboard_layout.php");


$company_id = $_SESSION["company_id"];


// Get total expenses

$total_result = $conn->query("
    SELECT SUM(amount) AS total
    FROM expenses
    WHERE company_id='$company_id'
");

$total_expenses = 0;

if($total_result){

    $total_expenses = $total_result->fetch_assoc()['total'] ?? 0;

}

// Fetch expenses

$result = $conn->query("
SELECT expenses.*, projects.project_name 
FROM expenses 
JOIN projects 
ON expenses.project_id = projects.project_id 
WHERE expenses.company_id = '$company_id'
");

// Monthly Expenses Queery
$result_month = $conn->query("SELECT MONTH(expense_date) AS month, SUM(amount) AS total 
FROM expenses WHERE company_id='$company_id'
GROUP BY MONTH(expense_date) ORDER BY MONTH(expense_date)");

if(!$result_month){
    die("Monthly Expense Query Error: " . $conn->error);
}

$months=[];

$totals=[];
// monthly expense loop
while($row=$result_month->fetch_assoc()){

    $months[]=date("M",mktime(0,0,0,$row['month'],1));

    $totals[]=$row['total'];
}

 // Category Queery 
    $result_category=$conn->query("SELECT category, SUM(amount) AS total FROM expenses
    WHERE company_id='$company_id'
    GROUP BY category");

    $categories=[];
    $categoryTotals=[];

    while($row=$result_category->fetch_assoc()){

            $categories[]=$row['category'];

        $categoryTotals[]=$row['total'];

    }


//Top Projects Query
$result_project=$conn->query("SELECT projects.project_name, SUM(expenses.amount) AS total
FROM expenses JOIN projects ON expenses.project_id = projects.project_id

WHERE expenses.company_id='$company_id'

GROUP BY projects.project_name

ORDER BY total DESC

LIMIT 5
");

$projectNames=[];

$projectTotals=[];

while($row=$result_project->fetch_assoc()){

    $projectNames[]=$row['project_name'];

    $projectTotals[]=$row['total'];

}
?>


<!DOCTYPE html>
<html>

<head>

<title>Expense Report</title>


<style>


body{

font-family:Arial;

background:#f4f6f9;

}



.report-box{

width:90%;

margin:30px auto;

background:white;

padding:25px;

border-radius:15px;

box-shadow:0px 5px 15px rgba(0,0,0,.2);

}



.page-title{
    text-align:center;
    color: #333;
    background: none;
    padding: 0;
    margin-bottom: 20px;
    border: none;
    box-shadow: none;
    border-radius: 0;
}



.summary{

background:#ff6b6b;

color:white;

padding:20px;

border-radius:12px;

text-align:center;

font-size:22px;

margin-bottom:25px;

}



table{

width:100%;

border-collapse:collapse;

}



th{

background:#343a40;

color:white;

padding:12px;

}



td{

padding:10px;

border-bottom:1px solid #ddd;

text-align:center;

}



tr:hover{

background:#f1f1f1;

}


.back-btn{



display:inline-block;

margin-top:20px;

background:#007bff;

color:white;

padding:10px 20px;

border-radius:8px;

text-decoration:none;

}


.chart-grid{

display:grid;

grid-template-columns:repeat(auto-fit,minmax(400px,1fr));

gap:25px;

margin-top:40px;

}

.chart-card{
    background:white;
    padding:20px;
    border-radius:15px;
    box-shadow:0px 5px 15px rgba(0,0,0,.15);
}

.monthly-chart{
    grid-column: 1 / -1;
}

#monthlyChart{
    height:400px !important;
}

</style>

</head>

<body>

<div class="report-box">

<h2 class="page-title">💰 Expense Report</h2>

<div class="summary">

Total Expenses:

<br>
<?= number_format($total_expenses,2) ?> RWF
</div>

<table>
    <tr>
    <th>Expense ID</th>
    <th>Expense Name</th>
    <th>Project ID</th>
    <th>Description</th>
    <th>Amount</th>
    <th>Expense Date</th>
    <th>Category</th>
    <th>Recorded By</th>
    <th>Action</th>
    </tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>

<td>
<?= $row['expense_id']; ?>
</td>

<td>
<?= $row['expense_name']; ?>
</td>

<td>
<?= $row['project_name']; ?>
</td>

<td>
<?= $row['description']; ?>
</td>

<td>
<?= number_format($row['amount'],0); ?> RWF
</td>

<td>
<?= $row['expense_date']; ?>
</td>

<td>
<?= $row['category']; ?>
</td>

<td>
<?= $row['recorded_by']; ?>
</td>

<td>

    <a href="edit_expense.php?id=<?= $row['expense_id']; ?>">
        Edit
    </a>

    |

    <a href="delete_expense.php?id=<?= $row['expense_id']; ?>"
    onclick="return confirm('Delete this expense?')">
        Delete
    </a>

</td>

</tr>

<?php endwhile; ?>

</table><br>


<!--Expense Analysis-->
<h2 style="text-align:center;">
    Expense Analysis
</h2>

<div class="chart-grid">


    <div class="chart-card monthly-chart">

        <h3>Monthly Expense Trend</h3>

        <canvas id="monthlyChart"></canvas>

    </div>


    <div class="chart-card">

        <h3>Expense Categories</h3>

        <canvas id="categoryChart"></canvas>

    </div>


    <div class="chart-card">

        <h3>Top 5 Projects</h3>
        <canvas id="projectChart"></canvas>

    </div>


</div>

<a href="Reporting_&_Monitoring.php" style="width:20%;" class="back-btn">
⬅ Back to Reports
</a>

</div>

<!--JavaScript-->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

// Monthly Expense Trend
new Chart(document.getElementById('monthlyChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode($months) ?>,
        datasets: [{
            label: 'Monthly Expenses',
            data: <?= json_encode($totals) ?>,
            borderColor: '#007bff',
            backgroundColor: 'rgba(0,123,255,0.2)',
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true
    }
});

// Expense Categories
new Chart(document.getElementById('categoryChart'), {
    type: 'pie',
    data: {
        labels: <?= json_encode($categories) ?>,
        datasets: [{
            data: <?= json_encode($categoryTotals) ?>
        }]
    }
});

// Top 5 Projects
new Chart(document.getElementById('projectChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($projectNames) ?>,
        datasets: [{
            label: 'Total Expense (RWF)',
            data: <?= json_encode($projectTotals) ?>
        }]
    },
    options: {
        responsive: true
    }
});

</script>

</body>

</html>