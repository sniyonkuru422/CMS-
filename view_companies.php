<?php
//Thispage allows SUPER_ADMIN to see all companies 
// and be able to  edit, and delete them. 
// Only SUPER_ADMIN can access this page. 
// Company Admins and other roles should not have access to this page.

session_start();

// Include the database connection file
include("database/connect.php");

// check if user is SUPER_ADMIN
if ($_SESSION["role"] !== "SUPER_ADMIN") {
    die("Access denied");
}

// Reset filters and view all companies
if (isset($_GET['reset'])) {
    header("Location: view_companies.php");
    exit();
}

// Get search filter 
$company_id = $_GET['company_id'] ?? '';
$search = $_GET['search'] ?? '';

// Base SQL query
$sql = "SELECT * FROM companies WHERE 1"; 

// Add search condition if search term is provided
if (!empty($search)) {
    $search = mysqli_real_escape_string($conn, $search); // Prevent SQL injection
    $sql .= " AND (companies.company_name LIKE '%$search%' OR companies.company_description LIKE '%$search%')";
}

// Add company filter if company_id is provided
if (!empty($company_id)) {
    $company_id = intval($company_id); // Ensure it's an integer to prevent SQL injection
    $sql .= " AND companies.company_id = '$company_id'";
}

// Fetch companies for the dropdown
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query Error: " . mysqli_error($conn));
}

// Check if any filter is active 
// to help be seen at after I view the single company
$filterActive = !empty($company_id) || !empty($search); 

?>

<!DOCTYPE html>
<html>
<head>
    <title>View Companies</title>
    <style>
        table{
            width: 100%;
            margin: auto;
            border-collapse: collapse;
        }
        th, td{
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }
        th{
            background-color: #007BFF;
            color: white;
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
</head>
<body>
    <h2 class="page-title" style="text-align: center;">List of Companies</h2>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>Company ID</th>
            <th>Company Name</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $row['company_id'] ?></td>
            <td><?= $row['company_name'] ?></td>
            <td><?= $row['created_at'] ?></td>
            <td>
                <a href="edit_company.php?id=<?= $row['company_id'] ?>">Edit</a> |
                <a href="delete_company.php?id=<?= $row['company_id']?>" onclick="return confirm('This will delete the company and all its data. Do you want tocontinue?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?> 
    </table> 


    <!-- Search form for searching the company in meny companies -->
<form method="GET" style="margin: bottom 20px; display:flex; gap:10px;">

<!--Search input button-->
<input type="text" name="search" placeholder="search company..."
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

    <!-- If any filter of search is active, show the "View All Companies" button after yo see the single company-->
    <?php if ($filterActive): ?>
        <a href="view_companies.php?reset=1" style="margin-left: auto;">
            <button style="padding:8px 15px; background:#2196F3; color:white; border:none;">
                View All Companies
            </button>
        </a>
    <?php endif; ?>

</form>
    <br><br>
    <a href="super_admin_dashboard.php" ><button>Back To The Dashboard</button></a><br><br><br>
    <a href="logout.php"><button>Logout</button></a>
</body>
</html>