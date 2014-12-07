<?php
    session_start();
	require 'dbHelper.php';
	$dbo = new db();
	echo "kugj";
	if(isset($_SESSION['sess_user_id']))
	{	
		echo "ghg";
		$user_id = $_SESSION['sess_user_id'];
 		$query = $dbo->doLogout($user_id);
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
?>
