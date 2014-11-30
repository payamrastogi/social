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
        <div class="jumbotron">
          <h1>Welcome!</h1>
          <p>Your Online Music Community</p>
          <p>
            <a href="login.php" class="btn btn-primary btn-large">
              Login
            </a>
            <a href="register.php"class="btn btn-primary btn-large">
              Register
            </a>
          </p>
        </div>
		<div class="row">
			<div class="col-sm-6 col-md-4">
				<div class="thumbnail">
					<!--<img data-src="holder.js/300x300" alt="...">-->
					<div class="caption">
						<h3>It's Free</h3>
						<p>Well, you hardly expected to have to pay for it, right?</p>
						<!--<p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>-->
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-4">
				<div class="thumbnail">
					<!--<img data-src="holder.js/300x300" alt="...">-->
					<div class="caption">
						<h3>It's Fun</h3>
						<p>We may only have 4 users, and those four users may all be me. But they're still fun.</p>
						<!--<p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>-->
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-4">
				<div class="thumbnail">
					<!--<img data-src="holder.js/300x300" alt="...">-->
					<div class="caption">
						<h3>Give It a Go</h3>
						<p>Or don't. Up to you.</p>
						</br>
					</div>
				</div>
			</div>
		</div><!-- / row-fluid -->
    </div>
    <?php include 'footer.php';?>
    </body>
</html>
