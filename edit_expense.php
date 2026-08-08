<?php

include("auth.php");

checkLogin();

requireAnyRole([
    "COMPANY_ADMIN",
    "PROJECT_MANAGER"
]);


include("database/connect.php");


$id = $_GET['id'];

$company_id = $_SESSION['company_id'];


// Fetch expense

$result = $conn->query("
SELECT * FROM expenses
WHERE expense_id='$id'
AND company_id='$company_id'
");


$row = $result->fetch_assoc();



if(isset($_POST['update_expense'])){


    $expense_name = $_POST['expense_name'];

    $description = $_POST['description'];

    $amount = str_replace(',', '', $_POST['amount']);

    $expense_date = $_POST['expense_date'];

    $category = $_POST['category'];



    $sql = "UPDATE expenses SET

    expense_name='$expense_name',
    description='$description',
    amount='$amount',
    expense_date='$expense_date',
    category='$category'


    WHERE expense_id='$id'
    AND company_id='$company_id'
    ";



    if($conn->query($sql)){

        $_SESSION['message']="Expense updated successfully!";

    }


    echo "<script>
    window.location.href='Expense-Management.php';
    </script>";

    exit();

}


?>


<!DOCTYPE html>
<html>

<head>

<title>Edit Expense</title>

<style>

        body{

        font-family:Arial;

        background:#f4f6f9;

    }


        .edit-box{

        width:450px;

        margin:50px auto;

        background:white;

        padding:25px;

        border-radius:12px;

        box-shadow:0 5px 15px rgba(0,0,0,.2);

    }


        input, textarea{

        width:100%;

        padding:10px;

        margin-bottom:15px;

        border-radius:6px;

        border:1px solid #ccc;

    }



        button{

        background:#28a745;

        color:white;

        border:none;

        padding:12px;

        width:100%;

        border-radius:6px;

        font-weight:bold;

        cursor:pointer;

    }


</style>

</head>
<body>

<div class="edit-box">
        
    <h2>Edit Expense</h2>
    <form method="POST">

        <label>Expense Name</label>
        <input type="text" name="expense_name" value="<?= htmlspecialchars($row['expense_name']); ?>">

        <label>Description</label>
        <textarea name="description"><?=htmlspecialchars($row['description']);  ?></textarea>

        <label>Amount</label>
        <input type="number" name="amount" value="<?= number_format($row['amount'],0); ?>">

        <label>Date</label>
        <input type="date" name="expense_date" value="<?= $row['expense_date']; ?>">

        <label>Category</label>
        <input type="text" name="category" value="<?= htmlspecialchars($row['category']); ?>">

        <div style="text-align:center; margin-top:20px;">
            <button style="width:50%;"  name="update_expense"> Update Expense</button>
        </div>
    </form>


</div>


</body>

</html>