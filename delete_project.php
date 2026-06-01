<?php
session_start();
include("database/connect.php"); // Include the database connection file

$id = $_GET["project_id"];// Get the project ID from the URL

// Check if the user has permission to delete projects
if(!in_array($_SESSION["role"], ['SITE_ENGINEER', 'COMPANY_ADMIN'])){
    die("Access denied");
}

// Delete the project from the database
$conn->query("DELETE FROM projects WHERE project_id = '$id'");// Delete the project from the database

// Redirect back to the project management page after deletion
header("Location: project-management.php");
exit;
?>