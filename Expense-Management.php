<?php

include("auth.php");// Include the authentication functions

// Restrict access to PROJECT_MANAGER role only
checkLogin();// Check if the user is logged in
requireAnyRole([
    "COMPANY_ADMIN",
    "PROJECT_MANAGER",
    "SITE_ENGINEER"
]);

include("database/connect.php"); // Include the database connection file
include("dashboard_layout.php"); // Include the dashboard layout file

$company_id = $_SESSION["company_id"];

// Fetch expenses for the company
$projects = $conn->query("SELECT * FROM projects WHERE company_id = '$company_id'");


// ADD EXPENSE
if (isset($_POST['add_expense'])) {
    $project_id = $_POST['project_id'];
    $description = $_POST['description'];
    $expense_name = $_POST['expense_name'];
    $amount = $_POST['amount'];
    $expense_date = $_POST['expense_date'];
    $category = $_POST['category'];
    $recorded_by = $_POST['recorded_by'];

    $conn->query("INSERT INTO expenses (project_id, description, expense_name, amount, date, category, recorded_by, company_id) VALUES ('$project_id', '$description', '$expense_name', '$amount', '$expense_date', '$category', '$recorded_by', '$company_id')");
}

// Fetch expenses for the company
$result = $conn->query("SELECT expenses.*, projects.project_name FROM expenses JOIN projects ON expenses.project_id = projects.project_id WHERE expenses.company_id = '$company_id'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Expense Management</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<body>
    <header>
        <h1>Expenses</h1>
        <!--ADD EXPENSES FORM-->
        <form method="POST" style="background: white; padding: 15px; border-radius: 8PX; margin-bottom: 20PX;">
            
        <h2>Add New Expense</h2>

            <label for="project_id">Project:</label>

            <select name="project_id" id="project_id" required>
                <option value="">Select a project</option>
                <?php while ($project = $projects->fetch_assoc()) { ?>
                    <option value="<?= $project['project_id'] ?>"><?= $project['project_name'] ?></option>
                <?php } ?>
            </select>


            <label for="expense_name">Expense Name:</label>
            <input type="text" name="expense_name" id="expense_name" required>

            <label for="description">Description:</label>
            <input type="text" name="description" id="description" required>

            <label for="amount">Amount:</label>
            <input type="number" name="amount" id="amount" step="0.01" required>

            <label for="expense_date">Date:</label>
            <input type="date" name="expense_date" id="expense_date" required>

            <label for="category">Category:</label>
            <input type="text" name="category" id="category" required>

            <label for="recorded_by">Recorded By:</label>
            <input type="text" name="recorded_by" id="recorded_by" required>

            <button type="submit" name="add_expense">Add Expense</button>
        </form>
    </header> 

    <main>
        <h2>Project Expenses</h2>
       
        <!--EXPENSE TABLE -->
        <table  border="1" width="100%" cellpadding="10" style="background:white;">
            <tr>
                <th>Expense ID</th>
                <th>expense_name</th>
                <th>project_id</th>
                <th>description</th>
                <th>amount</th>
                <th>expense_date</th>
                <th>category</th>
                <th>recorded_by</th>
                <th>Action</th>
            </tr>

            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['Expense_id']; ?></td>
                <td><?= $row['expense_name']; ?></td>
                <td><?= $row['project_id']; ?></td>
                <td><?= $row['description']; ?></td>
                <td><?= $row['amount']; ?></td>
                <td><?= $row['Expense_date']; ?></td>
                <td><?= $row['category']; ?></td>
                <td><?= $row['recorded_by']; ?></td>

            <!-- Delete button-->  
            <td>
                <a href ="delete_expense.php?id=<?= $row['expense_id'];?>"
                onclick="return confirm ('Delete this expense')">Delete</a>
            </td>  

            </tr>
            <?php endwhile;?> 
            
        </table>

            <!-- TOTAL EXPENSE PER PROJECT -->

        <h3>Total Expenses Per Project</h3>

        <?php
        $totals = $conn->query("SELECT projects.project_name, SUM(expenses.amount) AS total
            FROM expenses
            JOIN projects ON expenses.project_id = projects.project_id
            WHERE expenses.company_id = $company_id
            GROUP BY projects.project_id
        ");

        while($t = $totals->fetch_assoc()):
        ?>

        <p>
            <?= $t['project_name']; ?> → Total: <?= $t['total']; ?>
        </p>

        <?php endwhile; ?>

    </main>

    </body>

    </html>