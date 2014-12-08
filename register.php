<?php
  //TODO: check emails better
  session_start();
  ob_start();
  $error = '';
  //echo $_POST['pas_user_password'];
  //echo $_POST['txt_user_email'];
  //echo $_POST['txt_user_name'];
  //echo $_POST['txt_user_fname'];
  //echo $_POST['txt_user_lname'];
  //echo "1223";
  if (isset($_POST['pas_user_password']) && 
		isset($_POST['txt_user_email']) && 
		isset($_POST['txt_user_name']) && 
		isset($_POST['txt_user_fname']) && 
		isset($_POST['txt_user_lname']) &&
		isset($_POST['submit']))
	{
		//echo "hello";
		require 'dbHelper.php';
		$dbo = new db();

		$user_fname = $_POST['txt_user_fname'];
		$user_lname = $_POST['txt_user_lname'];
		$user_name = $_POST['txt_user_name'];
		$user_password = $_POST['pas_user_password'];
		$user_conf_password = $_POST['pas_user_conf_password'];
		$user_email = $_POST['txt_user_email'];
		
		$user_fname = str_replace(' ', '', $user_fname);
		$user_lname = str_replace(' ', '', $user_lname);
		$user_name = str_replace(' ', '', $user_name);
		$user_password = str_replace(' ', '', $user_password);
		$user_email = str_replace(' ', '', $user_email);
		
		//echo $_POST['pas_user_password'];
		//echo $_POST['txt_user_email'];
		//echo $_POST['txt_user_name'];
		//echo $_POST['txt_user_fname'];
		//echo $_POST['txt_user_lname'];

		if (strlen($user_password) < 2)
			$error = $error . " - Passwords must be 6 characters or more";
		elseif($user_password == $user_conf_password)
		{
			if($dbo->userExists($user_name) || $user_name == '' || $user_name == ' ')
				$error = $error . ' - Username already in use';
			else
			{
				$user_id = $dbo->createUser($user_fname, $user_lname,$user_name, $user_password, $user_email);
				//echo "112";
				//echo "hello".$user_id;
				//if (! )
				//	$error = $error . ' - an error occured';
				if(isset($user_id))
				{
					$_SESSION['sess_user_type'] = 'user';
					$_SESSION['sess_user_name'] = $user_name;
					$_SESSION['sess_user_id'] = $user_id;
					$_SESSION['sess_user_repo'] = '1';
					//echo $user_id;
					header('Location: ./settings.php');
				}
			}
		}
		else
		{
			$error = $error . " - Passwords must match";
		}
		if (strlen($user_name) < 3) 
		{
			$error = $error . " - Username must be 3 characters or more";
		}
		if (strlen($user_email) < 3 || ! filter_var( $user_email, FILTER_VALIDATE_EMAIL )) 
		{
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
		<li class="" ><a href="registerband.php">Register Band</a></li>
      </ul>
      <form class="form-register" action="" method="post">
        <h2 class="form-register-heading">Register</h2>
		<input type="text" class="input-block-level" placeholder="First Name" name="txt_user_fname">
		<input type="text" class="input-block-level" placeholder="Last Name" name="txt_user_lname">
		<input type="text" class="input-block-level" placeholder="Username" name="txt_user_name">
        <input type="text" class="input-block-level" placeholder="Email" name="txt_user_email">
        <input type="password" class="input-block-level" placeholder="Password" name="pas_user_password">
        <input type="password" class="input-block-level" placeholder="Confirm Password" name="pas_user_conf_password">
        <center>
			<button class="btn btn-large btn-primary" type="submit" name="submit">Register</button>
        </center>
      </form>
      <center><a href="index.php">Home</a></center>

    </div> <!-- /container -->
    <?php include 'footer.php'; ?>
 </body>
</html>
