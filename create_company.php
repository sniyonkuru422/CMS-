<?php
session_start();
include("database/connect.php"); // Include the database connection file


// Restrict access to only SUPER_ADMIN

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "SUPER_ADMIN") { // Check if user is logged in and is SUPER_ADMIN
    header("Location: login.html");
    exit();
}


//$conn = mysqli_connect($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$Success = ""; // Variable to hold success message after company creation

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $company_name = $_POST["company_name"];
    $company_email = $_POST["company_email"];
    $company_phone = $_POST["company_phone"];
    $company_address = $_POST["company_address"];


    $sql = "INSERT INTO companies (company_name, company_email, company_address, company_phone) VALUES ('$company_name', '$company_email', '$company_address', '$company_phone')";
    
    if (mysqli_query($conn, $sql)) {
        $Success = "Company created successfully!";
   } else {
       echo "Error: " . $sql . "<br>" . mysqli_error($conn);
   }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Company</title>
    <link rel="stylesheet" href="CSS/style.css">

<!-- This styling helps in making the form look better and more user friendly-->
<style>
    
    body {
        font-family: Arial;
        background: #f4f4f4;
        background-image: url('images/capture2.jpg');
        background-size: cover;
    }
    .container {
        width: 400px;
        margin: 80px auto;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px #aaa;
    }
    h2 {
        text-align: center;
    }
    .message {
        text-align: center;
        margin-bottom: 10px;
        color: green;
    }
    label {
        font-weight: bold;
        width: 100%;
        padding: 10px;
        margin: 5px 0 15px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    input {
        width: 100%;
        padding: 10px;
        margin: 5px 0 15px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    button {
        width: 100%;
        padding: 10px;
        background: #007BFF;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    .back {
        display: block;
        text-align: center;
        margin-top: 20px;
        color: #007BFF;
        text-decoration: underline;
    }

</style>
</head>

<body>
    <div class="container">

    <h1>Create a New Company</h1>

    <!-- MESSAGE DISPLAY WITH A DISAPEARING MESSAGE BOX -->
    <?php if (!empty($Success)): ?>
        <div class="toast" id="toastMessage">
            <span><?= $Success ?></span>
            <button onclick="closeToast()">x</button>
        </div>
    <?php endif; ?>
    

    <!-- COMPANY CREATION FORM -->

    <form method="POST" action="">
        <label for="company_name">Company Name:</label><br>
        <input type="text" id="company_name" name="company_name" required><br><br>

        <label for="company_email">Company Email:</label><br>
        <input type="email" id="company_email" name="company_email" required><br><br>

        <label for="company_phone">Company Phone:</label><br>
        <input type="text" id="company_phone" name="company_phone" required><br><br>

        <label for="company_address">Company Address:</label><br>
        <input type="text" id="company_address" name="company_address" required><br><br>

        <button type="submit">Create Company</button>
    </form><br>
    
    
    <!-- BACK TO DASHBOARD LINK -->
    <a class="back" href="super_admin_dashboard.php">Back to Dashboard</a>
    <script src="js/script.js"></script>

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
</body>
</html>