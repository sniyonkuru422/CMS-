<?php

//session_start();

include("auth.php");
checkLogin();

include("database/connect.php");

$company_id = $_SESSION['company_id'];
$user_id = $_SESSION['user_id'];

requireRole('SITE_ENGINEER');


// SAVE ISSUE

if(isset($_POST['submit_issue'])){


    $project_id = $_POST['project_id'];
    $title = $_POST['issue_title'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];


   $stmt = $conn->prepare("
    INSERT INTO issues
    (company_id, project_id, reported_by, issue_title, description, priority, issue_date)
    VALUES (?, ?, ?, ?, ?, ?, CURDATE())
");


$stmt->bind_param(
    "iiisss",
    $company_id,
    $project_id,
    $user_id,
    $title,
    $description,
    $priority
);

    if($stmt->execute()){

        echo "<script>
        alert('Issue reported successfully');
        window.location='issue_reporting.php';
        </script>";

    }

}


    // GET PROJECTS

    $projects = $conn->query("
    SELECT project_id, project_name 
    FROM projects
    WHERE company_id=$company_id
    ");


    include("dashboard_layout.php");

    ?>


    <h2>Report Site Issue</h2>

    <form method="POST">

    <label>Select Project</label><br>
    <select name="project_id" required>

        <option value="">
        --Select Project--
        </option>

        <?php while($p=$projects->fetch_assoc()){ ?>
            <option value="<?= $p['project_id']; ?>">
                <?= $p['project_name']; ?>
            </option>
        <?php } ?>

    </select>

    <br><br>

    <label>Issue Title</label><br>
    <input style="width:100%" type="text" 
    name="issue_title"
    required>

    <br><br>
    <label>Description</label><br>

    <textarea name="description"
    rows="5"
    cols="46"
    required></textarea>


    <br><br>

    <label>Priority</label><br>
    <select name="priority">
         <option value="">
        -- Select --
        </option>
        <option value="LOW">
            Low
        </option>

        <option value="MEDIUM">
            Medium
        </option>

        <option value="HIGH">
            High
        </option>

    </select>

    <br><br>


    <button name="submit_issue">
        Submit Issue
    </button>

    </form>