<?php
	session_start();
	require 'dbHelper.php';
    $dbo = new db();
	$user_id = '';
	$successMessage = '';
	if(isset($_GET['unfan_band_id']))
	{
		$unfan_band_id = $_GET['unfan_band_id'];
		$user_id = $_SESSION['sess_user_id'];
		$unfan_band_name = $_GET['unfan_band_name'];
		$queryUnfanBand = $dbo->unfanBand($unfan_band_id, $user_id);
		$successMessage = $successMessage . '- Unfollow '.$unfan_band_name;
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
    }
    $queryAll = $dbo->getFanOf($user_id, -1, -1); //Used for counting rows
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

    $query = $dbo->getFanOf($user_id, $startFrom, $resultsPerPage);
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
				<li class="active"><a href="fanof.php">Fan of</a></li>
				<li class=""><a href="list.php?user_name=<?php echo $user_name; ?>">My list</a></li>
				<li class=""><a href="searchConcert.php?user_name=<?php echo $user_name; ?>">Concerts</a></li>
			</ul>
			<div class="container" style="position: relative; top: 10px;">
				<form class="navbar-form navbar-left" role="search" action="bandSearch.php" method="get">
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Search bands" name="searchBandName">
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
				$band_id = $row['band_id'];
				$query1 = $dbo->getBandDetails($band_id);
				$row_banddetails = $query1->fetch(PDO::FETCH_ASSOC);
				?>
				<div class="row-fluid" style="margin-top: 20px;">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">
								<a href="./band.php?band_name=<?php echo $row_banddetails['band_name']?>&redirected=true">
									<span class="glyphicon glyphicon-music" aria-hidden="true"></span>
									<?php echo $row_banddetails['band_name']; ?>	
								</a>
								<a href="./fanof.php?unfan_band_id=<?php echo $row_banddetails['band_id']?>&redirected=true&unfan_band_name=<?php echo $row_banddetails['band_name']?>">
									<span class="glyphicon glyphicon-minus" aria-hidden="true" name="gly_unfollow"></span>
								</a>
							</h3>
							
						</div>
						<div class="panel-body">
							<div class="col-lg-3">
								<div class="input-group">
									<p>Website: <a href="<?php echo $row_banddetails['band_website']; ?>" target="_blank"><?php echo $row_banddetails['band_website']; ?></p></a>
								</div><!-- /input-group -->
							</div><!-- /.col-lg-3 -->
						</div><!-- panel-body -->
						<div class="panel-body">
							<div class="col-lg-3">
								<div class="input-group">
									<p>Number of members: <?php echo $row_banddetails['band_members']; ?></p>
								</div><!-- /input-group -->
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
                    echo "<li><a href='./fanof.php?page=$previousPage'>Prev</a></li>";
                }


                for ($i=1; $i < $numPages+1; $i++)
                {
                    $theClass = '';
                    if($i == $page)
                        $theClass = 'active';
                    echo "<li class='$theClass'><a href='./fanof.php?page=$i'>$i</a></li>";
                }

                if ($page == $numPages)
                    echo "<li class='disabled'><a href='#'>Next</a></li>";
                else
                {
                    $nextPage = $page+1;
                    echo "<li><a href='./fanof.php?page=$nextPage'>Next</a></li>";
                }

                ?>
              </ul>
            </nav>
        </div> <!-- /container -->
        <?php include 'footer.php'; ?>
    </body>
</html>
