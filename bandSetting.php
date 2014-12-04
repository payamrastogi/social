<?php
    session_start();
    require 'dbHelper.php';

    $dbo = new db();

    if (isset($_SESSION['username'])){
        $username = $_SESSION['username'];
		$userId = $_SESSION['userId'];
	}
    else
        header('Location: ./login.php');

    $successMessage = '';
    $errorMessage = '';

    if (isset($_POST['about']) && $_POST['about'] != '')
    {
        $about = $_POST['about'];
        $dbo->updateBandInfo($userId, $about, 'bandDesc');
        $successMessage = $successMessage . 'Updated About Band ' ;
    }
    if (isset($_POST['firstName']) && $_POST['firstName'] != '')
    {
        $firstName = $_POST['firstName'];
        $dbo->updateBandInfo($userId, $firstName, 'bandName');
        $successMessage = $successMessage . '- Updated Band Name ';
    }
	if (isset($_POST['country']) && $_POST['country'] != '')
    {
        $country = $_POST['country'];
        $dbo->updateBandInfo($userId, $country, 'originCountry');
        $successMessage = $successMessage . '- Updated Country ';
    }
	if (isset($_POST['state']) && $_POST['state'] != '')
    {
        $state = $_POST['state'];
        $dbo->updateBandInfo($userId, $state, 'originCity');
        $successMessage = $successMessage . '- Updated State ';
    }
    if($successMessage == '' && $errorMessage == '' && isset($_POST['submit']))
        $blueMessage = 'Nothing was updated';
    if(isset($_POST['submit']) && $_POST['submit'] == 'next')
	  header('Location: ./addArtist.php'); 	
 ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Profile Settings</title>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
		<script type="text/javascript" src="http://bdhacker.sourceforge.net/javascript/countries/countries-2.0-min.js"></script>
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
                    <h3>Band Details</h3>
                </div>
                <!--<div class="span4 offset4">
                    <div class="control-group">
                        <div class="controls">
                            <br/><button type="submit" class="btn btn-primary btn-large" name="submit">Save</button>
                        </div>
                    </div> -->
                </div>
            </div>
            <div class="row-fluid" style="margin-top: 20px;margin-left: 200px">
                <div class="span4 well">
                    <h2>About Band</h2>
                        <div class="control-group">
                            <label class="control-label" for="textAreaAbout">About</label>
                            <div class="controls">
                              <textarea maxlength="15000" rows="12" class="input-xlarge" id="textAreaAbout" name="about" placeholder="What best describe you band?"></textarea>
                            </div>
                        </div>
                </div>

                <div class="span4 well">
                    <h2>Details</h2>
                    <h4>Not all fields required</h4>

                        <div class="control-group">
                            <label class="control-label" for="inputFirstName">Band Name</label>
                            <div class="controls">
                              <input id="inputFirstName" type="text" class="input-xlarge" name="firstName" placeholder="Band Name"/>
                            </div>
                        </div>
                        <!--<div class="control-group">
                            <label class="control-label" for="inputSurname">Surname</label>
                            <div class="controls">
                              <input id="inputSurname" type="text" class="input-xlarge" name="surname" placeholder="Surname"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="inputGender">Gender&nbsp&nbsp</label>
                            <div class="controls">
                              &nbsp&nbsp&nbsp&nbsp<input type="radio" name="gender" value="MALE">Male &nbsp&nbsp<input type="radio" name="gender" value="FEMALE">Female
                            </div>
                        </div> -->
						<div class="control-group">
							<label class="control-label" for="inputCountry">Country</label>
							<div class="controls">
								<select onchange="print_state('state',this.selectedIndex);" id="country" name = "country"></select>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="inputState">City/State:</label>
							<div class="controls">
								<select name ="state" id = "state"></select>
							</div>
						</div>
						<script type="text/javascript">
							print_country("country");
							$('#country').val('USA');
							print_state('state',$('#country')[0].selectedIndex);
						</script>						
                        <!-- <div class="control-group">
                            <label class="control-label" for="inputLocation">Location</label>
                            <div class="controls">
                              <input id="inputLocation" type="text" class="input-xlarge" name="location" placeholder="Location"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="inputDOB">Date of Birth</label>
                            <div class="controls">
                              <input id="inputDOB" type="date" class="input-xlarge" name="dob" placeholder="Date of Birth"/>
                            </div>
                        </div> -->
                </div>
                <!--<div class="span4 well">
                    <h2>Login Details</h2>
                    <h4>Not all fields required</h4>
                        <div class="control-group">
                            <label class="control-label" for="inputusername">New Username</label>
                            <div class="controls">
                              <input id="inputusername"class="input-xlarge" type="text" name="username" placeholder="New Username"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="inputPassword">New Password</label>
                            <div class="controls">
                              <input id="inputPassword" type="password" class="input-xlarge" name="password" placeholder="New Password"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="inputConfirmPassword">Confirm Password</label>
                            <div class="controls">
                              <input id="inputConfirmPassword"class="input-xlarge" type="password" name="confirmPassword" placeholder="Confirm Password"/>
                            </div>
                        </div> -->
                </div>
            </div>
			<div class="span4 offset4">
                    <div class="control-group" >
                        <div class="controls">
                            <br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="submit" class="btn btn-primary btn-large" value="save" name="submit">Save</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<button type="submit" class="btn btn-primary btn-large" value="next" name="submit">Next >></button>
                        </div>
                    </div>
            </div>
        </form>
        </div>

        <?php include 'footer.php'; ?>
    </body>
</html>