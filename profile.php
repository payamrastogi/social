<?php
//TODO: make this visible to only logged in users
    session_start();
    require 'dbHelper.php';
    $dbo = new db();
	$redirected = '';
	$successMessage ='';
	if(isset($_POST['txt_review']) && isset($_POST['btn_submit_post']) && $_POST['txt_review'] != '')
	{
		$to_user_name = $_GET['user_name'];
		$query_review = $dbo->getUserId($to_user_name);
		//echo $to_user_name;
		$row_to_user_id = $query_review->fetch(PDO::FETCH_ASSOC);
		$to_user_id = $row_to_user_id['user_id'];
		//echo $to_user_id;
		$review = $_POST['txt_review'];
		//echo $review;
		$by_user_id = $_SESSION['sess_user_id'];
		//echo $by_user_id;
        $dbo->updateUserReview($by_user_id, $to_user_id, $review);
        $successMessage = $successMessage . '- message saved ' ;
	}
	unset($_POST['txt_review']);
	unset($_POST['btn_submit_post']);
    if (! isset($_GET['user_name']))
    {
        if (! isset($_SESSION['sess_user_name']))
        {
            $dbo->close();
            unset($dbo);
            header('Location: ./logout.php');
        }
        else
        {
            $dbo->close();
            unset($dbo);
            $user_name = $_SESSION['sess_user_name'];
        }
    }
    else
    {
        $user_name = $_GET['user_name'];
		$user_id = $_SESSION['sess_user_id'];
		if(isset($_GET['redirected']))
		{
			$redirected = $_GET['redirected'];
			$_SESSION['redirected'] = $redirected;
		}
		//echo "12".$user_id;
		if(isset($redirected))
		{
			$query = $dbo->getUserId($user_name);
			if ($row = $query->fetch(PDO::FETCH_ASSOC))
			{
				$user_id = $row['user_id'];
				//echo "Hello".$user_id;
			}
		}
		//echo $user_id;
    }
    $query = $dbo->getUserDetails($user_id);

    if ($row = $query->fetch(PDO::FETCH_ASSOC))
    {
        $fullName = $row['user_fname'] . ' ' . $row['user_lname'];
		$imagePath = $row['user_image'];
        $now = new DateTime('now');
        $birth = new DateTime($row['user_dob']);
        $interval = $now->diff($birth);
        $age = $interval->format('%Y');

        $birthdate = $row['user_dob'];
        $location = $row['user_country'];
        $gender = $row['user_gender'];
		$email = $row['user_email'];
    }
	
	$query = $dbo->getUserGenres($user_id);
	if ($row = $query->fetch(PDO::FETCH_ASSOC))
	   {
	    $genre = $row['genre_name'];
	   }

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo "$fullName"; ?></title>
    </head>
    <body>

    <?php include 'header.php'; ?>

    <div class="container" style="position: relative; top: 40px;">
        <ul class="nav nav-tabs">
		<li class="">
				<a href="home.php">Home</a>
			</li>
        <li class="active">
          <a href="profile.php">Profile</a>
        </li>
		<li class=""><a href="friends.php">Following</a></li>
		<li class=""><a href="fanof.php">Fan of</a></li>
        <li><a href="list.php">My list</a></li>
		<li class=""><a href="searchConcert.php">Concerts</a></li>
		<?php if($_SESSION['sess_user_repo'] > 12)
					{?>
						<li class=""><a href="addConcertUser.php">Add Concerts</a></li>
				<?php } ?>
		</ul>
        <?php echo "<h1 style=\"font-size: 60px;\">$fullName</h1>"; ?>
        <div class="row-fluid" style="margin-top: 20px;">
            <div class="span4 well">
                <img src="<?php echo"$imagePath" ?>" width="300" height="300">
            </div>
			<div class="span8" style="padding-left:40px;">
                <div class="row-fluid">
                    <div id = "columns">
					<div class="span4">
                        <h2>About Me</h2>
                        <?php
                            echo "<p>Age: $age</p>";
							echo "<p>DOB: $birthdate</p>";
                            echo "<p>Gender: $gender</p>";
							echo "<p>Email: $email</p>";
                            echo "<p>Location: $location</p>";
							echo "<p>Genre:$genre</p>";
							if($user_name == $_SESSION['sess_user_name'])
                                echo "<a class=\"btn\" href=\"./settings.php#about\">Edit</a>";
                        ?>
                    </div>
                </div>
            </div>
							<form method="POST" action="">
							<div class="row-fluid" style="margin-top: 20px;">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h3 class="panel-title">
											<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
												Post a message
											</h3>
										</div>
										<div class="panel-body">
											<textarea class="form-control" rows="3" id="txt_review" name="txt_review"></textarea>
											<button type="submit" class="btn btn-primary" name="btn_submit_post" id="btn_submit">Submit</button>
										</div><!-- panel-body -->
									</div><!-- Panel Profile Details -->
								</div>
							</form>
        </div>
					<?php 		
							$to_user_name = $_GET['user_name'];
							$query = $dbo->getUserId($to_user_name);
							$row_user_id = $query->fetch(PDO::FETCH_ASSOC);
							$to_user_id = $row_user_id['user_id'];
							$query3 = $dbo->getUserReviews($to_user_id);
							while ($row_reviews = $query3->fetch(PDO::FETCH_ASSOC))
							{	
								$user_reviews = $row_reviews['user_review'];
								$by_user_id = $_SESSION['sess_user_id']; 
								$query4 = $dbo->getUserDetails($by_user_id);
								$row_userdetails = $query4->fetch(PDO::FETCH_ASSOC)
		
								?>
							   <div class="row-fluid" style="margin-top: 20px;">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h3 class="panel-title">
												<a href="./profile.php?user_name=<?php echo $row_userdetails['user_name']?>&redirected=true">
													<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
													<?php echo $row_userdetails['user_fname'] . ' ' . $row_userdetails['user_lname']; ?>	
												</a>
											</h3>
										</div>
										<div class="panel-body">
											<p><?php echo $row_reviews['user_review']?></p>
										</div><!-- panel-body -->
									</div><!-- Panel Profile Details -->
								</div>
							<?php } ?>
    </div>

    <?php include 'footer.php'; ?>
    </body>
</html>
