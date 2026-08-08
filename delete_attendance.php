<?php

ob_start();

include("auth.php");

checkLogin();

include("database/connect.php");

// Check if attendance_id exists
if (!isset($_GET['id'])) {
    die("Attendance record ID is missing.");
}

$attendance_id = (int)$_GET['id'];


// Delete attendance record
$stmt = $conn->prepare("
    DELETE FROM attendance 
    WHERE attendance_id = ?
");

$stmt->bind_param("i", $attendance_id);


if ($stmt->execute()) {

    header("Location: Worker-Attendance-Monitoring.php?deleted=1");
    exit();

} else {

    die("Delete Error: " . $stmt->error);

}

?>