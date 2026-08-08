<?php

include("auth.php");
checkLogin();

include("database/connect.php");


// Check if attendance ID exists

if (!isset($_GET['id'])) {

    die("Attendance ID not found.");
}


$attendance_id = $_GET['id'];

// UPDATE ATTENDANCE RECORD

if(isset($_POST['update'])){


    $date = $_POST['date'];

    $status = $_POST['status'];

    $role = $_POST['role'];



    $stmt = $conn->prepare("
        UPDATE attendance

        SET 
            date=?,
            status=?,
            role=?

        WHERE attendance_id=?
    ");



    $stmt->bind_param(
        "sssi",
        $date,
        $status,
        $role,
        $attendance_id
    );



    if($stmt->execute()){


        header("Location: Worker-Attendance-Monitoring.php?updated=1");

        exit();


    }

    else{

        die("Update failed: ".$stmt->error);
    }

}


// FETCH EXISTING DATA

$query = $conn->query("
SELECT *

FROM attendance

WHERE attendance_id=$attendance_id
");


$row = $query->fetch_assoc();

?>

<!DOCTYPE html>

<html>
<head>
<title>Edit Attendance</title>
<style>
        body {
            background-color: #afec1e;
            font-family: Arial, sans-serif;
        }

        .edit-container {
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        h2 {
            text-align: center;
            color: #eaece7;
            margin-bottom: 20px;
        }

        .edit-card {
            background: white;
            width: 420px;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 5px 15px rgba(0,0,0,0.2);
        }

        label {
            font-weight: bold;
            color: #444;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .update-btn {
            background-color: #28a745;
        }

        .back-btn {
            background-color: #d16a16;
            margin-left: 10px;
        }

        button:hover {
            opacity: 0.85;
        }

</style>
</head>

<body>
    <div class="edit-container">
            <h2>Edit Attendance Record</h2>

        <form method="POST">

            <label>Date</label><br>

            <input 
            type="date" 
            name="date"
            value="<?= $row['date']; ?>"
            required>

            <br><br>

            <label>Role / Category</label><br>

            <input 
            type="text"
            name="role"
            value="<?= htmlspecialchars($row['role']); ?>"
            required>

            <br><br>

            <label>Status</label><br>

            <select name="status">

            <option value="Present"
            <?= $row['status']=="Present"?'selected':''; ?>>
            Present
            </option>


            <option value="Late"
            <?= $row['status']=="Late"?'selected':''; ?>>
            Late
            </option>


            <option value="Absent"
            <?= $row['status']=="Absent"?'selected':''; ?>>
            Absent
            </option>

            </select>

            <br><br>

            <button 
            name="update"
            style="
            background:green;
            color:white;
            padding:8px 15px;
            border:none;
            border-radius:5px;
            cursor:pointer;
            ">

            Update Attendance

            </button>

            <a href="Worker-Attendance-Monitoring.php"
            style="
            margin-left:10px;
            background:red;
            color:white;
            padding:8px 15px;
            text-decoration:none;
            border-radius:5px;
            ">

            Cancel

            </a>

        </form>
    </div>
</body>
</html>