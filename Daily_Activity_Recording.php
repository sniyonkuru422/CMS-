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

if (isset($_POST['save_activity'])){
    $project_id = $_POST['project_id'];
    $activity = $_POST['activity'];
    $materials = $_POST['materials_used'];
    $workers = $_POST['workers'];
    $equipment = $_POST['equipment_used'];
    $issues = $_POST['issues'];
    $date = $_POST['date'];

    $query = "INSERT INTO daily_activities
        (project_id, activity, materials_used, workers, equipment_used, issues, date)
        VALUES ('$project_id', '$activity', '$materials', '$workers', '$equipment', '$issues', '$date')";

        mysqli_query($conn, $query);
}
?>
<div classs="main-content">
<h2>Daily Activity Recording</h2>

<form method="POST">
    <!-- Below there's select option for dropdown to make system real and user-friendly-->
    <select name="project_id" required>
        <option value="">Select Project</option>
        <?php
        $projects = mysqli_query($conn,"SELECT * FROM projects");
        while ($p = mysqli_fetch_assoc($projects)) {
            echo "<option value='{$p['id']}'>{$p['name']}</option>";
        }
        ?>
    </select>
    <textarea name="activity" placeholder="work done today" required></textarea>
    <textarea name="material_used" placeholder="Material used"></textarea>
    <nput type="number" name="workers" placeholder="number of workers">
        <textarea name="equipment_used" placeholder="Equipment used"></textarea>
        <textarea name="issues" id="Issues / delays"></textarea>
        <input type="date" name="date" required>
        <button name="save_activity">Save</button>

</form>

<hr>
<h3>Daly Records</h3>
<table border="1">
    <tr>
        <th>Project</th>
        <th>Activity</th>
        <th>Date</th>
    </tr>

<?php
$result = mysqli_query($conn,"SELECT * FROM daily_activities ORDER BY date DESC");

while ($row = mysqli_fetch_array($result)) {
    echo "<tr>
        <td>{$row['project_id']}</td>
        <td>{$row['activity']}</td>
        <td>{$row['date']}</td>
        </tr>";
}
?>
</table>
</div>