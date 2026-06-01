<?php
include 'database/connect.php';

$search = $_GET['search'] ?? '';
$company_id = $_GET['company_id'] ?? '';

// Bello there how to check if any filter is active 
// to help be seen at after I view the single company
$filterActive = !empty($user_id) || !empty($search); 


// Base SQL query
$sql = "SELECT users.user_id, users.name, users.email, users.role, companies.company_name 
        FROM users 
        JOIN companies ON users.company_id = companies.company_id
        WHERE 1"; // 1 is a dummy condition to simplify appending further conditions

// Add search condition if search term is provided
if (!empty($search)) {
    $search = mysqli_real_escape_string($conn, $search); // Prevent SQL injection
    $sql .= " AND (users.name LIKE '%$search%' OR users.email LIKE '%$search%')";
}

// Add company filter if company_id is provided
if (!empty($company_id)) {
    $company_id = intval($company_id); // Ensure it's an integer to prevent SQL injection
    $sql .= " AND users.company_id = '$company_id'";
}

// Execute the query
$result = mysqli_query($conn, $sql);

// Error handling here Or Error checking

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!--I Added the style below to make the table look clean -->

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
    background: linear-gradient(135deg, #4CAF50, #2e7d32);
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 15px;
    font-weight: bold;
    letter-spacing: 1px;
}
</style>

<!-- Table -->
<h2 class="page-title">View All Users of CMS System</h2>
<div class="container-dashboard"> <!-- Add a container for better styling -->
<table border="1" cellpadding="10" cellspacing="0">
    <tr>
        <th>No</th>
        <th>User name</th>
        <th>Email</th>
        <th>Company</th>
        <th>Role</th>
        <th>Actions</th>
        <th>Reset Password</th>
    </tr>

 <?php
 $count = 1; // 

    // for printing
    while($row = mysqli_fetch_assoc($result)) {
       echo" <tr>
            <td>" .$count++. "</td>
            <td>" .$row['name']. "</td>
            <td>" .$row['email']. "</td>
            <td>" .$row['company_name']. "</td>
            <td>" .$row['role']. "</td>
            
            <td>

                <a href= 'edit_user.php?user_id=".$row['user_id']."'>
                    <button style='background:#2196F3; color:white; border:none; padding:5px 10px; border-radius:5px;'>Edit</button>
                </a>
                <a href='delete_user.php?user_id=".$row['user_id']."' onclick=\"return confirm('Are you sure')\">
                    <button style='background:#f44336'; color:white; border:none; padding:5px 10px; border-radius:5px;'>Delete</button>
                </a>
            </td>  
            <td>
                <a href= 'reset_password.php?user_id=" .$row['user_id']. "'
                    onclick=\"return confirm('Reset this user password?')\">
                    <button style='background:#ff9800; color:white; border:none; padding:5px 10px; border-radius:5px;'>
                        Reset
                    </button>
                </a>
            </td>
        </tr>";

    }
 ?>
</table>
</div>

<!-- Search form -->
<form method="GET" style="margin: bottom 20px; display:flex; gap:10px;">

<!--Search input button-->
<input type="text" name="search" placeholder="search user..."
    value="<?= $_GET['search'] ?? '' ?>"
    style="padding:8px; width:200px;">

    <!--Company Filter -->
    <select name="company_id" style="padding:8px;">
        <option value="">All companies</option>

        <?php
        $companies = mysqli_query($conn,"SELECT * FROM companies");
        while($c = mysqli_fetch_assoc($companies)):
        ?> 
            <option value="<?=$c['company_id']; ?>"
                <?=(isset($_GET['company_id']) && $_GET['company_id'] == $c['company_id']) ? 'selected' : '' ?>>
                <?= $c['company_name']; ?>
            </option>
        <?php endwhile; ?>
    </select>

    <button type="submit" style="padding:8px 15px; background:#4CAF50; color:white; border:none;">
        Search
    </button>

    <!-- If any filter of search is active, show the "View All users" button after yo see the single company-->
    <?php if ($filterActive): ?>
        <a href="view_users.php"
            style="margin-left:auto;
            padding:8px 15px;
            background:#2196F3;
            color:white;
            border:none;
            text-decoration:none;
            border-radius:5px;
            display:inline-block;">
                View All Users
        </a>
    <?php endif; ?>

</form>
<br><br>
    <a href="super_admin_dashboard.php"><button>Back to Dashboard</button></a><br><br>
    <a href="manage_users.php"><button>Back to Manage Users</button></a><br><br>
    <a href="logout.php"><button>Logout</button></a>
