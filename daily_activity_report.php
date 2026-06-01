<?php
include("database/connect.php");// Include the database connection file
include("dashboard_layout.php");// connect Layout
?>

<div class="main-content">
    <h2>Daily Activity Report</h2>

    <table border="1">
        <tr>
            <th>Project</th>
            <th>Activity</th>
            <th>Workers</th>
            <th>Date</th>
        </tr>
    
        <?php
        
        // This will show Daily Activity reports

        $result = mysqli_query($conn,"query");

        while ($row = mysqli_fetch_array($result)) {
            echo "<tr
                <td>{$row['project_name']}</td>
                <td>{$row['activity']}</td>
                <td>{$row['workers']}</td>
                <td>{$row['date']}</td>

            </tr>";
        }
        ?>
    </table>
</div>