<?php
//session_start();
include("auth.php");// Include the authentication functions

// Restrict access to PROJECT_MANAGER role only
checkLogin();// Check if the user is logged in
requireAnyRole([
    "COMPANY_ADMIN",
    "PROJECT_MANAGER",
    "SITE_ENGINEER"
]);


include("database/connect.php"); // Include the database connection file
include("dashboard_layout.php"); // Include the common dashboard layout

// Initialize message variable for feedback
$message ="";
// Check if there's a message in the session and set it to display, then clear it to prevent repetition
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // IMPORTANT: prevents repetition
}

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION["user_id"])){
    header("Location: login.html");
    exit();
} 

$company_id = $_SESSION["company_id"];

// Allow only COMPANY_ADMIN to access this page, if not redirect to login page
$allowed_roles = ['COMPANY_ADMIN', 'PROJECT_MANAGER', 'SITE_ENGINEER'];

if (!in_array($_SESSION['role'], $allowed_roles)) {
    header("Location: login.html");
    exit();
}

$message = ""; // Initialize message variable
// Add project (ONLY ALLOWED ROLES)/ Insert project and it will be inserted into the database
if (isset($_POST["add_project"])) {
    $project_name = $_POST["project_name"];
    $location = $_POST["location"];
    $start_date = $_POST["start_date"];
    $end_date = $_POST["end_date"];
    $budget=$_POST["budget"];
    $progress=$_POST["progress"];
    $company_id = $_SESSION["company_id"];
    $status=$_POST["status"];


    // Below there's a query to insert project into database
    $sql = "INSERT INTO projects ( project_name, location, start_date, end_date, budget, progress, company_id, status) VALUES ('$project_name', '$location', '$start_date', '$end_date', '$budget', '$progress', '$company_id', '$status')";

    // Execute the query and set success or error message
   
    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "Project added successfully.";
        header("Location: project-management.php");
        exit();
    } else {
        $_SESSION['message'] = "Error in adding project.";
        header("Location: project-management.php");
        exit();
    }

}

// Fetch projects for this company only
$result = $conn->query("SELECT * FROM projects WHERE company_id = '$company_id'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Projects Management</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<body>
    <div class="page-header">
        <h1>Projects Manager Dashboard</h1>
        <h3> Here you will manage all your projects.</h3>
    </div>

    <!-- Display success or error message if set-->
    <?php if(!empty($message)) : ?> 
        <div class="alert success">
            <?= $message ?>
        </div>
    <?php endif ?>

    <!-- Add project form -->

    <form method ="POST" style="background: white; padding: 15px; border-radius:8px;">
        <label for="project_name">Project Name:</label><br>
        <input type="text" id="project_name" name="project_name" required><br><br>

        <label for="location">Location:</label><br>
        <input type="text" id="location" name="location" required><br><br>

        <label for="start_date">Start Date:</label><br>
        <input type="date" id="start_date" name="start_date" required><br><br>

        <label for="end_date">End Date:</label><br>
        <input type="date" id="end_date" name="end_date" required><br><br>

        <label for="budget">Budget:</label><br>
        <input type="number" id="budget" name="budget" required><br><br>

        <label for="status">Status:</label><br>
        <input type="text" id="status" name="status" value="Active" readonly><br><br>

        <label for="progress">Progress:</label><br>
        <input type="number" id="progress" name="progress" placeholder="Progress %" min="0" max="100" value="0"><br><br>

        <button type="submit" name ="add_project">Add Project</button>
    </form>
    <hr>

  <!-- Projects List -->

    <h3>Projects List</h3>

    <div class = "container-dashboard">
        <table border="1" style="width:100%; background:white; border-collapse:collapse;">

            <tr>
                <th>Project Name</th>
                <th>Location</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Budget</th>
                <th>Status</th>
                <th>Progress</th>
                <th>Actions</th>
            </tr>

        <?php while ($row = mysqli_fetch_assoc($result)): ?>

            <tr>
                <td><?php echo $row['project_name']; ?></td>
                <td><?php echo $row['location']; ?></td>
                <td><?php echo $row['start_date']; ?></td>
                <td><?php echo $row['end_date']; ?></td>
                <td><?php echo $row['budget']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td><?php echo $row['progress']; ?>%</td>

                <td>
                    <a href="edit_project.php?project_id=<?php echo $row['project_id']; ?>">
                        Edit
                    </a>

                    |

                    <a href="delete_project.php?project_id=<?php echo $row['project_id']; ?>"
                    onclick="return confirm('Are you sure you want to delete this project?')">
                        Delete
                    </a>
                </td>
            </tr>

        <?php endwhile; ?>

        </table>
    </div>

</body>
</html>

        <!--Java Script for Auto disappear Slide-in Alerts-->
        <script>
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach((alert) => {
                    alert.style.display = 'none';
                    
                });
            }, 5000); // 5 seconds
        </script>

