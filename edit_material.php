<?php
session_start();
include("database/connect.php"); // Include the database connection file

$conn = mysqli_connect($host, $username, $password, $dbname);

if(!in_array("role", ['PROJECT_MANAGER','COMPANY_ADMIN'])){
    die("Access denied");
}

$id = $_GET['id'];
$company_id = $_SESSION['company_id'];

$result = $conn->query("SELECT * FROM materials WHERE id = '$id' AND company_id = '$company_id'");
$row = $result->fetch_assoc();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $material_name = $_POST["material_name"];
    $quantity = $_POST["quantity"];
    $unit_price = $_POST["unit_price"];
    $supplier = $_POST["supplier"];
    $total_cost = &$quantity * $unit_price; // Calculate total cost

    $conn->query("UPDATE materials SET material_name='$material_name', quantity='$quantity', unit_price='$unit_price', supplier='$supplier', total_cost='$total_cost' WHERE id='$id' AND company_id='$company_id'");
    header("Location: Material-management.php");
    exit;
    
}
?>

<form method="POST">
<input type="text" name="material_name" value="<?= $row['material_name']?>" required>
<input type="number" name="quantity" value="<?= $row['quantity']?>" required>
<input type="number" name="unit_price" value="<?= $row['unit_price']?>" required>
<input type="text" name="supplier" value="<?= $row['supplier']?>" required>
<input type="submit" value="Update Material">
</form>