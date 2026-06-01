<!--This is where you yiu can set message notifications, filter the charts with the form, 
Cards UI with smart warning when the expenses are higher compared to the one spent on the wokers-->
<?php
include("dashboard_layout.php");
include("database/connect.php");

$company_id = $_SESSION["company_id"];

//The line below shows how the project will be selected with the expenses that spent on it
$data = $conn->query("SELECT projects.project_name, SUM(expenses.amount) 
AS total FROM expenses JOIN projects ON expenses.project_id = projects.project_id 
WHERE expenses.company_id = $company_id
GROUP BY projects.project_id");

$projects = [];
$totals = [];

//if no data found, get message says that

if ($data->num_rows > 0) {
    while($row = $data->fetch_assoc())
        {
    $projects[] = $row['project_name'];
    $totals[] = $row['total'];

}
} else{
    echo "<p style='color:red;'>No data found for selected filters.</p>";
}
?>

<!-- Below there is Filters to charts by (date & project)-->


<div class="msin-content">
<h2>Analytics Dashboard</h2>

<!-- fiLTER FORM-->

<form method="GET">
    <select name="project_id">
        <option value="">All Projects</option>
        <?php
        $proj = $conn->query("SELECT * FROM projects WHERE company_id =$company_id");
        while($p = $proj->fetch_assoc()){
            $selected = (isset($_GET['project_id']) && $_GET['project_id'] == $p['project_id']) ? "selected" : "";
            echo "<option value='{$p['project_id']}' $selected>{$p['project_name']}</option>";
        }
        ?>
    </select>

    <input type="date" name="start_date" value="<?= $_GET['start_date'] ?? '' ?>">
    <input type="date" name="end_date" value="<?= $_GET['end_date'] ?? '' ?>">
 
    <button type="submit">Filter</button>
</form>

<!--Bellow There Is How To Set Smart Warning-->
<?php
$warning ="";

// Get totals
$total_exp = $total_expense ?? 0;
$total_wrk = $total_workers ?? 0;

//simple logc
if ($total_exp > 1000000 && $total_wrk > 0) {
    $warning = "⚠️ High expenses with low workforce detected!";
}
elseif ($total_exp >1000000){
    $warning = "⚠️ Expenses are very high!";
}
elseif ($total_wrk < 5){
    $warning = "⚠️ Very low workforce detected!";
}
?>
<!--Show Warnng-->
<?php if($warning != ""): ?>
<div style="background:#ff4d4d; color:white; padding:15px; 
border-radius:80; margin-bottom:15px; font-weight: bold;">

<?= $warning ?>
</div>
<?php endif; ?>

<!--MODERN DASHBOARD CARDS (Clean UI)-->

<div style="display:flex; gap:20px; margin:20px 0; flex-wrap:wrap;">

    <?php
    $expense_color = ($total_expense > 1000000) 
        ? "linear-gradient(135deg,#ff4d4d,#b71c1c)" 
        : "linear-gradient(135deg,#4CAF50,#2e7d32)";
    ?>
    <div style="
        flex:1;
        background:linear-gradient(135deg,#4CAF50,#2e7d32);
        color:white;
        padding:20px;
        border-radius:15px;
        box-shadow:0 4px 10px rgba(0,0,0,0.2);
    ">
        <h4>Total Expenses</h4>
        <h2><?= number_format($total_expense) ?> RWF</h2>
    </div>

    <div style="
        flex:1;
        background:linear-gradient(135deg,#2196F3,#0d47a1);
        color:white;
        padding:20px;
        border-radius:15px;
        box-shadow:0 4px 10px rgba(0,0,0,0.2);
    ">
        <h4>Total Workers</h4>
        <h2><?= number_format($total_workers) ?></h2>
    </div>

    <div style="
        flex:1;
        background:linear-gradient(135deg,#FF9800,#e65100);
        color:white;
        padding:20px;
        border-radius:15px;
        box-shadow:0 4px 10px rgba(0,0,0,0.2);
    ">
        <h4>Total Projects</h4>
        <h2><?= number_format($total_projects) ?></h2>
    </div>

</div>
<!-- This shows filter form with dashboard summary cards-->

<?php
//Total calculation 

$total_expense = $conn->query("
    SELECT SUM(amount) as total FROM expenses 
    WHERE company_id = $company_id
")->fetch_assoc()['total'] ?? 0;

$total_workers = $conn->query("
    SELECT SUM(workers) as total 
    FROM daily_activities
")->fetch_assoc()['total'] ?? 0;

$total_projects = $conn->query("
    SELECT COUNT(*) as total 
    FROM projects 
    WHERE company_id = $company_id
")->fetch_assoc()['total'] ?? 0;
?>


<!-- Add Filter Logic-->
 <?php
 $where ="WHERE expenses.company_id = $company_id";

 if (!empty($_GET['project_id'])) {
    $project_id = $_GET['project_id'];
    $where .= "AND expenses.project_id = '$project_id'";

 }

 if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
    $start = $_GET['start_date'];
    $end = $_GET['end_date'];
    $where .= "AND expenses.date BETWEEN '$start' AND '$end'";

 }
 ?>

<h2> Expense Chart </h2>

<!--ADD ALL CANVAS-->

<h3>Expenses per Project</h3>
<canvas id="expenseChart"></canvas>

<h3>Equipment Status</h3>
<canvas id="equipmentChart"></canvas>

<h3>Workers Per Day</h3>
<canvas id="activityChart"></canvas>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

    // Expense charts

new Chart(document.getElementById('expenseChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($projects); ?>,
        datasets:[{
            label: 'Expenses per project',
            data: <?= json_encode($totals); ?>
            backgroundColor:'rgba(54, 162, 235, 0.6)'
        }]
    }

});

// Equipment Chart
    new Chart(document.getElementById('equipmentChart'), {
        type: 'pie',
        data: {
            label: <?= json_encode($labels ?? []); ?>,
            datasets: [{
                data: <?= json_encode($values ?? []); ?>
            }]
        }

    });

// Daily Activity Chart
new Chart(document.getElementById('activityChart'),{
    type: 'line',
    data: {
        labels: <?= json_encode($dates ?? []); ?>,
        datasets:[{
            labels:"Workers",
            data: <?= json_encode($workers ?? []); ?>
        }]
    }
});
</script>
</div>
