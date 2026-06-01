<?php

include("auth.php");// Include the authentication functions

// Restrict access to PROJECT_MANAGER role only
checkLogin();// Check if the user is logged in
requireAnyRole([
    "COMPANY_ADMIN",
    "PROJECT_MANAGER",
    "SITE_ENGINEER",
    "STORE_KEEPER",
]);

include("database/connect.php"); // Include the database connection file
include("dashboard_layout.php"); // Include the common dashboard layout

if (!isset($_SESSION["user_id"])){
    header("Location: login.html");
    exit();
}

//$conn = mysqli_connect($host, $username, $password, $dbname); // This is also the connection code in the connect.php file, so we don't need to repeat it here.

$company_id = $_SESSION["company_id"];
$role = $_SESSION['role'];

// Handle add Material (ONLY ALLOWED ROLES)/ Inser material
if ($_SERVER["REQUEST_METHOD"] == "POST" && in_array($role, ['COMPANY_ADMIN','PROJECT_MANAGER', 'SITE_ENGINEER', 'STORE_KEEPER'])) {
    
    $name = $_POST["name"];
    $quantity = $_POST["quantity"];
    $unit_price = $_POST["unit_price"];
    $supplier = $_POST["supplier"];
    $total_cost = &$quantity * $unit_price; // Calculate total cost

    $sql = "INSERT INTO materials ( name, quantity, unit_price, supplier, total_cost) VALUES ('$name', '$quantity', '$unit_price', '$supplier', '$total_cost')";
    

    if (mysqli_query($conn, $sql)) {
        echo "Material added successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    
}

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

//Fetch materials for this company only
    $result = $conn->query("SELECT * FROM materials WHERE company_id = '$company_id'");
?>

<!-- Add Edit/ Delete buttons -->

<td>
<?php if(in_array($role, ['PROJECT_MANAGER', 'COMPANY_ADMIN'])):?>
    <a href="edit-material.php?id=<?= $row['id'] ?>">Edit</a>|
    <a href="delete_material.php?id=<?= $row['id']?>" onclick="return confirm('Delete?')">Delete</a></a>
<?php endif; ?>
</td>

<!-- Display role for debugging -->

<td><?=$role?></td>

<!DOCTYPE html>
<html>
    <head>
        <title>Material Management</title>
        <p> Here you will manage all your materials.</p>
        
        <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        body {font-family: Arial;}
        .card {background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px #ccc; margin: 20px;}
        table {width: 100%; border-collapse: collapse;}
        th, td {border: 1px solid #ddd; padding: 10px; text-align: left;}
    </style>
    </head>


    <body>
        <mg src="images/Materals1.png" width="100%" height="180" style="object-fit:cover; border-radus:10px;">

        <div class="card">
       <h2>Material Management</h2>
         <?php if (in_array($role, ['COMPANY_ADMIN','PROJECT_MANAGER'])):?>
          
       <form method="POST" action="">
           <label for="material_name">Material Name:</label>
           <input type="text" id="name" name="name" required><br><br>

           <label for="quantity">Quantity:</label>
           <input type="number" id="quantity" name="quantity" required><br><br>

           <label for="unit_price">Unit Price:</label>
           <input type="number" id="unit_price" name="unit_price" step="0.01" required><br><br>

           <label for="supplier">Supplier:</label>
           <input type="text" id="supplier" name="supplier" required><br><br>

           <input type="submit" value="Add Material">
       </form>
       <?php endif; ?>
       </div>

       <div class = "card">
        
         <h3>Materials List</h3>
         <table border="1">
             <tr>
                 <th>Name</th>
                 <th>Quantity</th>
                 <th>Unit Price</th>
                 <th>Supplier</th>
                 <th>Total Cost</th>
             </tr>
             <?php
             if ($result->num_rows > 0) {
                 while($row = $result->fetch_assoc()) {
                
                     echo "<tr>";
                     echo "<td>" . $row["name"] . "</td>";
                     echo "<td>" . $row["quantity"] . "</td>";
                     echo "<td>$" . $row["unit_price"] . "</td>";
                     echo "<td>" . $row["supplier"] . "</td>";
                     echo "<td>$" . $row["total_cost"] . "</td>";
                     echo "</tr>";
                 }
             } else {
                 echo "<tr><td colspan='5'>No materials found.</td></tr>";
         }

    ?>
            
         </table>
         </div>
    <i class ="fas fa-box"></i>
    <img src="images/materials.png" alt="Materials Image" style="width:100%; max-width:600px; margin-top:20px;">

    <a href="company_admin_dashboard.php">Back</a>    
    </body>
</html>
