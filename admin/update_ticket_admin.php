<?php
session_start();
include("../config/db.php");

//allow only admin

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != "admin"){
    header("Location: ../auth/login.php");
    exit();
}

// Validate inputs
if(!isset($_POST['ticket_id'])){
    echo "Invalid request!";
    exit();
}

$ticket_id = $_POST['ticket_id'];
$status = $_POST['status'];
$assigned_to = $_POST['assigned_to'];

if($assigned_to === "" || $assigned_to == 0){
    $assigned_to = NULL;
}

$stmt = $conn->prepare("UPDATE tickets SET status=?, assigned_to=? WHERE id=?");
$stmt->bind_param("sii", $status, $assigned_to, $ticket_id);

if($stmt->execute()){
    // Redirect with success message
    header("Location: index.php?page=manage_ticket&msg=updated");
    exit();
}
else{
    echo "Error updating ticket:".$conn->error;

}
?>