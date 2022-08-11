<?php
include("db.php");
if (isset($_REQUEST['id'])) {
	echo $id=$_REQUEST['id'];
}
$query=mysqli_query($conn,"UPDATE users SET actv_status='Offline' WHERE unique_id='$id' ");
if ($query) {
	session_start();
	session_destroy();
	header("location:../index.php");
}

?>