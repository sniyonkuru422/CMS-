<?php 
include ("database/connect.php");

if (isset($_GET['user_id'])){ // Check if user_id is set in the URL parameters
    $user_id = intval($_GET['user_id']); // Ensure it's an integer to prevent SQL injection

    $delete = "DELETE FROM users WHERE user_id = $user_id";

    if (mysqli_query($conn, $delete)) {
        header("Location: view_users.php");// Redirect to the view_users page after successful deletion
        exit();
    }
    else {
        echo "Error deleting user: " . mysqli_error($conn);

    }
} 
    else {
        echo "No user selected for deletion.";
    }
   
?>