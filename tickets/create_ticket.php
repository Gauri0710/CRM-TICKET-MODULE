<?php
session_start();

//checking user already login
if(!isset($_SESSION["user_id"])){
    header("Location: ../auth/login.php");
    exit();
}

//only authors can create tickets

if($_SESSION["role"]!="author"){
    echo "You do not have permission to create tickets";
    exit();
}

//database connection
 require_once "../config/db.php";

 //initialize variables
 $name="";
 $description="";
 $message="";

 if($_SERVER["REQUEST_METHOD"]=="POST"){
    $name=trim($_POST["name"]);
    $description=trim($_POST["description"]);
    $assigned_to = !empty($_POST["assigned_to"]) ? (int)$_POST["assigned_to"] : NULL;

    
    //file upload
    $file_path=NULL;
    if(isset($_FILES["file"]) && $_FILES["file"]["error"]==0){
        $upload_dir="../uploads/";
        $filename=basename($_FILES["file"]["name"]);
        $file_path=$upload_dir.time()."_".$filename;
        if(!move_uploaded_file($_FILES["file"]["tmp_name"],$file_path)){
            $message="FAiled to upload the file.";

        }
    }

    if(empty($name)||empty($description)){
        $message="Title and description are required";
    }else {
        $created_by=$_SESSION["user_id"];
        $assigned_at=!empty($assigned_to)?date("Y-m-d H:i:s"):NULL;

        //insert in to tickets table
        $stmt = $conn->prepare("INSERT INTO tickets (name, description, status, file, created_by, assigned_to, assigned_at) VALUES (?, ?,'pending',?,?,?,?)");
        $stmt->bind_param("sssiis", $name, $description, $file_path, $created_by, $assigned_to, $assigned_at);

        
        if($stmt->execute()){
            //update user role in users table
            if (!empty($assigned_to)) { 
            $update_role = $conn->prepare("UPDATE users SET role='assignee' WHERE id=? AND role!='admin'");
            $update_role->bind_param("i", $assigned_to);
            $update_role->execute();
            }
            $message="Ticket Created Successfully";
            header("Location:my_tickets.php?success=1");
            exit();
        }else {
            $message="Something Went Wrong";
        }
    }

 }

 //fetch users for assignee dropdown(exclude current user)

 $current_user_id=$_SESSION["user_id"];
 $user_result=$conn->query("SELECT id,name FROM users where id!=$current_user_id");
 ?>

 <!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Ticket</title>
    <link rel="stylesheet" href="../assets/css/style.css">

 </head>
 <body>
 <div class="container">
    <div class="card">
    <h2>Create Ticket</h2>
    <?php if(!empty($message)): ?>
        <p style="color:red;"><?= $message ?></p>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="name" required><br><br>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" required></textarea><br><br>
        </div>
        <div class="mb-3">
             <label>Assigned To(Optional)</label>
             <select name="assigned_to">
                <option value="">--Select User--</option>
                <?php  while($user=$user_result->fetch_assoc()): ?>
                    <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['name']) ?>
                </option>
                <?php endwhile; ?>
                </select><br><br>
        </div>
        <div class="mb-3">
            <label>Upload File (Optional)</label> 
            <input type="file" name="file">
        </div><br><br>  
        <button type="submit" class="btn btn-success">Create Ticket</button>
        <a href="../dashboard/index.php" class="btn btn-info">Back</a>
    </form>   
    </div>
    </div>


    
 </body>
 </html>