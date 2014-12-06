<?php
	session_start();
	require 'dbHelper.php';
	$dbo = new db();
	$user_id = '';
	$successMessage = '';
	$errorMessage = '';
	if(isset($_POST['btn_add']))
	{	
		echo "hello1";
		$user_id = $_POST['hid_user_id'];
		$band_id = $_SESSION['sess_band_id'];
		$user_name = $_POST['hid_user_name'];
		$inband_position = "band member";
		//echo $_POST['checkbox1'];
		if(isset($_POST['checkbox1']) && $_POST['checkbox1']=='1')
		{
			echo "hello";
			$inband_position = "band manager";
		}
		//echo $user_name;
		echo $inband_position;
		$query = $dbo->addBandMember($band_id, $user_id, $inband_position);
		//echo "jkjk".$query;
		if($query)
		{
			$successMessage = $successMessage . '- Added - '.$user_name;
		}
		else
		{
			$errorMessage = $errorMessage . '- Already member';
		}
	}
    if (isset($_GET['searchBandMember']))
    {
        $searchBandMember = $_GET['searchBandMember'];
		$band_user_name = $_SESSION['sess_band_user_name'];
        $queryAll = $dbo->searchMembers($searchBandMember, -1, -1); //Used for counting rows
		//echo "hello".$queryAll;
        $numResults = $queryAll->rowCount();

        $resultsPerPage = 2;
        $numPages = ceil($numResults / $resultsPerPage);

        if (isset($_GET['page']) && (int)$_GET['page'] <= $numPages && $_GET['page'] != '')
            $page = (int)$_GET['page'];
        else
            $page = 1;

        $startFrom = ($page - 1) * $resultsPerPage;

        $query = $dbo->searchMembers($searchBandMember, $startFrom, $resultsPerPage);

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
					<a href="bandHome.php?band_user_name=<?php echo $band_user_name; ?>">Home</a>
				</li>
				<?php 
					if( isset($band_user_name) || (isset($inband_position) && $inband_position = 'band manager'))
					{?>
						<li class="active"><a href="addMember.php">Add Member</a></li>
						<li class=""><a href="addConcert.php">Add Concert</a></li>
				<?php }?>
			</ul>
			<div class="container" style="position: relative; top: 10px;">
				<form class="navbar-form navbar-left" role="search" action="memberSearch.php" method="get">
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Search bands" name="searchBandMember">
						</div>
				</form>
			</div>
			<div class="container" style="position: relative; top: 20px;">
				<p>Your search for <?php
				$plural = $numResults != 1 ? "results" : "result";
				echo "'$searchBandMember' returned ". $numResults . ' ' . $plural ?></p>
				<p>Showing <?php echo $resultsPerPage ?> results per page</p>
			</div>
			<?php
				if (isset($successMessage) && $successMessage != '')
					echo "<div class=\"alert alert-success\" id=\"formSuccess\" style=\"position: relative; top: 10px;\">
					<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
					<strong>Success! </strong> $successMessage
					</div>";
				if (isset($errorMessage) && $errorMessage != '')
					echo "<div class=\"alert alert-error\" id=\"formError\">
					<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
					<strong>ERROR! </strong> $errorMessage
					</div>";
			?>
			<?php
            while ($row = $query->fetch(PDO::FETCH_ASSOC))
            {?>
                <a href="./profile.php?user_name=<?php echo $row['user_name']?>&redirected=true">
                    <div class="row-fluid" style="margin-top: 20px;">
                       <div class="row-fluid" style="margin-top: 20px;">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title">
										<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
										<a href="./profile.php?user_name=<?php echo $row['user_name']?>&redirected=true">
											<?php echo $row['user_fname']." ".$row['user_lname']; ?>
										</a><form method="post" action="">
											<input type="hidden" id="hid_user_id" name="hid_user_id" value="<?php echo $row['user_id']; ?>" />
											<input type="hidden" id="hid_user_name" name="hid_user_name" value="<?php echo $row['user_name']; ?>" />
											<input type="checkbox" id="checkbox1" name="checkbox1" value="1">Band Manager
											<button type="submit" class="btn btn-primary" name="btn_add" id="btn_add">+</button>
										</form>
									</h3>
								</div>
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
                    echo "<li><a href='./memberSearch.php?page=$previousPage&searchBandMember=$searchBandMember'>Prev</a></li>";
                }


                for ($i=1; $i < $numPages+1; $i++)
                {
                    $theClass = '';
                    if($i == $page)
                        $theClass = 'active';
                    echo "<li class='$theClass'><a href='./memberSearch.php?page=$i&searchBandMember=$searchBandMember'>$i</a></li>";
                }

                if ($page == $numPages)
                    echo "<li class='disabled'><a href='#'>Next</a></li>";
                else
                {
                    $nextPage = $page+1;
                    echo "<li><a href='./memberSearch.php?page=$nextPage&searchBandMember=$searchBandMember'>Next</a></li>";
                }

                ?>
              </ul>
            </nav>
        </div> <!-- /container -->
        <?php include 'footer.php'; ?>
    </body>
</html>
