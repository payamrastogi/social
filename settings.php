<?php
    session_start();
    require 'dbHelper.php';

    $dbo = new db();

    if (isset($_SESSION['sess_user_id']))
        $user_id = $_SESSION['sess_user_id'];
    else
        header('Location: ./login.php');

    $successMessage = '';
    $errorMessage = '';

    if (isset($_POST['about']) && $_POST['about'] != '')
    {
        $about = $_POST['user_description'];
        $dbo->updateProfileInfo($user_id, $about, 'user_description');
        $successMessage = $successMessage . '- Updated About Me ' ;
    }
    if (isset($_POST['txt_user_fname']) && $_POST['txt_user_fname'] != '')
    {
        $user_fname = $_POST['txt_user_fname'];
        $dbo->updateProfileInfo($user_id, $user_fname, 'user_fname');
        $successMessage = $successMessage . '- Updated First Name ';
    }
    if (isset($_POST['txt_user_lname']) && $_POST['txt_user_lname'] != '')
    {
        $user_lname = $_POST['txt_user_lname'];
        $dbo->updateProfileInfo($user_id, $user_lname, 'user_lname');
        $successMessage = $successMessage . '- Updated Last Name ';
    }
    if (isset($_POST['rd_user_gender']) && $_POST['rd_user_gender'] != '')
    {
        $user_gender = $_POST['rd_user_gender'];
        $dbo->updateProfileInfo($user_id, $user_gender, 'user_gender');
        $successMessage = $successMessage . '- Updated Gender ';
    }
    if (isset($_POST['txt_user_street']) && $_POST['txt_user_street'] != '')
    {
        $user_street = $_POST['txt_user_street'];
        $dbo->updateProfileInfo($user_id, $user_street, 'user_street');
        $successMessage = $successMessage . '- Updated Street ';
    }
    if (isset($_POST['cal_user_dob'])) {
        $user_dob = $_POST['cal_user_dob'];
        if ($user_dob != '')
        {
            $dbo->updateProfileInfo($user_id, $user_dob, 'user_dob');
            $successMessage = $successMessage . '- Updated Date of Birth ';
        }
    }
    if (isset($_POST['txt_user_password']) && isset($_POST['txt_user_conf_password']))
    {
        $user_password = $_POST['txt_user_password'];
        $user_conf_password = $_POST['txt_user_conf_password'];
		echo $user_password;
		echo $user_conf_password;

        if ($user_password != $user_conf_password)
            $errorMessage = $errorMessage ." - Passwords must match. ";
        elseif (strlen($user_password) < 6 && strlen($user_password) > 0) {
            $errorMessage = $errorMessage ." - Passwords must be 6 characters or more. ";
        }
        elseif(strlen($user_password) > 0)
        {
            $dbo->updateProfileInfo($user_id, $user_password, 'user_password');
            $successMessage = $successMessage . '- Updated Password ';
        }
    }
	if (isset($_POST['txt_user_email']) && $_POST['txt_user_email'] != '')
    {
        $user_email = $_POST['txt_user_email'];
        $dbo->updateProfileInfo($user_id, $user_email, 'user_email');
        $successMessage = $successMessage . '- Updated email ' ;
    }
    if($successMessage == '' && $errorMessage == '' && isset($_POST['submit']))
        $blueMessage = 'Nothing was updated'
 ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Profile Settings</title>
    </head>

    <body>
        <?php include 'header.php'; ?>
        <?php
         if (isset($errorMessage) && $errorMessage != '')
            echo "<div class=\"alert alert-error\" id=\"formError\">
                   <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
                   <strong>ERROR! </strong> $errorMessage
                 </div>";
         if (isset($successMessage) && $successMessage != '')
            echo "<div class=\"alert alert-success\" id=\"formSuccess\">
                   <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
                   <strong>Success! </strong> $successMessage
                 </div>";
        if (isset($blueMessage) && $blueMessage != '')
            echo "<div class=\"alert alert-info\" id=\"formError\">
                   <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
                   $blueMessage
                 </div>";
       ?>
        <div class="container">
            <form method="post" action="">
            <div class="row-fluid">
                <div class="span4">
                    <h3>Update your details</h3>
                </div>
                <div class="span4 offset4">
                    <div class="control-group">
                        <div class="controls">
                            <br/><button type="submit" class="btn btn-primary btn-large" name="submit">Save</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row-fluid" style="margin-top: 20px;">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Login Details</h3>
					</div>
					<div class="panel-body">
						<div class="col-lg-3">
							<div class="input-group">
								<input type="text" class="form-control" name="txt_user_password" placeholder="Password"/>
							</div><!-- /input-group -->
						</div><!-- /.col-lg-3 -->
						<div class="col-lg-3">
							<div class="input-group">
								<input type="text" class="form-control" name="txt_user_conf_password" placeholder="Confirm Password"/>
							</div><!-- /input-group -->
						</div><!-- /.col-lg-3 -->
					</div><!-- panel-body -->
					<div class="panel-body">
						<div class="col-lg-3">
							<div class="input-group">
								<span class="input-group-addon">
									<input type="checkbox">
								</span>
								<input type="email" class="form-control" name="txt_user_email" placeholder="Email"/>
							</div><!-- /input-group -->
						</div><!-- /.col-lg-3 -->
					</div><!-- panel-body -->
				</div><!-- Panel Profile Details -->
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Profile Details</h3>
					</div>
					<div class="panel-body">
						<div class="col-lg-3">
							<div class="input-group">
								<span class="input-group-addon">
									<input type="checkbox">
								</span>
								<input type="text" class="form-control" name="txt_user_fname" placeholder="First Name"/>
							</div><!-- /input-group -->
						</div><!-- /.col-lg-3 -->
						<div class="col-lg-3">
							<div class="input-group">
								<span class="input-group-addon">
									<input type="checkbox">
								</span>
								<input type="text" class="form-control" name="txt_user_lname" placeholder="Last Name"/>
							</div><!-- /input-group -->
						</div><!-- /.col-lg-3 -->
					</div><!-- panel-body -->
					<div class="panel-body">
						<div class="col-lg-6">
							<label class="radio-inline">
								<input type="radio" name="rd_user_gender" id="rd_user_gender_male" value="male"> Male
							</label>
							<label class="radio-inline">
								<input type="radio" name="rd_user_gender" id="rd_user_gender_female" value="female"> Female
							</label>
							<label class="radio-inline">
								<input type="radio" name="rd_user_gender" id="rd_user_gender_undisclosed" value="donotdisclose"> Do not disclose
							</label>
						</div><!-- /.col-lg-3 -->
					</div><!-- panel-body -->
					<div class="panel-body">
						<div class="col-lg-3">
							<label class="control-label" for="cal_user_dob">Date of Birth</label>
                            <div class="controls">
                              <input id="cal_user_dob" type="date" class="form-control" name="cal_user_dob" placeholder="Date of Birth"/>
                            </div>
						</div><!-- /.col-lg-3 -->
					</div><!-- panel-body -->
				</div><!-- Panel Profile Details -->
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Address</h3>
					</div>
					<div class="panel-body">
						<div class="col-lg-3">
							<div class="input-group">
								<span class="input-group-addon">
									<input type="checkbox">
								</span>
								<input type="text" class="form-control" name="txt_user_street" placeholder="Street"/>
							</div><!-- /input-group -->
						</div><!-- /.col-lg-3 -->
						<div class="col-lg-3">
							<div class="input-group">
								<span class="input-group-addon">
									<input type="checkbox">
								</span>
								<input type="text" class="form-control" name="txt_user_city" placeholder="City"/>
							</div><!-- /input-group -->
						</div><!-- /.col-lg-3 -->
					</div><!-- panel-body -->
					<div class="panel-body">
						<div class="col-lg-3">
							<div class="input-group">
								<span class="input-group-addon">
									<input type="checkbox">
								</span>
								<input type="text" class="form-control" name="txt_user_state" placeholder="State"/>
							</div><!-- /input-group -->
						</div><!-- /.col-lg-3 -->
						<div class="col-lg-3">
							<div class="input-group">
								<span class="input-group-addon">
									<input type="checkbox">
								</span>
								<input type="text" class="form-control" name="txt_user_country" placeholder="Country"/>
							</div><!-- /input-group -->
						</div><!-- /.col-lg-3 -->
					</div><!-- panel-body -->
					<div class="panel-body">
						<div class="col-lg-3">
							<div class="input-group">
								<span class="input-group-addon">
									<input type="checkbox">
								</span>
								<input type="text" class="form-control" name="txt_user_zipcode" placeholder="Zipcode"/>
							</div><!-- /input-group -->
						</div><!-- /.col-lg-3 -->
					</div><!-- panel body -->
				</div><!-- Panel Address Details -->
            </div>
        </form>
        </div>

        <?php include 'footer.php'; ?>
    </body>
</html>
