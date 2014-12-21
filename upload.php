<?php
  session_start();
    require 'dbHelper.php';
    $dbo = new db();
if(isset($_FILES['file_upload']))
{
	// Check for errors
	if($_FILES['file_upload']['error'] > 0){
		die('An error ocurred when uploading.');
	}

	if(!getimagesize($_FILES['file_upload']['tmp_name'])){
		die('Please ensure you are uploading an image.');
	}

	// Check filetype
	if($_FILES['file_upload']['type'] != 'image/jpeg'){
		die('Unsupported filetype uploaded.');
	}

	// Check filesize
	if($_FILES['file_upload']['size'] > 500000){
		die('File uploaded exceeds maximum upload size.');
	}

	// Check if the file exists
	if(file_exists('upload/' . $_FILES['file_upload']['name'])){
		die('File with that name already exists.');
	}

	// Upload file
	if(!move_uploaded_file($_FILES['file_upload']['tmp_name'], 'upload/' . $_FILES['file_upload']['name'])){
		die('Error uploading file - check destination is writeable.');
	}
	
	$user_image =  'upload/' . $_FILES['file_upload']['name'];
	echo $user_image;
	$user_id = $_SESSION['sess_user_id'];
	$query = $dbo->addPhoto($user_id,$user_image);
	header('Location: ./userprofile.php');
	die('File uploaded successfully.');
}
?>
<html>
<body>
<form method='post' enctype='multipart/form-data' action='upload.php'>
    File: <input type='file' name='file_upload'>
    <input type='submit'>
	<a href="./userprofile.php">cancel</a>
</form>
</body>
</html>