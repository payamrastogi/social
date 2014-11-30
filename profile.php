<?php
//TODO: make this visible to only logged in users
    session_start();
    require 'dbHelper.php';
    $dbo = new db();
	$redirected = '';

    if (! isset($_GET['user_name']))
    {
        if (! isset($_SESSION['sess_user_name']))
        {
            $dbo->close();
            unset($dbo);
            header('Location: ./logout.php');
        }
        else
        {
            $dbo->close();
            unset($dbo);
            $user_name = $_SESSION['sess_user_name'];
        }
    }
    else
    {
        $user_name = $_GET['user_name'];
		$user_id = $_SESSION['sess_user_id'];
		$redirected = $_GET['redirected'];
		$_SESSION['redirected'] = $redirected;
		//echo "12".$user_id;
		if(isset($redirected))
		{
			$query = $dbo->getUserId($user_name);
			if ($row = $query->fetch(PDO::FETCH_ASSOC))
			{
				$user_id = $row['user_id'];
				//echo "Hello".$user_id;
			}
		}
		//echo $user_id;
    }
    $query = $dbo->getUserDetails($user_id);

    if ($row = $query->fetch(PDO::FETCH_ASSOC))
    {
        $fullName = $row['user_fname'] . ' ' . $row['user_lname'];

        $now = new DateTime('now');
        $birth = new DateTime($row['user_dob']);
        $interval = $now->diff($birth);
        $age = $interval->format('%Y');

        //$about = $row['about'];
        //$profilePic = $row['picture'];
        //$location = $row['location'];
        //$gender = $row['gender'];
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo "$fullName"; ?></title>
    </head>
    <body>

    <?php include 'header.php'; ?>

    <div class="container" style="position: relative; top: 40px;">
        <ul class="nav nav-tabs">
        <li class="active">
          <a href="profile.php?user_name=<?php echo $user_name; ?>">Profile</a>
        </li>
        <li><a href="photos.php?user_name=<?php echo $user_name; ?>">Photos</a></li>
        <li><a href="friends.php">Friends</a></li>
      </ul>
        <?php echo "<h1 style=\"font-size: 60px;\">$fullName</h1>"; ?>
        <div class="row-fluid" style="margin-top: 20px;">
            <div class="span4 well">
                <a href="photos.php"><img src="<?php echo"$profilePic" ?>"></a>
            </div>
            <div class="span8">
                <div class="row-fluid">
                    <div class="span4">
                        <?php
                            //echo "<p>Age: $age</p>";
                            //echo "<p>Gender: $gender</p>";
                            //echo "<p>Location: $location</p>";
                         ?>
                    </div>
                    <div class="span8">
                        <h2>About Me</h2>
                        <?php
                            //echo "<p>$about</p>";
                            // Show edit button if the user is on own profile
							//echo $_SESSION['sess_user_name'];
							//echo $user_name;
                            if($user_name == $_SESSION['sess_user_name'])
                                echo "<a class=\"btn\" href=\"./settings.php#about\">Edit</a>";
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    </body>
</html>
