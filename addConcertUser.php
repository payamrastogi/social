<?php
	session_start();
	require 'dbHelper.php';
    $dbo = new db();
	$user_id = '';
	$successMessage = '';
    $errorMessage = '';
	
	if (!isset($_SESSION['sess_user_name']))
    {
        if (! isset($_SESSION['sess_user_name']))
        {
            header('Location: ./logout.php');
        }
        else
		{
            $user_name = $_SESSION['sess_user_name'];
			if (!isset($_SESSION['sess_user_id']))
			{
				$user_id = $_SESSION['sess_band_id'];
			}
		}
    }
    else
    {
        $user_name = $_SESSION['sess_user_name'];
		$user_id = $_SESSION['sess_user_id'];
    }
	if(isset($_POST['btn_add_concert']))
	{
		if (isset($_POST['txt_concert_name']) && $_POST['txt_concert_name'] != '')
		{
			$concert_name = $_POST['txt_concert_name'];
		}
		else
		{
			 $errorMessage = $errorMessage ." - Provide Concert name. ";
		}
		if (isset($_POST['example1']) && $_POST['example1'] != '')
		{
			$concert_sdate= $_POST['example1'];
		}
		else
		{
			$errorMessage = $errorMessage ." - Provide Concert Start date. ";
		}
		if (isset($_POST['timepicker1']) && $_POST['timepicker1'] != '')
		{
			$concert_stime= $_POST['timepicker1'];
			//echo $concert_stime;
		}
		else
		{
			$errorMessage = $errorMessage ." - Provide Concert Start time. ";
		}
		if (isset($_POST['example2']) && $_POST['example2'] != '')
		{
			$concert_edate= $_POST['example2'];
		}
		else
		{
			$errorMessage = $errorMessage ." - Provide Concert End date. ";
		}
		if (isset($_POST['timepicker2']) && $_POST['timepicker2'] != '')
		{
			$concert_etime= $_POST['timepicker2'];
		}
		else
		{
			$errorMessage = $errorMessage ." - Provide Concert End time. ";
		}
		if (isset($_POST['sel_location']) && $_POST['sel_location'] != '')
		{
			$concert_lid= $_POST['sel_location'];
			//echo $concert_lid;
		}
		else
		{
			$errorMessage = $errorMessage ." - Select location. ";
		}
		if (isset($_POST['txt_concert_tcost']) && $_POST['txt_concert_tcost'] != '')
		{
			$concert_tcost= $_POST['txt_concert_tcost'];
		}
		else
		{
			$errorMessage = $errorMessage ." - Provide Ticket cost. ";
		}
		if (isset($_POST['txt_concert_capacity']) && $_POST['txt_concert_capacity'] != '')
		{
			$concert_capacity= $_POST['txt_concert_tcost'];
		}
		else
		{
			$concert_capacity = '';
		}
		if (isset($_POST['txt_concert_desription']) && $_POST['txt_concert_desription'] != '')
		{
			$concert_description= $_POST['txt_concert_desription'];
		}
		else
		{
			$concert_description = '';
		}
		if (isset($_POST['sel_bands']) && $_POST['sel_bands'] != '')
		{
			//echo "khk";
			//echo $_POST['sel_bands'];
			$band_id_arr = $_POST['sel_bands'];
		}
		else
		{
			$errorMessage = $errorMessage ." - error occured. ";
		}
		if (isset($_POST['sel_genres']) && $_POST['sel_genres'] != '')
		{
			$genre_id_arr = $_POST['sel_genres'];
		}
		else
		{
			$errorMessage = $errorMessage ." - error occured. ";
		}
		if (isset($_POST['sel_tshops']) && $_POST['sel_tshops'] != '')
		{
			$shop_id_arr = $_POST['sel_tshops'];
		}
		else
		{
			$errorMessage = $errorMessage ." - error occured. ";
		}
		if($errorMessage=='')
		{
			$concert_id = $dbo->insertConcertByBand($concert_name,$concert_sdate,$concert_stime, $concert_edate, $concert_etime, $concert_lid, $concert_tcost, $concert_capacity, $concert_description);
			//echo $concert_id;
		}
		if(isset($concert_id) && $concert_id!='')
		{
			if(is_array($band_id_arr))
			{
				foreach($band_id_arr as $band_id)
				{
					//echo "hh";
					$dbo->insertBandConcert($concert_id, $band_id);
				}
			}
			else
			{
				$errorMessage = $errorMessage ." - error occured. ";
			}
			if(is_array($genre_id_arr))
			{
				foreach($genre_id_arr as $genre_id)
				{
					//echo "hh";
					$dbo->insertGenreConcert($concert_id, $genre_id);
				}
			}
			else
			{
				$errorMessage = $errorMessage ." - error occured. ";
			}
			if(is_array($shop_id_arr))
			{
				foreach($shop_id_arr as $shop_id)
				{
					//echo "hh";
					$dbo->insertShopConcert($concert_id, $shop_id);
				}
			}
			else
			{
				$errorMessage = $errorMessage ." - error occured. ";
			}
			
		}
	}
 ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add Concert</title>
			<link rel="stylesheet" href="./css/datepicker.css">
        <link rel="stylesheet" href="./css/bootstrap.css">
		        <script src="//code.jquery.com/jquery-1.10.1.min.js"></script>
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

        <link href="./css/bootstrap-timepicker.min.css" rel="stylesheet">
        <script src="./js/bootstrap-timepicker.min.js"></script>
    </head>

    <body>
		<?php include 'header.php'; ?>
		<div class="container" style="position: relative; top: 10px;">
			<ul class="nav nav-tabs">
			<li class="">
				<a href="home.php?user_name=<?php echo $user_name; ?>">Home</a>
			</li>
			<li>
				<a href="userprofile.php">Profile</a>
			</li>
			<li class=""><a href="friends.php">Following</a></li>
			<li class=""><a href="fanof.php">Fan of</a></li>
			<li class=""><a href="list.php?user_name=<?php echo $user_name; ?>">My list</a></li>
			<li class=""><a href="searchConcert.php?user_name=<?php echo $user_name; ?>">Concerts</a></li>
			<?php if($_SESSION['sess_user_repo'] > 12)
			{?>
			<li class="active"><a href="addConcert.php">Add Concerts</a></li>
			<?php } ?>
		</ul>
			<?php
				if (isset($successMessage) && $successMessage != '')
					echo "<div class=\"alert alert-success\" id=\"formSuccess\" style=\"position: relative; top: 10px;\">
					<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
					<strong>Success! </strong> $successMessage
					</div>";
				if (isset($errorMessage) && $errorMessage != '')
					echo "<div class=\"alert alert-error\" id=\"formError\">
                   <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
                   <strong>ERROR! </strong> $errorMessage
                 </div>";
			?>
				
			<div class="row-fluid" style="margin-top: 20px;">
			   <div class="row-fluid" style="margin-top: 20px;">
					<div class="panel panel-default">
						<div class="panel-heading">
							Heading
						</div>
						<div class="panel-body">
							<form role="form" class="form-horizontal" method="post" action="">
								<div class="col-sm-6 col-md-4">
									<div class="thumbnail">
										<div class="input-group">
											<label for="txt_concert_name">Concert Name</label>
											<input type="text" class="form-control" name="txt_concert_name" placeholder="Concert Name" required/>
										</div>
										<div class="input-group">
											<label for="txt_sdate">Concert description</label>
											<textarea class="form-control" name="txt_concert_desription	" placeholder="Concert description..."  id="" required></textarea>
										</div>
										<div class="input-group">
											<label for="txt_sdate">Start date</label>
											<input type="text" class="form-control" placeholder="Start date" name="example1" id="example1" required/>
										</div>
										<div class="input-group">
											<div class="col-md-12">
												<label for="txt_sdate">Start time</label>
												<div class="input-group bootstrap-timepicker">
												<input id="timepicker1" name="timepicker1" type="text" class="form-control" required>
													<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
												</div>
											</div>
										</div>
										<div class="input-group">
											<label for="txt_sdate">End date</label>
											<input type="text" class="form-control" placeholder="End date" name="example2" id="example2" required/>
										</div>
										<div class="input-group">
											<div class="col-md-12">
												<label for="txt_sdate">End time</label>
												<div class="input-group bootstrap-timepicker">
												<input id="timepicker2" name="timepicker2" type="text" class="form-control" required>
													<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-6 col-md-4">
									<div class="thumbnail">
										<div class="input-group">
											<label for="txt_location">Location</label>
											<select name="sel_location" class="form-control" required>
											<?php
												$query2 = $dbo->getLocations();
												while($row_locations = $query2->fetch(PDO::FETCH_ASSOC))
												{?> 
											  <option value="<?php echo $row_locations['location_id'];?>"><?php echo $row_locations['location_name'].", ".$row_locations['location_type'];?></option>
											<?php } ?>
											</select>
										</div>
										<div class="input-group">
											<label for="sel_bands">Band Performing</label>
											<select name="sel_bands[]" multiple class="form-control" required>
											 <?php
												$query5 = $dbo->getBands();
												while($row_bands = $query5->fetch(PDO::FETCH_ASSOC))
												{?> 
											  <option value="<?php echo $row_bands['band_id'];?>"><?php echo $row_bands['band_name'];?></option>
											<?php } ?>
											</select>
										</div>
										<div class="input-group">
											<label for="sel_genres">Genre</label>
											<select name="sel_genres[]" multiple class="form-control" required>
											 <?php
												$query4 = $dbo->getParentGenre();
												while($row_genres = $query4->fetch(PDO::FETCH_ASSOC))
												{?> 
											  <option value="<?php echo $row_genres['p_genre_id'];?>"><?php echo $row_genres['p_genre_name'];?></option>
											<?php
													$query5 = $dbo->getSubGenre($row_genres['p_genre_id']);
													while($row_genres = $query5->fetch(PDO::FETCH_ASSOC))
													{?>
													<option value="<?php echo $row_genres['s_genre_id'];?>"><?php echo $row_genres['s_genre_name'];?></option>
													<?php }
												}
											?>
											</select>
										</div>
									</div>
								</div>
								<div class="col-sm-6 col-md-4">
									<div class="thumbnail">
										<div class="input-group">
											<label for="txt_concert_tcost">Ticket Cost</label>
											<input type="text" class="form-control" name="txt_concert_tcost" placeholder="Ticket Cost" required/>
										</div>
										<div class="input-group">
											<label for="txt_concert_tcost">Capacity</label>
											<input type="text" class="form-control" name="txt_concert_capacity" placeholder="Capacity" required/>
										</div>
										<div class="input-group">
											<label for="sel_tshops">Tickets Available @</label>
											<select name="sel_tshops[]" multiple class="form-control" required>
											 <?php
												$query3 = $dbo->getShops();
												while($row_shops = $query3->fetch(PDO::FETCH_ASSOC))
												{?> 
											  <option value="<?php echo $row_shops['shop_id'];?>"><?php echo $row_shops['shop_name'].", ".$row_shops['shop_street'];?></option>
											<?php } ?>
											</select>
										</div>
									</div>
								</div>
								<div class="col-sm-6 col-md-4">
									<div class="thumbnail">
										<button type="submit" class="btn btn-primary btn-large" name="btn_add_concert">Save</button>
									</div>
								</div>
							</form>
						</div><!-- panel-body -->
					</div><!-- Panel Profile Details -->
				</div>
			</div>
        </div> <!-- /container -->
        <?php include 'footer.php'; ?>
		<script src="./js/jquery-1.9.1.min.js"></script>
        <script src="./js/bootstrap-datepicker.js"></script>
		<script src="./js/bootstrap-timepicker.min.js"></script>
        <script type="text/javascript">
            // When the document is ready
            $(document).ready(function () {
                
                $('#example1').datepicker({
                    format: "yyyy-mm-dd"
                });  
				$('#example2').datepicker({
                    format: "yyyy-mm-dd"
                });
				
				$('#timepicker1').timepicker({
				showMeridian:false
				});
				$('#timepicker2	').timepicker({
				showMeridian:false
				});

            });
        </script>
    </body>
</html>