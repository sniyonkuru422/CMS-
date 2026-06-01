<?php
session_start();
include("database/connect.php"); // Include the database connection file


$conn = mysqli_connect($host, $username, $password, $dbname);

if ($_SESSION["role"] !== "SUPER_ADMIN") {
    die("Access denied");
}

if (isset(($_GET['id']))) {
    $id = $_GET['id'];

    $result = $conn->query("SELECT * FROM companies WHERE company_id = $id");

    if ($result->num_rows > 0) {
        $company = $result->fetch_assoc();
    } else {
        echo "Company not found";
        exit();
    }
} else {
    echo "No ID provided";
    exit();
}

// Fetch the company details to pre-fill the form
$result = $conn->query("SELECT * FROM companies WHERE company_id = '$id'");
$companies = $result->fetch_assoc();

//Update company details if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $company_name = $_POST['company_name'];
    
    $conn->query("UPDATE companies SET company_name='$company_name' WHERE company_id='$id'");
    
    header("Location: view_companies.php");
    exit;
}
?>

<h2>Edit Company</h2>
<form method="POST">
    <input type="text" name="company_name" value="<?= $company['company_name'] ?>" required>
    <button type="submit">Update Company</button>
</form> <br><br>
<a href="view_companies.php" ><button>Back</button></a><br><br>
<a href="logout.php"><button>Logout</button></a>