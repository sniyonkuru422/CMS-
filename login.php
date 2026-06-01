<?php

//database connection
session_start();
include("database/connect.php");

//Check connection 
if($conn->connect_error){
   die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

// Fetch the user from the database

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);


    if (mysqli_num_rows($result) == 1) {
        //echo "user found<br>";
        $row = mysqli_fetch_assoc($result);

        //echo "Entered password: " .$password . "<br>";
        //echo "Database Password: " .$row["password"] . "<br>";

      
        //VERIFY PASSWORD CORRECTLY// login check

        // Password Check for the expexted role (if provided) and the role in the database, and also verify the password
        if (password_verify($password, $row["password"])
            
        ){ 
            $expected_role = $_POST["expected_role"] ?? '';// Get the expected role from the form (if provided)
            $db_role = strtoupper(trim($row["role"])); // Get the role from the database and trim whitespace

            // Debug Here to see Iif the expected role and database role are being read correctly (Temporary)
            //echo "Expected Role: " . $expected_role . "<br>";
            //echo "Database Role: " . $db_role . "<br>";

            //if (!empty($expected_role) && $expected_role !== $db_role) {// If there's an expected role and it doesn't match the database role, block login. "ROLE BASED LOGIN"
                //echo "Access denied. You do not have permission to view this page."; // Block wrong-role logins with “Role mismatch”
               // exit();
          // }

            // SET SESSION(to keep the user logged in and to use the session variables for access control and personalization)
            $_SESSION["user_id"] = $row["user_id"];
            $_SESSION["role"] = strtoupper(trim($row["role"]));
            $_SESSION["company_id"] = $row["company_id"];
            $_SESSION["username"] = $row["name"];

            
    // Define the role 
        $role = $_SESSION["role"]; 

        //Redirect based on role 
            if ($role == "SUPER_ADMIN") {
                header("Location: super_admin_dashboard.php");
                exit();
                
            } 
            elseif ($role == "COMPANY_ADMIN") {
                header("Location: company_admin_dashboard.php");
                exit();
                
            } 
            elseif ($role == "SITE_ENGINEER") {
                header("Location: site_engineer_dashboard.php");
                exit();
                
            }
            elseif ($role == "PROJECT_MANAGER") {
                header("Location: project_manager_dashboard.php");
                exit();
                
            }
            elseif ($role == "SUPERVISOR") {
                header("Location: supervisor_dashboard.php");
                exit();
                
            }elseif ($role == "STORE_KEEPER") {
                header("Location: store_keeper_dashboard.php");
                exit();
                
            }
        
         else {
           // Debug (Temporary)
            echo "unknown role. ";
            exit();
            
        }
        } else {
            echo "Invalid email or password.";
        }
    } 
     else {
        echo "Invalid email or password.";
    }
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>