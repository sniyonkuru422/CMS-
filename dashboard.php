<?php include("company_admin_dashboard.php");
include("dashboard_layout.php");
?>

<h2>Dashboard Overview</h2>

<p>Welcome to your system dashboard!</p>

/** Add sections for low stock materials notifications and open issues/complaints */
<?php if($lowMaterials->num_rows > 0): ?> //This line avoid empty alert box when there are no low stock materials
<div style="background:#ffcccc; padding:10px; border-radius:5px; margin-top:20px;">
    <h3>⚠ Low Stock Materials Alert</h3>
    
    <?php while($row = $lowMaterials->fetch_assoc()):?>
        <p><?= $row['name'] ?> is low (<?= $row['quantity'] ?> left) </p>
    <?php endwhile; ?>
</div>
<?php endif; ?>

<?php
$conn = mysqli_connect("localhost","root","","construction_management");
$company_id = $_SESSION["company_id"];

$materials = $conn->query("SELECT SUM(total_cos) as total FROM materials WHERE company_id = $company_id")->fetch_assoc()['total'];
$expenses = $conn->query("SELECT SUM(amount) as total FROM expenses WHERE company_id = $company_id")->fetch_assoc()['total'];
$projects = $conn->query("SELECT COUNT(*) as total FROM projects WHERE company_id = $company_id")->fetch_assoc()['total'];

// Low material notifcations or Additional queries for low stock materials and open issues/complaints
$lowMaterials = $conn->query("SELECT * FROM materials WHERE quantity <= min_quantity AND company_id = $company_id")->fetch_all(MYSQLI_ASSOC);
//$issuescomplaints = $conn->query("SELECT * FROM issues WHERE status = 'OPEN' AND company_id = $company_id")->fetch_all(MYSQLI_ASSOC);
?>  

<html>
<body>

<div class ="card">
    <h3>Projects?></h3>
    <p><?=$projects?></p>
</div>

<div class ="card">
    <h3>Materials</h3>
    <p><?=$materials?></p>
</div>

<div class ="card">
    <h3>Expenses</h3>
    <p><?=$expenses?></p>
</div>

</body>
</html>
