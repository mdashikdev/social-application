<?php 
include("db.php");
include("function_core.php");
session_start();
	$current_user=$_SESSION['user_id'];
	$post_id=$_REQUEST['post_id'];
	$post_content=$_REQUEST['pst_cntnt'];
	$tempname=$_FILES['pst_img']['tmp_name'];

	$ob=new post();
	echo $ob->edit_post($conn,$post_id,$post_content,$tempname);




?>