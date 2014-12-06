<?php
	session_start();
	require 'dbHelper.php';
    $dbo = new db();
	$user_id = '';
	$successMessage = '';
	
	if (!isset($_SESSION['sess_band_user_name']))
    {
        if (! isset($_SESSION['sess_band_user_name']))
        {
            header('Location: ./logout.php');
        }
        else
		{
            $band_user_name = $_SESSION['sess_band_user_name'];
			if (!isset($_SESSION['sess_band_user_id']))
			{
				$band_id = $_SESSION['sess_band_id'];
			}
		}
    }
    else
    {
        $band_user_name = $_SESSION['sess_band_user_name'];
		$band_id = $_SESSION['sess_band_id'];
    }
    $queryAll = $dbo->getAllBandMembers($band_id, -1, -1); //Used for counting rows
	
    $numResults = $queryAll->rowCount();
    $resultsPerPage = 2;
    $numPages = ceil($numResults / $resultsPerPage);

    if (isset($_GET['page']) && (int)$_GET['page'] <= $numPages && $_GET['page'] != '')
        $page = (int)$_GET['page'];
    else
        $page = 1;

    $startFrom = ($page - 1) * $resultsPerPage;

    $query = $dbo->getAllBandMembers($band_id, $startFrom, $resultsPerPage);
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
					<a href="bandHome.php?band_user_name=<?php echo $band_user_name; ?>">Home</a>
				</li>
				<?php 
					if( isset($band_user_name) || (isset($inband_position) && $inband_position = 'band manager'))
					{?>
						<li class=""><a href="addMember.php">Add Member</a></li>
						<li class="active"><a href="addConcert.php">Add Concert</a></li>
				<?php }?>
			</ul>
			<?php
				if (isset($successMessage) && $successMessage != '')
					echo "<div class=\"alert alert-success\" id=\"formSuccess\" style=\"position: relative; top: 10px;\">
					<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
					<strong>Success! </strong> $successMessage
					</div>";
			?>
           <?php
				$row = $query->fetch(PDO::FETCH_ASSOC);
				$user_id = $row['user_id'];
				$query1 = $dbo->getUserDetails($user_id);
				$row_userdetails = $query1->fetch(PDO::FETCH_ASSOC);
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
													<input type="text" class="form-control" name="txt_concert_name" placeholder="Concert Name"/>
												</div>
												<div class="input-group">
													<label for="txt_sdate">Concert description</label>
													<textarea class="form-control" name="txt_concert_desription	" placeholder="Concert description..."  id=""></textarea>
												</div>
												<div class="input-group">
													<label for="txt_sdate">Start date</label>
													<input type="text" class="form-control" placeholder="Start date" name="example1" id="example1"/>
												</div>
												<div class="input-group">
													<div class="col-md-12">
														<label for="txt_sdate">Start time</label>
														<div class="input-group bootstrap-timepicker">
														<input id="timepicker1" type="text" class="form-control">
															<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
														</div>
													</div>
												</div>
												<div class="input-group">
													<label for="txt_sdate">End date</label>
													<input type="text" class="form-control" placeholder="End date" name="example2" id="example2"/>
												</div>
												<div class="input-group">
													<div class="col-md-12">
														<label for="txt_sdate">End time</label>
														<div class="input-group bootstrap-timepicker">
														<input id="timepicker2" type="text" class="form-control">
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
													<select class="form-control">
													  <option>1</option>
													  <option>2</option>
													  <option>3</option>
													  <option>4</option>
													  <option>5</option>
													</select>
												</div>
												<div class="input-group">
													<label for="txt_bandperforming">Band Performing</label>
													<select multiple class="form-control">
													  <option>1</option>
													  <option>2</option>
													  <option>3</option>
													  <option>4</option>
													  <option>5</option>
													</select>
												</div>
											</div>
										</div>
										<div class="col-sm-6 col-md-4">
											<div class="thumbnail">
												<div class="input-group">
													<label for="txt_concert_tcost">Ticket Cost</label>
													<input type="text" class="form-control" name="txt_concert_tcost" placeholder="Ticket Cost"/>
												</div>
												<div class="input-group">
													<label for="txt_concert_tshop">Tickets Available @</label>
													<select multiple class="form-control">
													  <option>1</option>
													  <option>2</option>
													  <option>3</option>
													  <option>4</option>
													  <option>5</option>
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
            <nav>
              <ul class="pagination">

                <?php

                if ($page == 1)
                    echo "<li class='disabled'><a href='#'>Prev</a></li>";
                else
                {
                    $previousPage = $page - 1;
                    echo "<li><a href='./addMember.php?page=$previousPage'>Prev</a></li>";
                }

                for ($i=1; $i < $numPages+1; $i++)
                {
                    $theClass = '';
                    if($i == $page)
                        $theClass = 'active';
                    echo "<li class='$theClass'><a href='./addMember.php?page=$i'>$i</a></li>";
                }

                if ($page == $numPages)
                    echo "<li class='disabled'><a href='#'>Next</a></li>";
                else
                {
                    $nextPage = $page+1;
                    echo "<li><a href='./addMember.php?page=$nextPage'>Next</a></li>";
                }

                ?>
              </ul>
            </nav>
        </div> <!-- /container -->
        <?php include 'footer.php'; ?>
		<script src="./js/jquery-1.9.1.min.js"></script>
        <script src="./js/bootstrap-datepicker.js"></script>
		<script src="./js/bootstrap-timepicker.min.js"></script>
        <script type="text/javascript">
            // When the document is ready
            $(document).ready(function () {
                
                $('#example1').datepicker({
                    format: "dd/mm/yyyy"
                });  
				$('#example2').datepicker({
                    format: "dd/mm/yyyy"
                });
				
				$('#timepicker1').timepicker();
				$('#timepicker2	').timepicker();

            });
        </script>
    </body>
</html>
