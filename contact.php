<?php
    if (isset($_POST['submit']))
    {
            $error = null;
            $name = $_POST['firstName'];
            $surname = $_POST['surname'];
            $email = $_POST['email'];
            $message = $_POST['message'];

            $ip=$_SERVER['REMOTE_ADDR'];

            $to = 'matthewoneill@outlook.com';
            $subject ="Message From Contact Form";
            $fullName = "\n - " . "$name " . "$surname.";

            //mail(my address, subject, body of email, sender's details)
            mail($to, $subject, $message . $fullName . ". " . $ip, 'From:' . $email) or die("Cannot send email.");

    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Contact Us</title>
        <link rel="stylesheet" type="text/css" href="css/main.css" />
        <script type="text/javascript" src="js/validateContact.js"></script>
    </head>
    <body>
        <?php include 'header.php';?>

        <?php
        if (isset($name) && isset($_POST['submit'])) {
            echo
                "<div class=\"alert alert-success\" id=\"formSuccess\">
                  <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
                  <strong>Woohoo!</strong> Your message was sent, $name!
                </div>";
        }
        else{
            echo "<div class=\"alert alert-error\" id=\"formError\">
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
          <strong>Warning!</strong> All fields must be filled out
        </div>";

        }
        ?>




        <div class="container">
            <form name="contact" class="contactForm" action="" method="post" onsubmit="return validateForm()">
                <div class="control-group">
                    <label class="control-label" for="inputFirstName">First Name</label>
                    <div class="controls">
                        <input class="input-xxlarge" type="text" id="inputFirstName" name="firstName" placeholder="First Name">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="inputSurname">Surname</label>
                    <div class="controls">
                        <input class="input-xxlarge" type="text" id="inputSurname" name="surname" placeholder="Surname">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="inputEmail">Email</label>
                    <div class="controls">
                      <input class="input-xxlarge" type="text" id="inputEmail" name="email" placeholder="Email">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="textAreaMessage">Message</label>
                    <div class="controls">
                      <textarea class="input-xxlarge" id="textAreaMessage" name="message" placeholder="Message"></textarea>
                    </div>
                </div>

                <div class="control-group">
                    <div class="controls">
                        <center><br/><button type="submit" class="btn btn-primary btn-large" name="submit">Send</button></center>
                    </div>
                </div>
            </form>
      </div>
      <?php
      include 'footer.php';
       ?>
    </body>
</html>
