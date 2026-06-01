<?php
session_start();
include("database/connect.php"); // Include the database connection file
$conn = mysqli_connect($host, $username, $password, $dbname);

if ($_SESSION["role"] !== "SUPER_ADMIN") {
    die("Access denied");

}
$id = $_GET['id'];

//Delete the company admin
$conn->query("DELETE FROM user WHERE user_id = '$id' AND role = 'COMPANY_ADMIN'");

header("Location: view_company_admins.php");
exit;

?>