<!DOCTYPE html>
<html lang="en">
<head>
  <title>Login & Registration</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container shadow-lg p-3 align-items-center w-50 mt-10px justify-content-center mt-3">
  <!-- Nav tabs --> 
  <ul class="nav nav-pills justify-content-center">
    <li class="nav-item">
      <a class="nav-link active" data-toggle="pill" href="#home">Login</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="pill" href="#menu1">Register</a>
    </li>
  </ul>

<div class="alert alert-success" style="display:none;margin-top:2%;" id="alert_for_login_register" role="alert"></div>
<div class="alert alert-danger" style="display:none;margin-top:2%;" id="alert_for_login_register_dngr" role="alert"></div>




  <!-- Tab panes -->
  <div class="tab-content">
    <div id="home" class="container tab-pane active">
      <h3>Login</h3>
      <form action="#" onsubmit="return false" id="login_form">
        <input type="email" name="eml" class="w-100 p-2 mt-3" required placeholder="Enter your email..." />
        <input type="password" required name="pass" class="w-100 p-2 mt-3" placeholder="Enter your password..." />
        <button type="submit" id="login_btn" class="bg-primary rounded text-light justify-content-center mt-3 w-100 p-2">Login</button>
      </form>
    </div>
    
    <div id="menu1" class="container tab-pane fade">
      <h3>Register</h3>
      <form action="#" onsubmit="return false" id="register_form">
        <input type="text" required name="nm" class="w-100 p-2 mt-3" placeholder="Enter your name..." />
        <input type="email" required name="eml" class="w-100 p-2 mt-3" placeholder="Enter your email..." />
        <input type="password" required name="pass" class="w-100 p-2 mt-3" placeholder="Enter your password..." />
        <button type="submit" id="register_btn" class="bg-primary rounded text-light justify-content-center mt-3 w-100 p-2">Register</button>
      </form>
    </div>
  </div>
</div>


<script type="text/javascript" src="js/ajax.js"></script>
</body>
</html>
