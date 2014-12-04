<?php
    //TODO: fix uplods of files with multiple dots
    session_start();
    require 'dbHelper.php';
    $dbo = new db();
	$user_id = '';
    if (! isset($_GET['user_name']))
    {
        if (! isset($_SESSION['sess_user_name']))
        {
            header('Location: ./logout.php');
        }
        else
            $user_name = $_SESSION['sess_user_name'];
    }
    else
    {
        $user_name = $_GET['user_name'];
		$user_id = $_SESSION['sess_user_id'];
    }
    /***********************************************/
    //Get user's details

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

    /* ********************************************** */
    //Upload Stuff

    if (isset($_FILES['files']))
    {
        $error = '';
        $success = '';
        $userDirectory = "./images/$user";
        $allowedExtensions = array("jpg", "jpeg", "png", "gif");
        $allowedTypes = array("image/jpeg", "image/gif", "image/png");

        //check if an image folder for the user exists
        if (!file_exists($userDirectory) and !is_dir($userDirectory))
            mkdir($userDirectory);

        foreach ($_FILES['files']['tmp_name'] as $key => $tmpFile)
        {

            $fileType = $_FILES['files']['type'][$key]; //mime
            $fileName = $_FILES['files']['name'][$key];
            $exploded = explode(".", $fileName);
            $fileExt = end($exploded);
            $errorCode = $_FILES['files']['error'][$key];

            if ($errorCode != 0)
            {
                $error = $error . " - Error code $errorCode: ";
                switch ($errorCode)
                {
                    case 1:
                        $error = $error ."The uploaded file exceeds the upload_max_filesize ";
                        break;
                    case 2:
                        $error = $error ."The uploaded file exceeds the MAX_FILE_SIZE ";
                        break;
                    case 3:
                        $error = $error ."No file was uploaded ";
                        break;
                    case 7:
                        $error = $error . "Failed to write file to disk ";
                    default:
                        break;
                }
            }

            if(! in_array(strtolower($fileExt), $allowedExtensions))
                $error = $error . " - $fileName's extension ($fileExt) is unsupported. Only .jpeg, .jpg, .png, and .gif allowed";

            if(! in_array($fileType, $allowedTypes))
                $error = $error . " - $fileName's file type ($fileType) is unsupported. Only jpegs, pngs, and gifs allowed";

            elseif($error == '')
            {
                $filePath = $userDirectory . '/' . $fileName;
                move_uploaded_file($tmpFile, $filePath);
                $dbo->newPhoto($username, "1", $filePath, $fileName);
                $success = $success . ' - Uploaded ' . $fileName;
            }
        }
    }//end if isset(files)

    /*************************************************/
    //Get the user's images
    //$userImagesQuery = $dbo->getUserPhotos($user);
    //$numberOfImages = $userImagesQuery->rowCount();

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo "$fullName"; ?>'s Photos</title>
    </head>
    <body>

    <?php include 'header.php'; ?>

    <div class="container" style="position: relative; top: 40px;">
        <ul class="nav nav-tabs">
            <li class="">
              <a href="profile.php?user=<?php echo $user; ?>">Profile</a>
            </li>
            <li class="active"><a href="photos.php?user=<?php echo $user; ?>">Photos</a></li>
            <li><a href="friends.php">Friends</a></li>
        </ul>

        <?php
            if (isset($error) && $error != '')
            echo "<div class=\"alert alert-error\" id=\"formError\">
                   <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
                   <strong>ERROR! </strong> $error
                 </div>";
         if (isset($success) && $success != '')
            echo "<div class=\"alert alert-success\" id=\"formSuccess\">
                   <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
                   <strong>Success! </strong> $success
                 </div>";
        ?>

        <!-- Let's show an upload dialog if the user is on their own photos page only. -->
        <?php
            if (isset($_SESSION['sess_user_name']) && $user_name == $_SESSION['sess_user_name'])
            {?>

                <h1 style=\"font-size: 60px;\"><?php echo $fullName ?>'s Photos</h1>
                <div class="row-fluid">
                    <div class="span4 well" id="uploadForm">
                        <h2>Upload a Photo</h2>
                        <p>Hold Ctrl to select multiple images (only works if your browser
                            supports <a href="http://html5test.com/">HTML 5</a>)</p>
                        <form method="post" action="" enctype="multipart/form-data">
                            <input type="hidden" name="MAX_FILE_SIZE" size="40"/>
                            <center><input type="file" name="files[]" accept="image/*"multiple="multiple"/><br />
                            <input class="btn btn-primary btn-large" type="submit" value="Upload!" /></center>
                        </form>
                    </div>
                    <div class="span8" >
                       <div id="myCarousel" class="carousel carousel-slide">
                          <div class="carousel-inner">
                            <div class="item active">
                              <img id="carouselPic" src="./images/quinn.jpg" alt="">
                              <div class="container">

                              </div>
                            </div>
                          </div>
                        </div><!-- /.carousel -->

                    </div>
                </div>

            <?php }
            else
                echo "<h1 style=\"font-size: 60px;\">$fullName's Photos</h1>";
         ?>

            <?php
            $count = 3;
            while ($rowOfImages = $userImagesQuery->fetch(PDO::FETCH_ASSOC))
            {
                $image = $rowOfImages['url'];
                $name = $rowOfImages['name'];
                ?>

                <ul class="thumbnails">
                    <li class="span4">
                         <div class="thumbnail">
                            <a href="<?php echo $image; ?>"><img src="<?php echo $image ?>" alt="<?php echo $name ?>"></a>
                            <h3><?php echo $name ?></h3>
                            <a class="btn" href="./changeProfilePic?return=pictures&pic=<?php echo $image ?>">Make This My Profile Picture</a>
                        </div>

                    </li>
                </ul>
            <?php } ?>

        </div>
    <?php include 'footer.php'; ?>
    </body>
    <style type="text/css">
        #carouselPic
        {

            max-height: 300px;
            width: 100%;
        }
        #uploadForm{
            min-height: 300px;
        }
    </style>

</html>
