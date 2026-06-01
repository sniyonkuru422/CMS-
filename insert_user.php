<?php
include 'database/connect.php';

// SQL querry to insert a new user into the users_admin_and_staff table

//User_id is not inseted manually because it is set to auto-increment in the database
$sql = "INSERT INTO users_admin_and_staff (company_id, name, email, password, role, phone, created_at) VALUES (1, 'John Doe', 'john.doe@example.com', 123456, 'Admin', 073456789, NOW())";

if (mysqli_query($conn, $sql)) {
    echo "New user created successfully";
} 

else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);

?>