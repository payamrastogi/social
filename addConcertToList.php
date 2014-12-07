<?php
	session_start();
	require 'dbHelper.php';
    $dbo = new db();
	$user_id = '';
	$successMessage = '';
	$check =0;
	$queryAll ='';
	if(isset($_GET['list_id']))
	{
		$check =1;

		//echo "123"; 
		$user_name = $_GET['user_name'];
		$list_id = $_GET['list_id'];

		$successMessage = $successMessage;
	}
	if(isset($_GET['add_list_id']) && isset($_GET['concert_id']))
	{
		$check =1;

		//echo "123"; 
		$user_name = $_GET['user_name'];
		$list_id = $_GET['add_list_id'];
		$concert_id = $_GET['concert_id'];
		$dbo->addConcertToList($list_id,$concert_id);
		$successMessage = $successMessage."Concert Added to List";
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
    
	//echo "hello".$queryAll;
	$queryAll = $dbo->getConcertNotInList($list_id,-1,-1);; //Used for counting rows
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

    $query = $dbo->getConcertNotInList($list_id,$startFrom, $resultsPerPage);
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
				<li class="active"><a href="list.php?user_name=<?php echo $user_name; ?>">My list</a></li>
				<li class=""><a href="searchConcert.php?user_name=<?php echo $user_name; ?>">Concerts</a></li>
			</ul>
			<div class="container" style="position: relative; top: 10px;">
				<form class="navbar-form navbar-left" role="search" action="searchConcert.php" method="get">
						
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
								<div class="input-group">
									<a href="./addConcertToList.php?add_list_id=<?php echo $list_id; ?>&redirected=true&user_name=<?php echo $user_name;?>&concert_id=<?php echo $concert_id;?>">
									<span class="glyphicon glyphicon-plus" aria-hidden="true" name="gly_unfollow"></span>
								</a>
								</div>
							</h3>
						</div>
						<div class="panel-body">
							<div class="col-lg-3">
								<div class="input-group">
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
                    echo "<li><a href='./addConcertToList.php?page=$previousPage&user_name=$user_name&list_id=$list_id'>Prev</a></li>";
                }


                for ($i=1; $i < $numPages+1; $i++)
                {
                    $theClass = '';
                    if($i == $page)
                        $theClass = 'active';
                    echo "<li class='$theClass'><a href='./addConcertToList.php?page=$i&user_name=$user_name&list_id=$list_id'>$i</a></li>";
                }

                if ($page == $numPages)
                    echo "<li class='disabled'><a href='#'>Next</a></li>";
                else
                {
                    $nextPage = $page+1;
                    echo "<li><a href='./addConcertToList.php?page=$nextPage&user_name=$user_name&list_id=$list_id'>Next</a></li>";
                }

                ?>
              </ul>
            </nav>
        </div> <!-- /container -->
        <?php include 'footer.php'; ?>
    </body>
</html>
