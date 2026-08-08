<?php

include("auth.php");
checkLogin();

requireAnyRole([
    "SITE_ENGINEER",
    "SUPERVISOR",
    "COMPANY_ADMIN"
]);

include("database/connect.php"); // Include the database connection file
include("dashboard_layout.php"); // Include the common dashboard layout

// Get logged-in user's information
$user_id = $_SESSION['user_id'];
$company_id = $_SESSION['company_id'];


if(isset($_POST['save_activity'])){

    $project_id = $_POST['project_id'];
    $activity_name = $_POST['activity_name'];
    $activity_description = $_POST['activity_description'];
    $materials = $_POST['materials_used'];
    $workers = $_POST['workers'];
    $equipment = $_POST['equipment_used'];
    $issues = $_POST['issues'];
    $activity_date = $_POST['activity_date'];

    $query = " INSERT INTO daily_activities (project_id, activity_name, activity_description, materials_used,
    workers, equipment_used, issues, activity_date, recorded_by
    )
    VALUES
    (
    '$project_id', '$activity_name','$activity_description','$materials',
    '$workers','$equipment','$issues','$activity_date', '$user_id' )";

    

    mysqli_query($conn,$query)
    or die(mysqli_error($conn));


}
?>

<body>
<head>
<!-- Bellow there is a css style for edit and delete button-->
    <Style>
        .edit-btn,
        .delete-btn{

        padding:8px 12px;
        border-radius:5px;
        text-decoration:none;
        color:white;
        font-size:14px;
        display:inline-block;
        }


        .edit-btn{
        background:#007bff;
        }


        .delete-btn{
            background:#dc3545;
        }


        .edit-btn:hover,
        .delete-btn:hover{

            opacity:0.8;
        }

        /* Activity Form Container */
        .activity-form-container{
            background: #b3fa0e;
            padding:25px;
            border-radius:10px;
            margin-bottom:30px;
            box-shadow:0 4px 12px rgba(0,0,0,0.15);
        }

        /* Activity Table Container */

        .activity-table-container{
            background: #b3fa0e;
            padding:25px;
            border-radius:10px;
            box-shadow:0 4px 12px rgba(0,0,0,0.15);
        }


        /* Form Inputs */
        .activity-form-container textarea,
        .activity-form-container select,
        .activity-form-container input{

            width:100%;
            padding:10px;
            margin:8px 0;
            border-radius:5px;
            border:1px solid #ccc;

        }

        /* Save Button */
        .save-btn{
            background: #28a745;
            color:white;
            border:none;
            padding:10px 25px;
            border-radius:5px;
            cursor:pointer;
            font-weight:bold;
        }

        .save-btn:hover{
            opacity:0.8;
        }

        /* Table Styling */
        .activity-table-container table{
            width:100%;
            border-collapse:collapse;
        }

        .activity-table-container th{
            background: #007bff;
            color:white;
            padding:10px;
        }

        .activity-table-container td{
            padding:10px;
            text-align:center;
        }
    /*Headings*/
        .activity-form-container h2,
        .activity-table-container h3{

            text-align:center;
            margin-bottom:20px;
            
        }

        .activity-form-container h2,
        .activity-table-container h3{
            text-align:center;
            color: #010a14;
            font-size:24px;
            margin-bottom:20px;
        }
    </Style>
</head>


<div class="main-content">
<!-- ==========================
     ACTIVITY FORM CONTAINER
