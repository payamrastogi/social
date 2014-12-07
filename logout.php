<?php
    session_start();
	require 'dbHelper.php';
	$dbo = new db();
	//echo "kugj";
	if(isset($_SESSION['sess_user_id']))
	{	
		//echo "ghg";
		$user_id = $_SESSION['sess_user_id'];
		$login_time = $_SESSION['sess_user_time'] ;
		$user_ctime = $_SESSION['sess_user_ctime'];
		$logout_time = date('Y-m-d H:i:s');
		$date1 = new DateTime($login_time);
		$date2 = new DateTime($logout_time);
		$diff1 = $date2->diff($date1);
		$minutes = $diff1->days * 24 * 60;
		$minutes += $diff1->h * 60;
		$minutes += $diff1->i;
		$date3 = new DateTime($user_ctime);

		$diff2 = $date2->diff($date3);
		$minutes1 = $diff2->days * 24 * 60;
		$minutes1 += $diff2->h * 60;
		$minutes1 += $diff2->i;
		$minutes = $minutes + $minutes1/525600;
		$score  = $minutes/120;
 		$query = $dbo->doLogout($user_id, $score);
		if($query)
		{	
			unset($_SESSION['userType']);
			unset($_SESSION['user_name']);
			session_unset();
			session_destroy();
			if (isset($_GET['return']))
				$page = $_GET['return'];
			else
				$page = 'index.php';
			header('Location: ./' . $page . '?success=Thanks+for+stopping+by');
		}
	}
	else if(isset($_SESSION['sess_band_user_name']))
	{
			unset($_SESSION['sess_user_type']);
			unset($_SESSION['sess_band_user_name']);
			session_unset();
			session_destroy();
			if (isset($_GET['return']))
				$page = $_GET['return'];
			else
				$page = 'index.php';
			header('Location: ./' . $page . '?success=Thanks+for+stopping+by');
	}
	
?>
