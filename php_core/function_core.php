<?php

function get_request_status($conn,$sent_id,$receive_id){
	$sql=" SELECT * FROM frd WHERE (sent_rqst_usr_id='$sent_id' AND receive_rqst_usr_id='$receive_id') OR (sent_rqst_usr_id='$receive_id' AND receive_rqst_usr_id='$sent_id') ";
	$query=mysqli_query($conn,$sql);
	while ($result=mysqli_fetch_assoc($query)) {
		return $result['status'];
	}

}
function get_friends($conn,$ssnId){
	$sql="SELECT * FROM users INNER JOIN frd ON frd.sent_rqst_usr_id=users.unique_id OR frd.receive_rqst_usr_id=users.unique_id WHERE frd.sent_rqst_usr_id='$ssnId' OR frd.receive_rqst_usr_id='$ssnId' AND users.unique_id != '$ssnId' AND frd.status='Confirm'  ";
	$query=mysqli_query($conn,$sql);
	while ($result=mysqli_fetch_assoc($query)) {
		return $result['receive_rqst_usr_id'];
	}

}
function get_user($conn,$id){
	$sql="SELECT * FROM users WHERE unique_id='$id' ";
	$query=mysqli_query($conn,$sql);
	if ($query) {
		while ($data= mysqli_fetch_assoc($query)) {
			return $data['name'];
		}
	}
}
function get_user_image($conn,$id){
	$sql="SELECT * FROM users WHERE unique_id='$id' ";
	$query=mysqli_query($conn,$sql);
	if ($query) {
		$data= mysqli_fetch_assoc($query);
		return $data['profile'];
	}
}
function get_user_id($conn,$id){
	$sql="SELECT * FROM users WHERE unique_id='$id' ";
	$query=mysqli_query($conn,$sql);
	if ($query) {
		$data= mysqli_fetch_assoc($query);
		return $data['unique_id'];
	}
}
function get_user_frds($conn,$id){
	$query2=mysqli_query($conn,"SELECT * FROM users INNER JOIN frd ON frd.sent_rqst_usr_id=users.unique_id OR frd.receive_rqst_usr_id=users.unique_id WHERE (frd.sent_rqst_usr_id='$id' OR frd.receive_rqst_usr_id='$id') AND users.unique_id != '$id' AND frd.status='Confirm' GROUP BY users.name ORDER BY frd.frd_id DESC");
	$output="";
						 if ($query2) {
						 	while ($usr_data=mysqli_fetch_assoc($query2)) {
						 			$output.= '
							     		<div style="cursor:pointer;" class="user_box border mt-2 ml-1 d-flex p-2" id="src_usr_for_msg">
							     			<div>
							     				<img class="rounded-circle " width="35px" height="35px" src="images/'.$usr_data["profile"].'">
							     			</div>
							     			<div>
							     				<strong id="full_view_btn" data-id="'.$usr_data["unique_id"].'" style="font-size:15px">
							     					'.$usr_data["name"].'
							     				</strong>
							     			</div>
							     		</div>
						 		';
						 	}
						 }
	return $output;
}
function get_who_follow($conn,$ssnId,$user_id){
	$query=mysqli_query($conn,"SELECT * FROM follow WHERE sender_id='$ssnId' AND reciever_id='$user_id' ");
	if (mysqli_num_rows($query) > 0) {
		$data=mysqli_fetch_assoc($query);
		$output='Following';
	}else{
		$output="Unfollow";
	}
	return $output;
}
function get_user_group_request_status($conn,$groupId,$user_id){
	$query=mysqli_query($conn,"SELECT * FROM group_requests  WHERE group_Id='$groupId' AND userId='$user_id' ");
	$output="";
	if ($query) {
		while ($data=mysqli_fetch_assoc($query)) {
			$output= $data['disabled'];
		};
		
	}
	return $output;
}
function check_admin($conn,$groupId,$user_id){
	$query=mysqli_query($conn,"SELECT * FROM users WHERE users.owner='$user_id' AND unique_id='$groupId' ");
	$output="";
	if ($query) {
		if (mysqli_num_rows($query)==1) {
			$output="Admin";
		}
		
	}
	return $output;
}
function group_member_count($conn,$group_id){
	$query=mysqli_query($conn,"SELECT * FROM group_members WHERE group_id='$group_id' ");
	if ($query) {
		return mysqli_num_rows($query);
	}
}
function check_already_request_or_not($conn,$group_id,$usr_id){
	$output= '';
	$query_for_check_invite_or_not=mysqli_query($conn,"SELECT * FROM group_invite WHERE invited_usr_id='$usr_id' AND disabled='0' ");
	if (mysqli_num_rows($query_for_check_invite_or_not) > 0) {
		$dt=mysqli_fetch_assoc($query_for_check_invite_or_not);
		$output='
			<button class="group_invite_accept_btn btn btn-primary w-100 p-0 m-1" id="group_invite_accept_btn'.$dt["invited_usr_id"].'" data-group_id="'.$dt["invited_group_id"].'" data-usr_id="'.$dt["invited_usr_id"].'" style="position:relative;bottom:0px;">Accept</button>
			<button class="group_invite_reject_btn btn btn-danger w-100 p-0 m-1" id="group_invite_reject_btn'.$dt["invited_usr_id"].'" data-group_id="'.$dt["invited_group_id"].'" data-usr_id="'.$dt["invited_usr_id"].'" style="position:relative;bottom:0px;">Reject</button>
		';
	}else{
		$query1=mysqli_query($conn,"SELECT * FROM group_requests WHERE group_Id='$group_id' AND userId='$usr_id' ");
			if ($query1) {
			$dsbl_sts="";
			$count=mysqli_num_rows($query1);
			if ($count==1) {
				while ($sts=mysqli_fetch_assoc($query1)) {
					$dsbl_sts=$sts['disabled'];
					if ($dsbl_sts==0) {
						$output= '<button class="btn btn-primary m-1" disabled>Sent Request</button>';
					}else{
						$output= '';
					}
				}
				
			}else{
				$output='<button class="btn btn-primary m-1" data-id="'.$group_id.'" id="group_request_sent_btn">Join</button>';
			}
		}
	}
	
	return $output;
}
function user_follower_count($conn,$id){
	$query=mysqli_query($conn,"SELECT * FROM follow WHERE reciever_id='$id' ");
	if ($query) {
		return mysqli_num_rows($query);
	}

}
function get_admin_sts($conn,$groupId,$id){
	$query=mysqli_query($conn,"SELECT * FROM group_members WHERE member_user_id='$id' AND group_id='$groupId' And (role='Admin' OR role='Moderator') ");
	$output='';
	if ($query) {
		while ($data=mysqli_fetch_assoc($query)) {
			$output=$data['role'];
		}
	}
	return $output;
}
function if_like_or_not($conn,$id,$current_id){
	$query=mysqli_query($conn,"SELECT * FROM `likes` WHERE liked_user_id='$current_id' AND liked_post_id='$id'; ");
	$output=mysqli_num_rows($query);
	if ($query) {
		if (mysqli_num_rows($query) > 0) {
			while ($data=mysqli_fetch_assoc($query)) {
				$output= $data['like_status'];
			}
		}else{
			$output= 'Not Like';
		}
	}
return $output;
}
function count_reacts($conn,$id){
	$query=mysqli_query($conn,"SELECT * FROM `likes` WHERE liked_post_id='$id'; ");
	if ($query) {
		return mysqli_num_rows($query);
	}
}
function like_users($conn,$id){
	$query=mysqli_query($conn,"SELECT * FROM likes WHERE liked_post_id='$id' AND like_status='Like' ");
	$name="";
	if ($query) {
		while ($data=mysqli_fetch_assoc($query)) {
			$usrId=$data['liked_user_id'];
			$name=get_user($conn,$usrId);
		}
	}
	return $name;
}
function get_comment_replies($conn,$parent_id){
	$query=mysqli_query($conn,"SELECT * FROM comment WHERE comment_parent_id='$parent_id' AND comment_parent_id !='0' ");
	return mysqli_num_rows($query);
}
function get_comment_count($conn,$post_id){
	$query=mysqli_query($conn,"SELECT * FROM comment WHERE comment_post_id='$post_id'");
	return mysqli_num_rows($query);
}


/**
 * 
 */
class post 
{
	
	function edit_post($conn,$id,$content,$img)
	{	
		$id=$id;
		$content=$content;
		$img=$img;
		$randName=uniqid()."ashik_creation";
		$query_for_past_info=mysqli_query($conn,"SELECT * FROM posts WHERE post_code='$id' ");
		if ($query_for_past_info) {
			$dbinfo=mysqli_fetch_assoc($query_for_past_info);
			$db_content=$dbinfo['post_content'];
			$db_img=$dbinfo['post_image'];
			if (empty($img)) {
				$img=$db_img;
			}else{
				move_uploaded_file($img, "../images/$randName.jpg");
				$img=$randName.".jpg";
			}
			if (empty($content)) {
				$content=$db_content;
			}
		}
		$query=mysqli_query($conn,"UPDATE posts SET post_content='$content', post_image='$img' WHERE post_code='$id' ");
		if ($query) {
			return "Edited";
		}else{
			return "Edit Failed";
		}
	}

	public function delete_post($conn,$id)
	{
		$query=mysqli_query($conn,"DELETE FROM posts WHERE post_code='$id' ");
		if ($query) {
			return "Deleted";
		}
	}
}



?>