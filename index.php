<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html>
<head>
    <title>CRM Ticket System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family: 'Segoe UI', sans-serif;
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            background: linear-gradient(135deg,#0f172a,#1e293b,#0f172a);
            overflow:hidden;
            color:white;
        }

        /* Background Glow Effects */
        body::before,
        body::after{
            content:"";
            position:absolute;
            width:450px;
            height:450px;
            border-radius:50%;
            filter:blur(140px);
            opacity:0.5;
        }

        body::before{
            background:#3b82f6;
            top:-120px;
            left:-120px;
        }

        body::after{
            background:#9333ea;
            bottom:-120px;
            right:-120px;
        }

        /* Glass Card */
        .container{
            position:relative;
            z-index:2;
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(20px);
            padding:50px 40px;
            width:420px;
            border-radius:25px;
            box-shadow:0 30px 60px rgba(0,0,0,0.6);
            text-align:center;
        }

        h1{
            font-size:30px;
            margin-bottom:35px;
            font-weight:600;
        }

        .button{
            display:block;
            padding:15px;
            margin:15px 0;
            border-radius:12px;
            font-size:18px;
            font-weight:600;
            text-decoration:none;
            color:white;
            transition:all .3s ease;
        }

        .login-btn{
            background: linear-gradient(135deg,#2563eb,#1d4ed8);
        }

        .register-btn{
            background: linear-gradient(135deg,#22c55e,#15803d);
        }

        .button:hover{
            transform:translateY(-5px);
            box-shadow:0 20px 35px rgba(0,0,0,0.5);
        }

        .subtitle{
            font-size:14px;
            color:#cbd5e1;
            margin-top:-15px;
            margin-bottom:25px;
        }

    </style>
</head>

<body>

    <div class="container">
        <h1>CRM Ticket System</h1>
        <p class="subtitle">Manage, Track & Resolve Tickets Efficiently</p>

        <a class="button login-btn" href="auth/login.php">Login</a>
        <a class="button register-btn" href="auth/register.php">Register</a>
    </div>

</body>
</html>