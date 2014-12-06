<?php
//TODO: make this visible to only logged in users
    session_start();
    require 'dbHelper.php';
    $dbo = new db();
	$redirected = '';
	$successMessage = '';
    $errorMessage = '';
	$user_id = $_SESSION['sess_user_id'];
	if (isset($_POST['txt_review']) && $_POST['txt_review'] != '')
	{
		$review = $_POST['txt_review'];
		$user_id = $_SESSION['sess_user_id'];
		$concert_id = $_POST['hid_concert_id'];
        $dbo->updateReview($user_id, $concert_id, $review);
        $successMessage = $successMessage . '- Review saved ' ;
	}
	if(isset($_POST['btn_calendar']))
	{
		$concert_id = $_POST['hid_cal_concert_id'];
		//echo $concert_id;
		//echo $user_id;
		$result = $dbo->addIntoCalendar($user_id,$concert_id);
		$successMessage = $successMessage . '- Entry Added ' ;
	}
    if (!isset($_GET['concert_id']))
    {
        if (!isset($_SESSION['sess_user_name']))
        {
            $dbo->close();
            unset($dbo);
            header('Location: ./logout.php');
        }
        else
        {
            $dbo->close();
            unset($dbo);
            $user_name = $_SESSION['sess_user_name'];
        }
    }
    else
    {
        $concert_id = $_GET['concert_id'];
		$user_id = $_SESSION['sess_user_id'];
		if(isset($_GET['redirected']))
		{
			$redirected = $_GET['redirected'];
			$_SESSION['redirected'] = $redirected;
		}
		//echo "12".$user_id;
		if(isset($redirected))
		{
			$query = $dbo->getConcertDetails($concert_id);

			if ($row = $query->fetch(PDO::FETCH_ASSOC))
			{
				$concert_id = $row['concert_id'];
				$concert_name = $row['concert_name'];
				$concert_sdate = $row['concert_sdate'];
				$concert_stime = $row['concert_stime'];
				$concert_edate = $row['concert_edate'];	
				$concert_etime = $row['concert_etime'];	
				$concert_lid = $row['concert_lid'];
				$concert_et = $row['concert_edate'];	
				$concert_description = $row['concert_description'];
				$concert_tcost = $row['concert_tcost'];
				$concert_capacity = $row['concert_capacity'];
			}
		}
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo "$concert_name"; ?></title>
		<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
		<link rel="stylesheet" href="jquery/jRating.jquery.css" type="text/css" />
		<script type="text/javascript">
		//<![CDATA[

		var customIcons = {
		  restaurant: {
			icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png'
		  },
		  bar: {
			icon: 'http://labs.google.com/ridefinder/images/mm_20_red.png'
		  }
		};

		function load() {
			//alert("hello");
		  var map = new google.maps.Map(document.getElementById("map"), {
			center: new google.maps.LatLng(47.6145, -122.3418),
			zoom: 15,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		  });
		  var infoWindow = new google.maps.InfoWindow();

		  // Change this depending on the name of your PHP file
		  downloadUrl("./mapping.php?location_id=<?php echo $concert_lid;?>", function(data) {
			//alert("3");
			var xml = data.responseXML;
			//alert(xml);
			var markers = xml.documentElement.getElementsByTagName("marker");
			//alert(markers.length);
			for (var i = 0; i < markers.length; i++) {
				//alert("4");
			  var name = markers[i].getAttribute("name");
			  //alert(name);
			  var address = markers[i].getAttribute("address");
			  //alert(address);
			  var type = markers[i].getAttribute("type");
			  //alert(type);
			  var point = new google.maps.LatLng(
				  parseFloat(markers[i].getAttribute("lat")),
				  parseFloat(markers[i].getAttribute("lng")));
			  //alert(point);
			  var html = "<b>" + name + "</b> <br/>" + address;
			  var icon = customIcons[type] || {};
			  var marker = new google.maps.Marker({
				map: map,
				position: point,
				icon: icon.icon
			  });
			  marker.setMap(map);
			  map.setCenter(marker.position)
			  bindInfoWindow(marker, map, infoWindow, html);
			}
		  });
		}

		function bindInfoWindow(marker, map, infoWindow, html) {
			//alert("5");
		  google.maps.event.addListener(marker, 'click', function() {
			//alert("6");
			infoWindow.setContent(html);
			infoWindow.open(map, marker);
		  });
		}

		function downloadUrl(url, callback) {
			//alert("2");
		  var request = window.ActiveXObject ?
			  new ActiveXObject('Microsoft.XMLHTTP') :
			  new XMLHttpRequest;

		  request.onreadystatechange = function() {
			if (request.readyState == 4) {
			  request.onreadystatechange = doNothing;
			  callback(request, request.status);
			}
		  };

		  request.open('GET', url, true);
		  request.send(null);
		}

		function doNothing() {}

		//]]>

		</script>
    </head>
    <body onload="load()">

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
    <div class="container" style="position: relative; top: 10px;">
		<p>
        <?php echo "<h3 style=\"font-size: 40px;\">$concert_name</h3>"; ?>
		<?php 
				$query1 = $dbo->getAverageRating($concert_id);
				$row_rating = $query1->fetch(PDO::FETCH_ASSOC);
				$avg_rating = $row_rating['avg_rating'];
		?>
		<div class="exemple">
			<div class="exemple5" data-average="<?php echo $avg_rating;?>" data-id="5"></div>
		</div>
		</p>
		<div class="row">
			<div class="col-sm-6 col-md-4">
				<div class="thumbnail">
					<!--<img data-src="holder.js/300x300" alt="...">-->
					<div class="caption">
					<?php 
						//echo $user_id;
						//echo $concert_id;
						$queryEntry = $dbo->checkCalendarEntry($user_id, $concert_id);
						if(!$queryEntry)
						{ ?>
						<form method="post" action="">
							<input type="hidden" id="hid_concert_id" name="hid_cal_concert_id" value="<?php echo $concert_id; ?>" />
							<button type="submit" class="btn btn-primary" name="btn_calendar" id="btn_calendar">Add to calendar</button>
						</form>
						<?php }?>
						<br/>
						<p>Description : <?php echo $concert_description ?></p>
						<p>Start: <?php echo $concert_sdate." ".$concert_stime ; ?> </p>
						<p>End: <?php echo $concert_edate." ".$concert_etime ; ?></p>	
						<p>Ticket cost: $<?php echo $concert_tcost; ?></p>
						<p>Capacity: <?php echo $concert_capacity; ?> persons</p>
						<p>Location: <div id="map" style="width: 335px; height: 300px"></div></p>
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-4">
				<div class="thumbnail">
					<!--<img data-src="holder.js/300x300" alt="...">-->
					<div class="caption">
						<h3>Bands Performing</h3>
						<p>
							<?php 
								$query1 = $dbo->getPerformingBands($concert_id);
								while($row_performingbands = $query1->fetch(PDO::FETCH_ASSOC))
								{ ?>
									<ul>
										<li>
											<a href="./band.php?band_name=<?php echo $row_performingbands['band_name']?>&redirected=true">
												<?php echo $row_performingbands['band_name']; ?>
											</a>
										</li>
									</ul>
							<?php   } ?>
						</p>
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-4">
				<div class="thumbnail">
					<!--<img data-src="holder.js/300x300" alt="...">-->
					<div class="caption">
						<h3>Tickets Available At</h3>
						<?php
							$query2 = $dbo->getTicketShopDetails($concert_id);
							while ($row_shops = $query2->fetch(PDO::FETCH_ASSOC))
							{	
								?>
							   <div class="row-fluid" style="margin-top: 20px;">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h3 class="panel-title">
												<span class="glyphicon glyphicon-paperclip" aria-hidden="true"></span>
												<?php echo $row_shops['shop_name']; ?>
											</h3>
											
										</div>
										<div class="panel-body">
											<p>Address : <?php echo $row_shops['shop_street'].", ".$row_shops['shop_city'].", ".$row_shops['shop_country']; ?></p>
											<p>Contact Name: <?php echo $row_shops['shop_oname']; ?></p>
											<p>Contact #: <?php echo $row_shops['shop_pnumber']; ?></p>
										</div><!-- panel-body -->
									</div><!-- Panel Profile Details -->
								</div>
							<?php } ?>
						</br>
					</div>
				</div>
			</div>
		</div><!-- / row-fluid -->
		<div class="row">
			<div class="col-sm-18 col-md-12">
				<div class="thumbnail">
					<div class="caption">
						<h3>Reviews</h3>
						<?php
							$query3 = $dbo->getUserDetails($user_id);
							$row_userdetails = $query3->fetch(PDO::FETCH_ASSOC);
							?>
							<form method="post" action="">
							<div class="row-fluid" style="margin-top: 20px;">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h3 class="panel-title">
											<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
												Write a review
											</h3>
										</div>
										<div class="panel-body">
											<textarea class="form-control" rows="3" id="txt_review" name="txt_review"></textarea>
											<input type="hidden" id="hid_concert_id" name="hid_concert_id" value="<?php echo $concert_id; ?>" />
											<br/>
											<button type="submit" class="btn btn-primary" name="btn_submit" id="btn_submit">Submit</button>
										</div><!-- panel-body -->
									</div><!-- Panel Profile Details -->
								</div>
							</form>
						<?php
							$query3 = $dbo->getReviews($concert_id);
							while ($row_reviews = $query3->fetch(PDO::FETCH_ASSOC))
							{	
								?>
							   <div class="row-fluid" style="margin-top: 20px;">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h3 class="panel-title">
												<a href="./profile.php?user_name=<?php echo $row_reviews['user_name']?>&redirected=true">
													<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
													<?php echo $row_reviews['user_fname'] . ' ' . $row_reviews['user_lname']; ?>	
												</a> @<?php echo $row_reviews['review_atime']; ?>
											</h3>
										</div>
										<div class="panel-body">
											<p><?php echo $row_reviews['user_review']?></p>
										</div><!-- panel-body -->
									</div><!-- Panel Profile Details -->
								</div>
							<?php } ?>
					</div>
				</div>
			</div>
		</div>
    </div>

    <?php include 'footer.php'; ?>
	<script type="text/javascript" src="jquery/jquery.js"></script>
	<script type="text/javascript" src="jquery/jRating.jquery.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('.exemple5').jRating({
				length:5,
				rateMax:5,
				decimalLength:1,
				onSuccess : function(element, rate){
					//alert('Success : your rate has been saved');
					var concert_id = "<?php echo $concert_id; ?>";
					var user_id = "<?php echo $user_id; ?>"; 
					$.ajax({
						type: "POST",
						dataType: "json",
						url:  "./php/jRating.php",
						data: { action:"rating",concert_id:concert_id , rate: rate,user_id:user_id },
						success: function (response) {
								alert("success");
							},
						error: function () {
							alert("error");
						}
						});
					alert(concert_id);
				},
				onError : function(){
					alert('Error : please retry');
				}
			});
		});
	</script>
    </body>
</html>
