<?php
	session_start();
	require 'dbHelper.php';
	$dbo = new db();
	$user_id = '';
	$successMessage = '';
	if(isset($_GET['fan_band_id']))
	{
		$fan_band_id = $_GET['fan_band_id'];
		$user_id = $_SESSION['sess_user_id'];
		$fan_band_name = $_GET['fan_band_name'];
		$queryfollow = $dbo->becomeFanOf($user_id, $fan_band_id);
		$successMessage = $successMessage . '- You have become fan of '.$fan_band_name;
	}
    if (isset($_GET['searchBandName']))
    {
        $searchBandName = $_GET['searchBandName'];
        $queryAll = $dbo->searchBands($searchBandName, -1, -1); //Used for counting rows
		//echo "hello".$queryAll;
        $numResults = $queryAll->rowCount();

        $resultsPerPage = 2;
        $numPages = ceil($numResults / $resultsPerPage);

        if (isset($_GET['page']) && (int)$_GET['page'] <= $numPages && $_GET['page'] != '')
            $page = (int)$_GET['page'];
        else
            $page = 1;

        $startFrom = ($page - 1) * $resultsPerPage;

        $query = $dbo->searchBands($searchBandName, $startFrom, $resultsPerPage);

    }
 ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Search</title>
    </head>

    <body>
        <?php include 'header.php'; ?>
        <div class="container" style="position: relative; top: 40px;">
			<ul class="nav nav-tabs">
				<li class="">
				<a href="home.php?user_name=<?php echo $user_name; ?>">Home</a>
				</li>
				<li>
					<a href="userprofile.php">Profile</a>
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
			<?php if(!isset($_GET['fan_band_id']))
			{ ?>
				<div class="container" style="position: relative; top: 20px;">
					<p>Your search for <?php
					$plural = $numResults != 1 ? "results" : "result";
					echo "'$searchBandName' returned ". $numResults . ' ' . $plural ?></p>
					<p>Showing <?php echo $resultsPerPage ?> results per page</p>
				</div>
			<?php } ?>
			<?php
				if (isset($successMessage) && $successMessage != '')
					echo "<div class=\"alert alert-success\" id=\"formSuccess\" style=\"position: relative; top: 10px;\">
					<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
					<strong>Success! </strong> $successMessage
					</div>";
			?>
			<?php
            while ($row = $query->fetch(PDO::FETCH_ASSOC))
            {?>
                <a href="./band.php?band_name=<?php echo $row['band_name']?>&redirected=true">
                    <div class="row-fluid" style="margin-top: 20px;">
                       <div class="row-fluid" style="margin-top: 20px;">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title">
										<span class="glyphicon glyphicon-music" aria-hidden="true"></span>
										<a href="./band.php?band_name=<?php echo $row['band_name']?>&redirected=true">
											<?php echo $row['band_name']; ?>
										</a>
										<a href="./bandSearch.php?fan_band_id=<?php echo $row['band_id']?>&redirected=true&fan_band_name=<?php echo $row['band_name']?>&searchBandName=<?php echo $searchBandName; ?>">
											<span class="glyphicon glyphicon-plus" aria-hidden="true" name="gly_fan"></span>
										</a>
									</h3>
								</div>
								<div class="panel-body">
									<div class="col-lg-3">
										<div class="input-group">
											<p>Website: <a href="<?php echo $row['band_website'];?>" target="_blank"><?php echo $row['band_website'];?></a></p>
										</div><!-- /input-group -->
									</div><!-- /.col-lg-3 -->
								</div><!-- panel-body -->
							</div><!-- Panel Profile Details -->
						</div>
                    </div>
                </a>
            <?php } ?>
            <nav>
              <ul class="pagination">

                <?php
                if ($page == 1)
                    echo "<li class='disabled'><a href='#'>Prev</a></li>";
                else
                {
                    $previousPage = $page - 1;
                    echo "<li><a href='./bandSearch.php?page=$previousPage&searchBandName=$searchBandName'>Prev</a></li>";
                }


                for ($i=1; $i < $numPages+1; $i++)
                {
                    $theClass = '';
                    if($i == $page)
                        $theClass = 'active';
                    echo "<li class='$theClass'><a href='./bandSearch.php?page=$i&searchBandName=$searchBandName'>$i</a></li>";
                }

                if ($page == $numPages)
                    echo "<li class='disabled'><a href='#'>Next</a></li>";
                else
                {
                    $nextPage = $page+1;
                    echo "<li><a href='./bandSearch.php?page=$nextPage&searchBandName=$searchBandName'>Next</a></li>";
                }

                ?>
              </ul>
            </nav>
        </div> <!-- /container -->
        <?php include 'footer.php'; ?>
    </body>
</html>
