<?php
include("database/connect.php");


$id = $_GET["id"];

$conn->query("DELETE FROM expenses WHERE expense_d = $id");

header("Location: Expense-management.php");
exit;
?>