
<?php
// The system tracks worker attendance, analyzes absenteeism, 
// and generates reports for management.
//session_start();

include("auth.php");
checkLogin();

requireAnyRole([
    "SUPERVISOR",
    "COMPANY_ADMIN"
]);

// Include the database connection file
include("dashboard_layout.php");
include("database/connect.php");


// ROLE SECURITY 
if (!in_array($_SESSION['role'], ['SUPERVISOR', 'SITE_ENGINEER','COMPANY_ADMIN'])) {
    header("Location: login.html");
    exit;
}
$company_id = $_SESSION["company_id"];

// SAVE ATTENDENCE

if (isset($_POST['save'])){
    $worker_id = $_POST['worker_id'];
    $worker_type = $_POST['worker_type'];
    $date = $_POST['date'];
    $status = $_POST['status'];
    $recorded_by = $_POST['recorded-by'];

    $conn->query("INSERT INTO attendance (worker_id, worker_type, date, status, company_id)')
    VALUES ('$worker_id', '$worker_type','$date','$status','$company_id')");

}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Attendence System</title>
    </head>

<body>
        <h2>Combined Attendence System</h2>

<!--SELECT A WORKER TYPE -->

<form method="POST">
    <label>Select Worker Type:</label>
    <select name="type" id="type" onchange="loadworker()" required>
        <option value="">--Select--</option>
        <option value="USER">System User</option>
        <option value="WORKER">Field Worker</option>
    </select>

    <br><br>

<!-- WORKER DROPDOWN/ (List of workers) -->
 <label>Select The Person</label>
 <select name="worker_id" id="workerList" required>
    <option value="">Select The Worker</option>
</select>

<br><br>

<label>Select The Date</label>
<input type="date" name="date" required><br><br>

<label>Attendance Status</label>
<select name="status">
    <option value="">---Select---</option>
    <option>Present</option>
    <option>Late</option>
    <option>Absent</option>
</select>

<br><br>
<button name="save">Save Attendence</button>
</form>

</body>

<hr>
<h3>Attendance Records</h3>

  <!--Button To Download Report-->
<div style = "text-align:right; margin-bottom:10px;">
    <a href = "attendance_report.php"
      style = "paddng:8px 15px; background:green; color:white; text-decoration:none; border-radius:5px;">
        📥 Download Report
    </a>
</div>

<table border="1" width="100%">
    <tr>
        <th>Name</th>
        <th>Worker type</th>
        <th>date</th>
        <th>Status</th>
    </tr>

<?php

// FETCH COMBINED DATA 
// 'SYSTEM USER'
$userData = $conn->query("SELECT attendance.*, user.name FROM attendance 
JOIN user ON attendance.worker_id = user.user_id WHERE attendance.worker_type='USER'
AND attendance.company_id=$company_id");

while($row = $userData->fetch_assoc()):
?>

<tr>
    <td><?=$row['name']; ?></td>
    <td>Sytem User</td>
    <td><?=$row['date']; ?></td>
    <td><?=$row['status']; ?></td>
</tr>
<?php endwhile; ?>


<?php 
// WORKER DATA

$workerData = $conn->query("SELECT attendance.*, workers.name FROM attendance
JOIN workers ON attendance.worker_id = workers.worker_id
WHERE attendance.worker_type = 'WORKER' AND attendance.company_id=$company_id");

while($row = $workerData->fetch_assoc()):
?>
<tr>
    <td><?=$row['name']; ?></td>
    <td>Field Worker</td>
    <td><?= $row['date']; ?></td>
    <td><?= $row['status']; ?></td>
</tr>
<?php endwhile; ?>

</table>

<!-- ATTENDENCE SUMMARY-->

 <h3>Attendance Summary (Absences)</h3>

<?php
$summary = $conn->query("
    SELECT 
        CASE 
            WHEN worker_type='USER' THEN user.name
            ELSE workers.name
        END AS name,
        COUNT(*) AS total_absent
    FROM attendance
    LEFT JOIN user ON attendance.worker_id = user.user_id
    LEFT JOIN workers ON attendance.worker_id = workers.worker_id
    WHERE status='Absent'
    AND company_id=$company_id
    GROUP BY worker_id, worker_type
    ORDER BY total_absent DESC
");

while($s = $summary->fetch_assoc()):
?>

<p>
    <?= $s['name']; ?> → Absent: <?= $s['total_absent']; ?>
</p>

<?php endwhile; ?> 



<!--JAVASCRIPT TO SWITCH DATA-->
<script>
    function loadWorkers(){
        var type = document.getElementById('type').value;
        var list = document.getElementById('workerList');

        list.innerHTML = "";

        if (type == "USER"){
            <?php
            $users = $conn->query("SELECT * FROM user WHERE company_id = $company_id 
            AND role IN('SUPERVISOR','SITE_ENGINEER','COMPANY_ADMIN')");
            ?>

            var users = [
                <?php while($u = $users->fetch_assoc()): ?>
                {id:"<?=$u['user_id']; ?>", name: "<?=$u['name']; ?>"},
                <?php endwhile; ?>
            ];

            user.foreach(function(u){
                var option = document.createElemt("option");
                option.value = u.id;
                option.text = u.name;
                list.appendChild(option);
            });
        }
    

    if (type === "WORKER"){
        <?php
        $workers = $conn->query("SELECT * FROM workers WHERE company_id=$company_id ");
        ?>

        var workers = [
            <?php while($w = $workers->fetch_assoc()): ?>
            {id: "<?=$w['worker_id']; ?>", name: "<?=$w['name']; ?>"},
            <?php endwhile; ?>
        ];

        workers.forEach(function(w){
            var option = document.createElement("option");
            option.value = w.id;
            opton.text = w.name;
            list.appendChild(opton);
        });
    }
            
}
</script>
</body>
</html>
