<?php
session_start();
function curPageName() {
 return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
}
?>

<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
<style type="text/css">
    body{
        padding-top: 0px;
    }
</style>
<link rel="stylesheet" type="text/css" href="css/bootstrap-responsive.css" />


<script src="http://code.jquery.com/jquery.min.js"></script>
<script src="js/bootstrap.js"></script>

<nav class="navbar navbar-default" role="navigation">
	 <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Moozik</a>
    </div>
	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <form class="navbar-form navbar-left" role="search" action="search.php" method="get">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Search" name="term">
        </div>
		</form>
           
                <ul class="nav navbar-nav navbar-right">
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
                                    <a class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-expanded=\"false\">".
                                        $_SESSION['sess_user_name'].
                                        "<span class=\"caret\"></span>
                                    </a>
                                    <ul class=\"dropdown-menu\" role=\"menu\">
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
</nav> <!-- End navbar -->
