<?php

//session_start();

include("auth.php");
checkLogin();

include("database/connect.php");


$company_id = $_SESSION['company_id'];


// Allow only Company Admin and Project Manager

if(
    $_SESSION['role'] != 'COMPANY_ADMIN' &&
    $_SESSION['role'] != 'PROJECT_MANAGER'
){

    header("Location: login.html");
    exit();

}


include("dashboard_layout.php");


$issues = $conn->query("

SELECT 
issues.*,
projects.project_name,
users.name AS reporter

FROM issues

JOIN projects 
ON issues.project_id = projects.project_id

JOIN users
ON issues.reported_by = users.user_id

WHERE issues.company_id=$company_id

ORDER BY issues.issue_date DESC

");

if (!$issues) {
    die("Database Error: " . $conn->error);
}


?>


<h2>Reported Site Issues</h2>

<table border="1" width="100%" cellpadding="8">

    <tr>

        <th>Project</th>
        <th>Reported By</th>
        <th>Issue Title</th>
        <th>Description</th>
        <th>Priority</th>
        <th>Status</th>
        <th>Date</th>

    </tr>

    <?php while($row=$issues->fetch_assoc()){ ?>

        <tr>

            <td>
            <?= htmlspecialchars($row['project_name']); ?>
            </td>

            <td>
            <?= htmlspecialchars($row['reporter']); ?>
            </td>

            <td>
            <?= htmlspecialchars($row['issue_title']); ?>
            </td>

            <td>
            <?= htmlspecialchars($row['description']); ?>
            </td>

            <td>
            <?= htmlspecialchars($row['priority']); ?>
            </td>

            <td>
            <?= htmlspecialchars($row['status']); ?>
            </td>

            <td>
            <?= $row['issue_date']; ?>
            </td>

        </tr>

    <?php } ?>


</table>