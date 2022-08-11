<?php 
include("db.php");
session_start();
$current_user=$_SESSION['user_id'];
$usr_id=$_REQUEST["cover_photo_change_id"];
$tempname=$_FILES['cover_photo']['tmp_name'];
$randName=uniqid()."ashik_creation";
$randId=uniqid();
$query=mysqli_query($conn,"UPDATE users SET cover_photo='$randName.jpg' WHERE unique_id='$usr_id' ");
$owner="0";
if (isset($_REQUEST['owner'])) {
	$owner=$_REQUEST['owner'];
}else{
	$owner='0';
}
	if ($query) {
		$upload_tmp=move_uploaded_file($tempname, "../images/$randName.jpg");
		if ($upload_tmp) {
		if (!empty($tempname)) {
			//post image
			$query=mysqli_query($conn,"INSERT INTO posts (post_user_id,post_image,post_code,post_owner,post_user_status) VALUES ('$current_user','$randName.jpg','$randId','$owner','group cover upload') ");
			if ($query) {
				move_uploaded_file($tempname, "../images/$randName.jpg");
				echo "Posted your image";
			}
		}
	}
	}else{
		echo "failed upload";
	}


?>