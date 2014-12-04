<?php
   //TODO: make this visible to only logged in users
    session_start();
    require 'dbHelper.php';
	$dbo = new db();
    if (! isset($_SESSION['username']))
    {
        $dbo->close();
        unset($dbo);
        header('Location: ./logout.php');
    }
	else
	{
		$user = $_SESSION['username'];
		$userId = $_SESSION['userId'];
		$query = $dbo->getUserDetails($user);

		if ($row = $query->fetch(PDO::FETCH_ASSOC))
		{
			$fullName = $row['fName'] . ' ' . $row['lName'];
			$about = $row['about'];
			$profilePic = $dbo->getProfilePhoto($userId);
			$country = $row['originCountry'];
			$state = $row['originCity'];
			$gender = $row['sex'];
		}
   }
?>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="stylesheet.css">
		<title><?php echo "$fullName"; ?></title>
		<script>
		function followBand(button) 
		{
			if (button.value=="") 
			{
				document.getElementById("txtHint").innerHTML="";
				return;
			} 
			if (window.XMLHttpRequest) 
			{
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			} 
			else 
			{ // code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function() 
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200) 
				{
					//document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
					button.innerHTML = '-';
					button.disabled='disabled';
				}
			}
			xmlhttp.open("GET","followBandUser.php?ftoId="+button.value,true);
			xmlhttp.send();
		}
		function unfollowBand(button) 
		{
			if (button.value=="") 
			{
				document.getElementById("txtHint").innerHTML="";
				return;
			}	 
			if (window.XMLHttpRequest) 
			{
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			} 
			else 
			{ // code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function() 
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200) 
				{
					//document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
					button.innerHTML = '+';
					button.disabled='disabled';
				}
			}
			xmlhttp.open("GET","unfollowBandUser.php?ftoId="+button.value,true);
			xmlhttp.send();
		}
		function rateBand(button) 
		{
			if (button.value=="") {
			document.getElementById("txtHint").innerHTML="";
			return;
		} 
		if (window.XMLHttpRequest) 
		{
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		} 
		else 
		{ 	// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function() 
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200) 
			{
				//document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
				alert('Thank you for Rating !');
				button.disabled='disabled';
			}
		}
		xmlhttp.open("GET","rateBandConcert.php?rateRtoId="+button.value+"&type=BAND",true);
		xmlhttp.send();
	}
	function recoBand(value) 
	{
		if (value=="") 
		{
			document.getElementById("txtHint").innerHTML="";
			return;
		} 
		if (window.XMLHttpRequest) 
		{
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		} 
		else 
		{ 
			// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function() 
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200) 
			{
				//document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
				alert('Thank you for Recommendation !');
			}
		}
		xmlhttp.open("GET","recoBandConcert.php?recoToId="+value+"&type=BAND",true);
		xmlhttp.send();
	}
	function followUser(button) 
	{
		if (button.value=="") 
		{
			document.getElementById("txtHint").innerHTML="";
			return;
		} 
  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else { // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
      //document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
	  button.innerHTML = '-';
	  button.disabled='disabled';
    }
  }
  xmlhttp.open("GET","followBandUser.php?ftoId="+button.value,true);
  xmlhttp.send();
  }
