<?php
    session_start();
    ob_start();
    require 'dbHelper.php';
    $isAdmin = false;
    $dbo = new db();
    if (! isset($_SESSION['userType'])) {
        header('Location: ./login.php');
    }
    elseif ($_SESSION['userType'] != 'admin') {
        header('Location: ./index.php');
    }
    else
    {
        $isAdmin = true;
        $message = NULL;
        if (isset($_FILES['file']))
        {
            move_uploaded_file($_FILES['file']['tmp_name'], "./files/{$_FILES['file']['name']}");
            $linkToFile = "<a href=\"./files/{$_FILES['file']['name']}\">Link to " . $_FILES['file']['name'] ."</a>";
            $message = "File uploaded successfully. $linkToFile";
        }
    }
?>
<?php if ($isAdmin)
{ ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin</title>
    </head>
    <body>
        <?php include 'header.php'; ?>

        <div class="container" style="position: relative; top:40px;">
            <div class="row-fluid">
                <div class="span4 well">
                    <h2>Upload a file</h2>
                    <?php
                        if ($message != NULL)
                            echo $message . "<br />";
                    ?>
                    <form method="post" action="" enctype="multipart/form-data">
                        <input type="file" name="file" /><br />
                        <input class="btn btn-primary" type="submit" value="Submit" />
                    </form>
                </div>
                <div class="span4 well">
                </div>
                <div class="span4 well">
                </div>
            </div>
            <div class="row-fluid">
                <div class="span4 well">
                    <h2>Upload a file</h2>
                    <?php
                        if ($message != NULL)
                            echo $message . "<br />";
                    ?>
                    <form method="post" action="" enctype="multipart/form-data">
                        <input type="file" name="file" /><br />
                        <input class="btn btn-primary" type="submit" value="Submit" />
                    </form>
                </div>
                <div class="span4 well">

                      <?php
                            $query = $dbo->getAllUsers();
                            $numUsers = $query->rowCount();
                            echo "<p>There are $numUsers users registered.</p>";
                            echo "<select class=\"input-large\" multiple='multiple'>";
                            while($row = $query->fetch(PDO::FETCH_ASSOC))
                            {
                                $un = $row['username'];
                                echo "<option value=\"$un\"/>$un</option>";
                            }
                            echo "</select>";
                       ?>
                       <br />
                       <!-- TODO: Implement -->
                       <a href="admin.php?delete=" class="btn btn-small">Delete</a>
                       <a href="admin.php?ban=" class="btn btn-small">Ban</a>
                       <a href="admin.php?makeAdmin=" class="btn btn-small">Make Admin</a>

                </div>
                <div class="span4 well">
                </div>
            </div>
        </div>
    <?php include("footer.php"); ?>
    </body>
</html>
<?php }
else
{
    echo "You are not an admin";
}
?>
