<?php
//include("database/connect.php");
$host = "localhost";
$username = "root";
$password = "";
$dbname = "construction_management_system";

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());

}
//else{
    //echo "Connected successfully";
//}

// SQL querry to insert a new user into the users_admin_and_staff table

//$sql = "INSERT INTO users_admin_and_staff (user_id,company_id, name, email, password, role, phone, created_at) VALUES ('12333','1', 'John Doe', 'john.doe@example.com', 'hashed_password', 'Admin', '073456789', NOW())";

//if (mysqli_query($conn, $sql)) {
    //echo "New user created successfully";
//} else {
    //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
//}

//mysqli_close($conn);



