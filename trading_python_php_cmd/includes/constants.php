<?php

//Database Constants local

define("DB_SERVER", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "binance");


//database constanst server
// define("DB_SERVER", "localhost");
// define("DB_USER", "xxx");
// define("DB_PASS", "xxx");
// define("DB_NAME", "theylen_crypto");

// 1. Create a database connection
$connection = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
if (!$connection) {
	die("Database connection failed: " . mysql_error());
}
//$query = "SET time_zone = '+00:07'";
//mysqli_query($connection, $query);
?>