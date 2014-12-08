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
  if (isset($_POST['pas_band_user_password']) && 
		isset($_POST['pas_band_user_conf_password']) && 
		isset($_POST['txt_band_name']) && 
		isset($_POST['txt_num_of_members']) &&
		isset($_POST['txt_band_user_name']) && 
		isset($_POST['submit']))
	{
		//echo "hello";
		require 'dbHelper.php';
		$dbo = new db();
		$band_name = $_POST['txt_band_name'];
		$band_num_members = $_POST['txt_num_of_members'];
		$band_user_name = $_POST['txt_band_user_name'];
		$band_user_password = $_POST['pas_band_user_password'];
		$band_user_conf_password = $_POST['pas_band_user_conf_password'];
		$band_website ='';
		if(isset($_POST['txt_band_website']))
			$band_website = $_POST['txt_band_website'];
			
		$band_name = str_replace(' ', '', $band_name);
		$band_user_name = str_replace(' ', '', $band_user_name);
		$band_user_password = str_replace(' ', '', $band_user_password);
		

		if (strlen($band_user_password) < 2)
		{
			$error = $error . " - Passwords must be 6 characters or more";
		}
		elseif (! filter_var( $band_num_members, FILTER_VALIDATE_INT ))
		{
			$error = $error . ' - Invalid number';
		}
		elseif($band_user_password == $band_user_conf_password)
		{
			if($dbo->bandExists($band_user_name) || $band_user_name == '' || $band_user_name == ' ')
				$error = $error . ' - Username already in use';
			else
			{
				$band_id = $dbo->createBand($band_name, $band_user_name, $band_user_password, $band_website,$band_num_members );
				if(isset($band_id))
				{
					$_SESSION['sess_band_name'] = $band_name;
					$_SESSION['sess_band_user_name'] = $band_user_name;
					$_SESSION['sess_band_id'] = $band_id;
					$_SESSION['sess_band_num_members']=$band_num_members;
					$_SESSION['sess_user_type']='band';
					//echo $user_id;
					header('Location: ./genre.php');
				}
			}
		}
		else
		{
			$error = $error . " - Passwords must match";
		}
		if (strlen($band_user_password) < 3) 
		{
			$error = $error . " - Username must be 3 characters or more";
		}
		if(isset($band_website) && $band_website!='')
		{
			if (strlen($band_website) < 3 || ! filter_var( $band_website, FILTER_VALIDATE_URL )) 
			{
				$error = $error . " - Website not real";
			}
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
        <li class="" ><a href="register.php">Register</a></li>
		<li class="active" ><a href="registerband.php">Register Band</a></li>
      </ul>
      <form class="form-register" action="" method="post">
        <h2 class="form-register-heading">Register</h2>
		<input type="text" class="input-block-level" placeholder="Band Name" name="txt_band_name">
		<input type="text" class="input-block-level" placeholder="# of members" name="txt_num_of_members">
		<input type="text" class="input-block-level" placeholder="Band Website" name="txt_band_website">
		<input type="text" class="input-block-level" placeholder="Username" name="txt_band_user_name">
        <input type="password" class="input-block-level" placeholder="Password" name="pas_band_user_password">
        <input type="password" class="input-block-level" placeholder="Confirm Password" name="pas_band_user_conf_password">
			
        <center>
			<button class="btn btn-large btn-primary" type="submit" name="submit">Register</button>
        </center>
      </form>
      <center><a href="index.php">Home</a></center>

    </div> <!-- /container -->
    <?php include 'footer.php'; ?>
 </body>
</html>
