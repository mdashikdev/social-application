<?php 
include("db.php");
session_start();
	$current_user=$_SESSION['user_id'];
	$post_content=$_REQUEST['pst_cntnt'];
	$tempname=$_FILES['pst_img']['tmp_name'];
	$randName=uniqid()."ashik_creation";
	$randId=uniqid();
	$owner="0";
	if (isset($_REQUEST['owner'])) {
		$owner=$_REQUEST['owner'];
	}else{
		$owner='0';
	}
	if (!empty($post_content) && !empty($tempname)) {
		//post content and image
		$query=mysqli_query($conn,"INSERT INTO posts (post_user_id,post_content,post_image,post_code,post_owner) VALUES ('$current_user','$post_content','$randName.jpg','$randId','$owner') ");
		if ($query) {
			$save_file=move_uploaded_file($tempname, "../images/$randName.jpg");
			if ($save_file) {
				echo "Posted your status";
			}
		}else{
			echo "failed";
		}
	}elseif (!empty($tempname)) {
		//post image
		$query=mysqli_query($conn,"INSERT INTO posts (post_user_id,post_image,post_code,post_owner) VALUES ('$current_user','$randName.jpg','$randId','$owner') ");
		if ($query) {
			move_uploaded_file($tempname, "../images/$randName.jpg");
			echo "Posted your image";
		}
	}elseif (!empty($post_content)) {
		//post content
		$query=mysqli_query($conn,"INSERT INTO posts (post_user_id,post_content,post_code,post_owner) VALUES ('$current_user','$post_content','$randId','$owner') ");
		if ($query) {
			echo "Posted";
		}
	}else{
		echo "failed to post";
	}




?>