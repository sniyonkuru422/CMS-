<?php

// Turn on errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("database/connect.php");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create new hashed password
$new = password_hash("123456", PASSWORD_DEFAULT);

// Update company admin password
$sql = "UPDATE users SET password='$new' WHERE role='COMPANY_ADMIN'";

if (mysqli_query($conn, $sql)) {
    echo "✅ Password reset successfully!";
} else {
    echo "❌ Error: " . mysqli_error($conn);
}
?>
