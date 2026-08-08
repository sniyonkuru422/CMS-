<?php

// Authentication and role checking
include("auth.php");

checkLogin();

requireAnyRole([
    "COMPANY_ADMIN",
    "PROJECT_MANAGER",
    "SITE_ENGINEER",
    "SUPERVISOR",
    "STORE_KEEPER"
]);


// Database connection
include("database/connect.php");


// Dashboard layout
include("dashboard_layout.php");


// Get logged-in company
$company_id = $_SESSION['company_id'];

?>

<head>
    <style>
/* CSS for summary cards*/
    .summary-cards{
    display:flex;
    gap:20px;
    margin:25px 0;
    flex-wrap:wrap;
    }

    .card{
    background:white;
    padding:20px;
    border-radius:12px;
    min-width:220px;
    flex:1;
    text-align:center;
    box-shadow:0 6px 18px rgba(0,0,0,0.2);
    transition:0.3s;
    }
    .card:hover{
    transform:translateY(-5px);
    }

/*Below there how we put colours in card's title*/
    .card h3{
    color: white;
    margin-bottom:10px;
    }
/*Below there how we put colours in card's numbers*/
    .card p{
    font-size:28px;
    font-weight:bold;
    color: #e7f342;
    }

    /* Total Projects */
    .projects-card{
        background: #5fbaf7 !important;
    }

    /* Total Budget */
    .budget-card{
        background: #2fee7e !important;
    }

    /* Total Expenses */
    .expense-card{
        background: #fca71f !important;
    }

    /* Remaining Budget */
    .remaining-card{
        background: #cf6ff5 !important;
    }

    /*Center the table heading*/
    table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
    background:white;
    }

    table th{
        /*The line below changes the table heading colours*/
        background: #f3a126;
        color:Green;
        padding:12px;
        text-align:center;
    }

    table td{
        padding:10px;
        text-align:center;
    }

    table tr:nth-child(even){
        background:#f8f9fa;
    }

    table tr:hover{
        background:#eef6ff;
    }

    /*Buttons style*/
    /* Print button */
    .print-btn{

        width:auto !important!
        background: #007bff;
        color:white;
        border:none;
        padding:10px 18px;
        border-radius:6px;
        cursor:pointer;
        font-weight:bold;
        margin-right:10px;
        display:inline-block;
    }

    .print-btn:hover{

        background: #0056b3;

    }

    /* PDF button */
    .pdf-btn{

        width:auto !important;
        background: #dc3545;
        color:white;
        text-decoration:none;
        padding:10px 18px;
        border-radius:6px;
        font-weight:bold;
        display:inline-block;

    }

    .pdf-btn:hover{
        background:#b02a37;
    }
    /*Add print Css*/
    @media print {

    /* Hide dashboard elements like sidebar during printing */
    .sidebar,
    .topbar,
    .navbar,
    .print-btn,
    .pdf-btn {
        display:none !important;
    }

    /* Make report use full page width */
    #print-area{
        width:100%;
    }

    body{
        background:white;
    }

    /* Remove unnecessary spacing */
    .main-content{
        margin:0;
        padding:0;
    }
}
    .report-buttons{
        width:100%;
        display:flex;
        justify-content:flex-end;
        gap:10px;
        margin-top:25px;
    }
    </style>

<!-- Chart.js library for creating budget charts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>   

</head>
<body>

