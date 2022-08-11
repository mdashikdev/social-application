<?php 
include("db.php");
session_start();
$current_user=$_SESSION['user_id'];


if (isset($_REQUEST["snt_msg"])) {
	if ($_REQUEST["snt_msg"]=="sent message") {
		$id= $_REQUEST["usrId"];
		$msg= $_REQUEST["msg"];
		$sql="INSERT INTO msgs (receive_user_id,sent_user_id,msg) VALUES ('$id','$current_user','$msg') ";
		$query=mysqli_query($conn,$sql);
		if ($query) {
			echo "ok";
		}else{
			echo "failed";
		}
	}
}

?>