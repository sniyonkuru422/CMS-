<?php

//session_start();

include("auth.php");

checkLogin();

requireRole('SITE_ENGINEER');


include("database/connect.php");


$company_id=$_SESSION['company_id'];

$user_id=$_SESSION['user_id'];



// SAVE REQUEST

if(isset($_POST['request_material'])){


$project_id=$_POST['project_id'];

$material=$_POST['material_name'];

$quantity=$_POST['quantity'];

$unit=$_POST['unit'];

$reason=$_POST['reason'];



$stmt=$conn->prepare("

INSERT INTO material_requests

(company_id,requested_by,project_id,material_name,quantity,unit,reason)

VALUES (?,?,?,?,?,?,?)

");



$stmt->bind_param(

"iiisiss",

$company_id,

$user_id,

$project_id,

$material,

$quantity,

$unit,

$reason

);



$stmt->execute();



echo "<script>
alert('Material request sent');
window.location='material_request.php';
</script>";

}


$projects=$conn->query(" SELECT project_id,project_name
FROM projects
WHERE company_id=$company_id

");

include("dashboard_layout.php");

?>


<h2>Request Construction Materials</h2>

<form method="POST">


    <label>Project</label><br>
    <select name="project_id" required>

        <option value="">
        --Select Project--
        </option>

            <?php while($p=$projects->fetch_assoc()){ ?>
            <option value="<?=$p['project_id']?>">
            <?=$p['project_name']?>

        </option>
        <?php } ?>

    </select>

    <br><br>

    <label>Material Name</label><br>

    <input type="text"
    name="material_name"
    required>


    <br><br>


    <label>Quantity</label><br>

    <input type="number"
    name="quantity"
    required>



    <br><br>


    <label>Unit</label><br>

    <input type="text"
    name="unit"
    placeholder="bags, pieces, tons">



    <br><br>


    <label>Reason</label><br>


    <textarea name="reason"></textarea>



    <br><br>


    <button name="request_material">

    Send Request

    </button>



</form>