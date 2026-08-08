<?php
include 'database/connect.php';

$search = $_GET['search'] ?? '';
$company_id = $_GET['company_id'] ?? '';

// Base SQL query
$sql = "SELECT workers.worker_id, workers.name, workers.phone, workers.nid, workers.worker_category, workers.created_at, companies.company_name
        FROM workers
        JOIN companies ON workers.company_id = companies.company_id
        WHERE 1";

// SEARCH FILTER
if (!empty($search)) {
    $search = mysqli_real_escape_string($conn, $search);
    $sql .= " AND (workers.name LIKE '%$search%' OR workers.nid LIKE '%$search%' OR workers.phone LIKE '%$search%')";
}

// COMPANY FILTER
if (!empty($company_id)) {
    $company_id = intval($company_id);
    $sql .= " AND workers.company_id = '$company_id'";
}

// EXECUTE QUERY
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// check and display a pop up message if there is no worker found
$noResults = (mysqli_num_rows($result) == 0 && !empty($search));


// Check filter active
$filterActive = !empty($search) || !empty($company_id);
?>

<!-- STYLE -->
<style>
table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background-color: #2196F3;
    color: white;
}

td, th {
    padding: 10px;
    text-align: left;
}

tr:nth-child(even) {
    background-color: #f2f2f2;
}

.page-title {
    text-align: center;
    color: white;
    background: linear-gradient(135deg, #eb4560, #2e7d32);
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 15px;
    font-weight: bold;
}
</style>

<!-- TITLE -->
<h2 class="page-title">View All Field Workers</h2>

<!-- Pop up message to display when no workers are found -->
<?php
if (mysqli_num_rows($result) == 0 && !empty($search)) {
    echo "
    <script>
        alert('No worker found. This person is not registered in the system or may have been deleted.');
    </script>
    ";
}
?>

<div class="container-dashboard">

<!-- SEARCH FORM -->
<form method="GET" style="margin-bottom:20px; display:flex; gap:10px;">

    <input type="text" name="search" placeholder="Search worker..."
        value="<?= $_GET['search'] ?? '' ?>"
        style="padding:8px; width:200px;">

    <select name="company_id" style="padding:8px;">
        <option value="">All Companies</option>

        <?php
        $companies = mysqli_query($conn, "SELECT * FROM companies");
        while ($c = mysqli_fetch_assoc($companies)):
        ?>
            <option value="<?= $c['company_id']; ?>"
                <?= (isset($_GET['company_id']) && $_GET['company_id'] == $c['company_id']) ? 'selected' : '' ?>>
                <?= $c['company_name']; ?>
            </option>
        <?php endwhile; ?>
    </select>

    <button type="submit" style="padding:8px 15px; background:#4CAF50; color:white; border:none;">
        Search
    </button>

    <?php if ($filterActive): ?>
        <a href="view_workers.php"
           style="margin-left:auto; padding:8px 15px; background:#2196F3; color:white; text-decoration:none; border-radius:5px;">
            View All Workers
        </a>
    <?php endif; ?>

</form>

<!-- TABLE -->
<table border="1" cellpadding="10" cellspacing="0">
    <tr>
        <th>No</th>
        <th>Name</th>
        <th>Phone</th>
        <th>NID</th>
        <th>Category/Role</th>
        <th>Company</th>
        <th>Created At</th>
        <th>Actions</th>
    </tr>

<?php
$count = 1;

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td>" . $count++ . "</td>
        <td>" . $row['name'] . "</td>
        <td>" . $row['phone'] . "</td>
        <td>" . $row['nid'] . "</td>
        <td>" . $row['worker_category'] . "</td>
        <td>" . $row['company_name'] . "</td>
        <td>" . $row['created_at'] . "</td>

        <td>
            <a href='edit_field_worker.php?worker_id=" . $row['worker_id'] . "'>
                <button style='background:#2196F3; color:white; border:none; padding:5px 10px; border-radius:5px;'>Edit</button>
            </a>

            <a href='delete_field_worker.php?worker_id=" . $row['worker_id'] . "' onclick=\"return confirm('Are you sure?')\">
                <button style='background:#f44336; color:white; border:none; padding:5px 10px; border-radius:5px;'>Delete</button>
            </a>
        </td>
    </tr>";
}
?>

</table>

</div>

<br><br>
<a href="super_admin_dashboard.php"><button>Back to Dashboard</button></a><br><br>
<a href="manage_users.php"><button>Back to Manage Users</button></a><br><br>
<a href="logout.php"><button>Logout</button></a>