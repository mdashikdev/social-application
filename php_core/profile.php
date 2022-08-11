<?php
include("db.php");
session_start();
$current_user=$_SESSION['user_id'];
$tempname=$_FILES['profile_photo']['tmp_name'];
$post_content=$_REQUEST['profile_caption'];
$randName=uniqid()."ashik_creation";
$randId=uniqid();
$owner="0";
if (isset($_REQUEST['owner'])) {
	$owner=$_REQUEST['owner'];
}else{
	$owner='0';
}
$query=mysqli_query($conn,"UPDATE users SET profile='$randName.jpg' WHERE unique_id='$current_user' ");
if ($query) {
	$upload_tmp=move_uploaded_file($tempname, "../images/$randName.jpg");
	if ($upload_tmp) {
		if (!empty($post_content) && !empty($tempname)) {
			//post content and image
			$query=mysqli_query($conn,"INSERT INTO posts (post_user_id,post_content,post_image,post_code,post_owner,post_user_status) VALUES ('$current_user','$post_content','$randName.jpg','$randId','$owner','profile upload') ");
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
			$query=mysqli_query($conn,"INSERT INTO posts (post_user_id,post_image,post_code,post_owner,post_user_status) VALUES ('$current_user','$randName.jpg','$randId','$owner','profile upload') ");
			if ($query) {
				move_uploaded_file($tempname, "../images/$randName.jpg");
				echo "Posted your profile photo";
			}
		}else{
			echo "failed to post";
		}
	}
}else{
	echo "failed upload";
}

?>