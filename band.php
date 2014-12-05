<?php
//TODO: make this visible to only logged in users
    session_start();
    require 'dbHelper.php';
    $dbo = new db();
	$redirected = '';

    if (!isset($_GET['band_name']))
    {
        if (!isset($_SESSION['sess_user_name']))
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
        $band_name = $_GET['band_name'];
		$user_id = $_SESSION['sess_user_id'];
		if(isset($_GET['redirected']))
		{
			$redirected = $_GET['redirected'];
			$_SESSION['redirected'] = $redirected;
		}
		//echo "12".$user_id;
		if(isset($redirected))
		{
			$query = $dbo->getBandId($band_name);
			if ($row = $query->fetch(PDO::FETCH_ASSOC))
			{
				$band_id = $row['band_id'];
				//echo "Hello".$user_id;
			}
		}
		//echo $user_id;
    }
    $query = $dbo->getBandDetails($band_id);

    if ($row = $query->fetch(PDO::FETCH_ASSOC))
    {
        $band_name = $row['band_name'];
		$band_members = $row['band_members'];
		$band_website = $row['band_website'];
		$band_id = $row['band_id'];
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
        <title><?php echo "$band_name"; ?></title>
    </head>
    <body>

    <?php include 'header.php'; ?>

    <div class="container" style="position: relative; top: 10px;">
        <?php echo "<h3 style=\"font-size: 40px;\">$band_name</h3>"; ?>
		<div class="row">
			<div class="col-sm-6 col-md-4">
				<div class="thumbnail">
					<!--<img data-src="holder.js/300x300" alt="...">-->
					<div class="caption">
						<p>#of Members: <?php echo $band_members; ?></p>
						<p>Website: <a href="<?php echo $band_website; ?>"><?php echo $band_website; ?></a>
						<?php 
							$query1 = $dbo->getBandMembers($band_id);
							?><p> <?php echo "Members:";?></p>
							<?php while($row_members = $query1->fetch(PDO::FETCH_ASSOC))
							{?>
								<ul>
									<li>
										<a href="./profile.php?user_name=<?php echo $row_members['user_name']?>&redirected=true">
											<?php echo $row_members['user_fname']." ".$row_members['user_lname'] ?>
										</a>
									</li>
								</ul>
							<?php } ?>
							<p>Followers: <?php $query2 = $dbo->getNumberOfFans($band_id);
											$row_fans = $query2->fetch(PDO::FETCH_ASSOC);
											echo $row_fans['total_fans'];	?></p>
							<p>Band Genres: <?php $query3 = $dbo->getBandGenres($band_id);
											while($row_bandgenres = $query3->fetch(PDO::FETCH_ASSOC))
											{ ?>
											<ul>
												<li>
													<?php echo $row_bandgenres['genre_name']; ?>
												</li>
											</ul>
										<?php } ?></p>
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
						<h3>Upcoming Events</h3>
						<?php
							$query4 = $dbo->getPerformingConcerts($band_id);
							while ($row_performing = $query4->fetch(PDO::FETCH_ASSOC))
							{	
								?>
							   <div class="row-fluid" style="margin-top: 20px;">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h3 class="panel-title">
												<a href="./concert.php?concert_id=<?php echo $row_performing['concert_id']?>&redirected=true">
													<span class="glyphicon glyphicon-headphones" aria-hidden="true"></span>
													<?php echo $row_performing['concert_name']; ?>	
												</a>
											</h3>
											
										</div>
										<div class="panel-body">
											<p>Start Date: <?php echo $row_performing['concert_sdate']; ?></p>
											<p>Description: <?php echo $row_performing['concert_description']; ?></p>
										</div><!-- panel-body -->
									</div><!-- Panel Profile Details -->
								</div>
							<?php } ?>
						</br>
					</div>
				</div>
			</div>
		</div><!-- / row-fluid -->
    </div>

    <?php include 'footer.php'; ?>
    </body>
</html>
