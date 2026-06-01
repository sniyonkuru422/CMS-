<?php
session_start();
include("database/connect.php"); // Include the database connection file
$conn = mysqli_connect($host, $username, $password, $dbname);

if ($_SESSION["role"] !== "SUPER_ADMIN") {
    die("Access denied");
}

// Get the company ID from the URL
$id = $_GET['id'];

// The below line code wll help to delete the company
$conn->query("DELETE FROM companies WHERE company_id = '$id'");

//Redirect back to the companies list
header("Location: view_companies.php");
exit;
?>