============================== -->

    <div class="activity-form-container">
        <h2>Daily Activity Recording</h2>
        <form method="POST">
            <!-- Below there's select option for dropdown to make system real and user-friendly-->
            <select name="project_id" required>
                <option value="">Select Project</option>
                <?php
            $projects = mysqli_query($conn,

                "SELECT project_id, project_name FROM projects
                WHERE company_id='$company_id'" );

                while ($p=mysqli_fetch_assoc($projects)){
                    echo "
                    <option value='{$p['project_id']}'>
                    {$p['project_name']}
                    </option>";
                }
                ?>

            </select>
            <textarea name="activity_name" placeholder="Activity Name" required></textarea>
            <textarea name="activity_description" placeholder="Describe the activity completed"></textarea>
            <textarea name="materials_used" placeholder="Materials Used"></textarea>
            <textarea name="workers" placeholder="number of workers"></textarea>
                <textarea name="equipment_used" placeholder="Equipment used"></textarea>
                <textarea name="issues" id="Issues / delays" placeholder="Issues"></textarea>
                <input type="date" name="activity_date" required>
                <button name="save_activity">Save</button>

        </form>
    </div><!---- End Activity Form Container -->
</div>

<!-- ==========================
     ACTIVITY TABLE CONTAINER
============================== -->

<div class="activity-table-container">
<h3>Daly Records</h3>
<hr>
<table border="1">
    <tr>
        <th>Project</th>
        <th>Activity Name</th>
        <th>Workers</th>
        <th>Equipment</th>
        <th>Issues</th>
        <th>Date</th>
        <th>Recorded By</th>
        <th>Action</th>
    </tr>

<?php
$result = mysqli_query($conn, "SELECT daily_activities.*, projects.project_name, users.name
FROM daily_activities
LEFT JOIN projects ON daily_activities.project_id = projects.project_id
LEFT JOIN users
ON daily_activities.recorded_by = users.user_id

ORDER BY activity_date DESC"

) or die(mysqli_error($conn));

while ($row = mysqli_fetch_array($result)) {
    echo "<tr>
        <td>{$row['project_name']}</td>
        <td>{$row['activity_name']}</td>
        <td>{$row['workers']}</td>
        <td>{$row['equipment_used']}</td>
        <td>{$row['issues']}</td>
        <td>{$row['activity_date']}</td>
        <td>{$row['name']}</td>
        <td>
        <a href='edit_activity.php?id={$row['activity_id']}'
        class='edit-btn'>
        ✏ Edit
        </a>


        <a href='delete_activity.php?id={$row['activity_id']}'
        class='delete-btn'
        onclick=\"return confirm('Are you sure you want to delete this activity?');\">
        🗑 Delete
        </a>
        
        </td>
        </tr>
    ";
}
?>
</table>
</div><!-- End Activity Table Container -->
</div><!-- End Main Content -->
</body>

<!--Examples of activity names and the possible issues-->
<!--Site Preparation -> Delay in site clearing, difficult terrain, access problems, weather interruptions       -->
<!--Excavation Works -> Rocky soil, unexpected underground utilities, equipment breakdown, heavy rainfall-->
<!--Foundation Construction -> Lack of materials, incorrect measurements, delayed inspection approval-->
<!--Concrete Mixing -> Rain interruption, insufficient workers, concrete delivery delays, quality concerns-->
<!--Reinforcement Steel Installation -> Steel delivery delays, incorrect cutting sizes, shortage of skilled workers-->
<!--Brick Laying / Masonry Works -> Material shortage, poor workmanship, alignment issues, slow progress-->
<!--Wall Plastering -> Material shortage, uneven surface preparation, poor finishing quality-->
<!--Electrical Installation -> Cable shortage, design changes, safety risks, inspection delays-->
<!--Plumbing Installation -> Pipe leakage, wrong installation, material delays -->
<!--Painting Works -> Paint shortage, poor surface preparation, color changes requested by client-->
<!--Door and Window Installation ->Supplier delays, transportation problems, wrong materials delivered -->
<!--Material Delivery -> Supplier delays, transportation problems, wrong materials delivered-->


<!-- Activity Name:

Brick Laying

Activity Description:

Workers constructed external walls for Block A using concrete blocks and mortar.

Materials Used:

Concrete blocks, cement, sand, water

Number of Workers:

12

Equipment Used:

Trowels, wheelbarrows, measuring tools

Issues / Delays:

Shortage of cement affected the progress of wall construction.

Activity Date:

2026-07-18
 -->