function unfollowUser(button) {
  if (button.value=="") {
    document.getElementById("txtHint").innerHTML="";
    return;
  } 
  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else { // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
      //document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
	  button.innerHTML = '+';
	  button.disabled='disabled';
    }
  }
  xmlhttp.open("GET","unfollowBandUser.php?ftoId="+button.value,true);
  xmlhttp.send();
}
</script>
   </head>
   <body style="background-color: #f1f1f1;">
      <?php include 'header.php'; ?>
      <div style="width:1100px;position: relative;">
      <table >
         <tr>
            <td>
               <div style="border:2px solid #a1a1a1;
                  margin-top:20px;
                  font-size:15px;
                  padding:10px 40px; 
                  box-shadow: 0px 0px 20px 3px #d3d3d3;
                  border-radius: 4px;
                  background-color: #ffffff;
                  width:250px;
                  height : 800px">
               <div>
                  <img src="<?php echo "$profilePic"; ?>"  height="60" width="60">
                  <b><a href="profile.php?user=<?php echo "$user"; ?>"><?php echo "$fullName"; ?></a></b>
               </div>
               <hr>
               <h3><a href="#" style="color: #8AC007;background-color: transparent;">Bands </a></h3>
               <div>
                  <b><a href="#" style="color: #0088cc;background-color: transparent;" >Bands You are Following</a></b>
                  <div style="margin-top:5px;width:270px;height:100px;overflow:auto;">
                     <table>
                        <?php
                           $query = $dbo->getBandByUser($userId);
                           while ($row = $query->fetch(PDO::FETCH_ASSOC))
                           {?>
                        <tr>
                           <td>
                              <table>
                                 <tr>
                                    <td><img src="<?php echo $dbo->getProfilePhoto($row['bandId']); ?>"  height="40" width="40"></td>
                                 </tr>
                                 <tr>
                                    <td><button class="btn btn-primary" style="width:40px; border-radius: 40px;" value="<?php echo $row['bandId'] ?>" name="unfollow" onclick="unfollowBand(this)"><b>-</b></button></td>
                                 </tr>
                              </table>
                           </td>
                           <td>
                              <table>
                                 <tr>
                                    <td><font size="3"><b><?php echo $row['bandName'] ?></b></font></td>
                                 </tr>
                                 <tr>
                                    <td><font size="2" color="grey"><?php echo $row['bandDesc'] ?></font></td>
                                 </tr>
                                 <tr>
                                    <td>
                                       <div class="starRating" id="<?php echo $row['bandId'] ?>_star">
                                          <div>
                                             <div>
                                                <div>
                                                   <div>
                                                      <input style="width:24px;" id="rating1" type="radio" name="rating_<?php echo $row['bandId'] ?>" value="1:<?php echo $row['bandId'] ?>" onChange="rateBand(this)">
                                                      <label for="rating1"><span>1</span></label>
                                                   </div>
                                                   <input style="width:24px;" id="rating2" type="radio" name="rating_<?php echo $row['bandId'] ?>" value="2:<?php echo $row['bandId'] ?>" onChange="rateBand(this)">
                                                   <label for="rating2"><span>2</span></label>
                                                </div>
                                                <input style="width:24px;" id="rating3" type="radio" name="rating_<?php echo $row['bandId'] ?>" value="3:<?php echo $row['bandId'] ?>" onChange="rateBand(this)">
                                                <label for="rating3"><span>3</span></label>
                                             </div>
                                             <input style="width:24px;" id="rating4" type="radio" name="rating_<?php echo $row['bandId'] ?>" value="4:<?php echo $row['bandId'] ?>" onChange="rateBand(this)">
                                             <label for="rating4"><span>4</span></label>
                                          </div>
                                          <input style="width:24px;" id="rating5" type="radio" name="rating_<?php echo $row['bandId'] ?>" value="5:<?php echo $row['bandId'] ?>" onChange="rateBand(this)">
                                          <label for="rating5"><span>5</span></label>
                                       </div>
									   <img style="margin-left:5px;" src="share-icon-128x128.png" height="25" width="25" onClick="recoBand('<?php echo $row['bandId'] ?>')">
                                    </td>
                                 </tr>
                              </table>
                        </tr>
                        <?php
                           }
                           ?>
                     </table>
                  </div>
               </div>
               <hr>
               <div>
                  <b><a href="#" style="color: #0088cc;background-color: transparent;" >Bands You May Like</a></b>
				  <div style="margin-top:5px;width:270px;height:100px;overflow:auto;">
                     <table>
                        <?php
                           $query = $dbo->getSuggestedBandsForUser($userId);
                           while ($row = $query->fetch(PDO::FETCH_ASSOC))
                           {?>
                        <tr>
                           <td>
                              <table>
                                 <tr>
                                    <td><img src="<?php echo $dbo->getProfilePhoto($row['bandId']); ?>"  height="40" width="40"></td>
                                 </tr>
                                 <tr>
                                    <td><button class="btn btn-primary" style="width:40px; border-radius: 40px;" value="<?php echo $row['bandId'] ?>" name="follow" onclick="followBand(this)"><b>+</b></button></td>
                                 </tr>
                              </table>
                           </td>
                           <td>
                              <table>
                                 <tr>
                                    <td><font size="3"><b><?php echo $row['bandName'] ?></b></font></td>
                                 </tr>
                                 <tr>
                                    <td><font size="2" color="grey"><?php echo $row['bandDesc'] ?></font></td>
                                 </tr>
                                 <tr>
                                    <td>
										Rating: <font size="3" color="#8AC007"><b><?php echo $row['avgRating'] ?> </b></font>| Followers: <font size="3" color="#8AC007"><b><?php echo $row['cnt'] ?> </b></font>
                                    </td>
                                 </tr>
                              </table>
                        </tr>
                        <?php
                           }
                           ?>
                     </table>
                  </div>
               </div>
               <div>
                  <hr>
                  <div>
                     <b><a href="#" style="color: #0088cc;background-color: transparent;" >Bands Recommended By Friend</a></b>
					 <div style="margin-top:5px;width:270px;height:100px;overflow:auto;">
                     <table>
                        <?php
                           $query = $dbo->getRecoBand($userId);
                           while ($row = $query->fetch(PDO::FETCH_ASSOC))
                           {?>
                        <tr>
                           <td>
                              <table>
                                 <tr>
                                    <td><img src="<?php echo $dbo->getProfilePhoto($row['bandId']); ?>"  height="40" width="40"></td>
                                 </tr>
                                 <tr>
                                    <td><button class="btn btn-primary" style="width:40px; border-radius: 40px;" value="<?php echo $row['bandId'] ?>" name="follow" onclick="followBand(this)"><b>+</b></button></td>
                                 </tr>
                              </table>
                           </td>
                           <td>
                              <table>
                                 <tr>
                                    <td><font size="3"><b><?php echo $row['bandName'] ?></b></font></td>
                                 </tr>
                                 <tr>
                                    <td><font size="2" color="grey"><?php echo $row['bandDesc'] ?></font></td>
                                 </tr>
                                 <tr>
                                    <td>
									Rating: <font size="3" color="#8AC007"><b><?php echo $row['avgRating'] ?> </b></font>| Followers: <font size="3" color="#8AC007"><b><?php echo $row['cnt'] ?> </b></font>
                                    </td>
                                 </tr>
                              </table>
                        </tr>
                        <?php
                           }
                           ?>
                     </table>
					</div>
                  </div>
                  <h3><a href="#" style="color: #8AC007;background-color: transparent;">Friends </a></h3>
                  <div>
                     <b><a href="#" style="color: #0088cc;background-color: transparent;" >Friends </a></b>
					 <div style="margin-top:5px;width:270px;height:100px;overflow:auto;">
                     <table>
                        <?php
                           $query = $dbo->getFriendByUser($userId);
                           while ($row = $query->fetch(PDO::FETCH_ASSOC))
                           {?>
                        <tr>
                           <td>
                              <table>
                                 <tr>
                                    <td><img src="<?php echo $dbo->getProfilePhoto($row['userId']); ?>"  height="40" width="40"></td>
                                 </tr>
                                 <tr>
                                    <td><button class="btn btn-primary" style="width:40px; border-radius: 40px;" value="<?php echo $row['userId'] ?>" name="unfollow" onclick="unfollowUser(this)"><b>-</b></button></td>
                                 </tr>
                              </table>
                           </td>
                           <td>
                              <table>
                                 <tr>
                                    <td><font size="3"><b><?php echo $row['fName'] . ' ' . $row['lName'] ?></b></font></td>
                                 </tr>
                                 <tr>
                                    <td><font size="2" color="grey"><?php echo $row['about']; ?></font></td>
                                 </tr>
                                 <tr>
                                    <td>
										Trust Score: <font size="3" color="#8AC007"><b><?php echo $row['trustScore'] ?> </b></font> | Followers: <font size="3" color="#8AC007"><b><?php echo $row['cnt'] ?> </b></font>
                                    </td>
                                 </tr>
                              </table>
                        </tr>
                        <?php
                           }
                           ?>
                     </table>
                  </div>
                  </div>
                  <!--<hr>
                  <div>
                     <b><a href="#" style="color: #0088cc;background-color: transparent;" >Friends You may Like</a></b>
                  </div>-->
               </div>
            </td>
            <td colspan=4>
               <div style="
                  border:2px solid #a1a1a1;
                  margin-top:20px;
                  font-size:15px;
                  padding:10px 40px; 
                  box-shadow: 0px 0px 20px 3px #d3d3d3;
                  border-radius: 4px;
                  background-color: #ffffff;
                  width:650px;
                  height : 800px">
                  <h2>Live Feeds</h2>
				  
                  <hr>
               </div>
            </td>
            <td>
               <div style="
                  border:2px solid #a1a1a1;
                  margin-top:20px;
                  font-size:15px;
                  padding:10px 40px; 
                  box-shadow: 0px 0px 20px 3px #d3d3d3;
                  border-radius: 4px;
                  background-color: #ffffff;
                  width:250px;
                  height : 800px">
                  <h3><a href="#" style="color: #8AC007;background-color: transparent;">Concerts </a></h3>
				  <a href="addConcert.php" style="color: #0088cc;background-color: transparent;" ><button class="btn btn-primary" style="width:40px; border-radius: 40px;" ><b>+</button> Add Concert</b></a>
				  <hr>
                  <div>
                     <b><a href="#" style="color: #0088cc;background-color: transparent;">Upcoming Concerts</a></b>
					 concerts rsvp by user and concert date >= now()
					 give recommendation button and no of rsvp for concert
                  </div>
                  <hr>
                  <div>
                     <b><a href="#" style="color: #0088cc;background-color: transparent;">Concerts Attended</a></b>
					 concerts rsvp by user and concert date < now()
					 give rating button and no of rsvp for concert
                  </div>
                  <hr>
                  <div>
                     <b><a href="#" style="color: #0088cc;background-color: transparent;">Concerts You may Like</a></b>
					 System suggestion for concert
					 no of rsvp for concert
                  </div>
                  <div>
                     <hr>
                     <div>
                        <b><a href="#" style="color: #0088cc;background-color: transparent;">Concerts Recommended By Friend</a></b>
						user recommendation table (ref. see band recommendation by frnd query)
						no of rsvp for concert
                     </div>
                  </div>
				  <hr>
				  <h3><a href="#" style="color: #8AC007;background-color: transparent;">Friends </a></h3>
                  <div>
                     <b><a href="#" style="color: #0088cc;background-color: transparent;" >Friends You may Like </a></b>
					 <div style="margin-top:5px;width:270px;height:100px;overflow:auto;">
                     <table>
                        <?php
                           $query = $dbo->getSuggestedFriend($userId);
                           while ($row = $query->fetch(PDO::FETCH_ASSOC))
                           {?>
                        <tr>
                           <td>
                              <table>
                                 <tr>
                                    <td><img src="<?php echo $dbo->getProfilePhoto($row['userId']); ?>"  height="40" width="40"></td>
                                 </tr>
                                 <tr>
                                    <td><button class="btn btn-primary" style="width:40px; border-radius: 40px;" value="<?php echo $row['userId'] ?>" name="follow" onclick="followUser(this)"><b>+</b></button></td>
                                 </tr>
                              </table>
                           </td>
                           <td>
                              <table>
                                 <tr>
                                    <td><font size="3"><b><?php echo $row['fName'] . ' ' . $row['lName'] ?></b></font></td>
                                 </tr>
                                 <tr>
                                    <td><font size="2" color="grey"><?php echo $row['about']; ?></font></td>
                                 </tr>
                                 <tr>
                                    <td>
										Trust Score: <font size="3" color="#8AC007"><b><?php echo $row['trustScore'] ?> </b></font> | Followers: <font size="3" color="#8AC007"><b><?php echo $row['cnt'] ?> </b></font>
                                    </td>
                                 </tr>
                              </table>
                        </tr>
                        <?php
                           }
                           ?>
                     </table>
                  </div>
                  </div>
            </td>
         </tr>
      </table>
      </div>
   </body>