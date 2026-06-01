<?php
include("dashboard_layout.php");
include("database/connect.php");

$company_id = $_SESSION['company_id'];

// GET DATA OR //The line below shows how the project will be selected with the expenses that spent on it
$result = $conn->query("SELECT projects.project_id,project.project_name,project.budget,IFNULL(SUM(expenses.amount), 0) AS total_expense from projects 
LEFT JOIN expenses ON projects.projects_id = expenses.project_id
WHERE projects.company_id = $company_id
GROUP BY projects.project_id");
?>

<h2>Budget vs Expenses Report</h2>

<table border="1" width="100" cellpadding="10" style="background: white;"></table>
<tr>
    <th>Project</th>
    <th>Budget</th>
    <th>Total Expenses</th>
    <th>Remaining</th>
    <th>Status</th>
</tr>

<?php 
while ($row = $result->fetch_assoc()):

$remainng = $row["budget"] - $row["total_expense"];

if ($remaining < 0) {

    $status = "<span style='color:red;'>Over Budget</span>";

}
else {
    $status = "<span style='color:green;'> Within Budget</span>";
}
?>

<tr style="<?= ($remaining < 0) ? 'background:#ffcccc;' : 'background:#ccffcc;' ?>">
    <td><?=$row['name']; ?></td>
    <td><?=$row['budget']; ?></td>
    <td><?=$row['total_expense']; ?></td>
    <td><?=$remaining; ?></td>
    <td><?=$status; ?></td>
</tr>
<?php endwhile; ?>

</table>