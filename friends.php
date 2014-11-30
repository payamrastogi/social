<?php
	session_start();
	require 'dbHelper.php';
    $dbo = new db();
	$user_id = '';
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
    $queryAll = $dbo->getFollowers($user_id, -1, -1); //Used for counting rows
	//echo "hello".$queryAll;
	
    $numResults = $queryAll->rowCount();
	echo "2".$user_id;
	echo "1".$numResults;
    $resultsPerPage = 2;
    $numPages = ceil($numResults / $resultsPerPage);

    if (isset($_GET['page']) && (int)$_GET['page'] <= $numPages && $_GET['page'] != '')
        $page = (int)$_GET['page'];
    else
        $page = 1;

    $startFrom = ($page - 1) * $resultsPerPage;

    $query = $dbo->getFollowers($user_id, $startFrom, $resultsPerPage);
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
             <h4>Showing <?php echo $resultsPerPage ?> results per page</h4>
           <?php

            while ($row = $query->fetch(PDO::FETCH_ASSOC))
            {	
				$following_uid = $row['following_uid'];
				$query1 = $dbo->getUserDetails($following_uid);
				$row_userdetails = $query1->fetch(PDO::FETCH_ASSOC);
				?>
                <a href="./profile.php?user_name=<?php echo $row_userdetails['user_name']?>&redirected=true">
                    <div class="row-fluid" style="margin-top: 20px;">
                        <div class="span5 well">
                            <img src="<?php echo $row_userdetails['picture'] ?>" />
                        </div>
                        <div class="span7 well">
                            <h4><?php echo $row_userdetails['user_fname'] . ' ' . $row_userdetails['user_lname']; ?></h4>
                            <p>Gender: <?php echo $row_userdetails['user_gender']; ?></p>
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
                    echo "<li><a href='./friends.php?page=$previousPage'>Prev</a></li>";
                }


                for ($i=1; $i < $numPages+1; $i++)
                {
                    $theClass = '';
                    if($i == $page)
                        $theClass = 'active';
                    echo "<li class='$theClass'><a href='./friends.php?page=$i'>$i</a></li>";
                }

                if ($page == $numPages)
                    echo "<li class='disabled'><a href='#'>Next</a></li>";
                else
                {
                    $nextPage = $page+1;
                    echo "<li><a href='./friends.php?page=$nextPage'>Next</a></li>";
                }

                ?>
              </ul>
            </nav>
        </div> <!-- /container -->
        <?php include 'footer.php'; ?>
    </body>
</html>
