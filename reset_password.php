<?php
include("database/connect.php");

if (isset($_GET['user_id'])) {// Check if user_id is set in the URL parameters
    $user_id = $_GET['user_id']; // Get the user_id from the URL parameters


    // Temporary password
    $new_password = "123456";
 
    // Hash it
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update database
    $update = "UPDATE users SET password='$hashed_password' WHERE user_id=$user_id";

    if (mysqli_query($conn, $update)) {
        echo "<script>
                alert('Password reset successfully! New password is: 123456');
                window.location.href='view_users.php';
              </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

} else {
    echo "No user selected!";
}
?>