<?php
include("database/connect.php");// Include the database connection file
include("dashboard_layout.php");// connect Layout

?>

<div class="main-content">
    <h2>Equipment Report</h2>

    <table border="1">
        <tr>
            <th>Equipment</th>
            <th>Quantity</th>
            <th>Conditon</th>
            <th>Status</th>
        </tr>

    <?php
    // This will show equipment reports
    $result = mysqli_query($conn,"SELECT * FROM equipment") or die(mysqli_error($conn));

    while ($row = mysqli_fetch_array($result)) {
        echo "<tr>
        <td>{$row['name']}</td>
        <td>{$row['quantity']}</td>
        <td>{$row['condition_status']}</td>
        <td>{$row['status']}</td>
    </tr>";
    }
    ?>
    </table>
</div>