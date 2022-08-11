<?php
include("db.php");
session_start();
$current_user=$_SESSION['user_id'];

$query=mysqli_query($conn,"SELECT * FROM users WHERE unique_id='$current_user' ");
$dbinfo=mysqli_fetch_assoc($query);
$dbname=$dbinfo['name'];
$dbemail=$dbinfo['email'];
$dbpass=$dbinfo['pass'];
$dbaddr=$dbinfo['address'];
if (empty($dbaddr)) {
	$dbaddr.="Bangladesh";
}

//Update Profile Info
$name=$_REQUEST['nm'];
$email=$_REQUEST['eml'];
$pass=$_REQUEST['pwd'];
$address=$_REQUEST['addr'];

if (empty($name)) {
	$name=$dbname;
}
if (empty($email)) {
	$email=$dbemail;
}
if (empty($pass)) {
	echo $pass=$dbpass;
}
if (empty($address)) {
	$address=$dbaddr;
}

$query2=mysqli_query($conn,"UPDATE users SET name='$name', email='$email', pass='$pass', address='$address' WHERE unique_id='$current_user' ");
if ($query2) {
	echo "Updated Your Profile";
}else{
	echo "Updating Failed";
}


?>