<?php
//TODO: make this visible to only logged in users
    session_start();
    require 'dbHelper.php';
	require_once("./source/activecalendar.php");
    $dbo = new db();
	$redirected = '';
	if(isset($_POST['btn_edit_genres']))
	{
		$user_name = $_SESSION['sess_user_name'];
		header('Location: ./editGenres.php?user_name='.$user_name);
	}

    if (!isset($_SESSION['sess_user_name']))
    {
        if (!isset($_SESSION['sess_user_name']))
        {
            $dbo->close();
            unset($dbo);
            header('Location: ./logout.php?');
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
        $user_name = $_SESSION['sess_user_name'];
		$user_id = $_SESSION['sess_user_id'];
		if(isset($_GET['redirected']))
		{
			$redirected = $_GET['redirected'];
			$_SESSION['redirected'] = $redirected;
		}
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

        $now = new DateTime('now');
        $birth = new DateTime($row['user_dob']);
        $interval = $now->diff($birth);
        $age = $interval->format('%Y');

        //$about = $row['about'];
        //$profilePic = $row['picture'];
        //$location = $row['location'];
        //$gender = $row['gender'];
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="./data/css/calendar.css">
        <title>Home</title>
    </head>
    <body>

    <?php include 'header.php'; ?>

    <div class="container" style="position: relative; top: 0px;">
		<ul class="nav nav-tabs">
			<li class="">
				<a href="home.php?user_name=<?php echo $user_name; ?>">Home</a>
			</li>
			<li class="active">
				<a href="userprofile.php">Profile</a>
			</li>
			<li class=""><a href="friends.php">Following</a></li>
			<li class=""><a href="fanof.php">Fan of</a></li>
			<li class=""><a href="list.php?user_name=<?php echo $user_name; ?>">My list</a></li>
			<li class=""><a href="searchConcert.php?user_name=<?php echo $user_name; ?>">Concerts</a></li>
			<?php if($_SESSION['sess_user_repo'] > 12)
			{?>
				<li class=""><a href="addConcertUser.php">Add Concerts</a></li>
			<?php } ?>
			<?php if(isset($_GET['redirected']))
			{?>
				<li class="active"><a href="#">View Profile</a></li>
			<?php } ?>
		</ul>
        <?php echo "<div style=\"font-size: 24px;\">$fullName</div>"; ?>
		<div class="row">
			<div class="col-sm-6 col-md-2">
				
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6 col-md-4">
				<div class="thumbnail">
					<!--<img data-src="holder.js/300x300" alt="...">-->
					<div class="caption">
						<p>Genres you like
						<?php 
								$query1 = $dbo->getUserGenres($user_id);
								while($row_usergenres = $query1->fetch(PDO::FETCH_ASSOC))
								{ ?>
									<ul>
										<li>
											<?php echo $row_usergenres['genre_name']; ?>
										</li>
									</ul>
							<?php   } ?>
							<form method="post" action="">
								<input type="hidden" id="hid_user_id" name="hid_user_id" value="<?php echo $user_id; ?>" />
								<button type="submit" class="btn btn-primary" name="btn_edit_genres" id="btn_edit_genres">Edit Genres</button>
							</form>
						</p>
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-4">
				<div class="thumbnail">
					<!--<img data-src="holder.js/300x300" alt="...">-->
					<div class="caption">
						
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-4">
				<!--<div class="thumbnail">-->
					<div class="row-fluid" style="margin-top: 0px;">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">
									
								</h3>
							</div>
							<div class="panel-body">
								<div class="col-lg-3">
									
								</div><!-- /.col-lg-3 -->
							</div><!-- panel-body -->
						</div><!-- Panel Profile Details -->
					</div>
				<!--</div>-->
			</div><!-- / row-fluid -->
		</div>
	</div>
    <?php include 'footer.php'; ?>
    </body>
</html>
