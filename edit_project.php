<?php
include 'database/connect.php';

$id = $_GET['project_id'];

$result = $conn->query("SELECT * FROM projects WHERE project_id = '$id'");
$project= $result->fetch_assoc();

if (isset($_POST['update'])) {
    $project_name = $_POST['project_name'];
    $location = $_POST['location'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $budget = $_POST['budget'];
    $status = $_POST['status'];

    // Update the project in the database
    $conn->query("UPDATE projects SET project_name='$project_name', location='$location', start_date='$start_date', end_date='$end_date', budget='$budget', status='$status', progress='$progress' WHERE project_id='$id'");
    
    // Redirect back to the project management page after updating
    header("Location: project-management.php");
    exit;
}
?>

<h2>Edit Project</h2>

<form method="POST">
    <label for="project_name">Project Name:</label><br>
    <input type="text" id="project_name" name="project_name" value="<?= $project['project_name'] ?>" required><br><br>

    <label for="location">Location:</label><br>
    <input type="text" id="location" name="location" value="<?= $project['location'] ?>" required><br><br>

    <label for="start_date">Start Date:</label><br>
    <input type="date" id="start_date" name="start_date" value="<?= $project['start_date'] ?>" required><br><br>

    <label for="end_date">End Date:</label><br>
    <input type="date" id="end_date" name="end_date" value="<?= $project['end_date'] ?>" required><br><br>

    <label for="budget">Budget:</label><br>
    <input type="number" id="budget" name="budget" value="<?= $project['budget'] ?>" required><br><br>

    <label for="status">Status:</label><br>
    <input type="text" id="status" name="status" value="<?= $project['status'] ?>" required><br><br>

    <label for="progress">Progress:</label><br>
    <input type="number" id="progress" name="progress" value="<?= $project['progress'] ?>" placeholder="Progress %" min="0" max="100" required><br><br>


    <button type="submit" name="update">Update Project</button>
</form>