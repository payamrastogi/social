<?php
	session_start();
	require 'dbHelper.php';
	$dbo = new db();
	$user_id = '';
	$successMessage = '';
	if(isset($_GET['follow_uid']))
	{
		$follow_uid = $_GET['follow_uid'];
		$user_id = $_SESSION['sess_user_id'];
		$follow_name = $_GET['follow_name'];
		$queryUnfollow = $dbo->followUsers($follow_uid, $user_id);
		$successMessage = $successMessage . '- following '.$follow_name;
	}
    if (isset($_GET['term']))
    {
        $term = $_GET['term'];
        $queryAll = $dbo->searchUsers($term, -1, -1); //Used for counting rows
		//echo "hello".$queryAll;
        $numResults = $queryAll->rowCount();

        $resultsPerPage = 2;
        $numPages = ceil($numResults / $resultsPerPage);

        if (isset($_GET['page']) && (int)$_GET['page'] <= $numPages && $_GET['page'] != '')
            $page = (int)$_GET['page'];
        else
            $page = 1;

        $startFrom = ($page - 1) * $resultsPerPage;

        $query = $dbo->searchUsers($term, $startFrom, $resultsPerPage);

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
        <div class="container">
            <h3>Your search for <?php
            $plural = $numResults != 1 ? "results" : "result";
             echo "'$term' returned ". $numResults . ' ' . $plural ?></h3>
             <h4>Showing <?php echo $resultsPerPage ?> results per page</h4>
			<?php
				if (isset($successMessage) && $successMessage != '')
					echo "<div class=\"alert alert-success\" id=\"formSuccess\">
					<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
					<strong>Success! </strong> $successMessage
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
											<?php echo $row['user_fname'] . ' ' . $row['user_lname']; ?>
										</a>
										<a href="./search.php?follow_uid=<?php echo $row['user_id']?>&redirected=true&follow_name=<?php echo $row['user_name']?>&term=<?php echo $term; ?>">
											<span class="glyphicon glyphicon-plus" aria-hidden="true" name="gly_follow"></span>
										</a>
									</h3>
								</div>
								<div class="panel-body">
									<div class="col-lg-3">
										<div class="input-group">
											<p>Gender: <?php echo $row['user_gender']; ?></p>
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
                    echo "<li><a href='./search.php?page=$previousPage&term=$term'>Prev</a></li>";
                }


                for ($i=1; $i < $numPages+1; $i++)
                {
                    $theClass = '';
                    if($i == $page)
                        $theClass = 'active';
                    echo "<li class='$theClass'><a href='./search.php?page=$i&term=$term'>$i</a></li>";
                }

                if ($page == $numPages)
                    echo "<li class='disabled'><a href='#'>Next</a></li>";
                else
                {
                    $nextPage = $page+1;
                    echo "<li><a href='./search.php?page=$nextPage&term=$term'>Next</a></li>";
                }

                ?>
              </ul>
            </nav>
        </div> <!-- /container -->
        <?php include 'footer.php'; ?>
    </body>
</html>
