<?php

session_start();


include("auth.php");

checkLogin();


include("database/connect.php");


$company_id=$_SESSION['company_id'];



// Only Company Admin and Project Manager

if(
$_SESSION['role'] != 'COMPANY_ADMIN' &&
$_SESSION['role'] != 'PROJECT_MANAGER'
){

header("Location: login.html");
exit();

}



include("dashboard_layout.php");



// Update status

if(isset($_POST['update_status'])){

$request_id=$_POST['request_id'];

$status=$_POST['status'];


$conn->query("UPDATE material_requests

SET status='$status'

WHERE request_id=$request_id

");


}


$requests=$conn->query(" SELECT

material_requests.*,

projects.project_name,

users.name AS requester


FROM material_requests


JOIN projects

ON material_requests.project_id = projects.project_id


JOIN users

ON material_requests.requested_by = users.user_id


WHERE material_requests.company_id=$company_id


ORDER BY created_at DESC


");

?>


<h2>Material Requests</h2>

<table border="1" width="100%" cellpadding="8">

    <tr>

    <th>Project</th>
    <th>Requested By</th>
    <th>Material</th>
    <th>Quantity</th>
    <th>Unit</th>
    <th>Reason</th>
    <th>Status</th>
    <th>Action</th>

    </tr>

    <?php while($row=$requests->fetch_assoc()){ ?>

        <tr>

            <td>
            <?= htmlspecialchars($row['project_name']); ?>
            </td>

            <td>
            <?= htmlspecialchars($row['requester']); ?>
            </td>

            <td>
            <?= htmlspecialchars($row['material_name']); ?>
            </td>

            <td>
            <?= $row['quantity']; ?>
            </td>

            <td>
            <?= $row['unit']; ?>
            </td>

            <td>
            <?= $row['reason']; ?>
            </td>

            <td>
            <?= $row['status']; ?>
            </td>

            <td>

                <form method="POST">

                <input 
                type="hidden" 
                name="request_id"
                value="<?= $row['request_id']; ?>">


                <select name="status">

                    <option value="PENDING">
                    Pending
                    </option>

                    <option value="APPROVED">
                    Approved
                    </option>

                    <option value="REJECTED">
                    Rejected
                    </option>

                </select>

                <button name="update_status">
                Update
                </button>


            </form>


            </td>


        </tr>


    <?php } ?>

</table>