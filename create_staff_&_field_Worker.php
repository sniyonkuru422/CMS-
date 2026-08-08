<?php
session_start();
include("database/connect.php"); // Include the database connection file

// Check for success message in session and store it in a variable, then unset it
$Success_message = "";

if (isset($_SESSION['Success'])) {
    $Success_message = $_SESSION['Success'];
    unset($_SESSION['Success']);
}

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

// Handle staff user creation
if(isset($_POST['save_staff'])) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hash the password
    //We’re now using password_hash() to store passwords safely in the database. This is a critical security improvement, as it ensures that even if the database is compromised, the actual passwords remain protected.
    $phone = $_POST["phone"]; // Staff, Manager

    $role = $_POST["role"]; // Set role based on user selection


    // Insert the new staff into the database
    $sql = "INSERT INTO users (company_id, name, email, password, role, phone, created_at) VALUES ('$company_id', '$name', '$email', '$password', '$role', '$phone', NOW())";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['Success'] = "Staff created successfully!";
        header("Location: create_staff_&_field_worker.php");
        exit();
    } else {
        $_SESSION['Error'] = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Handle field worker creation
if(isset($_POST['save_worker'])) {
    $name = $_POST["name"];
    $phone = $_POST["phone"];
    $nid = $_POST["nid"];
    $Worker_Category = $_POST["worker_category"];

    // Insert the new field worker into the database
    $sql = "INSERT INTO workers (company_id, name, phone, nid, Worker_Category, created_at) VALUES ('$company_id', '$name', '$phone', '$nid', '$Worker_Category', NOW())";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['Success'] = "Field Worker created successfully!";
        header("Location: create_staff_&_field_worker.php");
        exit();
    } else {
        $_SESSION['Error_message'] = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Staff User Or Field Worker </title>
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

 <!-- JavaScript to control the visibility of Staff form and Worker fields -->
    <script>
    function toggleFields() {

        var type = document.getElementById("user_type").value;

        document.getElementById("staffForm").style.display = "none";
        document.getElementById("workerForm").style.display = "none";

        if(type === "STAFF") {
            document.getElementById("staffForm").style.display = "block";
        }

        if(type === "WORKER") {
            document.getElementById("workerForm").style.display = "block";
        }
    }
    </script>

<body style="background: url('images/Staff-Picture.jpg')  center/cover no-repeat fixed;">

<div class="page-container">  <!-- Contains a form for better styling -->
    <div style="width:370px; margin: 20px auto; background: #333; color: white; padding: 14px; text-align: center;border-radius: 20px;">
        <h2>Create Staff User or Field Worker</h2>
    </div>

    <form method="POST">
        <div>
            <form method="POST">
                <!-- User Type Selection Dropdown -->
                <label for="user_type">Select User Type:</label><br>
                <select name="user_type" id="user_type" onchange="toggleFields()" required>
                    <option value="">-- Select Type --</option>
                    <option value="STAFF">Staff User</option>
                    <option value="WORKER">Field Worker</option>
                </select><br><br>
            </form>
        </div>
                <!-- Worker Form -->
                <div id="workerForm" style="display:none;">
                    <form method="POST">
                        <h2>Create Field Worker</h2><br>
                        
                            <input type="hidden" name="user_type" value="WORKER">
                            
                            <label>Full Name</label>
                            <input style="width: 355px !important; padding: 10px !important; box-sizing: border-box !important; height: 37px !important;type="text" name="name" required>

                            <label>Phone Number</label>
                            <input style="width: 355px !important; padding: 10px !important; box-sizing: border-box !important; height: 37px !important; type="text" name="phone" required>

                            <label>NID</label>
                            <input style="width: 355px !important; padding: 10px !important; box-sizing: border-box !important; height: 37px !important; type="text" name="nid" required>
                        
                        <!--Worker Fields Selection-->
                        
                            <label>Worker Category</label>
                            <select style="width: 355px !important; padding: 10px !important; box-sizing: border-box !important; height: 37px !important;" name="worker_category">
                                <option value="">-- Select Category --</option>
                                <option>Mason</option>
                                <option>Carpenter</option>
                                <option>Electrician</option>
                                <option>Plumber</option>
                                <option>Laborer</option>
                            </select><br>
                    
                            <button type="submit" name="save_worker">
                                Save Person
                            </button><br><br> 
                    </form>
                </div>
                

                <!-- Form fields for staff details -->
                    <div id="staffForm" style="display:none;">
                        <form method="POST">
                            <h2>Create Staff User</h2><br>
                        
                            <input type="hidden" name="user_type" value="STAFF">
                            <label for="name">Name:</label>
                            <input style="width: 355px !important; padding: 10px !important; box-sizing: border-box !important; height: 37px !important;type="text" id="name" name="name" required><br><br>

                            <label for="email">Email:</label>
                            <input style="width: 355px !important; padding: 10px !important; box-sizing: border-box !important; height: 37px !important;type="email" id="email" name="email" required><br><br>

                            <label for="password">Password:</label>
                            <input style="width: 355px !important; padding: 10px !important; box-sizing: border-box !important; height: 37px !important;" type="password" id="password" name="password" required><br><br>

                            <label for="phone">Phone:</label>
                            <input style="width: 355px !important; padding: 10px !important; box-sizing: border-box !important; height: 37px !important;" type="text" id="phone" name="phone" required><br><br>

                            <label for="role">Staff Role:</label>
                            <select style="width: 355px !important; padding: 10px !important; box-sizing: border-box !important; height: 37px !important;" id="role" name="role" required>
                                <option value="">Select Staff Role</option>
                                <option value="SITE_ENGINEER">Site Engineer</option>
                                <option value="PROJECT_MANAGER">Project Manager</option>
                                <option value="STORE_KEEPER">Store Keeper</option>
                                <option value="SUPERVISOR">Supervisor</option>
                            </select><br><br>

                            <button type="submit" name="save_staff">Create Staff User</button>
                        </form>
                    </div>   
                    <!-- Display success or error message -->
                    <?php if (!empty($Success_message)): ?>
                        <div class = "toast" id="toast">
                            <?= $Success_message ?>
                        </div>
                        <?php endif; ?>

                <!-- Button wiith better styling -->
                <a href="manage_users.php"
                        style="display:block;
                        width:400px;
                        box-sizing:border-box;
                        background:#2980b9;
                        color:white;
                        padding:16px;
                        text-align:center;
                        text-decoration:none;
                        border-radius:15px;
                        margin-top:15px;
                        margin-left:auto;
                        margin-right:auto;">
                            Go Back To The Manage Users Page
                </a>

    
</div>
</form>
</body>
</html>


