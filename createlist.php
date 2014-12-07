<?php
	session_start();
	require 'dbHelper.php';
    $dbo = new db();
	$user_id = '';
	$successMessage = '';
	if(isset($_POST['txt_list_name']) && isset($_POST['sel_genres']))
	{
		echo "hekkio";
		$list_name = $_POST['txt_list_name'];
		$user_id = $_SESSION['sess_user_id'];
		$user_name = $_SESSION['sess_user_name'];
		$sel_genres = $_POST['sel_genres'];
		$list_id = $dbo->createList($list_name, $user_id);
		if(is_array($sel_genres))
		{
			foreach($sel_genres as $genre_id)
			{
				//echo "hh";
				$dbo->insertGenreList($list_id, $genre_id);
			}
		}
		header('Location: ./list.php?user_name='.$user_name);
	}
	
	if (!isset($_GET['user_name']))
    {
        if (! isset($_SESSION['sess_user_name']))
        {
            header('Location: ./logout.php');
        }
        else
		{
            $user_name = $_SESSION['sess_user_name'];
			$user_id = $_SESSION['sess_user_id'];
		}
    }
    else
    {
        $user_name = $_GET['user_name'];
		$user_id = $_SESSION['sess_user_id'];
		$_SESSION['sess_user_name'] = $user_name;
    }
    $queryAll = $dbo->getRecommendedList($user_id, -1, -1); //Used for counting rows
	//echo "hello".$queryAll;
	
    $numResults = $queryAll->rowCount();
	//echo "2".$user_id;
	//echo "1".$numResults;
    $resultsPerPage = 2;
    $numPages = ceil($numResults / $resultsPerPage);

    if (isset($_GET['page']) && (int)$_GET['page'] <= $numPages && $_GET['page'] != '')
        $page = (int)$_GET['page'];
    else
        $page = 1;

    $startFrom = ($page - 1) * $resultsPerPage;

    $query = $dbo->getRecommendedList($user_id, $startFrom, $resultsPerPage);
 ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Fan of</title>
    </head>

    <body>
		<?php include 'header.php'; ?>
		<div class="container" style="position: relative; top: 40px;">
			<ul class="nav nav-tabs">
				<li class="">
				<a href="home.php?user_name=<?php echo $user_name; ?>">Home</a>
			</li>
				<li>
					<a href="profile.php?user_name=<?php echo $user_name; ?>">Profile</a>
				</li>
				<li class=""><a href="friends.php">Following</a></li>
				<li class=""><a href="fanof.php">Fan of</a></li>
				<li class="active"><a href="list.php?user_name=<?php echo $user_name; ?>">My list</a></li>
				<li class=""><a href="searchConcert.php?user_name=<?php echo $user_name; ?>">Concerts</a></li>
			</ul>
			<div class="container" style="position: relative; top: 10px;">
				<form class="navbar-form navbar-left" role="search" action="createlist.php" method="POST">
						<div class="input-group">
							<label for="sel_genres">List Name</label>
							<input type="text" class="form-control" placeholder="List Name" name="txt_list_name" required>
						</div>
						<br/>
						<br/>
						<div class="input-group">
							<label for="sel_genres">Genre</label>
							<select name="sel_genres[]" multiple class="form-control" required>
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
						<br/>
						<div class="input-group">
							<button type="submit" class="btn btn-primary btn-large">Create List</button>
						</div>
				</form>
			</div>
			<?php
				if (isset($successMessage) && $successMessage != '')
					echo "<div class=\"alert alert-success\" id=\"formSuccess\" style=\"position: relative; top: 10px;\">
					<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
					<strong>Success! </strong> $successMessage
					</div>";
			?>
        </div> <!-- /container -->
        <?php include 'footer.php'; ?>
    </body>
</html>
