<?php
   session_start();
   ob_start();
   require 'dbHelper.php';

   $pdo = new db();
   $errorMessage = '';
   if (isset($_POST['btn_submit']) && isset($_POST['rd_user_type']))
   {
		if($_POST['rd_user_type']=='user')
		{
			if ($row = $pdo->verifyLogin($_POST['txt_user_name'], $_POST['pass_user_password']))
			{

				$_SESSION['sess_user_type'] = $row['user_type'];
				$_SESSION['sess_user_name'] = $row['user_name'];
				$_SESSION['sess_user_id'] = $row['user_id'];

				if ($_SESSION['userType'] == 'admin')
				{
					header('Location: ./admin.php');
				}
				else
				{
					header('Location: ./home.php?user_name=' . $row['user_name']);
				}
			}
			else
				$errorMessage = $errorMessage . ' - Incorrect user username or password';
		}
		elseif($_POST['rd_user_type']=='band')
		{
			if ($row = $pdo->verifyBandLogin($_POST['txt_user_name'], $_POST['pass_user_password']))
			{
				$_SESSION['sess_band_user_name'] = $row['band_user_name'];
				$_SESSION['sess_band_name'] = $row['band_name'];
				$_SESSION['sess_band_id'] = $row['band_id'];
				$_SESSION['sess_user_type'] = $_POST['rd_user_type'];
				$_SESSION['sess_band_website'] = $row['band_website'];
				$_SESSION['sess_band_members'] = $row['band_members'];

				if (isset($_SESSION['userType']) && $_SESSION['userType']== 'admin')
				{
					header('Location: ./admin.php');
				}
				else
				{
					//echo "hello";
					header('Location: ./bandHome.php?band_user_name=' . $row['band_user_name']);
				}
			}
			else
				$errorMessage = $errorMessage . ' - Incorrect band username or password';
		}
   }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sign in</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="css/bootstrap-responsive.css" />
        <style type="text/css">
            body {
              padding-top: 10px;
              padding-bottom: 40px;
              background-color: #f5f5f5;
            }

            .form-signin {
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
            .form-signin .form-signin-heading,
            .form-signin .checkbox {
              margin-bottom: 10px;
            }
            .form-signin input[type="text"],
            .form-signin input[type="password"] {
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
         if (isset($errorMessage) && $errorMessage != '')
            echo "<div class=\"alert alert-error\" id=\"formError\">
                   <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
                   <strong>ERROR! </strong>" . $errorMessage . "
                 </div>";
       ?>
       <ul class="nav nav-tabs">
        <li class="active">
          <a href="login.php">Login</a>
        </li>
        <li><a href="register.php">Register</a></li>
		<li><a href="registerband.php">Register Band</a></li>
      </ul>
      <form class="form-signin" action="" method="post">
        <h2 class="form-signin-heading">Sign In</h2>
        <input type="text" class="input-block-level" placeholder="Username" name="txt_user_name" required>
        <input type="password" class="input-block-level" placeholder="Password" name="pass_user_password" required>
		<input type="radio" class="input-block-level"  id="rd_user" name="rd_user_type" value="user">User
		<input type="radio" class="input-block-level"  id="rd_band" name="rd_user_type" value="band">Band
        <center>
        <button class="btn btn-large btn-primary" type="submit" name="btn_submit">Sign in</button>
        </center>
      </form>
      <center><a href="index.php">Home</a></center>
    </div> <!-- /container -->
    <?php include 'footer.php'; ?>
    </body>
</html>
