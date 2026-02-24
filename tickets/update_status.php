<?php
session_start();

//Checking whether user logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

//only assignee can update the status
if ($_SESSION["role"] != "assignee") {
    echo "You do not have permission to update ticket status.";
    exit();
}

require_once "../config/db.php";

$assignee_id=$_SESSION["user_id"];
$ticket_id=$_GET['id']??null;

if(!$ticket_id){
    header("Location: view_assigned_tickets.php");
    exit();
}

//fetch tickets assigned

$stmt=$conn->prepare("SELECT * FROM tickets WHERE id=? AND assigned_to=?");
$stmt->bind_param("ii",$ticket_id,$assignee_id);
$stmt->execute();
$ticket=$stmt->get_result()->fetch_assoc();

if(!$ticket){
    echo "Ticket Not Found or Not Assigned to You!";
    exit();
}
$message="";

if ($_SERVER["REQUEST_METHOD"] == "POST"){

    $status=$_POST["status"];
    if(empty($status)){
        $message="Please select a status..";
    }else {
    
    $updated_at=date("Y-m-d H:i:s");


    if($status=="completed"){
        $completed_at=date("Y-m-d H:i:s");
    }else{
        $completed_at=NULL;
    }
    $stmt_update=$conn->prepare("UPDATE tickets SET status=?,updated_at=?,completed_at=? WHERE id=? AND assigned_to=?");
    $stmt_update->bind_param("sssii",$status,$updated_at,$completed_at,$ticket_id,$assignee_id);
    if($stmt_update->execute()){
        header("Location: view_assigned_tickets.php?success=1");
        exit();
    }else {
        $message="Something went wrong!Try Again.";
    }
 }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Status</title>
    <link rel="stylesheet" href="../assets/css/style.css">

</head>
<body>
    <div class="container">
    <div class="card">
        <h2>Update Ticket Status</h2>
        <?php if ($message): ?>
            <p style="color:red; font-weight:bold;"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <!--ticket info-->
        <br>
        <p><strong>Title:</strong> <?= htmlspecialchars($ticket['name']) ?></p><br>
        <p><strong>Description:</strong> <?= htmlspecialchars($ticket['description']) ?></p><br>
        <p><strong>Current Status:</strong> <?= htmlspecialchars($ticket['status']) ?></p><br>

        <form method="POST">
            <label>Update Status:</label>
            <select name="status">
                <option value="pending" <?= $ticket['status']=="pending" ? "selected" : "" ?>>Pending</option>
                <option value="inprogress" <?= $ticket['status']=="inprogress" ? "selected" : "" ?>>In Progress</option>
                <option value="completed" <?= $ticket['status']=="completed" ? "selected" : "" ?>>Completed</option>
                <option value="onhold" <?= $ticket['status']=="onhold" ? "selected" : "" ?>>On Hold</option>
            </select>
            <br><br>
            <button type="submit" class="btn btn-success">Update Status</button>
            <a href="view_assigned_tickets.php" class="btn btn-info">Back</a>
        </form>
        </div>
     </div>
</body>
</html>