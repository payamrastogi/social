<?php
	session_start();
	require 'dbHelper.php';
    $dbo = new db();
	$user_id = '';
	$successMessage = '';
	$check =0;
	if(isset($_GET['sel_criteria']))
	{
		$check =1;
		if(isset($_GET['searchConcert']) && $_GET['searchConcert']!='')
		{
			if($_GET['sel_criteria']==0)
			{
				$genre_name = $_GET['searchConcert'];
				$sel_criteria = $_GET['sel_criteria'];
				$user_name = $_SESSION['sess_user_name'];
				header('Location: ./searchConcertResult.php?user_name='.$user_name.'&genre_name='.$genre_name.'&sel_criteria='.$sel_criteria);
			}
			$successMessage = $successMessage;
		}
		if(isset($_GET['searchConcert']) && $_GET['searchConcert']!='')
		{
			if($_GET['sel_criteria']==1)
			{
				$concert_name = $_GET['searchConcert'];
				$sel_criteria = $_GET['sel_criteria'];
				$user_name = $_SESSION['sess_user_name'];
				header('Location: ./searchConcertResultName.php?user_name='.$user_name.'&concert_name='.$concert_name.'&sel_criteria='.$sel_criteria);
			}
			$successMessage = $successMessage;
		}
		if(isset($_GET['searchConcert']) && $_GET['searchConcert']!='')
		{
			if($_GET['sel_criteria']==2)
			{
				$band_name = $_GET['searchConcert'];
				$sel_criteria = $_GET['sel_criteria'];
				$user_name = $_SESSION['sess_user_name'];
				$queryBand = $dbo->getBandId($band_name);
				$row_band = $query1->fetch(PDO::FETCH_ASSOC);
				$band_id = $row_band['band_id'];
				header('Location: ./searchConcertResultBand.php?user_name='.$user_name.'&band_id='.$band_id.'&sel_criteria='.$sel_criteria);
			}
			$successMessage = $successMessage;
		}
		if(isset($_GET['sel_criteria']) && $_GET['sel_criteria']==3)
		{
			$sel_criteria = $_GET['sel_criteria'];
			$user_name = $_SESSION['sess_user_name'];
			header('Location: ./searchConcertResultUpcoming.php?user_name='.$user_name.'&sel_criteria='.$sel_criteria);
		}
		if(isset($_GET['sel_criteria']) && $_GET['sel_criteria']==4)
		{
			$sel_criteria = $_GET['sel_criteria'];
			$user_name = $_SESSION['sess_user_name'];
			header('Location: ./searchConcertResultUpcomingWeek.php?user_name='.$user_name.'&sel_criteria='.$sel_criteria);
		}
		if(isset($_GET['sel_criteria']) && $_GET['sel_criteria']==5)
		{
			$sel_criteria = $_GET['sel_criteria'];
			$user_name = $_SESSION['sess_user_name'];
			header('Location: ./searchConcertResultUpcomingMonth.php?user_name='.$user_name.'&sel_criteria='.$sel_criteria);
		}
		if(isset($_GET['sel_criteria']) && $_GET['sel_criteria']==6)
		{
			$sel_criteria = $_GET['sel_criteria'];
			$user_name = $_SESSION['sess_user_name'];
			header('Location: ./searchConcertResultRecently.php?user_name='.$user_name.'&sel_criteria='.$sel_criteria);
		}
		if(isset($_GET['sel_criteria']) && $_GET['sel_criteria']==7)
		{
			$sel_criteria = $_GET['sel_criteria'];
			$user_name = $_SESSION['sess_user_name'];
			header('Location: ./searchConcertResultPast.php?user_name='.$user_name.'&sel_criteria='.$sel_criteria);
		}
		if(isset($_GET['sel_criteria']) && $_GET['sel_criteria']==8)
		{
			$sel_criteria = $_GET['sel_criteria'];
			$user_name = $_SESSION['sess_user_name'];
			header('Location: ./searchConcertResultAll.php?user_name='.$user_name.'&sel_criteria='.$sel_criteria);
		}
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
    $queryAll = $dbo->getUserConcerts($user_id, -1, -1); //Used for counting rows
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

    $query = $dbo->getUserConcerts($user_id, $startFrom, $resultsPerPage);
 ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Concert</title>
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
				<li class=""><a href="list.php?user_name=<?php echo $user_name; ?>">My list</a></li>
				<li class="active"><a href="searchConcert.php?user_name=<?php echo $user_name; ?>">Concerts</a></li>
			</ul>
			<div class="container" style="position: relative; top: 10px;">
				<form class="navbar-form navbar-left" role="search" action="searchConcert.php" method="get">
						<div class="form-group">
							<select name="sel_criteria" class="form-control" onchange='this.form.submit()'>
							<option value="0" <?php if(isset($_GET['sel_criteria']) && $_GET['sel_criteria'] == 0){echo 'selected="selected"';}?>">By genre</option>
							<option value="1" <?php if(isset($_GET['sel_criteria']) && $_GET['sel_criteria'] == 1){echo 'selected="selected"';}?>">By Name</option>
							<option value="2" <?php if(isset($_GET['sel_criteria']) && $_GET['sel_criteria'] == 2){echo 'selected="selected"';}?>">By Band</option>
							<option value="3" <?php if(isset($_GET['sel_criteria']) && $_GET['sel_criteria'] == 3){echo 'selected="selected"';}?>">Upcoming Concerts</option>
							<option value="4" <?php if(isset($_GET['sel_criteria']) && $_GET['sel_criteria'] == 4){echo 'selected="selected"';}?>">Upcoming concerts - next week</option>
							<option value="5" <?php if(isset($_GET['sel_criteria']) && $_GET['sel_criteria'] == 5){echo 'selected="selected"';}?>">Upcoming concerts - next month</option>
							<option value="6" <?php if(isset($_GET['sel_criteria']) && $_GET['sel_criteria'] == 6){echo 'selected="selected"';}?>">Recently added Concerts</option>
							<option value="7" <?php if(isset($_GET['sel_criteria']) && $_GET['sel_criteria'] == 7){echo 'selected="selected"';}?>">Past Concerts</option>
							<option value="8" <?php if(isset($_GET['sel_criteria']) && $_GET['sel_criteria'] == 8){echo 'selected="selected"';}?>">All</option>
							</select>
							<noscript><input type="submit" value="Submit" name="sel_submit"></noscript>
						</div>
						<?php 	
								if((isset($_GET['sel_criteria']) && ($_GET['sel_criteria'] == 0 || $_GET['sel_criteria'] == 1 || $_GET['sel_criteria'] == 2)) || $check==0) 
								{ ?>
									<div class="form-group">
										<input type="text" class="form-control" placeholder="Search" name="searchConcert">
									</div>
								<?php	}?>
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
				$concert_id = $row['concert_id'];
				$query1 = $dbo->getConcertDetails($concert_id);
				$row_concertdetails = $query1->fetch(PDO::FETCH_ASSOC);
				?>
				<div class="row-fluid" style="margin-top: 20px;">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">
								<a href="./concert.php?concert_id=<?php echo $concert_id; ?>&redirected=true">
									<span class="glyphicon glyphicon-headphones" aria-hidden="true"></span>
										<?php echo $row_concertdetails['concert_name']; ?>	
								</a>
							</h3>
						</div>
						<div class="panel-body">
							<div class="col-lg-3">
								<div class="input-group">
									<?php if($row['planned']=='yes') 
									{ ?>
										<p>Plan:<?php echo $row['plan'] ?></p>
								<?php } ?>
									<?php if($row['reviewed']=='yes') 
									{ ?>
										<p>Review:<?php echo $row['user_review']." @".$row['review_atime']?></p>
								<?php } ?>
								<?php if($row['rated']=='yes') 
									{ ?>
										<p>You Rated:<?php echo $row['rating'];?></p>
								<?php } ?>
									<p>Description:<?php echo $row_concertdetails['concert_description'];?></p>
									<p>Start date:<?php echo $row_concertdetails['concert_sdate'];?></p>
									<p>End date:<?php echo $row_concertdetails['concert_edate'];?></p>
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
                    echo "<li><a href='./searchConcert.php?page=$previousPage'>Prev</a></li>";
                }


                for ($i=1; $i < $numPages+1; $i++)
                {
                    $theClass = '';
                    if($i == $page)
                        $theClass = 'active';
                    echo "<li class='$theClass'><a href='./searchConcert.php?page=$i'>$i</a></li>";
                }

                if ($page == $numPages)
                    echo "<li class='disabled'><a href='#'>Next</a></li>";
                else
                {
                    $nextPage = $page+1;
                    echo "<li><a href='./searchConcert.php?page=$nextPage'>Next</a></li>";
                }

                ?>
              </ul>
            </nav>
        </div> <!-- /container -->
        <?php include 'footer.php'; ?>
    </body>
</html>
