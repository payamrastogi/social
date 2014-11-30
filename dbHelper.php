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
        public function close()
        {
            $link =null;
        }
        public function userExists($username)
        {
            $queryString ="SELECT * FROM `users` WHERE username = '$username'";
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
		
		public function getUserId($user_name)
		{
			$queryString = "SELECT * FROM users WHERE users.user_name LIKE '$user_name'";
			$query = $this->pdo->query($queryString);
			return $query;
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

            //if ($goFor != -1 && $start == -1)
            //    $queryString = $queryString .  " LIMIT 0, $goFor";

            //elseif ($start != -1 && $goFor == -1)
            //    $queryString = $queryString .  " LIMIT $start, 2372662636281763";

            //elseif($start != -1 && $goFor != -1)
            //    $queryString = $queryString .  " LIMIT $start, $goFor";

            $query = $this->pdo->query($queryString);
			//echo $query;
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

        public function createUser($username, $password, $email)
        {
            if (! $this->userExists($username))
            {
                $queryString = "INSERT INTO users (username, password, userType) VALUES
                ('$username', '$password', 'user')";
                $query = $this->pdo->query($queryString);

                $queryString = "INSERT INTO userDetail (username, email) VALUES
                ('$username', '$email')";
                $query = $this->pdo->query($queryString);

                return true;
            }
            else
                return false;
        }

        public function newPhoto($username, $album, $url, $name)
        {
            $queryString = sprintf(
            "INSERT INTO photos (owner, album, name, url) VALUES ('%s','%s', '%s','%s')",
            $username, $album, $name, $url);
            $query = $this->pdo->query($queryString);
        }
    }
?>

