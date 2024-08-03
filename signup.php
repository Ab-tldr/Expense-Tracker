<?php
require_once 'includes/signup_view.inc.php';
session_start();



?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #4d62bd;
      background-image: url('expense.jpg'); 
    background-size: cover; 
    background-repeat: no-repeat; 
    display: flex;
    }

    .container {
      width: 100%;
      max-width: 400px;
      margin: 50px auto;
      background-color:  #4d62bd;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      color: black;
    }

    input[type="text"], input[type="email"], input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
    }

    input[type="submit"] {
      background-color: black;
      color: #ffffff;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      width: 100%;
    }

    input[type="submit"]:hover {
      background-color: #0012b3;
    }

    .signup-link {
      text-align: center;
      margin-top: 15px;
    }

    .signup-link a {
      color: black;
      text-decoration: none;
    }

    .signup-link a:hover {
      text-decoration: underline;
    }
    .marquee {
  width: 100%;
  white-space: nowrap;
  overflow: hidden;
  color:white;
  background-color:green;
  position: relative;
}

.marquee p {
  display: inline-block;
  padding-left: 100%;
  animation: animate 10s linear infinite;
}

@keyframes animate {
  100% { transform: translate(-100%, 0); }
}

    
  </style>
</head>
<body>

  <div class="container">
    <h2>Sign Up</h2>
    <form action="includes/signup.inc.php" method="POST">
      <input type="text" name="username" placeholder="Username" >
      <input type="email" name="email" placeholder="Email" >
      <input type="password" name="pwd" placeholder="Password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*]).{8,}$" title="Password must contain at least one lowercase letter, one uppercase letter, one digit, one special character, and be at least 8 characters long." required>

      <input type="submit" value="Sign up">
     
    </form>
    <div class="signup-link">
      Already have an account? <a href="login.php">Sign in</a>
    </div>
    
    <?php
    check_signup_errors();
    if(isset($_GET["signup"])&&$_GET["signup"]==="success"){
      echo '<br>';
      echo '<div class="marquee"><p><b>Signup success!</b><p></div>';
  }
    ?>
    
  </div>

</body>
</html>