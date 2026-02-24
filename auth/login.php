<?php
session_start();
require_once "../config/db.php";

$message="";

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $email=trim($_POST["email"]);
    $password=trim($_POST["password"]);
    if(empty($email)||empty($password)){
        $message="All fields are required";
    }else{
        //check if user exist
        $stmt=$conn->prepare("SELECT id,name,password,role FROM users WHERE email=?");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0){
            $stmt->bind_result($id,$name,$hashed_password,$role);
            $stmt->fetch();
            //verify password
            if (password_verify($password,$hashed_password)){

                //Login
                $_SESSION["user_id"]=$id;
                $_SESSION["user_name"]=$name;
                $_SESSION["role"]= $role; 
                if ($role == 'admin') {
    header("Location: ../admin/index.php");
}
elseif ($role == 'assignee') {
    header("Location: ../assignee/index.php");
}
else {
    header("Location: ../dashboard/index.php");
}
exit();
                
            }else {
                $message="Incorrect password";
            }

        }else {
            $message="Email not registered";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <style>
        body {
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #e8f1ff;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

        .card {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
            width: 350px;
            text-align: center;
        }

        h2 {
            margin-bottom: 25px;
            color: #333;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 8px 0 15px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background: #0056b3;
        }

        p a {
            color: #007bff;
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
        }

        .message {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="card">
    <h2>Login</h2>
    <?php if(!empty($message)): ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>
    
    <form method="POST">
        <input type="email" name="email" placeholder="Your Email" required>
        <input type="password" name="password" placeholder="Your Password" required>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an Account?<a href="register.php">Register</a></p>

</div>   
</body>
</html>