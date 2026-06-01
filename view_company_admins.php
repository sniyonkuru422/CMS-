<?php
// this page/file is for the SUPER_ADMIN to view all the admins in the system,
//  and be able to edit and delete them.
session_start();


// Include the database connection file
include("database/connect.php");

//this also for database connection
//$conn = mysqli_connect($host, $username, $password, $dbname);

// Restrict access or check if user is SUPER_ADMIN
if ($_SESSION["role"] !== "SUPER_ADMIN") {
    die("Access denied");
}

// Fetch company admins for the dropdown
$result = $conn->query("SELECT users.user_id, users.name, users.email, users.role, users.phone, users.company_id, companies.company_name
 FROM users 
 JOIN companies ON users.company_id = companies.company_id
 WHERE users.role = 'COMPANY_ADMIN'
");

// Below there is error check that will 
// check the errors that can't make fetch company admins happen
if (!$result) {
    die("Query Error: " . $conn->error);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>View Company Admins</title>
    <style>
        table{
            width: 80%;
            margin: auto;
            border-collapse: collapse;
        }
        th, td{
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }
        th{
            background-color: green;
            color: white;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Company Admins List</h2>
    <table>
        <tr>
            <th>User ID</th>
            <th>Name</th>
            <th>Company Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Phone</th>
            <th>Edit</th>
            <th>Delete</th>
            <th>Reset Password</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['user_id'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['company_name'] ?></td>
            <td><?= $row['email'] ?></td>
            <td><?= $row['role'] ?></td>
            <td><?=$row['phone']?></td>
            <td><a href="edit_company_admin.php?id=<?= $row['user_id'] ?>">Edit</a></td>
            <td><a href="delete_company_admin.php?id=<?= $row['user_id']?>" onclick="return confirm('Do you want to delete this Admin?')">Delete</a></td>
            <td><a href="reset_password.php?id=<?= $row['user_id'] ?>" onclick="return confirm('Reset this user password')">
                <button style="background:#ff9800; color:white; border:none; padding:5px 10px; border-radius:5px;">
                    Reset
                </button>
            </a>
        </td>
        </tr>
        <?php endwhile; ?>
    </table><br><br><br>
<a href="create_company_admin.php" ><button>Back to Create Company Admin</button></a><br><br>
<a href="super_admin_dashboard.php" ><button>Back To The Dashboard</button></a><br><br><br>
<a href="logout.php"><button>Logout</button></a>
</body>
</html>