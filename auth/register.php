<?php
session_start();
require_once "../config/db.php";
$message="";
if($_SERVER["REQUEST_METHOD"]=="POST") {

    $name=trim($_POST["name"]);
    $email=trim($_POST["email"]);
    $password=trim($_POST["password"]);

    // validation
    if(empty($name)||empty($email)||empty($password)){
        $message="All fields are required";
    }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $message = "Invalid email format!";
    }
    else {
        //check duplicate email
        $check=$conn->prepare("SELECT id FROM users WHERE email=?");
        $check->bind_param("s",$email);
        $check->execute();
        $check->store_result();

        if($check->num_rows>0){
            $message="Email already exists";
        } else {
            //Hash password
            $hashed=password_hash($password,PASSWORD_DEFAULT);

            //insert new row

            $stmt=$conn->prepare("INSERT INTO users(name,email,password)VALUES(?,?,?)");
            $stmt->bind_param("sss",$name,$email,$hashed);
            if($stmt->execute()){
                $message="Registration successful! please Log in";
            } else{
                $message="something went wrong";

            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
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

        input[type="text"],
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
    <h2>Register</h2>
    <?php if(!empty($message)): ?>
        <p style="color:red"><?= $message ?></p>
        <?php endif; ?>
    <form method="POST">
        <label>Name:</label>&nbsp;
        <input type="text" name="name" placeholder="Your Name" required><br><br>
        <label>Email :</label>
        <input type="email" name="email" placeholder="Your Email" required><br><br>
        <label>Password :</label>
        <input type="password" name="password" placeholder="Your Password" required><br><br>

        <button type="submit">Register</button>
    </form>
    <p>Already Have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>