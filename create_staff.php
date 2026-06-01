<?php
session_start();
include("database/connect.php"); // Include the database connection file

//Check if logged in, and if not logged in -> redirect to login page
if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}
// Restrict/ give access to COMPANY_ADMIN only
if ($_SESSION['role'] !== "COMPANY_ADMIN") {
    die("Access denied. You do not have permission to access this page.");
}

// Connect to the database
//$conn = mysqli_connect($host, $username, $password, $dbname);
//if (!$conn) {
  //  die("Connection failed: " . mysqli_connect_error());
//}

$company_id = $_SESSION["company_id"]; //  auto-assign staff to this company

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $staff_name = $_POST["staff_name"];
    $staff_email = $_POST["staff_email"];
    $staff_password = password_hash($_POST["staff_password"], PASSWORD_DEFAULT); // Hash the password
    //We’re now using password_hash() to store passwords safely in the database. This is a critical security improvement, as it ensures that even if the database is compromised, the actual passwords remain protected.
    $staff_phone = $_POST["staff_phone"]; // Staff, Manager

    $role = $_POST["role"]; // Set role based on user selection

    // Insert the new staff into the database
    $sql = "INSERT INTO users (company_id, name, email, password, role, phone, created_at) VALUES ('$company_id', '$staff_name', '$staff_email', '$staff_password', '$role', '$staff_phone', NOW())";
    
    if (mysqli_query($conn, $sql)) {
        $Success_message = "Staff created successfully!";
    } else {
        $Error_message = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Staff User </title>
    <link rel="stylesheet" href="CSS/style.css">
</head>

<!-- JavaScript to auto-hide the toast message after 3 seconds -->
 <script>
    function closeToast() {
        document.getElementById("toast").style.display = "none";
    }
    // Auto-hide the toast after 3 seconds
    setTimeout(() => {
        const toast = document.getElementById("toast");
        if (toast) {
            toast.style.display = "none";
        }
    }, 3000);
 </script>


<body style="background: url('images/Staff-Picture.jpg')  center/cover no-repeat fixed;">

<div class="container-dashboard">  <!-- Contains a form for better styling -->
<header>
        <h2>Create Staff User of CMS</h2>
</header>

    <form method="POST" action="" style="display: block; margin: 100px auto; padding: 45px; background: white; box-shadow: 0 0 10px rgba(0,0,0,0.1); border-radius: 70px; max-width: 300px;">
        <label for="staff_name">Staff Name:</label><br>
        <input type="text" id="staff_name" name="staff_name" required><br><br>

        <label for="staff_email">Staff Email:</label><br>
        <input type="email" id="staff_email" name="staff_email" required><br><br>

        <label for="staff_password">Staff Password:</label><br>
        <input type="password" id="staff_password" name="staff_password" required><br><br>

        <label for="staff_phone">Staff Phone:</label><br>
        <input type="text" id="staff_phone" name="staff_phone" required><br><br>

        <label for="role">Staff Role:</label><br>
        <select style="width: 100% !important; padding: 10px !important; box-sizing: border-box !important; height: 42px !important;" id="role" name="role" required>
            <option value="">Select Staff Role</option>
            <option value="SITE_ENGINEER">Site Engineer</option>
            <option value="PROJECT_MANAGER">Project Manager</option>
            <option value="STORE_KEEPER">Store Keeper</option>
            <option value="SUPERVISOR">Supervisor</option>
        </select><br><br>

        <button type="submit">Create Staff</button>

        <!-- Display success or error message -->
        <?php if (isset($Success_message)): ?>
            <div class = "toast" id="toast">
                <?= $Success_message ?>
            </div>
            <?php endif; ?>

       <!-- Button wiith better styling -->
       <a href="manage_users.php"
            style="display:block;
            background:#2980b9;
            color:white;
            padding:10px;
            text-align:center;
            text-decoration:none;
            border-radius:5px;
            margin-top:15px;">
                Go Back To The Manage Users Page
        </a>

    </form>
</div>
</body>
</html>


