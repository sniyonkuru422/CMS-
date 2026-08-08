<?php
include("auth.php");

checkLogin();

requireAnyRole([
    "COMPANY_ADMIN",
    "PROJECT_MANAGER",
    "SITE_ENGINEER",
    "SUPERVISOR",
    "STORE_KEEPER"
]);

include("database/connect.php");// Include the database connection file
include("dashboard_layout.php");// connect Layout

//Get the logged-in user's company
$company_id = $_SESSION['company_id'];
?>

<head>
<!--Css for (Appearance of the summary cards)-->
    <style>

    .summary-cards{

        display:flex;
        gap:20px;
        margin:25px 0;
        flex-wrap:wrap;

    }

        /* Styling for each summary card */
    .card{

        flex:1;
        min-width:180px;
        padding:20px;
        border-radius:10px;
        text-align:center;
        color:white;
        box-shadow:0px 5px 15px rgba(0,0,0,0.2);
    }


    /* Different colors for different information categories */
    .total-card{/* Total Number of Equipment*/
        background: #6aaef7 !important; /* Blue */
    }

    /* Equipment ready for use */
    .available-card{
        background: #3ffa6b !important; /* Green */
    }

    /* Equipment requiring maintenance */
    .maintenance-card{
        background:#ffc107 !important; /* Orange */
        color:#333 !important;
    }

    /* Damaged equipment */
    .damaged-card{
        background: #f35a69 !important; /* Red */
    }

/* Total equipment financial value */
    .value-card{
        background:#6f42c1 !important; /* Purple */
    }

    .card h3{
        margin:0;
        color:#666;
    }

    .card p{
        font-size:28px;
        font-weight:bold;
        color:white;
    }
    /* This makes the card to look verry professional */
    /* This makes the card moves when you a mouse on it*/
    .card:hover{
    transform:translateY(-5px);
    transition:0.3s;
    }

    /* Styles the print report button */
    .print-btn{

        background:#007bff;
        color:white;
        border:none;
        padding:12px 20px;
        border-radius:8px;
        cursor:pointer;
        font-size:15px;
        font-weight:bold;

    }

    .print-btn:hover{
        background:#0056b3;
    }

    /* Print settings */
    @media print{

        /* Hide navigation and buttons when printing */
        .sidebar,
        .topbar,
        .print-btn{

            display:none;

        }

        /* Use full page width for the report */
        .main-content{

            width:100%;
            margin:0;

        }

    }

    /* Professional table styling */

    .report-table{
        width:100%;
        border-collapse:collapse;
        margin-top:20px;
    }

    .report-table th{
        background:#007bff;
        color:white;
        padding:12px;
    }

    .report-table td{
        padding:10px;
        border:1px solid #ddd;
    }

    .report-table tr:nth-child(even){
        background:#f7f7f7;
    }

    .report-table tr:hover{
        background:#eef6ff;
    }

    /* Align report buttons to the right side */
    .report-buttons{
        text-align:right;
        width:100%;
        display:flex;
        margin:20px 0;
        gap:10px;
    
    }

    /* PDF download button styling */
    .print-btn,
    .pdf-btn{
       
        background:#dc3545;
        color:white;
        padding:10px 15px;
        border-radius:8px;
        text-decoration:none;
        font-size:15px;
        font-weight:bold;
        margin-left:10px;
        display:inline-block;

    }
    /* General button design */
    .report-buttons button{

        width:auto !important;
        display:inline-block;
        padding:12px 20px;
        border:none;
        border-radius:8px;
        color:white;
        font-size:15px;
        font-weight:bold;
        cursor:pointer;
    }

    .print-btn{
    background:#007bff;
    }

    .pdf-btn{
    background:#dc3545;
    margin-left:10px;
    }

    .print-btn:hover{
    background:#0056b3;
}



    .pdf-btn:hover{
        background:#b02a37;
    }
    </style>

<!-- Chart.js library used to create interactive charts for equipment reports -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<!--Calculate report summary values-->
<div class="main-content">
    <h2>Equipment Report</h2>

<!--Add a Report Generation Date-->
<p>
    <strong>Report Generated:</strong>
    <?= date("d M Y H:i"); ?>
