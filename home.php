<?php
//TODO: make this visible to only logged in users
    session_start();
    require 'dbHelper.php';
	require_once("./source/activecalendar.php");
    $dbo = new db();
	$redirected = '';

    if (!isset($_GET['user_name']))
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
        $user_name = $_GET['user_name'];
		$user_id = $_SESSION['sess_user_id'];
		if(isset($_GET['redirected']))
		{
			$redirected = $_GET['redirected'];
			$_SESSION['redirected'] = $redirected;
		}
		if(isset($redirected))
		{
			$query = $dbo->getUserDetails($user_id);

			if ($row = $query->fetch(PDO::FETCH_ASSOC))
			{
				$fullName = $row['user_fname'] . ' ' . $row['user_lname'];
				$last_accessed = $row['user_laccess'];
				$trust_score = $row['user_repo'];
			}
		}
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
			<li class="active">
				<a href="home.php?user_name=<?php echo $user_name; ?>">Home</a>
			</li>
			<li>
				<a href="profile.php?user_name=<?php echo $user_name; ?>">Profile</a>
			</li>
			<li class=""><a href="friends.php">Following</a></li>
			<li class=""><a href="fanof.php">Fan of</a></li>
			<li class=""><a href="list.php?user_name=<?php echo $user_name; ?>">My list</a></li>
			<li class=""><a href="searchConcert.php?user_name=<?php echo $user_name; ?>">Concerts</a></li>
		</ul>
        <?php echo "<div style=\"font-size: 40px;\">$fullName</div>"; ?>
		<p>Last accessed @ <?php echo $last_accessed; ?></p>
		<?php echo $_SESSION['sess_user_time']; ?>
		<div class="row">
			<div class="col-sm-6 col-md-2">
				<div class="progress">
					<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="<?php echo $trust_score; ?>" aria-valuemin="0" aria-valuemax="20" style="width: <?php echo ($trust_score/20)*100; ?>%;">
						Trust Score: <?php echo $trust_score; ?>
					</div>
				</div>
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
						</p>
						<p> You are fan of
						<ul>
						<?php 
							$query1 = $dbo->getFanOf($user_id,-1, -1);
							for($i=0;$i<2;$i++)
							{
								$row_band_id = $query1->fetch(PDO::FETCH_ASSOC);
								$query2 = $dbo->getBandDetails($row_band_id['band_id']);
								$row_banddetails = $query2->fetch(PDO::FETCH_ASSOC);
								?>

										<li>
											<a href="./band.php?band_name=<?php echo $row_banddetails['band_name']?>&redirected=true">
												<?php echo $row_banddetails['band_name']; ?>	
											</a>
										</li>
							<?php   } ?>
						</ul>
						<a href="fanof.php">More...</a>
						</p>
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-4">
				<div class="thumbnail">
					<!--<img data-src="holder.js/300x300" alt="...">-->
					<div class="caption">
						<h3>Bands Performing</h3>
						<p>
							
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-4">
				<!--<div class="thumbnail">-->
					<div class="row-fluid" style="margin-top: 0px;">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">
									This month
								</h3>
							</div>
							<div class="panel-body">
								<div class="col-lg-3">
									<?php 
										$year = date('Y');
										//echo $year;
										$month = date('m');
										//echo $month;
										$cal = new activeCalendar($year,$month);
										//echo $cal->showMonth();
										$query2 = $dbo->getThisMonthConcerts($user_id);
										while($row_concerts = $query2->fetch(PDO::FETCH_ASSOC))
										{
											$eventDay = $row_concerts['concert_sdate']."";
											$eventDay = explode('-', $eventDay)[2];
											//echo $eventDay;
											$eventUrl = "./concert.php?concert_id=".$row_concerts['concert_id'];
											$cal->setEventContent($year,$month,$eventDay,$row_concerts['concert_name'],$eventUrl);
										}
										echo $cal->showMonth();
									?>
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
