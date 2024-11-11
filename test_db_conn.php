<?php

include_once('_admin/base.inc.php');

// Retrieve the database credentials from constants
$dbdriver = DBDRIVER;
$dbhost = DBHOST;
$dbuser = DBUSER;
$dbpass = DBPASS;
$dbname = DBNAME;

// Attempt to connect to the MySQL database
$conn = @mysql_connect($dbhost, $dbuser, $dbpass);

// Check if the connection was successful
if (!$conn) {
    // Connection failed, display the error
    echo 'Failed to connect to MySQL: ' . mysql_error();
    exit; // Exit the script if the connection fails
}

// Try selecting the database
$dbname_selected = @mysql_select_db($dbname, $conn);

// Check if the database selection was successful
if (!$dbname_selected) {
    // Database selection failed, display the error
    echo 'Failed to select the database: ' . mysql_error();
    exit; // Exit the script if the database selection fails
}

// If the script reaches here, it means the connection and database selection were successful
echo 'Successfully connected to the database: ' . $dbname;

?>