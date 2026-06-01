<?php
session_start();
include("database/connect.php"); // Include the database connection file
$conn = mysqli_connect($host, $username, $password, $dbname);

if(!in_array("role", ['PROJECT_MANAGER','COMPANY_ADMIN'])){
    die("Access denied");
}

$id = $_GET['id'];
$company_id = $_SESSION['company_id'];

$conn->query("DELETE FROM materials WHERE id = '$id' AND company_id = '$company_id'");

header("Location: Material-management.php");
exit;
?>