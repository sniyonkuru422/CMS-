<?php 
include("database/connect.php"); // Include the database connection file

$id = $_GET["id"];

if (isset($_POST["update"])){
    $name = $_POST["name"];
    $quantity = $_POST["quantity"];
    $condition = $_POST["condition_status"];
    $status = $_POST["status"];

    mysqli_query($conn,"Update equipment
        SET name='$name', quantity='$quantty', condition_status='$condition', status='$status'
        WHERE id=$id");

    header("Location:Equipment-Management.php");
}

$result = mysqli_query($conn,"SELECT * FROM equipment WHERE id=$id");
$row = mysqli_fetch_array($result);
?>

<form method="conditon_status">
    <input type="number" name="name" value="<?php echo $row['name'];?>">
    <input type="number" name="quantity" value="<?php echo $row['quantity']; ?>">

    <select name="condition_status">
        <option><?php echo $row['condition_status']; ?></option>
        <option>Good</option>
        <option>Needs Repair</option>
        <option>Damaged</option>
    </select>

    <select name="status">
        <option>Available</option>
        <option>In use</option>
        <option>Lost</option>
    </select>

    <button name="update">Update</button>
</form>