<?php

// Authentication
include("auth.php");

checkLogin();

requireAnyRole([
    "COMPANY_ADMIN",
    "SITE_ENGINEER",
    "SUPERVISOR"
]);

// Database connection
include("database/connect.php");

$company_id = $_SESSION['company_id'];

// Get the activity ID
if (!isset($_GET['id'])) {
    header("Location: Daily_Activity_Recording.php");
    exit();
}

$activity_id = intval($_GET['id']);


// ===============================
// UPDATE ACTIVITY
// ===============================

if (isset($_POST['update_activity'])) {

    $project_id = $_POST['project_id'];
    $activity_name = $_POST['activity_name'];
    $activity_description = $_POST['activity_description'];
    $materials_used = $_POST['materials_used'];
    $workers = $_POST['workers'];
    $equipment_used = $_POST['equipment_used'];
    $issues = $_POST['issues'];
    $activity_date = $_POST['activity_date'];

    $update = mysqli_query($conn,

    "UPDATE daily_activities

    SET

    project_id='$project_id',
    activity_name='$activity_name',
    activity_description='$activity_description',
    materials_used='$materials_used',
    workers='$workers',
    equipment_used='$equipment_used',
    issues='$issues',
    activity_date='$activity_date'

    WHERE activity_id='$activity_id'")

    or die(mysqli_error($conn));

    header("Location: Daily_Activity_Recording.php");
    exit();
}

// ===============================
// LOAD CURRENT RECORD
// ===============================

$result = mysqli_query($conn,

"SELECT *
FROM daily_activities
WHERE activity_id='$activity_id'")

or die(mysqli_error($conn));

$row = mysqli_fetch_assoc($result);

// Dashboard to load the layout
include("dashboard_layout.php");
?>

<head>
    <style>

        .edit-container{
        background: #fff8dc;          /* Light yellow */
        border:2px solid #f5b904;    /* Yellow border */
        border-radius:10px;
        padding:25px;
        margin:20px auto;
        width:80%;
        box-shadow:0 4px 10px rgba(0,0,0,0.15);
    }

    .edit-container h2{
        color: #b8860b;
        text-align:center;
        margin-bottom:20px;
    }

    .edit-container label{
        font-weight:bold;
        display:block;
        margin-top:12px;
    }

    .edit-container input,
    .edit-container textarea,
    .edit-container select{
        width:100%;
        padding:10px;
        border:1px solid #ccc;
        border-radius:5px;
        margin-top:5px;
        box-sizing:border-box;
    }
    </style>
</head>

<div class="main-content">
    <div class="edit-container">

        <h2>Edit Daily Activity</h2>

        <form method="POST">

            <label>Project</label>
            <select name="project_id" required>

            <?php

            $projects = mysqli_query($conn, "SELECT project_id, project_name
            FROM projects
            WHERE company_id='$company_id'");

            while($project=mysqli_fetch_assoc($projects)){
            $selected = ($project['project_id']==$row['project_id']) ? "selected" : "";

            echo "

            <option value='{$project['project_id']}' $selected>
            {$project['project_name']}

            </option>

            ";

            }

            ?>

            </select>


            <br><br>

            <label>Activity Name</label>
            <input type="text" name="activity_name" value="<?= htmlspecialchars($row['activity_name']) ?>" required>

            <br><br>
            <label>Description</label>
            <textarea name="activity_description"><?= htmlspecialchars($row['activity_description']) ?></textarea>
            <br><br>

            <label>Materials Used</label>
            <textarea name="materials_used"><?= htmlspecialchars($row['materials_used']) ?></textarea>

            <br><br>
            <label>Workers</label>

            <input type="number" name="workers" value="<?= $row['workers'] ?>">

            <br><br>

            <label>Equipment Used</label>

            <textarea name="equipment_used"><?= htmlspecialchars($row['equipment_used']) ?></textarea>

            <br><br>
            <label>Issues</label>

            <textarea name="issues"><?= htmlspecialchars($row['issues']) ?></textarea>
            <br><br>

            <label>Activity Date</label>

            <input type="date" name="activity_date" value="<?= $row['activity_date'] ?>" required>

            <br><br>

            <button type="submit" name="update_activity">
                Update Activity
            </button> <br><br>

            <button type="cancel" name="update_activity" a href="Daily_Activity_Recording.php">
                Cancel
            </button>

        </form>
    </div>
</div>