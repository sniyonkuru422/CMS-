<?php
//session_start(); I commented this out because it's already included in the dashboard_layout.php file, and including it again can cause issues. Since dashboard_layout.php is included at the top of this file, we can rely on it to start the session for us.

include("auth.php");// Include the authentication functions

// Restrict access to PROJECT_MANAGER role only
checkLogin();// Check if the user is logged in
requireAnyRole([
    "COMPANY_ADMIN",
    "PROJECT_MANAGER",
    "SITE_ENGINEER",
    "SUPERVISOR", 
    "STORE_KEEPER"
]);

include ("database/connect.php"); // Include the database connection file
include("dashboard_layout.php"); // connect Layout
// INSERT EQUIPMENT
if (isset($_POST['add_equipment'])){
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $conditon = $_POST['condition_status'];
    $project_id = $_POST['project_id'];
    $status = $_POST['status'];

    $query ="INSERT INTO equipment (name, quantity, condition_status, projet_id, status) 
    vALUES ('$name', '$quantity', '$condition','$project_id', '$status')";

    mysqli_query($conn, $query) or die(mysqli_error($conn));
}
?>

<div class="main-content">
<h2> Equipment Management</h2>
<img src="images/Equipment.png" width="100%" height="180" style="object-fit: cover; border-radius: 10px;">

<form method="POST">
    <input type="text" name="name" placeholder="=Equipment Name" required>
    <input type="number" name="quantity" placeholder="quantity" required>

    <select name="condition_status">
        <option>Good</option>
        <option>Needs Repar</option>
        <option>Damaged</option>
    </select>

<!-- Below there's select for dropdown to make system real and user-friendly-->
    <select name="project_id" required>
        <option value="">Select Project</option>
        <?php
        $projects = mysqli_query($conn,"SELECT * FROM projects");
        while ($p = mysqli_fetch_assoc($projects)) {
            echo "<option value='{$p['id']}'>{$p['name']}</option>";
        }
        ?>
    </select>

    <select name="status">
        <option>Available</option>
        <option>In Use</option>
        <option>Lost</option>
    </select>

    <button name="add_equipment">Add Equiment</button>

</form>

<hr>

<h3>Equipment List</h3>

<table border="1">
    <tr>
        <th>Name</th>
        <th>Quantity</th>
        <th>Condition</th>
        <th>Status</th>
        <th>Action</th>
    </tr>

    <?php
    $result = mysqli_query($conn,"SELECT * FROM equipment");


    while ($row = mysqli_fetch_array($result)){
        echo "<tr>
              <td>{$row['name']}</td>
              <td>{$row['quantity']}</td>
              <td>{$row['condition_status']}</td>
              <td>{$row['status']}</td>
              <td>
                <a href='edit_equipment.php?id={$row['id']}'>Edit Equipment</a> |
                <a href='delete_equipment.php?id={$row['id']}'>Delete Equipment</a>
              </td>
        </tr>";
    }

    ?>
    </table>
    </div>
   