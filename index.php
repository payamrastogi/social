<?php
$now = new DateTime('now');
$birth = new DateTime('91-11-14');
$interval = $now->diff($birth);
$age = $interval->format('%Y');
$error = '';
if (isset($_GET['error']))
  $error = $error . ' - ' . $_GET['error'];
$success = '';
if (isset($_GET['success'])) {
  $success = $success . ' - ' . $_GET['success'];
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home</title>
    </head>
    <body>
    <?php include 'header.php'; ?>
    <div class="container" style="margin-top: 20px;">
      <?php
            if (isset($error) && $error != '')
            echo "<div class=\"alert alert-error\" id=\"formError\">
                   <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
                   <strong>ERROR! </strong> $error
                 </div>";
         if (isset($success) && $success != '')
            echo "<div class=\"alert alert-success\" id=\"formSuccess\">
                   <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
                   <strong>Success! </strong> $success
                 </div>";
        ?>
        <div class="hero-unit">
          <h1>Welcome!</h1>
          <p>The open source social network</p>
          <p>
            <a href="login.php" class="btn btn-primary btn-large">
              Login
            </a>
            <a href="register.php"class="btn btn-primary btn-large">
              Register
            </a>
          </p>
        </div>

        <div class="row-fluid">

          <div class="span4">
            <h2>It's Free</h2>
            <p>Well, you hardly expected to have to pay for it, right?</p>
          </div>
          <div class="span4">
            <h2>It's Fun</h2>
            <p>We may only have 4 users, and those four users may all be me. But they're still fun.</p>
          </div>
          <div class="span4">
            <h2>Give It a Go</h2>
            <p>Or don't. Up to you.</p>
            <a href="register.php" class="btn btn-large">Register</a>
          </div>
        </div><!-- / row-fluid -->
    </div>
    <?php include 'footer.php';?>

    </body>
</html>
