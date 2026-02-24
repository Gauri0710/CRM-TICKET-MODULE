<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html>
<head>
    <title>CRM Ticket Modules</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e8f1ff; 
            background-size: cover; 
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: rgba(255, 255, 255, 0.85); 
            width: 400px;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            text-align: center;
        }

        h1 {
            margin-bottom: 30px;
            color: #333;
            font-size: 28px;
        }

        a.button {
            display: inline-block;
            padding: 12px 20px;
            margin: 10px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 18px;
        }

        a.button:hover {
            background: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>CRM Ticket Module</h1>

        <a class="button" href="auth/login.php">Login</a>
        <a class="button" href="auth/register.php">Register</a>
    </div>
</body>
</html>