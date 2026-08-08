<?php

// Authentication
include("auth.php");

checkLogin();

requireAnyRole([
    "COMPANY_ADMIN",
    "SITE_ENGINEER",
    "SUPERVISOR"
]);

// Database connection
include("database/connect.php");

// Ensure an activity ID was provided
if (isset($_GET['id'])) {

    $activity_id = intval($_GET['id']);

    // Delete only if the activity belongs to the logged-in company
    $company_id = $_SESSION['company_id'];

    $query = "
    DELETE daily_activities
    FROM daily_activities
    INNER JOIN projects
    ON daily_activities.project_id = projects.project_id
    WHERE daily_activities.activity_id = '$activity_id'
    AND projects.company_id = '$company_id'
    ";

    mysqli_query($conn, $query);//call the queery to run

}

// Return to Daily Activity Recording page
header("Location: Daily_Activity_Recording.php");
exit();

?>