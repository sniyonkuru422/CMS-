<?php
include("database/connect.php");

// Check if worker_id is provided
if (isset($_GET['worker_id'])) {

    $worker_id = intval($_GET['worker_id']); // protect against SQL injection

    // Delete query
    $delete = "DELETE FROM workers WHERE worker_id = $worker_id";

    if (mysqli_query($conn, $delete)) {

        // Redirect back to workers list after delete
        header("Location: view_workers.php");
        exit();

    } else {
        echo "Error deleting worker: " . mysqli_error($conn);
    }

} else {
    echo "No worker selected for deletion.";
}
?>