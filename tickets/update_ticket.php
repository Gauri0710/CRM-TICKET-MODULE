<?php
session_start();

//redirect if not logged in

if(!isset($_SESSION["user_id"])){
    header("Location: ../auth/login.php");
    exit();
}

//only authors can update tickets
if($_SESSION["role"]!="author"){
    echo "You do not have permission to edit tickets.";
    exit();
}

require_once "../config/db.php";

$user_id=$_SESSION["user_id"];

//Get ticket ID from the URL
$ticket_id=$_GET['id']??null;
if(!$ticket_id){
    header("Location: my_tickets.php");
    exit();

}

//Fetch ticket details
$stmt=$conn->prepare("SELECT * FROM tickets WHERE id=? AND created_by=?");
$stmt->bind_param("ii",$ticket_id,$user_id);
$stmt->execute();
$ticket=$stmt->get_result()->fetch_assoc();

if(!$ticket){
    echo "Tickets not found or You do not have permission to edit it";
    exit();
}

//form submission
$message="";
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $name=trim($_POST["name"]);
    $description=trim($_POST["description"]);
    
    $assigned_to=$_POST["assigned_to"]??NULL;

    $old_assigned_to = $ticket['assigned_to']; // from DB
    $old_assigned_at = $ticket['assigned_at'];  

    if ($old_assigned_to != $assigned_to) {
    // Only set assigned_at when assignee changes
       if(!empty($assigned_to)){
          $assigned_at = date("Y-m-d H:i:s");
       }else{
          $assigned_at=NULL;
       }
    } else {
    // Keep old assigned time
       $assigned_at =$old_assigned_at;
    }

    //handle file upload
    $file_path=$ticket['file']; //keep the existing file if not changed
    if(isset($_FILES["file"]) && $_FILES["file"]["error"]==0){
        $upload_dir="../uploads/";
        $filename=basename($_FILES["file"]["name"]);
        $file_path = $upload_dir . time() . "_" . $filename;
        if(!move_uploaded_file($_FILES["file"]["tmp_name"], $file_path)){
            $message="Failed To upload File.";
        }
    }
    if(empty($name) || empty($description)){
        $message = "Title and Description are required.";
    }else {
        //$assigned_at=!empty($assigned_to)?date("Y-m-d H:i:s"):NULL;
        $stmt_update=$conn->prepare(
            "UPDATE tickets SET name=?,description=?,file=?,assigned_to=?,assigned_at=? WHERE id=? AND created_by=?" 
        );
        $stmt_update->bind_param("sssisii",$name,$description,$file_path,$assigned_to,$assigned_at,$ticket_id,$user_id);
        if($stmt_update->execute()){
            if (!empty($assigned_to)) {

        // Update new assignee (only if not admin)
            $update_role = $conn->prepare("UPDATE users SET role='assignee' WHERE id=? AND role!='admin'");
            $update_role->bind_param("i", $assigned_to);
            $update_role->execute();
            }
            header("Location: my_tickets.php?success=1");
            exit();
        }else {
            $message="Something went wrong. Please try again.";
        }
    }
      
    
}
//fetch all users except current user for assignee drop down
$user_result=$conn->query("SELECT id,name FROM users WHERE id!=$user_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Ticket</title>
    <link rel="stylesheet" href="../assets/css/style.css">

</head>
<body>
    <div class="container">
        <div class="card">
            <h2>Update Ticket</h2>
            <?php if($message): ?>
                <p style="color:red; font-weight:bold;"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">

            <div class="mb-3">
            <label>Title : </label>
            <input type="text" name="name" value="<?= htmlspecialchars($ticket["name"]) ?>" ><br><br>
            </div>

            <div class="mb-3">
            <label>Description</label>
            <textarea name="description"><?= htmlspecialchars($ticket['description']) ?></textarea><br><br>
            </div>
            
            <div class="mb-3">
            <label>Assign To : </label>
            <select name="assigned_to" >
                <option value="">--Select Assignee--</option>
                <?php while($user=$user_result->fetch_assoc()): ?>
                <option 
                  value="<?= $user['id'] ?>" 
                <?= ($ticket['assigned_to'] == $user['id']) ? "selected" : "" ?>>
                <?= htmlspecialchars($user['name']) ?>
                </option>                
                <?php endwhile; ?>
            </select><br><br>  
            </div>

            <!-- file -->
            <div>
            <label>File(Optional)</label>
            <input type="file" name="file"><br><br>
            </div>

            <?php if($ticket['file']): ?>
                <p>
                    <strong>Current File:</strong>
                    <a href="<?= $ticket['file'] ?>" target="_blank">
                        <?= basename($ticket['file']) ?>
                    </a>
                </p>
            <?php endif; ?>
            
            <div class="mb-3">
            <!--buttons--> 
            <button type="submit" class="btn btn-warning">Update Ticket</button>
            <a href="my_tickets.php" class="btn btn-success">Back</a>
            </div>
            </div>
    </form>            
    </div>
</div>
</body>
</html>