<!--Start the Report page-->
<div class="main-content"> 
    <div id="print-area"><!--For Print Area which includes what to find there-->
            <h2>Project Budget Report</h2>

        <!-- Calculate the Summary information-->
        <?php

        // Count total projects
        $totalProjects = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT COUNT(*) total
        FROM projects
        WHERE company_id='$company_id'"

        ))['total'];

        // Calculate total budget
        $totalBudget = mysqli_fetch_assoc(mysqli_query($conn,

        "SELECT SUM(budget) total
        FROM projects
        WHERE company_id='$company_id'"

        ))['total'];


        // Calculate total expenses
        $totalExpenses = mysqli_fetch_assoc(mysqli_query($conn,

        "SELECT SUM(amount) total
        FROM expenses
        WHERE company_id='$company_id'"

        ))['total'];

        // Calculate remaining budget
        $remainingBudget = $totalBudget - $totalExpenses;

        ?>

        <!--Display the summary cards-->
        <div class="summary-cards">

            <div class="card projects-card">
                <h3>Total Projects</h3>
                <p>
                    <?= $totalProjects ?>
                </p>

            </div>

            <div class="card budget-card">
                <h3>Total Budget</h3>
                <p>
                    <?= number_format($totalBudget) ?> RWF
                </p>
            </div>

            <div class="card expense-card">
                <h3>Total Expenses</h3>
                <p>
                    <?= number_format($totalExpenses) ?> RWF
                </p>
            </div>

            <div class="card remaining-card">
                <h3>Remaining Budget</h3>
                <p>
                    <?= number_format($remainingBudget) ?> RWF
                </p>
            </div>
        </div>

    <!--Add the chart container-->

        <h3>Budget vs Expenses Overview</h3>
        <div style="width:90%; margin:auto; height:250px; margin:50px auto;">
            <canvas id="budgetChart"></canvas>
        </div>

        <!--Add the project budget table-->
        <h3 Stile="text-align:center;">Project Budget Details</h3>

        <table border="1" width="100%">
        <tr>
            <th>Project Name</th>
            <th>Location</th>
            <th>Budget</th>
            <th>Expenses</th>
            <th>Remaining</th>
            <th>Status</th>
        </tr>

        <?php

        // Arrays that will store data for the chart
        $chartProjects = [];
        $chartBudget = [];
        $chartExpenses = [];

        $result = mysqli_query($conn,
        "SELECT projects.project_name, projects.location, projects.budget,
        SUM(expenses.amount) AS total_expenses
        FROM projects
        LEFT JOIN expenses
        ON projects.project_id = expenses.project_id
        WHERE projects.company_id='$company_id'
        GROUP BY projects.project_id"
        );

        while($row=mysqli_fetch_assoc($result)){
            $chartProjects[] = $row['project_name'];
            $chartBudget[] = $row['budget'];
            $chartExpenses[] = $row['total_expenses'] ?? 0;

        $expenses = $row['total_expenses'] ?? 0;
        $remaining = $row['budget'] - $expenses;

        if($remaining < 0){
            $status = "<span style='color:white;background: #dc3545;padding:5px 12px;border-radius:20px;font-weight:bold; display:inline-block;'> Over Budget</span>";
        }else{
            $status = "<span style='color:white;background: #14c73e;padding:5px 12px;border-radius:20px;font-weight:bold; display:inline-block;'> On Budget</span>";
        }
        ?>
        <tr>

            <td>
            <?= $row['project_name']; ?>
            </td>

                <td>
                <?= $row['location']; ?>
                </td>

                <td>
                <?= number_format($row['budget']); ?>
                </td>

                <td>
                <?= number_format($expenses); ?>
                </td>

                <td>
                <?= number_format($remaining); ?>
                </td>

                <td>
                <?= $status; ?>
                </td>

        </tr>
    <?php } ?>
    </table><br>

        <!-- Print and PDF buttons -->
    <div style="width:100%; display:flex;
        justify-content:flex-end;
        gap:10px; margin-top:25px;">

        <!-- Print Report Button -->
        <button type="button" 
        onclick="window.print();" style="
        width:auto; background: #007bff; color:white;
        padding:10px 18px;
        border:none;
        border-radius:6px;
        cursor:pointer;
        font-weight:bold;
        ">

        🖨 Print Project Report

        </button>

        <!-- Download PDF Report Button -->
        <a href="download_project_report.php"
        style="
        
        background: #dc3545;
        color:white;
        padding:10px 13px;
        border-radius:6px;
        text-decoration:none;
        font-weight:bold;
        display:inline-block;
        ">

        📄 Download PDF Report

        </a>


    </div>
</div>
</div>
</body>

<!-- Java Script for Chart -->
<script>

    const ctx = document.getElementById('budgetChart');

    new Chart(ctx, {
    type: 'bar',


    data: {
    labels: <?= json_encode($chartProjects); ?>,

    datasets: [
        {
        label:'Project Budget',
        data: <?= json_encode($chartBudget); ?>,
        barThickness:40
        },

            {
                label:'Total Expenses',
                data: <?= json_encode($chartExpenses); ?>
                //barThickness:40
            }

        ]
    },

    options: {
    responsive:true,
    maintainAspectRatio:false,

    plugins:{

        legend:{
            position:'top'
        }
    },

    scales:{
        y:{
            beginAtZero:true
        }

        }
    }


    });


</script>
