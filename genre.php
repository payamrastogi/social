<?php
	session_start();
    if (isset($_SESSION['sess_band_id']) &&
		isset($_SESSION['sess_band_user_name']) &&
		isset($_SESSION['sess_band_name']))
	{
        $band_user_name = $_SESSION['sess_band_user_name'];
		$band_id = $_SESSION['sess_band_id'];
		$band_name = $_SESSION['sess_band_name'];
	}
    else
        header('Location: ./login.php');
 
		require 'dbHelper.php';
		$dbo = new db();
        $query = $dbo->getParentGenre();

 ?>
<?php
//echo $_SESSION['sess_band_id'];
$successMessage = '';
$errorMessage = '';
 if(isset($_POST['submit']))
 {
	if(!empty($_POST['genre'])) 
	{
		//echo $_SESSION['sess_band_id'];
		$dbo->deleteBandGenres($_SESSION['sess_band_id']);
		foreach((array)$_POST['genre'] as $genre_id) 
		{
			//echo $genre_id;
			$dbo->updateBandGenres($genre_id,$_SESSION['sess_band_id']);
		}
		$successMessage = $successMessage . 'Genre Updated ' ;
	}
	if($successMessage == '' && $errorMessage == '' && isset($_POST['submit']))
	{
        $blueMessage = 'Nothing was updated';
	}
	if(isset($_POST['submit']) && $_POST['submit'] == 'next')
	{
		//echo "hello";
		header('Location: ./bandHome.php?band_user_name='.$band_user_name);
	}
 }

?>

<html>
	<head>
		<?php include 'header.php'; ?>
		<?php
			 if (isset($errorMessage) && $errorMessage != '')
				echo "<div class=\"alert alert-error\" id=\"formError\">
					   <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
					   <strong>ERROR! </strong> $errorMessage
					 </div>";
			 if (isset($successMessage) && $successMessage != '')
				echo "<div class=\"alert alert-success\" id=\"formSuccess\">
					   <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
					   <strong>Success! </strong> $successMessage
					 </div>";
			if (isset($blueMessage) && $blueMessage != '')
				echo "<div class=\"alert alert-info\" id=\"formError\">
					   <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
					   $blueMessage
					 </div>";
		   ?>
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
		<script type="text/javascript"
				src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
		<link rel="stylesheet" type="text/css"
			  href="http://code.jquery.com/ui/1.10.2/themes/base/jquery-ui.css"/>
		<script type="text/javascript" src="src/js/jquery.tree.js"></script>
		<link rel="stylesheet" type="text/css" href="src/css/jquery.tree.css"/>

		<script type="text/javascript">
			//<!--
			$(document).ready(function() {
				$( "#accordion" ).accordion({
					'collapsible': true,
					'active': null
				});
				$('.jquery').each(function() {
					eval($(this).html());
				});
				$('.button').button();
			});
			//-->
		</script>
	</head>
	<body>
		<div class="container">
            <form method="post" action="">
				<div class="row-fluid">
					<div class="span4">
						<h3>Select Genre</h3>
					</div>
				</div>
				<div class="row-fluid" style="margin-top: 20px;margin-left: 200px">
					<div id="example-7" style="margin-right:250px;margin-top:20px;background-color: #f5f5f5;border: 1px solid #e3e3e3;" >
						<script class="jquery" lang="text/javascript" >
							$('#example-7 div').tree({
								onCheck: {
									ancestors: 'checkIfFull',
									descendants: 'check'
								},
								onUncheck: {
									ancestors: 'uncheck'
								}
							});
						</script>
						<div style="background-color: #f5f5f5;border: 1px solid #e3e3e3;">
							<ul style="background-color: #f5f5f5;">
								<?php
									while ($row = $query->fetch(PDO::FETCH_ASSOC))
									{ ?>
									<li>
										<input type="checkbox" title="<?php echo $row['p_genre_name'] ?>" name=genre[] value="<?php echo $row['p_genre_id'] ?>">
										<span>
											<?php echo $row['p_genre_name'] ?>
										</span>
										<ul style="background-color: #f5f5f5;">
											<?php
												$query1 = $dbo->getSubGenre($row['p_genre_id']);
												while ($row1 = $query1->fetch(PDO::FETCH_ASSOC))
												{ ?>
													<li>
														<input type="checkbox" title="<?php echo $row1['s_genre_name'] ?>" 
															name=genre[] 
															value="<?php echo $row1['s_genre_id'] ?>">
														<span>
															<?php echo $row1['s_genre_name'] ?>
														</span>
													</li>
											<?php } ?>
										</ul>
									</li>
									<?php 
									}
								?>
							</ul>
						</div>
					</div>
					<div class="span4 offset4" style="margin-left:250px">
						<div class="control-group" >
							<div class="controls" >
								<br/>
								<button type="submit" class="btn btn-primary btn-large" value="save" name="submit">Save</button>
								<button type="submit" class="btn btn-primary btn-large" value="next" name="submit">Next >></button>
							</div>
						</div>
					</div>
				</div>
			</form>
		<?php include 'footer.php'; ?>
	</body>
</html>