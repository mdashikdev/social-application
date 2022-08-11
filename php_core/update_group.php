<?php
include("db.php");
//Update Group Info
$name=$_REQUEST['nm'];
$grp_id=$_REQUEST['grp_id'];
$query1=mysqli_query($conn,"SELECT * FROM users WHERE unique_id='$grp_id' ");
$feth_info=mysqli_fetch_assoc($query1);
if ($feth_info['name'] == $name) {
	echo "This is current name";
}else{
	$query2=mysqli_query($conn,"UPDATE users SET name='$name' WHERE unique_id='$grp_id' ");
		if ($query2) {
			echo "Updated Your Group";
		}else{
			echo "Updating Failed";
		}
}



?>