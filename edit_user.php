<?php
include("database/connect.php");

// Get user ID OR User Data (SELECT)
// Validate ID
if (!isset($_GET['user_id']) || empty($_GET['user_id'])) {
    header("Location: view_users.php");
    exit();
}

    $user_id = intval($_GET['user_id']);

    $result = mysqli_query($conn, "SELECT * FROM users WHERE user_id = $user_id");
    
    if (!$result || mysqli_num_rows($result) == 0 ){
        echo "User not found!";
            exit();
        } 
        
    $user = mysqli_fetch_assoc($result);// Fetch user data as an associative array

// Update User
if (isset($_POST["Update"])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $update = "UPDATE users SET name='$name', email='$email', role='$role'
    WHERE user_id = $user_id";

    if (mysqli_query($conn, $update)){
        header("Location: view_users.php");
        exit();
    }
    else {
        echo "Error updating user: " . mysqli_error($conn);
    }
}
?>

<h2>Edit User</h2>
<form method="POST">
    <label for="name">Name:</label><br>
    <input type="text" name="name" value="<?=$user['name']; ?>" required><br><br>
    <label for="email">Email:</label><br>
    <input type="email" name="email" value="<?=$user['email']; ?>" required><br><br>

    
    <label for="role">Select Staff Role:</label><br>
    <select style="width: 24% !important; padding: 10px !important; box-sizing: border-box !important; height: 20px !important;" id="role" name="role" required>
        <!--<option value="Select ">Select Staff Role</option>-->
        <option value="COMPANY_ADMIN" <?= ($user['role'] == 'COMPANY_ADMIN') ? 'selected' : ''; ?>>COMPANY ADMIN</option>
        <option value="PROJECT_MANAGER" <?= ($user['role'] == 'PROJECT_MANAGER') ? 'selected' : ''; ?>>PROJECT MANAGER</option>
        <option value="SITE_ENGINEER" <?= ($user['role'] == 'SITE_ENGINEER') ? 'selected' : ''; ?>>SITE ENGINEER</option>
        <option value="STORE_KEEPER" <?= ($user['role'] == 'STORE_KEEPER') ? 'selected' : ''; ?>>STORE KEEPER</option>
        <option value="SUPERVISOR" <?= ($user['role'] == 'SUPERVISOR') ? 'selected' : ''; ?>>SUPERVISOR</option>

    </select><br><br>

    <button type="submit" style="padding:8px 15px; background:#4CAF50; color:white; border:none;" name="update"> Update User</button>
</form>

<br>
<a href="view_users.php"> <button type="submit" style="padding:8px 15px; background:#4CAF50; color:white; border:none;">
        Back
    </button></a>