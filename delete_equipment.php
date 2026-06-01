<?php
include("database/connect.php"); // Include the database connection file

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM equipment WHERE id=$id");

header("Location: Equpment-Management.php");
?>