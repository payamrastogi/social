<?php
//TODO: make this visible to only logged in users
    session_start();
    require 'dbHelper.php';
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
		//echo "12".$user_id;
		if(isset($redirected))
		{
			$query = $dbo->getUserDetails($user_id);

			if ($row = $query->fetch(PDO::FETCH_ASSOC))
			{
				$fullName = $row['user_fname'] . ' ' . $row['user_lname'];
			}
		}
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo "$user_name"; ?></title>
    </head>
    <body>

    <?php include 'header.php'; ?>

    <div class="container" style="position: relative; top: 10px;">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#">Home</a>
			</li>
			<li>
				<a href="profile.php?user_name=<?php echo $user_name; ?>">Profile</a>
			</li>
			<li class=""><a href="friends.php">Following</a></li>
			<li class=""><a href="fanof.php">Fan of</a></li>
			<li class=""><a href="list.php?user_name=<?php echo $user_name; ?>">My list</a></li>
		</ul>
        <?php echo "<h3 style=\"font-size: 40px;\">$fullName</h3>"; ?>
		<div class="row">
			<div class="col-sm-6 col-md-4">
				<div class="thumbnail">
					<!--<img data-src="holder.js/300x300" alt="...">-->
					<div class="caption">
						
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
				<?php
					
					while ($row = $query->fetch(PDO::FETCH_ASSOC))
					{	
						$followed_uid = $row['followed_uid'];
						$query1 = $dbo->getUserDetails($followed_uid);
						$row_userdetails = $query1->fetch(PDO::FETCH_ASSOC);
						?>
					   <div class="row-fluid" style="margin-top: 20px;">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title">
										<a href="./profile.php?user_name=<?php echo $row_userdetails['user_name']?>&redirected=true">
											<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
											<?php echo $row_userdetails['user_fname'] . ' ' . $row_userdetails['user_lname']; ?>	
										</a>
										<a href="./friends.php?unfollow_uid=<?php echo $row_userdetails['user_id']?>&redirected=trueZ&unfollow_name=<?php echo $row_userdetails['user_name']?>">
											<span class="glyphicon glyphicon-minus" aria-hidden="true" name="gly_unfollow"></span>
										</a>
									</h3>
									
								</div>
								<div class="panel-body">
									<div class="col-lg-3">
										<div class="input-group">
											<p>Gender: <?php echo $row_userdetails['user_gender']; ?></p>
										</div><!-- /input-group -->
									</div><!-- /.col-lg-3 -->
								</div><!-- panel-body -->
							</div><!-- Panel Profile Details -->
						</div>
					<?php } ?>
			</div>
		</div><!-- / row-fluid -->
    </div>

    <?php include 'footer.php'; ?>
    </body>
</html>
