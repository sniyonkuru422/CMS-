<?php
include("database/connect.php");

// Get Worker ID
if (!isset($_GET['worker_id']) || empty($_GET['worker_id'])) {
    header("Location: view_workers.php");
    exit();
}

$worker_id = intval($_GET['worker_id']);

$result = mysqli_query($conn,
    "SELECT * FROM workers WHERE worker_id = $worker_id");

if (!$result || mysqli_num_rows($result) == 0) {
    echo "Worker not found!";
    exit();
}

$worker = mysqli_fetch_assoc($result);


// Update Worker
if (isset($_POST['update'])) {

    $name = $_POST['Name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $nid = $_POST['nid'] ?? '';

    $worker_category = $_POST['Worker_Category'] ?? '';

    $nid = trim($_POST['nid'] ?? '');

    if ($nid == ""){
        $nid = $worker['nid']; // // Use existing NID if not provided or if it's empty.
    } else {
        $nid = mysqli_real_escape_string($conn, $nid); // Sanitize input
    }

    $update = "
        UPDATE workers
        SET
            name='$name',
            phone='$phone',
            NID='$nid',
            worker_category='$worker_category'
        WHERE worker_id=$worker_id
    ";

    if (mysqli_query($conn, $update)) {
        header("Location: view_workers.php");
        exit();
    } else {
        echo "Error updating worker: " . mysqli_error($conn);
    }
}
?>


        <h1 style="text-align:center; margin: 50px auto;">Edit Field Worker</h1>
<div class="form-container" style="width: 35%; margin: 50px auto; border: 5px solid #1443a7; padding: 30px; border-radius: 8px;">
        <form method="POST" style="text-align:center;">

            <label>Full Name:</label><br>
            <input type="text"
                name="Name"
                value="<?= $worker['name'] ?? ''?>"
                required><br><br>

            <label>Phone Number:</label><br>
            <input type="text"
                name="phone"
                value="<?= $worker['phone'] ?? ''?>"
                required><br><br>

            <label>NID:</label><br>
            <input type="text"
                name="nid"
                value="<?= $worker['NID'] ?? ''?>"
                required><br><br>

            <label>Worker Category:</label><br>
            <select name="worker_category" required
                style="width: 33%; padding:2px; box-sizing: border-box">
                <option value="" disabled <?= empty($worker['worker_category']) ? 'selected' : ''; ?>>
                    --- Select ---
                </option>

                <option value="Mason"
                <?= ($worker['worker_category']=='Mason') ? 'selected' : ''; ?>>
                    Mason
                </option>

                <option value="Carpenter"
                <?= ($worker['worker_category']=='Carpenter') ? 'selected' : ''; ?>>
                    Carpenter
                </option>

                <option value="Electrician"
                <?= ($worker['worker_category']=='Electrician') ? 'selected' : ''; ?>>
                    Electrician
                </option>

                <option value="Plumber"
                <?= ($worker['worker_category']=='Plumber') ? 'selected' : ''; ?>>
                    Plumber
                </option>

                <option value="Laborer"
                <?= ($worker['worker_category']=='Laborer') ? 'selected' : ''; ?>>
                    Laborer
                </option>

            </select>

            <br><br>

            <button type="submit"
                    name="update"
                    style="padding:8px 32px;
                        background:#4CAF50;
                        color:white;
                        border:none;
                        border-radius: 8px;">
                Update Worker
            </button>

       

        </form>
        <div style="text-align: center;">
            <a href="view_workers.php">
                <button style="padding:8px 5px;
                            background:#2196F3;
                            color:white;
                            border:none;
                            border-radius: 10px;">
                    Back to View All Workers
                </button>
            </a>
        </div>
</div>