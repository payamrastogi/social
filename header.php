<?php
session_start();
function curPageName() {
 return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
}
?>

<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
<style type="text/css">
    body{
        padding-top: 40px;
    }
</style>
<link rel="stylesheet" type="text/css" href="css/bootstrap-responsive.css" />


<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="js/bootstrap.js"></script>

<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-circle-arrow-down"></span>
            </a>

            <a href="index.php" class="brand">Moozik!</a>

            <div class="nav-collapse collapse">

                <form class="navbar-search" action="search.php" method="get">
                  <input type="text" class="search-query" placeholder="Search - Leave Blank for All Users" name="term">
                </form>
                <ul class="nav pull-right">
                    <?php
                        if(curPageName() == 'index.php'){
                        echo'<li class="active"><a href="index.php">Home</a></li>
                        <li class=""><a href="contact.php">Contact Us</a></li>';
                        }
                        elseif (curPageName() == 'contact.php') {
                        echo'<li class=""><a href="index.php">Home</a></li>
                        <li class="active"><a href="contact.php">Contact Us</a></li>';
                        }
                        else{
                        echo'<li class=""><a href="index.php">Home</a></li>
                        <li class=""><a href="contact.php">Contact Us</a></li>';
                        }
                        if (isset($_SESSION['sess_user_type'])) {
                           if ($_SESSION['sess_user_type'] == 'admin') {
                                $cssClass= '';
                                if(curPageName() == 'admin.php'){
                                    $cssClass = "active";
                                }
                               echo "<li class='$cssClass'><a href=\"admin.php\">Admin Panel</a></li>";
                           }
                        }
                        if (isset($_SESSION['sess_user_type']) && ($_SESSION['sess_user_type'] == 'user' || $_SESSION['sess_user_type'] == 'admin'))
                        {
                            $strToPrint = "<li>
                                    <a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"profile.php?user_name=" . $_SESSION['sess_user_name'] . "\">
                                        <i class=\"icon-thumbs-up\"></i> " .  $_SESSION['sess_user_name'] . "
                                        <span class=\"caret\"></span>
                                    </a>
                                    <ul class=\"dropdown-menu\">
                                        <li><a href=\"profile.php?user_name=" . $_SESSION['sess_user_name'] . "\">Profile</a></li>
                                        <li><a href=\"messages.php\">Messages</a></li>
                                        <li><a href=\"settings.php\">Settings</a></li>
                                        <li class=\"divider\"></li>
                                        <li><a href=\"logout.php\">Logout</a></li>
                                    </ul>
                            </li>";
                            echo "$strToPrint";
                        }
                        else
                        {
                            echo"<li><a href=\"login.php\">Login / Register</a></li>";
                        }
                        ?>
                </ul>
            </div> <!-- Nav Collapse -->
        </div> <!-- End Container -->
    </div> <!-- End Navbar inner -->
</div> <!-- End navbar -->
