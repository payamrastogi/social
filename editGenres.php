<?php
//TODO: make this visible to only logged in users
    session_start();
    require 'dbHelper.php';
	require_once("./source/activecalendar.php");
    $dbo = new db();
	$redirected = '';
	if(isset($_POST['btn_submit']) && isset($_POST['sel_genres']))
	{
		$user_id = $_SESSION['sess_user_id'];
		$user_name = $_SESSION['sess_user_name'];
		$sel_genres = $_POST['sel_genres'];
		if(is_array($sel_genres))
		{
			$dbo->deleteUserGenres($user_id);
			foreach($sel_genres as $genre_id)
			{
				//echo "1231";
				$dbo->insertUserGenres($genre_id, $user_id);
			}
		}
		header('Location: ./userprofile.php');
	}
	if(isset($_POST['btn_cancel']))
	{
		header('Location: ./userprofile.php');
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
			<li class="">
				<a href="home.php">Home</a>
			</li>
			<li class="active">
				<a href="userprofile.php">Profile</a>
			</li>
			<li class=""><a href="friends.php">Following</a></li>
			<li class=""><a href="fanof.php">Fan of</a></li>
			<li class=""><a href="list.php">My list</a></li>
			<li class=""><a href="searchConcert.php">Concerts</a></li>
			<?php if($_SESSION['sess_user_repo'] > 12)
			{?>
				<li class=""><a href="addConcertUser.php">Add Concerts</a></li>
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
						<form method="POST" action="">
							<div class="input-group">
							<label for="sel_genres">Genre</label>
							<select name="sel_genres[]" multiple class="form-control" size="15">
							 <?php
								$query = $dbo->getParentGenre();
								while($row_genres = $query->fetch(PDO::FETCH_ASSOC))
								{?> 
							  <option value="<?php echo $row_genres['p_genre_id'];?>"><?php echo $row_genres['p_genre_name'];?></option>
							<?php
									$query5 = $dbo->getSubGenre($row_genres['p_genre_id']);
									while($row_genres = $query5->fetch(PDO::FETCH_ASSOC))
									{?>
									<option value="<?php echo $row_genres['s_genre_id'];?>"><?php echo $row_genres['s_genre_name'];?></option>
									<?php }
								}
							?>
							</select>
							</div>
							<br/>
							<div class="input-group">
								<button type="submit" class="btn btn-primary btn-large" name="btn_submit">Submit</button>&nbsp;
								<button type="submit" class="btn btn-primary btn-large" name="btn_cancel">Cancel</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
    <?php include 'footer.php'; ?>
    </body>
</html>
