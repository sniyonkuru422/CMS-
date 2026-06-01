<?php
//This file creates a user profile page where users can view and update their personal information, such as name, email, contact details, and profile picture. The page also allows users to change their password and manage their account settings for a personalized experience within the construction management system.
session_start();
$conn = mysqli_connect($host, $username, $password, $dbname);

$user_id = $_SESSION["user_id"];
$result = $conn->query("SELECT * FROM users WHERE user_id = '$user_id'");
$user = $result->fetch_assoc();
?>

<h2>User Profile</h2>
<img src="uploads/<?= $user['profile_pic'] ?>"  width="150" style="border-radius: 50%;" height="150"><br>

<p> Email: <?= $user['email'] ?></p>
<p> Role: <?= $user['role'] ?></p>

<form method="POST"  enctype="multipart/form-data">
    <input type="file" name="profile_pic" accept="image/*"><br><br>
    <button type="submit">Upload Profile Picture</button>
</form>

<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $fle = $_FILES["profile_pic"] ['name'];
    move_uploaded_file($_FILES["profile_pic"] ['tmp_name'], "uploads/" . $fle);

    $conn->query("UPDATE user SET profile_pic='$file' WHERE user_id='$user_id'");

    header("Location: profile.php");
    
}
?>

