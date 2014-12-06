<?php

    // ini_set('error_reporting', E_ALL);
    // ini_set( 'display_errors', 1 );
    //
    /**
    * provides a dbHelper object with methods we'll need in the app
    */
    class db
    {
        private $pdo = null;

        //private
        /***********************************************/

        public function __construct()
        {
            require_once 'database_connect.php';
            $this->pdo = new PDO("$databaseEngine:host=$databaseHost;dbname=$databaseName",$databaseUsername, $databasePassword);
        }

        public function verifyLogin($user_name, $user_password)
        {
            /**
            *   Returns false if can't log in else returns the user data
            */
            $queryString = sprintf("SELECT * FROM users WHERE user_name='%s'", $user_name);
            $query = $this->pdo->query($queryString);
            $row = $query->fetch(PDO::FETCH_ASSOC);

            if ($row && $row['user_password'] == $user_password)
                return $row;
            else
                return false;
        }
		
		public function verifyBandLogin($user_name, $user_password)
        {
            /**
            *   Returns false if can't log in else returns the user data
            */
            $queryString = "SELECT * FROM bands WHERE band_user_name='$user_name'";
            $query = $this->pdo->query($queryString);
            $row = $query->fetch(PDO::FETCH_ASSOC);

            if ($row && $row['band_user_password'] == $user_password)
                return $row;
            else
                return false;
        }
		
        public function close()
        {
            $link =null;
        }
        public function userExists($username)
        {
            $queryString ="SELECT * FROM `users` WHERE user_name = '$username'";
            $query = $this->pdo->query($queryString);

            return ($query->rowCount() > 0) ? true : false;
        }
		
		public function bandExists($bandname)
        {
            $queryString ="SELECT * FROM `bands` WHERE band_name = '$bandname'";
            $query = $this->pdo->query($queryString);

            return ($query->rowCount() > 0) ? true : false;
        }

        public function getAllUsers()
        {
            /**
            *   Returns a query
            */
            $queryString = "SELECT * FROM `users` INNER JOIN userDetail on users.username=userDetail.username";
            $query = $this->pdo->query($queryString);

            return $query;
        }

        public function getUserDetails($user_id)
        {
            $queryString = "SELECT * FROM users INNER JOIN userDetails on users.user_id=userDetails.user_id WHERE users.user_id LIKE '$user_id'";
            $query = $this->pdo->query($queryString);

            return $query;
        }
		
		 public function getBandDetails($band_id)
        {
            $queryString = "SELECT band_id, band_name, band_website, band_members FROM bands where band_id = '$band_id'";
            $query = $this->pdo->query($queryString);
            return $query;
        }
		
		public function getUserId($user_name)
		{
			$queryString = "SELECT * FROM users WHERE users.user_name LIKE '$user_name'";
			$query = $this->pdo->query($queryString);
			return $query;
		}
		
		public function getBandId($band_name)
		{
			$queryString = "SELECT band_id, band_name, band_members, band_website FROM bands WHERE bands.band_name LIKE '$band_name'";
			$query = $this->pdo->query($queryString);
			return $query;
		}
		
		public function getBandMembers($band_id)
		{
			$queryString = "Select u2.user_id,b.inband_position, b.band_joinedon, u2.user_fname, u2.user_lname, u2.user_dob, u1.user_name
							from bandmembers b, userdetails u2, users u1
							where u1.user_id = b.user_id
							and u2.user_id = u1.user_id
							and b.band_id = '$band_id';";
			$query = $this->pdo->query($queryString);
			return $query;
		}
		
		public function getAllBandMembers($band_id, $start, $goFor)
        {
            /**
            *   $start:
            *       the start of the LIMIT. Expects -1 if no LIMIT
            *   $goFor:
            *       the end of the LIMIT. Expects -1 if no LIMIT
            */

            //TODO: better search
            $start = (int)$start;
            $goFor = (int)$goFor;

            //base search:
            $queryString = "SELECT user_id, band_id FROM bandmembers";

            if (str_replace(' ', '', $band_id) != '')
                $queryString = $queryString . " WHERE band_id = '$band_id'";

            if ($goFor != -1 && $start == -1)
				$queryString = $queryString .  " LIMIT 0, $goFor";

            elseif ($start != -1 && $goFor == -1)
				$queryString = $queryString .  " LIMIT $start, 2372662636281763";

            elseif($start != -1 && $goFor != -1)
                $queryString = $queryString .  " LIMIT $start, $goFor";

            $query = $this->pdo->query($queryString);
			//echo $query;
            return $query;
        }
		
		public function searchMembers($searchBandMember, $start, $goFor)
        {
            /**
            *   $start:
            *       the start of the LIMIT. Expects -1 if no LIMIT
            *   $goFor:
            *       the end of the LIMIT. Expects -1 if no LIMIT
            */

            //TODO: better search
            $start = (int)$start;
            $goFor = (int)$goFor;

            //base search:
            $queryString = "SELECT u1.user_id, u1.user_name, u2.user_fname, u2.user_lname FROM users u1, userdetails u2";

            if (str_replace(' ', '', $searchBandMember) != '')
                $queryString = $queryString . " WHERE u1.user_id = u2.user_id
												and  u1.user_name LIKE '%$searchBandMember%' 
												union
												SELECT u1.user_id, u1.user_name, u2.user_fname, u2.user_lname FROM users u1, userdetails u2
												WHERE u1.user_id = u2.user_id
												and  u2.user_fname LIKE '%$searchBandMember%'
												union 
												SELECT u1.user_id, u1.user_name, u2.user_fname, u2.user_lname FROM users u1, userdetails u2
												WHERE u1.user_id = u2.user_id
												and u2.user_lname LIKE '%$searchBandMember%';";

            if ($goFor != -1 && $start == -1)
				$queryString = $queryString .  " LIMIT 0, $goFor";

            elseif ($start != -1 && $goFor == -1)
				$queryString = $queryString .  " LIMIT $start, 2372662636281763";

            elseif($start != -1 && $goFor != -1)
                $queryString = $queryString .  " LIMIT $start, $goFor";

            $query = $this->pdo->query($queryString);
			//echo $query;
            return $query;
        }
		
		public function addBandMember($band_id, $user_id, $inband_position)
		{
			$queryString = "Select * from bandmembers where band_id = '$band_id' and user_id = '$user_id';";
			$query = $this->pdo->query($queryString);
			if($query->rowCount() > 0)
			{
				return false;
			}
			else
			{
				echo "hello1234";
				echo $band_id;
				echo $user_id;
				echo $inband_position;
				$queryInsert = "Insert into bandmembers (band_id, user_id, inband_position) values ($band_id, $user_id, '$inband_position');";
				$queryResult= $this->pdo->query($queryInsert);
				return true;
			}
		}
		
		public function getBandGenres($band_id)
		{
			$queryString = "Select g.genre_id, g.genre_name 
							from genres g, bandgenres b
							where g.genre_id = b.genre_id
							and b.band_id = '$band_id'";
			$query = $this->pdo->query($queryString);
			return $query;
		}
		
		public function getUserGenres($user_id)
		{
			$queryString = "Select g.genre_id, g.genre_name 
							from genres g, usergenres u
							where g.genre_id = u.genre_id
							and u.user_id = '$user_id'";
			$query = $this->pdo->query($queryString);
			return $query;
		}
		
		public function getPerformingConcerts($band_id)
		{
			$queryString = "select c.concert_id, c.concert_name, c.concert_sdate, c.concert_description
							from concerts c, performing p
							where p.band_id = '$band_id'
							and c.concert_id = p.concert_id;";
			$query = $this->pdo->query($queryString);
			return $query;
		}
		
		public function getPerformingBands($concert_id)
		{
			$queryString = "select b.band_name, b.band_id
							from bands b, performing p
							where b.band_id = p.band_id
							and p.concert_id = '$concert_id';";
			$query = $this->pdo->query($queryString);
			return $query;
		}
		
		public function getConcertDetails($concert_id)
		{
			$queryString = "select concert_id, concert_name, concert_sdate, concert_stime, concert_edate,concert_etime, concert_lid, concert_capacity, 			concert_tcost, concert_description
							from concerts
							where concert_id = '$concert_id';";
			$query = $this->pdo->query($queryString);
			return $query;
		}
		
		public function getNumberOfFans($band_id)
		{
			$queryString = "select count(*) as total_fans 
							from fanof
							where band_id ='$band_id';";
			$query = $this->pdo->query($queryString);
			return $query;
		}
		
		public function getTicketShopDetails($concert_id)
		{
			$queryString = "select t2.shop_id, t2.shop_name, t2.shop_street, t2.shop_city, t2.shop_country, t2.shop_oname, t2.shop_pnumber 
							from tickets t1, ticketshops t2
							where t1.concert_id = '$concert_id'
							and t1.shop_id = t2.shop_id;";
			$query = $this->pdo->query($queryString);
			return $query;
		}
		
		public function getThisMonthConcerts($user_id)
		{
			$queryString = "select c.concert_sdate,c.concert_name, c.concert_id  
							from plans p , concerts c
							where p.user_id = '$user_id'
							and p.concert_id = c.concert_id
							and month(concert_sdate) = month(now())
							and plan <> 'No';";
			$query = $this->pdo->query($queryString);
			return $query;
		}
		
		public function getAverageRating($concert_id)
		{
			$queryString = "select avg(rating) as avg_rating, concert_id 
							from plans
							where concert_id = '$concert_id'
							and rated='yes';";
			$query = $this->pdo->query($queryString);
			return $query;
		}
		
		public function updatingRating($concert_id, $user_id, $rating)
		{
			$queryString = "Update plans set rating = '$rating'
							where concert_id='$concert_id'
								and user_id = '$user_id';";
			$query = $this->pdo->query($queryString);
			return $query;
		}
		
		public function getReviews($concert_id)
		{
			$queryString = "select u1.user_id, p.user_review, u1.user_fname, u1.user_lname, u2.user_name, p.review_atime
							from plans p, userdetails u1, users u2
							where p.concert_id = '$concert_id'
							and p.user_id = u1.user_id	
							and u1.user_id = u2.user_id
							and p.reviewed = 'yes';
							order by p.review_atime desc";
			$query = $this->pdo->query($queryString);
			return $query;
		}
		
		public function updateReview($user_id, $concert_id, $review)
		{
			$queryString ="SELECT * FROM plans WHERE user_id = '$user_id' and  concert_id='$concert_id'";
            $query = $this->pdo->query($queryString);

            if($query->rowCount() > 0)
			{
				$queryString = "Update plans 
								set user_review = '$review',
								reviewed = 'yes'
								where user_id='$user_id'
								and concert_id ='$concert_id';";
			}
			else
			{
				$queryString = "Insert into plans (user_id, concert_id, user_review, reviewed) values ('$user_id', '$concert_id', '$review', 'yes');";
			}
			$query = $this->pdo->query($queryString);
		}
		
		public function addIntoCalendar($user_id, $concert_id)
		{
			if (!$this->checkCalendarEntry($user_id, $concert_id))
			{
				$queryString = "Insert into calendar (user_id, concert_id) values ('$user_id', '$concert_id');";
				$query = $this->pdo->query($queryString);
			}
			else return false;
		}
		
		public function checkCalendarEntry($user_id, $concert_id)
		{
			$queryString ="SELECT * FROM calendar WHERE user_id = '$user_id' and concert_id = '$concert_id'";
            $query = $this->pdo->query($queryString);

            return ($query->rowCount() > 0) ? true : false;
		}
		
        public function searchUsers($searchTerm, $start, $goFor)
        {
            /**
            *   $start:
            *       the start of the LIMIT. Expects -1 if no LIMIT
            *   $goFor:
            *       the end of the LIMIT. Expects -1 if no LIMIT
            */

            //TODO: better search
            $start = (int)$start;
            $goFor = (int)$goFor;

            //base search:
            $queryString = "SELECT * FROM users INNER JOIN userDetails on users.user_id=userDetails.user_id";

            if (str_replace(' ', '', $searchTerm) != '')
                $queryString = $queryString . " WHERE users.user_name LIKE '%$searchTerm%'";

            if ($goFor != -1 && $start == -1)
				$queryString = $queryString .  " LIMIT 0, $goFor";

            elseif ($start != -1 && $goFor == -1)
				$queryString = $queryString .  " LIMIT $start, 2372662636281763";

            elseif($start != -1 && $goFor != -1)
                $queryString = $queryString .  " LIMIT $start, $goFor";

            $query = $this->pdo->query($queryString);
			//echo $query;
            return $query;
        }
		
		public function searchBands($searchBandName, $start, $goFor)
        {
            /**
            *   $start:
            *       the start of the LIMIT. Expects -1 if no LIMIT
            *   $goFor:
            *       the end of the LIMIT. Expects -1 if no LIMIT
            */

            //TODO: better search
            $start = (int)$start;
            $goFor = (int)$goFor;

            //base search:
            $queryString = "SELECT band_id, band_name, band_members, band_website FROM bands";

            if (str_replace(' ', '', $searchBandName) != '')
                $queryString = $queryString . " WHERE bands.band_name LIKE '%$searchBandName%'";

            if ($goFor != -1 && $start == -1)
				$queryString = $queryString .  " LIMIT 0, $goFor";

            elseif ($start != -1 && $goFor == -1)
				$queryString = $queryString .  " LIMIT $start, 2372662636281763";

            elseif($start != -1 && $goFor != -1)
                $queryString = $queryString .  " LIMIT $start, $goFor";

            $query = $this->pdo->query($queryString);
			//echo $query;
            return $query;
        }
		
		public function getFollowers($user_id, $start, $goFor)
        {
            /**
            *   $start:
            *       the start of the LIMIT. Expects -1 if no LIMIT
            *   $goFor:
            *       the end of the LIMIT. Expects -1 if no LIMIT
            */

            //TODO: better search
            $start = (int)$start;
            $goFor = (int)$goFor;

            //base search:
            $queryString = "SELECT followed_uid FROM follows";

            if (str_replace(' ', '', $user_id) != '')
                $queryString = $queryString . " WHERE following_uid = '$user_id'";

            if ($goFor != -1 && $start == -1)
				$queryString = $queryString .  " LIMIT 0, $goFor";

            elseif ($start != -1 && $goFor == -1)
				$queryString = $queryString .  " LIMIT $start, 2372662636281763";

            elseif($start != -1 && $goFor != -1)
                $queryString = $queryString .  " LIMIT $start, $goFor";

            $query = $this->pdo->query($queryString);
			//echo $query;
            return $query;
        }
		
		public function getFanOf($user_id, $start, $goFor)
        {
            /**
            *   $start:
            *       the start of the LIMIT. Expects -1 if no LIMIT
            *   $goFor:
            *       the end of the LIMIT. Expects -1 if no LIMIT
            */

            //TODO: better search
            $start = (int)$start;
            $goFor = (int)$goFor;

            //base search:
            $queryString = "SELECT band_id FROM fanof";

            if (str_replace(' ', '', $user_id) != '')
                $queryString = $queryString . " WHERE user_id = '$user_id'";

            if ($goFor != -1 && $start == -1)
				$queryString = $queryString .  " LIMIT 0, $goFor";

            elseif ($start != -1 && $goFor == -1)
				$queryString = $queryString .  " LIMIT $start, 2372662636281763";

            elseif($start != -1 && $goFor != -1)
                $queryString = $queryString .  " LIMIT $start, $goFor";

            $query = $this->pdo->query($queryString);
			//echo $query;
            return $query;
        }
		
		public function unfollowUsers($unfollow_uid, $user_id)
		{
			$queryString = "delete FROM follows where following_uid=$user_id and followed_uid=$unfollow_uid";
			$query = $this->pdo->query($queryString);
			return $query;
		}
		
		public function unfanBand($band_id, $user_id)
		{
			$queryString = "delete FROM fanof where user_id=$user_id and band_id=$band_id";
			$query = $this->pdo->query($queryString);
			return $query;
		}
		
		public function followUsers($follow_uid, $user_id)
		{
			$queryString = "Insert into follows(following_uid, followed_uid) values ($user_id, $follow_uid)";
			$query = $this->pdo->query($queryString);
			return $query;
		}
		
		public function becomeFanOf($user_id, $band_id)
		{
			$queryString = "Insert into fanof (user_id, band_id) values ($user_id, $band_id)";
			$query = $this->pdo->query($queryString);
			return $query;
		}

        public function getUserPhotos($username)
        {
            $queryString = sprintf("SELECT * FROM photos WHERE owner='%s' ORDER BY id DESC",
                $username);
            $query = $this->pdo->query($queryString);

            return $query;
        }

        public function changePassword($username, $newPassword)
        {
            $queryString = "UPDATE users SET password='$newPassword' WHERE username = '$username'";
            $query = $this->pdo->query($queryString);
        }

        public function deleteUser($username)
        {
            if ($this->userExists($username))
            {
                $queryString = "DELETE FROM users WHERE username = '$username'";
                $query = $this->pdo->query($queryString);
            }
            else
                return false;
        }

        public function updateProfileInfo($user_id, $newValue, $type)
        {
            /**
            *   $type:
            *       the field to updateProfileInfo
            */
			$tableName ="";
            $detailFields = array('user_desc', 'user_gender', 'user_fname', 'user_lname', 'user_city','user_state','user_country','user_zipcode', 'user_dob');
            $userFields = array('user_password');
            if (in_array($type, $detailFields))
                $tableName = "userdetails";
            elseif (in_array($type, $userFields))
                $tableName = "users";
            $queryString = "UPDATE".$tableName." SET $type='$newValue' WHERE user_id='$user_id'";

            $query = $this->pdo->query($queryString);
        }

        public function createUser($user_fname,$user_lname,$user_name, $user_password, $user_email)
        {
            if (! $this->userExists($user_name))
            {
				
                //$queryString = "INSERT INTO users (username, password, userType) VALUES
                //('$username', '$password', 'user')";
				//$queryString = "CALL insert_user_details($user_fname, $user_lname, $user_name, $user_password, $user_email)";
                //$query = $this->pdo->query($queryString);
                //return $query;
				try 
				{
					// execute the stored procedure
					$sql = 'CALL insert_user_details(:user_name,:user_password,:user_fname,:user_lname,:user_email,@user_id)';
					$stmt = $this->pdo->prepare($sql);
 
 
					$stmt->bindParam(':user_fname', $user_fname, PDO::PARAM_STR);
					$stmt->bindParam(':user_lname', $user_lname, PDO::PARAM_STR);
					$stmt->bindParam(':user_name', $user_name, PDO::PARAM_STR);
					$stmt->bindParam(':user_password', $user_password, PDO::PARAM_STR);
					$stmt->bindParam(':user_email', $user_email, PDO::PARAM_STR);
					$stmt->execute();
					$stmt->closeCursor();
					// execute the second query to get customer's level
					$r = $this->pdo->query("SELECT @user_id AS user_id")->fetch(PDO::FETCH_ASSOC);
					if ($r) 
					{
						echo sprintf('Customer is %s', $r['user_id']);
					}
					return $r['user_id'];
				} 
				catch (PDOException $pe) 
				{
					die("Error occurred:" . $pe->getMessage());
				}
            }
            else
                return false;
        }
		
		
		 public function createBand($band_name,$band_user_name,$band_user_password, $band_website, $band_members)
        {
            if (! $this->bandExists($band_user_name))
            {
				try 
				{
					// execute the stored procedure
					$sql = 'CALL insert_band_details(:band_name,:band_user_name,:band_user_password,:band_website,:band_members,@band_id)';
					$stmt = $this->pdo->prepare($sql);
 
					$stmt->bindParam(':band_name', $band_name, PDO::PARAM_STR);
					$stmt->bindParam(':band_user_name', $band_user_name, PDO::PARAM_STR);
					$stmt->bindParam(':band_user_password', $band_user_password, PDO::PARAM_STR);
					$stmt->bindParam(':band_website', $band_website, PDO::PARAM_STR);
					$stmt->bindParam(':band_members', $band_members, PDO::PARAM_STR);
					$stmt->execute();
					$stmt->closeCursor();
					// execute the second query to get customer's level
					$r = $this->pdo->query("SELECT @band_id AS band_id")->fetch(PDO::FETCH_ASSOC);
					if ($r) 
					{
						echo sprintf('Customer is %s', $r['band_id']);
					}
					return $r['band_id'];
				} 
				catch (PDOException $pe) 
				{
					die("Error occurred:" . $pe->getMessage());
				}
            }
            else
                return false;
        }
		
		public function getParentGenre()
        {
            $queryString = "select distinct level2 as p_genre_id, genre_name as p_genre_name from view_genre_category, genres where level2 = genre_id";
            $query = $this->pdo->query($queryString);
            return $query;
        }
		
		public function getSubGenre($p_genre_id){
			$queryString = "select distinct level3 as s_genre_id, genre_name as s_genre_name from view_genre_category, genres where level2 = '$p_genre_id' and level3 = genre_id;";
            $query = $this->pdo->query($queryString);

            return $query;
		}
		
		public function deleteBandGenres($band_id)
        {
            $queryString = "delete FROM band_genres WHERE band_id = $band_id";
            $query = $this->pdo->query($queryString);
        }
		
		public function updateBandGenres($genre_id, $band_id)
        {
            $queryString = "insert into bandgenres (band_id, genre_id) values ($band_id, $genre_id);";
            $query = $this->pdo->query($queryString);
        }
		
        public function newPhoto($username, $album, $url, $name)
        {
            $queryString = sprintf(
            "INSERT INTO photos (owner, album, name, url) VALUES ('%s','%s', '%s','%s')",
            $username, $album, $name, $url);
            $query = $this->pdo->query($queryString);
        }
		
		public function generateMapXML($location_id)
		{
			$queryString = "SELECT * FROM locations WHERE location_id='$location_id';";
			$query = $this->pdo->query($queryString);
			if (!$query) 
			{
				die('Invalid query: ' . mysql_error());
			}

			header("Content-type: text/xml");
			
			// Start XML file, echo parent node
			echo '<markers>';

			// Iterate through the rows, adding XML nodes for each
			while ($row= $query->fetch(PDO::FETCH_ASSOC))
			{
				// ADD TO XML DOCUMENT NODE
				echo '<marker ';
				echo 'name="' . $this->parseToXML($row['location_name']) . '" ';
				echo 'address="' . $this->parseToXML($row['location_street']) . '" ';
				echo 'lat="' . $row['location_latitude'] . '" ';
				echo 'lng="' . $row['location_longitude'] . '" ';
				echo 'type="' . $row['location_type'] . '" ';
				echo '/>';
			}

			// End XML file
			echo '</markers>';
		}
		
		public function parseToXML($htmlStr)
		{	
			$xmlStr=str_replace('<','&lt;',$htmlStr);
			$xmlStr=str_replace('>','&gt;',$xmlStr);
			$xmlStr=str_replace('"','&quot;',$xmlStr);
			$xmlStr=str_replace("'",'&#39;',$xmlStr);
			$xmlStr=str_replace("&",'&amp;',$xmlStr);
			return $xmlStr;
		}
    }
?>

