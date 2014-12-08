<?php
$requestURI = explode('/', $_SERVER['REQUEST_URI']);
$scriptName = explode('/' ,$_SERVER['SCRIPT_NAME']);
 
for($i= 0;$i < sizeof($scriptName);$i++)
        {
      if ($requestURI[$i]     == $scriptName[$i])
              {
                unset($requestURI[$i]);
            }
      }
print_r ($requestURI);
$command = array_values($requestURI);
echo $command[0];
header('Location: ./profile.php?user_name='.$command[0]);
/* switch($command[1])
      {
      case 'payamrastogi' :
               
                break;
 
      case 'commandTwo' :
                echo 'You entered command: '.$command[0];
                break;
		} */
?>