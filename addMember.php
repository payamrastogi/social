<?php
	session_start();
	require 'dbHelper.php';
    $dbo = new db();
	$user_id = '';
	$successMessage = '';
	
	if (!isset($_SESSION['sess_band_user_name']))
    {
        if (! isset($_SESSION['sess_band_user_name']))
        {
            header('Location: ./logout.php');
        }
        else
		{
            $band_user_name = $_SESSION['sess_band_user_name'];
			if (!isset($_SESSION['sess_band_user_id']))
			{
				$band_id = $_SESSION['sess_band_id'];
			}
		}
    }
    else
    {
        $band_user_name = $_SESSION['sess_band_user_name'];
		$band_id = $_SESSION['sess_band_id'];
    }
    $queryAll = $dbo->getAllBandMembers($band_id, -1, -1); //Used for counting rows
	
    $numResults = $queryAll->rowCount();
    $resultsPerPage = 2;
    $numPages = ceil($numResults / $resultsPerPage);

    if (isset($_GET['page']) && (int)$_GET['page'] <= $numPages && $_GET['page'] != '')
        $page = (int)$_GET['page'];
    else
        $page = 1;

    $startFrom = ($page - 1) * $resultsPerPage;

    $query = $dbo->getAllBandMembers($band_id, $startFrom, $resultsPerPage);
 ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add Member</title>
    </head>

    <body>
		<?php include 'header.php'; ?>
		<div class="container" style="position: relative; top: 10px;">
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
							<input type="text" class="form-control" placeholder="Search member" name="searchBandMember" required>
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
				$user_id = $row['user_id'];
				$query1 = $dbo->getUserDetails($user_id);
				$row_userdetails = $query1->fetch(PDO::FETCH_ASSOC);
				?>
				<a href="./profile.php?user_name=<?php echo $row['user_name']?>&redirected=true">
                    <div class="row-fluid" style="margin-top: 20px;">
                       <div class="row-fluid" style="margin-top: 20px;">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title">
										<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
										<a href="./profile.php?user_name=<?php echo $row_userdetails['user_name']?>&redirected=true">
											<?php echo $row_userdetails['user_fname']." ".$row_userdetails['user_lname']; ?>
										</a>
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
                    echo "<li><a href='./addMember.php?page=$previousPage'>Prev</a></li>";
                }

                for ($i=1; $i < $numPages+1; $i++)
                {
                    $theClass = '';
                    if($i == $page)
                        $theClass = 'active';
                    echo "<li class='$theClass'><a href='./addMember.php?page=$i'>$i</a></li>";
                }

                if ($page == $numPages)
                    echo "<li class='disabled'><a href='#'>Next</a></li>";
                else
                {
                    $nextPage = $page+1;
                    echo "<li><a href='./addMember.php?page=$nextPage'>Next</a></li>";
                }

                ?>
              </ul>
            </nav>
        </div> <!-- /container -->
        <?php include 'footer.php'; ?>
    </body>
</html>
