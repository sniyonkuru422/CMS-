<?php
session_start();
include("database/connect.php"); // Include the database connection file


if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "SUPER_ADMIN") {
    header("Location: login.html");
    exit();
}
$conny = mysqli_connect($host, $username, $password, $dbname);
if (!$conny) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch all companies for the dropdown
$company_sql = "SELECT company_id, company_name FROM companies";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $company_id = $_POST["company_id"];
    $admin_name = $_POST["admin_name"];
    $admin_email = $_POST["admin_email"];
    $admin_password = password_hash($_POST["admin_password"], PASSWORD_DEFAULT); // Hash the password
    $admin_phone = $_POST["admin_phone"];

    $role = "COMPANY_ADMIN"; 

    // Insert the new admin into the database
    $sql = "INSERT INTO users (company_id, name, email, password, role, phone, created_at) VALUES ('$company_id', '$admin_name', '$admin_email', '$admin_password', 'COMPANY_ADMIN', '$admin_phone', NOW())";
    
    if (mysqli_query($conny, $sql)) {
        echo "Company admin created successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conny);
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Create Company Admin</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>

<header>
        <h2>Create Company Admin</h2>
</header>

<!-- MESSAGE DISPLAY WITH A DISAPEARING MESSAGE BOX -->
    <?php if (!empty($Success)): ?>
        <div class="toast" id="toastMessage">
            <span><?= $Success ?></span>
            <button onclick="closeToast()">x</button>
        </div>
    <?php endif; ?>
    <div class="container">
        <!-- your form-->
   

    <form method="POST" action="">
        <label for="company_id">Select Company:</label><br>
        <select id="company_id" name="company_id" required>
            <option value="">--Select Company--</option>
            <?php
            $result = mysqli_query($conny, $company_sql);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['company_id'] . "'>" . $row['company_name'] . "</option>";
            }
            ?>
        </select><br><br>

        <label for="admin_name">Admin Name:</label><br>
        <input type="text" id="admin_name" name="admin_name" required><br><br>

        <label for="admin_email">Admin Email:</label><br>
        <input type="email" id="admin_email" name="admin_email" required><br><br>

        <label for="admin_password">Admin Password:</label><br>
        <input type="password" id="admin_password" name="admin_password" required><br><br>

        <label for="admin_phone">Admin Phone:</label><br>
        <input type="text" id="admin_phone" name="admin_phone" required><br><br>

        <button type="submit">Create Admin</button>
    </form>
    </div>

<!--Below there is JavaScript for close button message box-->
<script>
    function closeToast(){
        document.getElementById("toastMessage").style.display ="none";
    }

    setTimeout(function(){
        let toast = document.getElementById("toastMessage");
        if (toast) {
            toast.style.display = "none";
        }
    }, 3000);
</script>

<br><br>
    <a href="super_admin_dashboard.php"><button>Back to Dashboard</button></a><br><br>
    <a href="view_companies.php" ><button>Go to vew companes</button></a><br><br>
    <a href="logout.php"><button>Logout</button></a>

</body>
</html>


