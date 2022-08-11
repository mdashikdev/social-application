<?php 
include("db.php");
	$name=$_REQUEST['nm'];
	$email=$_REQUEST['eml'];
	$password=$_REQUEST['pass'];
	$unId=uniqid();

if (!empty($name) && !empty($email) && !empty($password)) {
		$sql_for_email="SELECT email FROM users WHERE email='$email' ";
		$query_for_email=mysqli_query($conn,$sql_for_email);
		$email_count=mysqli_num_rows($query_for_email);
		if ($email_count > 0) {
			echo "'.$eml.' This email was already registered";
		}else{
			$sql_for_register="INSERT INTO users (unique_id, name,email,pass,user_type) VALUES ('$unId','$name','$email','$password','user')";
			$query_for_register=mysqli_query($conn,$sql_for_register);
			if ($query_for_register) {
				echo "Your Account Registered! Now you can Login your account...";
			}else{
				echo "Failed";
			}
		}
}else{
	echo "All field required";
}

?>