</p>

    <?php
        // Get the company ID of the currently logged-in user.
        // This ensures each user only sees reports for their own company.
        $company_id = $_SESSION['company_id'];

        /* Count the total number of equipment belonging to the logged-in company */
        $totalEquipment = mysqli_fetch_assoc(mysqli_query($conn,

        "SELECT COUNT(*) total
        FROM equipment
        WHERE company_id='$company_id'"

        ))['total'];

        $availableEquipment = mysqli_fetch_assoc(mysqli_query($conn,

        "SELECT COUNT(*) total
        FROM equipment
        WHERE company_id='$company_id'
        AND status='Available'"

        ))['total'];

        /* Count equipment whose status is 'Available' */
        $maintenanceEquipment = mysqli_fetch_assoc(mysqli_query($conn,

        "SELECT COUNT(*) total
        FROM equipment
        WHERE company_id='$company_id'
        AND status='Under Maintenance'"

        ))['total'];

        /* Count equipment currently under maintenance */
        $damagedEquipment = mysqli_fetch_assoc(mysqli_query($conn,

        "SELECT COUNT(*) total
        FROM equipment
        WHERE company_id='$company_id'
        AND condition_status='Damaged'"

        ))['total'];

        /* Calculate the total monetary value of all equipment owned by the company */
        $totalValue = mysqli_fetch_assoc(mysqli_query($conn,

        "SELECT SUM(total_cost) total
        FROM equipment
        WHERE company_id='$company_id'"

        ))['total'];

        ?>

    <!-- Equipment Summary Cards (HTML Cards) -->
    <!-- These cards provide managers with a quick overview of equipment information -->
    <div class="summary-cards">

        <!-- Displays the total number of equipment -->
        <div class="card total-card">
            <h3>Total Equipment</h3>
            <p><?= $totalEquipment ?></p>
        </div>

        <!-- Displays the number of available equipment -->
        <div class="card available-card">
            <h3>Available</h3>
            <p><?= $availableEquipment ?></p>
        </div>

        <!-- Displays equipment currently under maintenance -->
        <div class="card maintenance-card">
            <h3>Maintenance</h3>
            <p><?= $maintenanceEquipment ?></p>
        </div>

        <!-- Displays the number of damaged equipment -->
        <div class="card damaged-card">
            <h3>Damaged</h3>
            <p><?= $damagedEquipment ?></p>
        </div>

        <!-- Displays the total value of all equipment -->
        <div class="card value-card">
            <h3>Total Value (RWF)</h3>
            <p><?= number_format($totalValue) ?></p>
        </div>

    </div>

    <?php

        // Retrieve equipment status counts for the logged-in company.
        // The data collected here will be displayed in the bar chart.

        $statusQuery = mysqli_query($conn,

        "
        SELECT status, COUNT(*) AS total
        FROM equipment
        WHERE company_id='$company_id'
        GROUP BY status
        "

        );


        // Store equipment status and their totals in an array
        $statusData = [];

        while($status = mysqli_fetch_assoc($statusQuery)){

            $statusData[$status['status']] = $status['total'];

        }

            /* Retrieve equipment condition statistics.
            These values will be displayed in the Equipment Condition Pie Chart. */

            $conditionQuery = mysqli_query($conn,

            "
            SELECT condition_status, COUNT(*) AS total
            FROM equipment
            WHERE company_id='$company_id'
            GROUP BY condition_status
            "

            );

            $conditionData = [];

            while($condition = mysqli_fetch_assoc($conditionQuery)){

                $conditionData[$condition['condition_status']] = $condition['total'];

            }

            /* Retrieve equipment grouped by equipment type.
            These values will be displayed in the Equipment Type Analysis Chart. */

            $typeQuery = mysqli_query($conn,

            "
            SELECT equipment_type, COUNT(*) AS total
            FROM equipment
            WHERE company_id='$company_id'
            GROUP BY equipment_type
            "

            );

            $typeLabels = [];
            $typeTotals = [];

            while($type = mysqli_fetch_assoc($typeQuery)){

                $typeLabels[] = $type['equipment_type'];
                $typeTotals[] = $type['total'];

            }

            /* Retrieve equipment that needs attention.
            This includes equipment that is damaged, needs repair,
            or currently under maintenance. */

            $alertQuery = mysqli_query($conn, 
            "
            SELECT equipment_name, equipment_type, condition_status, status
            FROM equipment
            WHERE company_id='$company_id'
            AND (
                condition_status='Damaged'
                OR condition_status='Needs Repair'
                OR status='Under Maintenance'
            )
            ORDER BY 
            CASE 
                WHEN condition_status='Damaged' THEN 1
                WHEN condition_status='Needs Repair' THEN 2
                WHEN status='Under Maintenance' THEN 3
                ELSE 4
            END
            ") or die(mysqli_error($conn));

    ?>

    <!-- Equipment Status Chart Section -->
    <div class="card" style="margin-bottom:30px;">
        <h3>Equipment Status Report</h3>
        <canvas id="equipmentStatusChart"></canvas>
    </div>

    <!-- Equipment Condition Pie Chart -->

    <div class="card" style="margin-top:30px;">
        <h3>Equipment Condition Report</h3>
        <canvas id="equipmentConditionChart"></canvas>
    </div>

    <!-- Equipment Type Analysis Chart -->

    <div class="card" style="margin-top:30px;">
        <h3>Equipment Type Analysis</h3>
        <canvas id="equipmentTypeChart"></canvas>
    </div><br>

    <!-- Download and Print report buttons -->
    <div class="report-buttons" style="margin:20px 0; text-align:right;">

        <!-- Button for downloading PDF report -->
        <button onclick="window.location.href='download_equipment_report.php';" class="pdf-btn">
            📄 Download PDF Report
        </button>

        <!-- Button for printing the report -->
        <button onclick="window.print();" class="print-btn">
            🖨 Print Equipment Report
        </button>

    </div>

        <table class="report-table">
            <h3 style="text-align:Center">Equipment Report Table</h3><br>
            <tr>
                <th>Equipment</th>
                <th>Equioment Typpe</th>
                <th>Quantity</th>
                <th>Conditon</th>
                <th>Status</th>
            </tr>

            <?php
            // This will show equipment reports based on the project that equipment was being used
            $result = mysqli_query($conn, "SELECT equipment.*, projects.project_name
                FROM equipment
                LEFT JOIN projects
                ON equipment.project_id = projects.project_id
                WHERE equipment.company_id = '$company_id'
                ") or die(mysqli_error($conn));

            $totalRecords = mysqli_num_rows($result);
            while ($row = mysqli_fetch_array($result)) {
                echo "<tr>
                <td>{$row['equipment_name']}</td>
                <td>{$row['equipment_type']}</td>
                <td>{$row['quantity']}</td>
                <td>{$row['condition_status']}</td>
                <td>{$row['status']}</td>
            </tr>";
            }
            ?>
        </table>

        <p style="margin-top:15px; font-weight:bold;">
            <strong>Total Equipment Records:</strong>
           <?= $totalRecords; ?>
        </p>

<!-- Equipment Alerts Panel -->
<div class="card" style="margin-top:30px;">

    <h3>⚠️ Equipment Requiring Attention</h3>

    <table border="1" width="100%" cellpadding="10" style="border-collapse:collapse; margin-top:15px;">
        <tr>
            <th>Equipment</th>
            <th>Type</th>
            <th>Condition</th>
            <th>Status</th>
            <th>Alert</th>
        </tr>

        <?php if(mysqli_num_rows($alertQuery) > 0): ?>
            <?php while($alert = mysqli_fetch_assoc($alertQuery)): ?>
                <tr>
                    <td><?= $alert['equipment_name']; ?></td>
                    <td><?= $alert['equipment_type']; ?></td>
                    <td><?= $alert['condition_status']; ?></td>
                    <td><?= $alert['status']; ?></td>
                    <td>
                        <?php
                        if($alert['condition_status'] == 'Damaged'){
                            echo '🔴 Immediate Attention';
                        } elseif($alert['condition_status'] == 'Needs Repair'){
                            echo '🟡 Schedule Repair';
                        } elseif($alert['status'] == 'Under Maintenance'){
                            echo '🟠 In Maintenance';
                        }
                        ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" style="text-align:center;">
                    ✅ No equipment currently requires attention.
                </td>
            </tr>
        <?php endif; ?>
    </table>

</div>
</div>
<script>

    // Creates an equipment status bar chart
    // showing the current equipment situation of the company.

    const statusChart = document.getElementById('equipmentStatusChart');


    new Chart(statusChart, {

        type: 'bar',

        data: {

            labels: [
                'Available',
                'In Use',
                'Under Maintenance',
                'Not In Use',
                'Lost'
            ],

            datasets: [{

                label: 'Number of Equipment',

                data: [

                    <?= $statusData['Available'] ?? 0 ?>,
                    <?= $statusData['In Use'] ?? 0 ?>,
                    <?= $statusData['Under Maintenance'] ?? 0 ?>,
                    <?= $statusData['Not In Use'] ?? 0 ?>,
                    <?= $statusData['Lost'] ?? 0 ?>

                ],

                borderWidth:1

            }]

        },

        options: {

            responsive:true,

            scales:{

                y:{

                    beginAtZero:true

                }

            }

        }

    });


    // Javascript for Equipment Condition Pie Chart 
    const conditionChart = document.getElementById('equipmentConditionChart');

    new Chart(conditionChart,{

        type:'pie',

        data:{

            labels:[

                'Good',
                'Needs Repair',
                'Damaged'

            ],

            datasets:[{

                data:[

                    <?= $conditionData['Good'] ?? 0 ?>,
                    <?= $conditionData['Needs Repair'] ?? 0 ?>,
                    <?= $conditionData['Damaged'] ?? 0 ?>

                ]

            }]

        },

        options:{

            responsive:true

        }

    });


    /* JavaScript for Equipment Type Analysis Chart */

    const typeChart = document.getElementById('equipmentTypeChart');

    new Chart(typeChart,{

        type:'bar',

        data:{

            labels: <?= json_encode($typeLabels); ?>,

            datasets:[{

                label:'Number of Equipment',

                data: <?= json_encode($typeTotals); ?>,

                borderWidth:1

            }]

        },

        options:{

            responsive:true,

            scales:{

                y:{

                    beginAtZero:true

                }

            }

        }

    });

</script>