<?php
$servername = "localhost";
$username = "root";
$password = "";
$db="my_application";

// Create connection
$conn = new mysqli($servername, $username, $password,$db);

if (!$conn) {
	echo "connection Failed";
}

?>