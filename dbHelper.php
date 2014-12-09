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
			
			// define and prepare the sql statement with input parameter: username
			$queryString = $this->pdo->prepare("SELECT * FROM users WHERE user_name = :user_name");
			
            // bind parameters with user-input			
			$queryString->bindParam(':user_name',$user_name, PDO::PARAM_STR);
           
		    // execute prepared query		
			$queryString->execute();
			
			//fetch row associated with user_name input
			$row = $queryString->fetch();
						
			//if user_name input matches with password return log on details else throw exception			   
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
			
			// define and prepare the sql statement with input parameter: band name
			$queryString = $this->pdo->prepare("SELECT * FROM bands WHERE band_user_name=:band_user_name");
			
			// bind parameters with user-input	
			$queryString->bindParam(':band_user_name',$user_name, PDO::PARAM_STR);
			
			// execute prepared query	
			$queryString->execute();
			
			//fetch row associated with user_name input
			$row = $queryString->fetch();
			
			//if band name input matches with password return band details else throw exception	
			if ($row && $row['band_user_password'] == $user_password)
			   return $row;
			else
			   return false;
}
			
		 public function doLogout($user_id, $score)
        {
			try
			{
				$query = "Update users set user_laccess = now(),user_repo = user_repo+ :score where user_id= :user_id;";
				$queryString = $this->pdo->prepare($query);
				$queryString->bindParam(':score',$score, PDO::PARAM_INT);
				$queryString->bindParam(':user_id',$user_id, PDO::PARAM_INT);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return $queryString;
        }
		
        public function close()
        {
            $link =null;
        }
        public function userExists($username)
        {
            // define and prepare the sql statement with input parameter: band name
			$queryString = $this->pdo->prepare("SELECT * FROM users WHERE user_name = :user_name");
			
			// bind parameters with user-input	
			$queryString->bindParam(':user_name',$user_name, PDO::PARAM_STR);
			
			// execute prepared query
			$queryString->execute();
			
			// if there was a row selected (user name match found in the database)
			if($queryString->rowCount())
			   return true;
			// otherwise if a row wasn't selected (no user name match found in the database)   
			elseif(!$queryString->rowCount())  
			// display error message 
			   return false;
			
			
        }
		
		public function bandExists($bandname)
        {
            // define and prepare the sql statement with input parameter: band name
			$queryString = $this->pdo->prepare("SELECT * FROM bands WHERE band_name = :band_name");
			
			// bind parameters with user-input	
			$queryString->bindParam(':band_name',$band_name, PDO::PARAM_STR);
			
			// execute prepared query
			$queryString->execute();
			
			// if there was a row selected band name match found in the database)
			if($queryString->rowCount())
			   return true;
			// otherwise if a row wasn't selected (no band name match found in the database)   
			elseif(!$queryString->rowCount())  
			// display error message 
			   return false;
			
			
        }

        public function getAllUsers()
        {
            /**
            *   Returns a query
            */
			
			$queryString = $this->pdo->prepare("SELECT * FROM users INNER JOIN userDetail on users.username= userDetail.username");
			$queryString->execute();
			return $queryString;
			
            
        }

        public function getUserDetails($user_id)
        {
            try
			{
			$query = "SELECT * FROM users INNER JOIN userDetails on users.user_id = userDetails.user_id WHERE users.user_id LIKE :user_id";
			$queryString = $this->pdo->prepare($query);
			$queryString->bindParam(':user_id',$user_id, PDO::PARAM_INT);
			$queryString->execute();
			}
			catch(PDOException $pe)
			{
			echo $pe->getMessage();
			}
			return $queryString;
			
			
        }
		
		public function getLocations()
        {
            $queryString = $this->pdo->prepare("SELECT * FROM locations");
			$queryString->execute();
			return $queryString;
			
			
        }
		
		public function getBands()
        {
            $queryString = $this->pdo->prepare("SELECT * FROM bands");
			$queryString->execute();
			return $queryString; 
			
			
        } 
		
		public function getShops()
        {
            $queryString = $this->pdo->prepare("SELECT * FROM ticketshops");
			$queryString->execute();
			return $queryString; 
			
			
        } 
		
		 public function getBandDetails($band_id)
        {
            try
			{
			$query = "SELECT band_id, band_name, band_website, band_members FROM bands where band_id = :band_id";
			$queryString = $this->pdo->prepare($query);
			$queryString->bindParam(':band_id',$band_id, PDO::PARAM_INT);
			$queryString->execute();
			}
			catch(PDOException $pe)
			{
			echo $pe->getMessage();
			}
			return $queryString; 
			
			/* $queryString = $this->pdo->prepare("SELECT band_id, band_name, band_website, band_members FROM bands where band_id = $band_id");
			$queryString->execute();
			return $queryString; */
			
			
        }
		
		public function getUserId($user_name)
		{
			try
			{
			$query = "SELECT * FROM users WHERE users.user_name LIKE :user_name";
			$queryString = $this->pdo->prepare($query);
			$queryString->bindParam(':user_name',$user_name, PDO::PARAM_INT);
			$queryString->execute();
			}
			catch(PDOException $pe)
			{
			echo $pe->getMessage();
			}
			return $queryString; 
			
			/* $queryString = $this->pdo->prepare("SELECT * FROM users WHERE users.user_name LIKE $user_name");
			$queryString->execute();
			return $queryString; */  
			
			
		}
		
		public function getBandId($band_name)
		{
			try
			{
				$query = "SELECT band_id, band_name, band_members, band_website FROM bands WHERE bands.band_name LIKE '%$band_name%';";
				$queryString = $this->pdo->prepare($query);
				//$queryString->bindParam(':band_name',$band_name, PDO::PARAM_STR);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return $queryString;
		}
		
		public function getBandMembers($band_id)
		{
			try
			{
				$query = "Select u2.user_id,b.inband_position, b.band_joinedon, u2.user_fname, u2.user_lname, u2.user_dob, u1.user_name from bandmembers b, userdetails u2, users u1 where u1.user_id = b.user_id and u2.user_id = u1.user_id and b.band_id = :band_id;";
				$queryString = $this->pdo->prepare($query);
				$queryString->bindParam(':band_id',$band_id, PDO::PARAM_INT);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return $queryString;
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
												and u2.user_lname LIKE '%$searchBandMember%'";

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
				$queryInsert = "Insert into bandmembers (band_id, user_id, inband_position) values ($band_id, $user_id, '$inband_position');";
				$queryResult= $this->pdo->query($queryInsert);
				return true;
			}
		}
		
		public function getBandGenres($band_id)
		{
			try
			{
				$query = "Select g.genre_id, g.genre_name from genres g, bandgenres b where g.genre_id = b.genre_id and b.band_id = :band_id;";
				$queryString = $this->pdo->prepare($query);
				$queryString->bindParam(':band_id',$band_id, PDO::PARAM_INT);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return $queryString;
		}
		
		public function getUserGenres($user_id)
		{
			try
			{
				$query = "Select g.genre_id, g.genre_name from genres g, usergenres u where g.genre_id = u.genre_id and u.user_id = :user_id;";
				$queryString = $this->pdo->prepare($query);
				$queryString->bindParam(':user_id',$user_id, PDO::PARAM_INT);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return $queryString;
		}
		
		public function getPerformingConcerts($band_id)
		{
			try
			{
				$query = "select c.concert_id, c.concert_name, c.concert_sdate, c.concert_description from concerts c, performing p where p.band_id = :band_id and c.concert_id = p.concert_id;";
				$queryString = $this->pdo->prepare($query);
				$queryString->bindParam(':band_id',$band_id, PDO::PARAM_INT);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return $queryString;
		}
		public function getBandConcert($band_id,$start, $goFor)
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
            $queryString = "select c.concert_id, c.concert_name, c.concert_sdate, c.concert_description
							from concerts c, performing p
							where p.band_id = '$band_id'
							and c.concert_id = p.concert_id";
			
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
		
		public function getConcertIdName($concert_name,$start, $goFor)
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
			//echo "Hello";
            //base search:
            $queryString = "select concert_id 
							from concerts
							where concert_name like '%$concert_name%'";
			
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
		public function getPerformingBands($concert_id)
		{
			try
			{
				$query = "select b.band_name, b.band_id from bands b, performing p where b.band_id = p.band_id and p.concert_id = :concert_id;";
				$queryString = $this->pdo->prepare($query);
				$queryString->bindParam(':concert_id',$concert_id, PDO::PARAM_INT);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return $queryString;
		}
		
		public function getConcertDetails($concert_id)
		{
			try
			{
				$query = "select concert_id, concert_name, concert_sdate, concert_stime, concert_edate,concert_etime, concert_lid, concert_capacity, concert_tcost, concert_description from concerts where concert_id = :concert_id;";
				$queryString = $this->pdo->prepare($query);
				$queryString->bindParam(':concert_id',$concert_id, PDO::PARAM_INT);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return $queryString;
		}
		
		public function getConcertId($concert_name)
		{
			try
			{
				$query = "select concert_id from concerts where concert_name like '%:concert_name%';";
				$queryString = $this->pdo->prepare($query);
				$queryString->bindParam(':concert_name',$concert_name, PDO::PARAM_STR);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return $queryString;		
		}
		public function getUpcomingConcert($start, $goFor)
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
            $queryString = "select * from concerts where concert_sdate > date(now())";
			
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
		
		public function getUpcomingConcertPast($start, $goFor)
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
            $queryString = "select * from concerts where concert_sdate < date(now())";
			
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
		
		public function getUpcomingConcertAll($start, $goFor)
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
            $queryString = "select * from concerts";
			
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
		public function getUpcomingConcertMonth($start, $goFor)
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
            $queryString = "select * from concerts where datediff(concert_sdate, date(now())) between 0 and 30";
			
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
		
		public function getUpcomingConcertRecently($user_id, $start, $goFor)
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
            $queryString = "select * from users, concerts where concert_atime > user_laccess and user_id = '$user_id'";
			
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
		
		public function getUpcomingConcertWeek($start, $goFor)
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
            $queryString = "select * from concerts where datediff(concert_sdate, date(now())) between 0 and 7";
			
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
		
		public function getNumberOfFans($band_id)
		{
			try
			{
				$query = "select count(*) as total_fans from fanof where band_id = :band_id;";
				$queryString = $this->pdo->prepare($query);
				$queryString->bindParam(':band_id',$band_id, PDO::PARAM_INT);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return $queryString;
		}
		
		public function getTicketShopDetails($concert_id)
		{
			try
			{
				$query = "select t2.shop_id, t2.shop_name, t2.shop_street, t2.shop_city, t2.shop_country, t2.shop_oname, t2.shop_pnumber from tickets t1, ticketshops t2 where t1.concert_id = :concert_id and t1.shop_id = t2.shop_id";
				$queryString = $this->pdo->prepare($query);
				$queryString->bindParam(':concert_id',$concert_id, PDO::PARAM_INT);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return $queryString;
		}
		
		public function getThisMonthConcerts($user_id)
		{
			try
			{
				$query = "select c.concert_sdate,c.concert_name, c.concert_id from calendar p , concerts c where p.user_id = :user_id and p.concert_id = c.concert_id and month(concert_sdate) = month(now());";
				$queryString = $this->pdo->prepare($query);
				$queryString->bindParam(':user_id',$user_id, PDO::PARAM_INT);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return $queryString;
		}
		
		public function getAverageRating($concert_id)
		{
			try
			{
				$query = "select avg(rating) as avg_rating, concert_id from plans where concert_id = :concert_id and rated='yes';";
				$queryString = $this->pdo->prepare($query);
				$queryString->bindParam(':concert_id',$concert_id, PDO::PARAM_INT);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return $queryString;	
		}
		
		public function updatingRating($concert_id, $user_id, $rating)
		{
			try
			{
				$query = "Update plans set rating = :rating where concert_id= :concert_id and user_id = :user_id;";
				$queryString = $this->pdo->prepare($query);
				$queryString->bindParam(':rating',$concert_id, PDO::PARAM_STR);
				$queryString->bindParam(':concert_id',$concert_id, PDO::PARAM_INT);
				$queryString->bindParam(':user_id',$user_id, PDO::PARAM_INT);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return $queryString;
		}
		
		public function getReviews($concert_id)
		{
			try
			{
				$query = "select u1.user_id, p.user_review, u1.user_fname, u1.user_lname, u2.user_name, p.review_atime from plans p, userdetails u1, users u2 where p.concert_id = :concert_id and p.user_id = u1.user_id and u1.user_id = u2.user_id and p.reviewed = 'yes';";
				$queryString = $this->pdo->prepare($query);
				$queryString->bindParam(':concert_id',$concert_id, PDO::PARAM_INT);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return $queryString;
		}
		
		public function updateReview($user_id, $concert_id, $review)
		{
			try
			{
				$query = "SELECT * FROM plans WHERE user_id = :user_id and concert_id = :concert_id;";
				$queryString = $this->pdo->prepare($query);
				$queryString->bindParam(':concert_id',$concert_id, PDO::PARAM_INT);
				$queryString->bindParam(':user_id',$user_id, PDO::PARAM_INT);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
            if($queryString->rowCount() > 0)
			{
				$query = "Update plans set user_review = '$review',reviewed = 'yes' where user_id='$user_id' and concert_id ='$concert_id';";
			}
			else
			{
				$query = "Insert into plans (user_id, concert_id, user_review, reviewed) values ('$user_id', '$concert_id', '$review', 'yes');";
			}
			$queryString = $this->pdo->query($query);
			return $queryString;
		}
		public function updateUserReview($by_user_id, $to_user_id, $review)
		{
			$queryString = "Insert into userreviews (to_user_id, by_user_id, user_review) values ('$to_user_id','$by_user_id','$review');";
			$query = $this->pdo->query($queryString);
			$query->execute();
			$queryString1 = "select last_insert_id() as id";
			$query1 = $this->pdo->query($queryString1);
			$row = $query1->fetch(PDO::FETCH_ASSOC);
			$id = $row['id'];
			$queryString2 = "delete from userreviews where id=$id";
			$query2 = $this->pdo->query($queryString2);
		}
		
		public function getUserReviews($user_id)
		{
			try
			{
				$query = "select * from userreviews where to_user_id = $user_id;";
				$queryString = $this->pdo->prepare($query);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return $queryString;
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
			try
			{
				$query = "SELECT * FROM calendar WHERE user_id = :user_id and concert_id = :concert_id;";
				$queryString = $this->pdo->prepare($query);
				$queryString->bindParam(':concert_id',$concert_id, PDO::PARAM_INT);
				$queryString->bindParam(':user_id',$user_id, PDO::PARAM_INT);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return ($queryString->rowCount() > 0) ? true : false;
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
		public function getUserConcerts($user_id, $start, $goFor)
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
            $queryString = "SELECT * FROM plans";

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
		
		public function getGenreConcerts($genre_id, $start, $goFor)
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
            $queryString = "SELECT * FROM concertgenres";

            if (str_replace(' ', '', $genre_id) != '')
                $queryString = $queryString . " WHERE genre_id = '$genre_id'";

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
			try
			{
				$query = "delete FROM follows where following_uid=:user_id and followed_uid=:unfollow_uid;";
				$queryString = $this->pdo->prepare($query);
				$queryString->bindParam(':unfollow_uid',$unfollow_uid, PDO::PARAM_INT);
				$queryString->bindParam(':user_id',$user_id, PDO::PARAM_INT);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return $queryString;		
		}
		
		public function unfanBand($band_id, $user_id)
		{
			try
			{
				$query = "delete FROM fanof where user_id=:user_id and band_id=:band_id;";
				$queryString = $this->pdo->prepare($query);
				$queryString->bindParam(':band_id',$band_id, PDO::PARAM_INT);
				$queryString->bindParam(':user_id',$user_id, PDO::PARAM_INT);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return $queryString;
		}	
		
		public function followUsers($follow_uid, $user_id)
		{
			try
			{
				$query = "Insert into follows(following_uid, followed_uid) values (:user_id, :follow_uid)";
				$queryString = $this->pdo->prepare($query);
				$queryString->bindParam(':follow_uid',$follow_uid, PDO::PARAM_INT);
				$queryString->bindParam(':user_id',$user_id, PDO::PARAM_INT);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return $queryString;
		}
		
		public function becomeFanOf($user_id, $band_id)
		{
			try
			{
				$query = "Insert into fanof (user_id, band_id) values (:user_id, :band_id)";
				$queryString = $this->pdo->prepare($query);
				$queryString->bindParam(':band_id',$band_id, PDO::PARAM_INT);
				$queryString->bindParam(':user_id',$user_id, PDO::PARAM_INT);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return $queryString;
		}

        public function getUserPhotos($username)
        {
            $queryString = $this->pdo->prepare("SELECT * FROM photos WHERE owner='%s' ORDER BY id DESC,$username");
			$queryString->execute();
			return $queryString;
        }

        public function changePassword($username, $newPassword)
        {
            $queryString = $this->pdo->prepare("UPDATE users SET password='$newPassword' WHERE username = '$username'");
			$queryString->execute();
			return $queryString;
			
			
        }

        public function deleteUser($username)
        {
            if ($this->userExists($username))
            {
                $queryString = $this->pdo->prepare("DELETE FROM users WHERE username = '$username'");
			    $queryString->execute();
			    return $queryString;
				
				
            }
            else
                return false;
        }

        public function updateProfileInfo($user_id, $newValue, $type)
        {
			$tableName ="";
            $detailFields = array('user_description', 'user_gender', 'user_fname', 'user_lname', 'user_city','user_state','user_country','user_zipcode', 'user_dob','user_street', 'user_email');
            $userFields = array('user_password');
            if (in_array($type, $detailFields))
                $tableName = "userdetails";
            elseif (in_array($type, $userFields))
                $tableName = "users";
            $queryString = "UPDATE ".$tableName." SET $type='$newValue' WHERE user_id='$user_id'";

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
		
		public function createList($list_name, $user_id)
        {
			try 
			{
				$sql = 'CALL insert_list(:list_name,:user_id,@list_id)';
				$stmt = $this->pdo->prepare($sql);
				$stmt->bindParam(':list_name', $list_name, PDO::PARAM_STR);
				$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
				$stmt->execute();
				$stmt->closeCursor();
				$r = $this->pdo->query("SELECT @list_id AS list_id")->fetch(PDO::FETCH_ASSOC);
				//if ($r) 
				//{
					//echo sprintf('Customer is %s', $r['user_id']);
				//}
				return $r['list_id'];
			} 
			catch (PDOException $pe) 
			{
				die("Error occurred:" . $pe->getMessage());
			}
        }
		
		 public function insertConcertByBand($concert_name,$concert_sdate,$concert_stime, $concert_edate, $concert_etime, $concert_lid, $concert_tcost, $concert_capacity, $concert_description)
        {
				
				try 
				{
					// execute the stored procedure
					$sql = 'CALL insert_concerts_by_band(:concert_name, :concert_sdate, :concert_stime, :concert_edate, :concert_etime, :concert_lid, :concert_tcost, :concert_capacity, :concert_description, @concert_id)';
					$stmt = $this->pdo->prepare($sql);
					$stmt->bindParam(':concert_name', $concert_name, PDO::PARAM_STR);
					$stmt->bindParam(':concert_sdate', $concert_sdate, PDO::PARAM_STR);
					$stmt->bindParam(':concert_stime', $concert_stime, PDO::PARAM_STR);
					$stmt->bindParam(':concert_edate', $concert_edate, PDO::PARAM_STR);
					$stmt->bindParam(':concert_etime', $concert_etime, PDO::PARAM_STR);
					$stmt->bindParam(':concert_lid', $concert_lid, PDO::PARAM_STR);
					$stmt->bindParam(':concert_tcost', $concert_tcost, PDO::PARAM_STR);
					$stmt->bindParam(':concert_capacity', $concert_capacity, PDO::PARAM_STR);
					$stmt->bindParam(':concert_description', $concert_description, PDO::PARAM_STR);
					$stmt->execute();
					$stmt->closeCursor();
					// execute the second query to get customer's level
					$r = $this->pdo->query("SELECT @concert_id AS concert_id")->fetch(PDO::FETCH_ASSOC);
					if ($r) 
					{
						//echo sprintf('Customer is %s', $r['user_id']);
					}
					return $r['concert_id'];
				} 
				catch (PDOException $pe) 
				{
					die("Error occurred:" . $pe->getMessage());
				}
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
            $queryString = $this->pdo->prepare("select distinct level2 as p_genre_id, genre_name as p_genre_name from view_genre_category, genres where level2 = genre_id");
			$queryString->execute();
			return $queryString;
        }
		
		public function getSubGenre($p_genre_id)
		{
			try
			{
				$query = "select distinct level3 as s_genre_id, genre_name as s_genre_name from view_genre_category, genres where level2 = :p_genre_id and level3 = genre_id;";
				$queryString = $this->pdo->prepare($query);
				$queryString->bindParam(':p_genre_id',$p_genre_id, PDO::PARAM_INT);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return $queryString;
		}
		
		public function getGenreId($genre_name)
		{
			try
			{
				$query = "select genre_id from genres where genre_name = :genre_name;";
				$queryString = $this->pdo->prepare($query);
				$queryString->bindParam(':genre_name',$genre_name, PDO::PARAM_STR);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return $queryString;
		}
		
		public function deleteBandGenres($band_id)
        {
			try
			{
				$query = "delete FROM band_genres WHERE band_id = :band_id;";
				$queryString = $this->pdo->prepare($query);
				$queryString->bindParam(':band_id',$band_id, PDO::PARAM_INT);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return $queryString;
        }
		
		public function updateBandGenres($genre_id, $band_id)
        {
			try
			{
				$query = "insert into bandgenres (band_id, genre_id) values (:band_id, :genre_id);";
				$queryString = $this->pdo->prepare($query);
				$queryString->bindParam(':band_id',$band_id, PDO::PARAM_INT);
				$queryString->bindParam(':genre_id',$genre_id, PDO::PARAM_INT);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return $queryString;
        }
		
		public function insertBandConcert($concert_id, $band_id)
        {
			try
			{
				$query = "insert into performing (band_id, concert_id) values (:band_id, :concert_id);";
				$queryString = $this->pdo->prepare($query);
				$queryString->bindParam(':band_id',$band_id, PDO::PARAM_INT);
				$queryString->bindParam(':concert_id',$concert_id, PDO::PARAM_INT);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return $queryString;
        }
		public function insertGenreConcert($concert_id, $genre_id)
        {
			try
			{
				$query = "insert into concertgenres(genre_id, concert_id) values (:genre_id, :concert_id);";
				$queryString = $this->pdo->prepare($query);
				$queryString->bindParam(':genre_id',$genre_id, PDO::PARAM_INT);
				$queryString->bindParam(':concert_id',$concert_id, PDO::PARAM_INT);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return $queryString;
        }
		public function insertGenreList($list_id, $genre_id)
        {
			try
			{
				$query = "insert into listgenres(genre_id, list_id) values (:genre_id, :list_id);";
				$queryString = $this->pdo->prepare($query);
				$queryString->bindParam(':genre_id',$genre_id, PDO::PARAM_INT);
				$queryString->bindParam(':list_id',$list_id, PDO::PARAM_INT);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return $queryString;
        }
		public function insertShopConcert($concert_id, $shop_id)
        {
			try
			{
				$query = "Insert into tickets(shop_id, concert_id) values (:shop_id, :concert_id);";
				$queryString = $this->pdo->prepare($query);
				$queryString->bindParam(':shop_id',$shop_id, PDO::PARAM_INT);
				$queryString->bindParam(':concert_id',$concert_id, PDO::PARAM_INT);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return $queryString;	
        }
		
        public function addPhoto($user_id,$user_image)
        {
           try
			{
				$query = "update userdetails set user_image = :user_image where user_id=:user_id;";
				$queryString = $this->pdo->prepare($query);
				$queryString->bindParam(':user_image',$user_image, PDO::PARAM_STR);
				$queryString->bindParam(':user_id',$user_id, PDO::PARAM_INT);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return $queryString;	
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
		
		public function getRecommendedList($user_id, $start, $goFor)
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
            $queryString = "select * from recommendedlist";

            if (str_replace(' ', '', $user_id) != '')
                $queryString = $queryString . " where user_id='$user_id'";

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
		
		public function getListConcerts($list_id)
        {
			try
			{
				$query = "select * from lists where list_id= :list_id;";
				$queryString = $this->pdo->prepare($query);
				$queryString->bindParam(':list_id',$list_id, PDO::PARAM_INT);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return $queryString;
        }
		
		public function deleteList($list_id,$user_id)
		{
			try 
			{
				$sql = 'CALL delete_list(:list_id,:user_id)';
				$stmt = $this->pdo->prepare($sql);
				$stmt->bindParam(':list_id', $list_id, PDO::PARAM_INT);
				$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
				$stmt->execute();
				$stmt->closeCursor();
			} 
			catch (PDOException $pe) 
			{
				die("Error occurred:" . $pe->getMessage());
			}
		}
		
		public function getConcertNotInList($list_id,$start, $goFor)
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
            $queryString = "select * from concerts where concert_id not in (select concert_id from lists where list_id = '$list_id')";
			
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
		
		public function addConcertToList($list_id, $concert_id)
        {
			try
			{
				$query = "Insert into lists(list_id, concert_id) values (:list_id, :concert_id);";
				$queryString = $this->pdo->prepare($query);
				$queryString->bindParam(':list_id',$list_id, PDO::PARAM_INT);
				$queryString->bindParam(':concert_id',$concert_id, PDO::PARAM_INT);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return $queryString;
        }
		
		public function getRecommendedConcert($user_id)
		{
			try
			{
				$query = "select c.concert_id, c.concert_name, count(*) from lists l1, concerts c where l1.list_id in (select list_id from listgenres l, usergenres u where l.genre_id in (select genre_id from usergenres where user_id=:user_id)) and c.concert_id = l1.concert_id and c.concert_id not in (select concert_id from lists l, recommendedlist r where l.list_id = r.list_id and r.user_id = :user_id) group by c.concert_id having count(*) > 1;";
				$queryString = $this->pdo->prepare($query);
				$queryString->bindParam(':user_id',$user_id, PDO::PARAM_STR);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return $queryString;
		}
		
		public function deleteUserGenres($user_id)
        {
			try
			{
				$query = "delete FROM usergenres WHERE user_id = :user_id";
				$queryString = $this->pdo->prepare($query);
				$queryString->bindParam(':user_id',$user_id, PDO::PARAM_INT);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return $queryString;
        }
		
		public function insertUserGenres($genre_id, $user_id)
        {
			try
			{
				$query = "insert into usergenres (genre_id, user_id) values (:genre_id, :user_id);";
				$queryString = $this->pdo->prepare($query);
				$queryString->bindParam(':genre_id',$genre_id, PDO::PARAM_INT);
				$queryString->bindParam(':user_id',$user_id, PDO::PARAM_INT);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return $queryString;
        }
		
		public function getFollowSuggestions($user_id)
        {
			try
			{
				$query = "select distinct u2.user_id from usergenres u1, usergenres u2 where u1.user_id = :user_id and u1.user_id <> u2.user_id and u2.genre_id in (select genre_id from usergenres where user_id=:user_id) and u2.user_id not in (select followed_uid from follows where following_uid=:user_id) group by u2.user_id;";
				$queryString = $this->pdo->prepare($query);
				$queryString->bindParam(':user_id',$user_id, PDO::PARAM_INT);
				$queryString->execute();
			}
			catch(PDOException $pe)
			{
				echo $pe->getMessage();
			}
			return $queryString;
        }
    }
?>

