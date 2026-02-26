<?php
session_start();
require_once("../config/db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'assignee') {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $ticket_id = (int) $_POST['ticket_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE tickets SET status=? WHERE id=? AND assigned_to=?");
    $stmt->bind_param("sii", $status, $ticket_id, $_SESSION['user_id']);

    $stmt->execute();
}

header("Location: index.php");
exit();