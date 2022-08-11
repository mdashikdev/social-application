<?php
include("db.php");
session_start();

	$email=$_REQUEST['eml'];
	$password=$_REQUEST['pass'];
	$sql_for_email_pass="SELECT * FROM users WHERE email='$email' AND pass='$password' ";
	$query_for_email_pass=mysqli_query($conn,$sql_for_email_pass);
	$acc_count=mysqli_num_rows($query_for_email_pass);
	if ($acc_count==1) {
		$get_unique_id=mysqli_fetch_assoc($query_for_email_pass);
		$get_user_id=$get_unique_id['unique_id'];
		$_SESSION['user_id'] = $get_user_id;
		if ($get_unique_id) {
			$query=mysqli_query($conn,"UPDATE users SET actv_status='Online' WHERE email='$email' AND pass='$password' ");
			if ($query) {
				echo "Congrats! You Are Successfully Logged In";
			}
		}else{
			echo "Can not get id from server!!";
		}
	}else{
		echo "Incorrect Email Or Password";
	}

?>