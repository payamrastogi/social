<?php
	session_start();
	require 'dbHelper.php';
    $dbo = new db();
	$user_id = '';
	$successMessage = '';
	if(isset($_GET['delete_list_id']) && isset($_GET['list_name']))
	{
		$list_id = $_GET['delete_list_id'];
		$user_id = $_SESSION['sess_user_id'];
		$list_name = $_GET['list_name'];
		$querydelete_list = $dbo->deleteList($list_id, $user_id);
		$successMessage = $successMessage . '- Deleted '.$list_name;
	}
	
	if (!isset($_SESSION['sess_user_name']))
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
        $user_name = $_SESSION['sess_user_name'];
		$user_id = $_SESSION['sess_user_id'];
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
				<a href="home.php">Home</a>
			</li>
				<li>
					<a href="userprofile.php">Profile</a>
				</li>
				<li class=""><a href="friends.php">Following</a></li>
				<li class=""><a href="fanof.php">Fan of</a></li>
				<li class="active"><a href="list.php">My list</a></li>
				<li class=""><a href="searchConcert.php">Concerts</a></li>
				<?php if($_SESSION['sess_user_repo'] > 12)
			{?>
			<li class=""><a href="addConcertUser.php">Add Concerts</a></li>
			<?php } ?>
			</ul>
			<div class="container" style="position: relative; top: 10px;">
				<form class="navbar-form navbar-left" role="search" action="createlist.php" method="get">
						<div class="form-group">
							<button type="submit" class="btn btn-primary btn-large">Create New List</button>
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
           <?php
			
            while ($row = $query->fetch(PDO::FETCH_ASSOC))
            {	
				$list_id = $row['list_id'];
				$list_name = $row['list_name'];
				$query1 = $dbo->getListConcerts($list_id);
				?>
				<div class="row-fluid" style="margin-top: 20px;">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">
									<span class="glyphicon glyphicon-music" aria-hidden="true"></span>
									<?php echo $list_name ?>	
								<a href="./list.php?delete_list_id=<?php echo $list_id; ?>&redirected=true&list_name=<?php echo $list_name;?>">
									<span class="glyphicon glyphicon-minus" aria-hidden="true" name="gly_unfollow"></span>
								</a>
							</h3>
							
						</div>
						<div class="panel-body">
							<div class="col-lg-3">
								<div class="input-group">
									<?php 
										while($row_concerts = $query1->fetch(PDO::FETCH_ASSOC))
										{
											$concert_id = $row_concerts['concert_id'];
											$query2 = $dbo->getConcertDetails($concert_id);
											$row_concertdetails = $query2->fetch(PDO::FETCH_ASSOC);
									?>
										<p><a href="./concert.php?concert_id=<?php echo $concert_id; ?>&redirected=true">
										<?php echo $row_concertdetails['concert_name']; ?></a></p>
								<?php } ?>
								</div>
								<div class="input-group">
									<a href="./addConcertToList.php?list_id=<?php echo $list_id; ?>&redirected=true">
									<span class="glyphicon glyphicon-plus" aria-hidden="true" name="gly_unfollow">Concert</span>
								</a>
								</div>
								<!-- /input-group -->
							</div><!-- /.col-lg-3 -->
						</div><!-- panel-body -->
					</div><!-- Panel Profile Details -->
				</div>
            <?php } ?>
            <nav>
              <ul class="pagination">

                <?php
                if ($page == 1)
                    echo "<li class='disabled'><a href='#'>Prev</a></li>";
                else
                {
                    $previousPage = $page - 1;
                    echo "<li><a href='./list.php?page=$previousPage'>Prev</a></li>";
                }

				//echo $page;
				//echo $numPages;
                for ($i=1; $i < $numPages+1; $i++)
                {
                    $theClass = '';
                    if($i == $page)
                        $theClass = 'active';
                    echo "<li class='$theClass'><a href='./list.php?page=$i'>$i</a></li>";
                }

                if ($page == $numPages)
                    echo "<li class='disabled'><a href='#'>Next</a></li>";
                else
                {
                    $nextPage = $page+1;
                    echo "<li><a href='./list.php?page=$nextPage'>Next</a></li>";
                }

                ?>
              </ul>
            </nav>
        </div> <!-- /container -->
        <?php include 'footer.php'; ?>
    </body>
</html>
