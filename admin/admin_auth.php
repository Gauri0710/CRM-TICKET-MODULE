<?php
session_start();

//check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

//check if user is admin
if($_SESSION['role']!='admin'){
    echo "Access Denied. You are not authorized to view this page.";
    exit();
}
?>