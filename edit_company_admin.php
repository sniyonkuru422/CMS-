<?php
session_start();

include("database/connect.php"); // Include the database connection file

$conn = mysqli_connect($host, $username, $password, $dbname);

if ($_SESSION["role"] !== "SUPER_ADMIN") {
    die("Access denied");
}

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM users WHERE user_id = '$id'");
$user = $result->fetch_assoc();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $company_id = $_POST['company_id'];

    $conn->query("UPDATE user SET email='$email', company_id='$company_id' WHERE user_id='$id'");

    header("Location: view_company_admins.php");
    exit;
}

?>
<h2>Edit Company Admin</h2>
<form method="POST">
    <input type="email" name="email" value="<?=$user['email']?>" required>
    <input type="hidden" name="company_id" value="<?=$user['company_id']?>">
    <button>Save Changes</button>
</form><br><br><br>
<a href="view_company_admins.php" ><button>Back</button></a><br><br>
<a href="logout.php"><button>Logout</button></a>