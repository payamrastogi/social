<?php
    //Fill in the blanks:
    $databaseEngine = "mysql";

    $databaseHost = "localhost";
    $databaseUsername = "root";
    $databasePassword = "root";
    $databaseName = "musicdb";

    //for testing on a local development server:
    if ($_SERVER['SERVER_NAME'] == 'localhost')
    {
        $databaseHost = "localhost";
		$databaseUsername = "root";
		$databasePassword = "root";
		$databaseName = "musicdb";
    }
?>
