<?php
  //TODO: check emails better
  session_start();
  ob_start();
  $error = '';
  if (isset($_POST['password']) && isset($_POST['email']) && isset($_POST['username']))
  {
    require 'dbHelper.php';
    $dbo = new db();

    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $email = $_POST['email'];

    $username = str_replace(' ', '', $username);
    $password = str_replace(' ', '', $password);
    $email = str_replace(' ', '', $email);

    if (strlen($password) < 6)
      $error = $error . " - Passwords must be 6 characters or more";
    elseif($password == $confirmPassword)
    {
      if($dbo->userExists($username) || $username == '' || $username == ' ')
        $error = $error . ' - Username already in use';
      else
      {
        if (! $dbo->createUser($username, $password, $email))
          $error = $error . ' - an error occured';

        $_SESSION['userType'] = 'user';
        $_SESSION['username'] = $username;
        header('Location: ./settings.php');
      }
    }
    else{
      $error = $error . " - Passwords must match";
    }
    if (strlen($username) < 3) {
      $error = $error . " - Username must be 3 characters or more";
    }
    if (strlen($email) < 3 || ! filter_var( $email, FILTER_VALIDATE_EMAIL )) {
      $error = $error . " - Email not real";
    }
  }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sign Up</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="css/bootstrap-responsive.css" />
        <style type="text/css">
            body {
              padding-top: 10px;
              padding-bottom: 40px;
              background-color: #f5f5f5;
            }

            .form-register {
              max-width: 300px;
              padding: 19px 29px 29px;
              margin: 0 auto 20px;
              background-color: #fff;
              border: 1px solid #e5e5e5;
              -webkit-border-radius: 5px;
                 -moz-border-radius: 5px;
                      border-radius: 5px;
              -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                 -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                      box-shadow: 0 1px 2px rgba(0,0,0,.05);
            }
            .form-register .form-register-heading,
            .form-register .checkbox {
              margin-bottom: 10px;
            }
            .form-register input[type="text"],
            .form-register input[type="password"] {
              font-size: 16px;
              height: auto;
              margin-bottom: 15px;
              padding: 7px 9px;
            }
    </style>
    </head>
    <body>
        <script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
        <script src="js/bootstrap.js"></script>

      <div class="container">
      <?php
         if (isset($error) && $error != ''){
            echo "<div class=\"alert alert-error\" id=\"formError\">
                   <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
                   <strong>ERROR! </strong>" . $error. "
                 </div>";
         }
       ?>

       <ul class="nav nav-tabs">
        <li class="">
          <a href="login.php">Login</a>
        </li>
        <li class="active" ><a href="register.php">Register</a></li>
      </ul>
      <form class="form-register" action="" method="post">
        <h2 class="form-register-heading">Register</h2>
        <input type="text" class="input-block-level" placeholder="Username" name="username">
        <input type="text" class="input-block-level" placeholder="Email" name="email">

        <input type="password" class="input-block-level" placeholder="Password" name="password">
        <input type="password" class="input-block-level" placeholder="Confirm Password" name="confirmPassword">

        <center>
        <button class="btn btn-large btn-primary" type="submit" name="submit">Register</button>
        </center>
      </form>
      <center><a href="index.php">Home</a></center>

    </div> <!-- /container -->
    <?php include 'footer.php'; ?>
 </body>
</html>
