<?php
	require 'dbHelper.php';
    $dbo = new db();
	if(isset($_GET['location_id']))
	{
		$location_id = $_GET['location_id'];
		$dbo->generateMapXML($location_id);
	}
?>