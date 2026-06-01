<?php
include("auth.php");
checkLogin();

requireAnyRole([
    "PROJECT_MANAGER",
    "COMPANY_ADMIN"
]);
?>