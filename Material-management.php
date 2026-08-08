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

// the code below is there to allow the pop up message
$message = "";
$message_type = "";

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'];

    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}

if (!isset($_SESSION["user_id"])){
    header("Location: login.html");
    exit();
}

//$conn = mysqli_connect($host, $username, $password, $dbname); // This is also the connection code in the connect.php file, so we don't need to repeat it here.

$company_id = $_SESSION["company_id"];
$role = $_SESSION['role'];

// Handle add Material (ONLY ALLOWED ROLES)/ Inser material
if ($_SERVER["REQUEST_METHOD"] == "POST" && in_array($role, ['COMPANY_ADMIN','PROJECT_MANAGER', 'SITE_ENGINEER', 'STORE_KEEPER'])) {
    
    $material_name = $_POST["material_name"];
    $quantity = $_POST["quantity"];
    $unit_price = $_POST["unit_price"];
    $supplier = $_POST["supplier"];
    
    $sql = "INSERT INTO materials (company_id, material_name, quantity, unit_price, supplier, date_added) VALUES ('$company_id' ,'$material_name', '$quantity', '$unit_price', '$supplier', NOW())";
    

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

<!-- Display role for debugging -->

<td><?=$role?></td>

<!DOCTYPE html>
<html>
    <head>
        <title >Material Management</title>
        <h4> Here you will create new materials and be able to see the created materials in the table.</h4>
        
        <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
body {
    font-family: Arial;
}

.card {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px #ccc;
    margin: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
}

/* Image animation */
.moving-image {
    display: block;
    margin: 20px auto;
    animation: slideImage 3s ease-in-out infinite alternate;
}

@keyframes slideImage {
    from {
        transform: translateX(-350px);
    }

    to {
        transform: translateX(350px);
    }
}
</style>

    </head>


    <body>
        <!-- This PHP IS FOR ALLOWING THE POP UP MESSAGE -->
        <?php if (!empty($message)) : ?>

        <div class="alert <?= $message_type ?>">
            <?= htmlspecialchars($message) ?>
        </div>

        <?php endif; ?>

        <div class="card">
        <h2 style="text-align: center;">Material Management</h2><br>
        <?php if (in_array($role, ['COMPANY_ADMIN','PROJECT_MANAGER'])):?>

       <div style="background:linear-gradient(135deg, #1aafb9,#81C784); padding:25px; border-radius:12px; box-shadow:0 5px 15px rgba(0,0,0,0.2);
        margin-bottom:20px;">   
       <form method="POST" action="">
           <label for="material_name">Material Name:</label>
           <input type="text" id="material_name" name="material_name" required><br><br>

           <label for="quantity">Quantity:</label>
           <input type="number" id="quantity" name="quantity" required><br><br>

           <label for="unit_price">Unit Price:</label>
           <input type="number" id="unit_price" name="unit_price" step="0.01" required><br><br>

           <label for="supplier">Supplier:</label>
           <input type="text" id="supplier" name="supplier" required><br><br>

           <button type="submit" class="Add Material" style='width:70%; background:#007bff; color:white; padding:8px 10px; border-radius:10px; text-decoration:none; margin-right:5px;'>
                Add material
            </button>
       </form>
       </div>
       <?php endif; ?>
       </div>

       <div class = "card">
        
         <h3 style="text-align: center;">List of Materials</h3><br>
         <table border="1">
             <tr>
                 <th>Name</th>
                 <th>Quantity</th>
                 <th>Unit Price</th>
                 <th>Supplier</th>
                 <th>Total Cost</th>
                 <th>Action</th>
             </tr>
             <?php
             if ($result->num_rows > 0) {
                 while($row = $result->fetch_assoc()) {

                    $total_cost = $row["quantity"] * $row["unit_price"];
                
                    echo "<tr>";
                    echo "<td>" . $row["material_name"] . "</td>";
                    echo "<td>" . $row["quantity"] . "</td>";
                    echo "<td>" . number_format($row["unit_price"]) . " RWF</td>";
                    echo "<td>" . $row["supplier"] . "</td>";
                    echo "<td>" . number_format($total_cost) . " RWF</td>";
                    
                    echo "<td>";

                    if(in_array($role, ['PROJECT_MANAGER', 'COMPANY_ADMIN'])) {

                        echo "<a href='edit_material.php?id=".$row['material_id']."' 
                        style='background:#007bff; color:white; padding:6px 12px; border-radius:7px; text-decoration:none; margin-right:5px;'>
                        Edit</a>";

                        echo "<a href='delete_material.php?id=".$row['material_id']."' 
                        onclick=\"return confirm('Are you sure you want to delete this material?')\"
                        style='background:#dc3545; color:white; padding:6px 12px; border-radius:7px; text-decoration:none;'>
                        Delete</a>";

                    }

                    echo "</td>";

                    echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No materials found.</td></tr>";
                }

    ?>
            
         </table>
         </div>
    <i class ="fas fa-box"></i>
    <img src="images/materials.png" class="moving-image" style="width:100%; max-width:600px;"><br>

    <div style="text-align:center; margin-top:15px;">
        <a href="company_admin_dashboard.php" class="back-btn" style="
            display:inline-block; width:20%; background: #05316b; color:white; padding:10px; border-radius:7px; text-decoration:none; font-size:16px;
            font-weight:bold; text-align:center; margin-top:15px; box-sizing:border-box;
        ">
        Back
        </a> 
    </div>   

         <!--This script is for allowing the pop up message to disappear after 3 seconds(Auto-hide after a few seconds) -->
<script>
        setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.display = "none";
        });
    }, 3000);
</script>
</body>
</html>
