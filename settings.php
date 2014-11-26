<?php
    session_start();
    require 'dbHelper.php';

    $dbo = new db();

    if (isset($_SESSION['username']))
        $username = $_SESSION['username'];
    else
        header('Location: ./login.php');

    $successMessage = '';
    $errorMessage = '';

    if (isset($_POST['about']) && $_POST['about'] != '')
    {
        $about = $_POST['about'];
        $dbo->updateProfileInfo($username, $about, 'about');
        $successMessage = $successMessage . 'Updated About Me ' ;
    }
    if (isset($_POST['firstName']) && $_POST['firstName'] != '')
    {
        $firstName = $_POST['firstName'];
        $dbo->updateProfileInfo($username, $firstName, 'firstName');
        $successMessage = $successMessage . '- Updated First Name ';
    }
    if (isset($_POST['surname']) && $_POST['surname'] != '')
    {
        $surname = $_POST['surname'];
        $dbo->updateProfileInfo($username, $surname, 'surname');
        $successMessage = $successMessage . '- Updated Surname ';
    }
    if (isset($_POST['gender']) && $_POST['gender'] != '')
    {
        $gender = $_POST['gender'];
        $dbo->updateProfileInfo($username, $gender, 'gender');
        $successMessage = $successMessage . '- Updated Gender ';
    }
    if (isset($_POST['location']) && $_POST['location'] != '')
    {
        $location = $_POST['location'];
        $dbo->updateProfileInfo($username, $location, 'location');
        $successMessage = $successMessage . '- Updated Location ';
    }
    if (isset($_POST['dob'])) {
        $dob = $_POST['dob'];
        if ($dob != '')
        {
            $dbo->updateProfileInfo($username, $dob, 'dob');
            $successMessage = $successMessage . '- Updated Date of Birth ';
        }
    }
    if (isset($_POST['password']) && isset($_POST['confirmPassword']))
    {
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];

        if ($password != $confirmPassword)
            $errorMessage = $errorMessage ." - Passwords must match. ";
        elseif (strlen($password) < 6 && strlen($password) > 0) {
            $errorMessage = $errorMessage ." - Passwords must be 6 characters or more. ";
        }
        elseif(strlen($password) > 0)
        {
            $dbo->updateProfileInfo($username, $password, 'password');
            $successMessage = $successMessage . '- Updated Password ';
        }
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
                <div class="span4 well">
                    <h2>About Me</h2>
                        <div class="control-group">
                            <label class="control-label" for="textAreaAbout">About</label>
                            <div class="controls">
                              <textarea maxlength="15000" rows="12" class="input-xlarge" id="textAreaAbout" name="about" placeholder="About Me"></textarea>
                            </div>
                        </div>
                </div>

                <div class="span4 well">
                    <h2>Details</h2>
                    <h4>Not all fields required</h4>

                        <div class="control-group">
                            <label class="control-label" for="inputFirstName">First Name</label>
                            <div class="controls">
                              <input id="inputFirstName" type="text" class="input-xlarge" name="firstName" placeholder="First Name"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="inputSurname">Surname</label>
                            <div class="controls">
                              <input id="inputSurname" type="text" class="input-xlarge" name="surname" placeholder="Surname"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="inputGender">Gender</label>
                            <div class="controls">
                              <input id="inputGender" type="text" class="input-xlarge" name="gender" placeholder="Gender"/>
                            </div>
                        </div>
                        <div class="control-group">
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
                        </div>
                </div>
                <div class="span4 well">
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
                        </div>
                </div>
            </div>
        </form>
        </div>

        <?php include 'footer.php'; ?>
    </body>
</html>
