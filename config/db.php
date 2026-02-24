<?php
$host="localhost";
$user="root";
$pass="";
$dbname="crm_db";
$conn=new mysqli($host,$user,$pass,$dbname);
if($conn->connect_error){
    die("connection Failed :" .$conn->connect_error);
}
?>

