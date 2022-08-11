<?php
include("db.php");
include("function_core.php");
include("time.php");
session_start();
$current_user=$_SESSION['user_id'];
	//search user
    if (isset($_REQUEST['srs_input'])) {
    	$searched_user=$_REQUEST['src_val'];
	    if (!empty($searched_user)) {
		    $get_search_user_sql="SELECT * FROM users WHERE NOT unique_id='$current_user' AND name LIKE '%$searched_user%' ";
		    $get_search_user_query=mysqli_query($conn,$get_search_user_sql);
		    $count_result=mysqli_num_rows($get_search_user_query);
		    echo "<strong style='font-size:15px'>
			    	Search result: ".$count_result."
			      </strong>";
		    while ($user_info= mysqli_fetch_assoc($get_search_user_query)) {
		    	$img=$user_info["profile"];
		    	if ($user_info['user_type'] == 'user') {
		    		$img=$user_info["profile"];
		    		$where_click="preview_usr_profile";
		    		$where_link="user_profile";
		    	}else{
		    		$img=$user_info["cover_photo"];
		    		$where_click="group_view_btn";
		    		$where_link="group";
		    	}
		    	echo '
		    		<a href="'.$where_link.'.php" id="'.$where_click.'" data-id="'.$user_info["unique_id"].'">
						<div  id="user_div" data-id="'.$user_info["unique_id"].'" >
							<img data-toggle="tooltip" data-placement="top" title="Click to view '.$user_info["name"].',s profile details" src="images/'.$img.'" class="img-fluid rounded-circle" alt="Responsive image" style="width:40px;height:40px;object-fit:cover">
							<div style="line-height: 1;">
								<strong><a href="'.$where_link.'.php" data-id="'.$user_info["unique_id"].'" id="preview_usr_profile" data-toggle="tooltip" data-placement="top" title="Click to view '.$user_info["name"].',s profile details">'.$user_info["name"].'</a></strong>
								<p style="border-bottom: solid thin #8faaa02b;opacity:0.5">'.$user_info["address"].'</p>
							</div>
						</div>
					</a>
		    	';
		    }
	    }else{
	    	echo "Please fill up input...";
	    }
    }

    //search user for message
    if (isset($_REQUEST['this_is_for_search_user_for_chat'])) {
    	if ($_REQUEST['this_is_for_search_user_for_chat']=="this is for search user for chat") {
    		$searched_user=$_REQUEST['search_val'];
		    $get_search_user_sql="SELECT * FROM users INNER JOIN frd ON frd.sent_rqst_usr_id=users.unique_id OR frd.receive_rqst_usr_id=users.unique_id WHERE (frd.sent_rqst_usr_id='$current_user' OR frd.receive_rqst_usr_id='$current_user') AND users.unique_id != '$current_user' AND frd.status='Confirm' AND name LIKE '%$searched_user%' GROUP BY users.name ORDER BY frd.frd_id DESC
";
		    $get_search_user_query=mysqli_query($conn,$get_search_user_sql);
		    echo "<strong>
			    	Search Result: ".$searched_user."
			      </strong>";
		    while ($user_info= mysqli_fetch_assoc($get_search_user_query)) {
		    	echo '
					<a href="#" id="select_user_for_message'.$user_info["unique_id"].'" data-id="'.$user_info["unique_id"].'" class="select_user_for_message">
						<div class="d-flex mb-1 p-1 border align-items-center" id="user_div" data-id="'.$user_info["unique_id"].'" >
							<img src="images/'.$user_info["profile"].'" class="ml-2 img-fluid rounded-circle" alt="Responsive image" style="width:40px;height:40px">
							<div>
								<strong class="ml-2">'.$user_info["name"].'</strong>
							</div>
						</div>
					</a>
		    	';
		    }
	    }else{
	    	echo "Please fill up input...";
	    }    	
	}
	//User check
	function get_id($conn,$id){
		$query=mysqli_query($conn,"SELECT * FROM users WHERE unique_id='$id' ");
		$dt=mysqli_fetch_assoc($query);
		$output ='
				<a href="inbox.php?unqusridfrmgs='.$dt["unique_id"].' ">
		     		<div style="cursor:pointer;" class="user_box d-flex p-2 rounded" id="src_usr_for_msg">
		     			<div>
		     				<img class="rounded-circle " width="35px" height="35px" src="images/default.png">
		     			</div>
		     			<div>
		     				<strong id="user_box_user_name" >
		     					'.$dt["name"].'
		     				</strong>
		     				<p id="user_box_user_msg">This is for test</p>
		     			</div>
		     		</div>
		     	</a>
		';
		return $output;
	}

	//Select user for chat 
	if (isset($_REQUEST["sel_usr_fr_cht"])) {
		if ($_REQUEST["sel_usr_fr_cht"]=="select user for chat") {
			$query=mysqli_query($conn,"SELECT * FROM users INNER JOIN msgs WHERE users.unique_id=msgs.sent_user_id ");
			while ($inf=mysqli_fetch_assoc($query)) {
				$his_id= $inf['receive_user_id'];
				echo get_id($conn,$his_id);
			}
		}
	}

	//Show Active users
	if (isset($_REQUEST["fnction_actv"])) {
		if ($_REQUEST["fnction_actv"]=="active status") {
			$query=mysqli_query($conn,"SELECT * FROM users INNER JOIN frd ON frd.sent_rqst_usr_id=users.unique_id OR frd.receive_rqst_usr_id=users.unique_id WHERE (frd.sent_rqst_usr_id='$current_user' OR frd.receive_rqst_usr_id='$current_user') AND users.unique_id != '$current_user' AND frd.status='Confirm' AND actv_status='Online' GROUP BY users.name ORDER BY frd.frd_id DESC ");
			if (mysqli_num_rows($query) == 0) {
				echo '<div style="width:40px;height:40px;background:gray;border-radius:50%;font-size:8px;text-align:center;line-height:4;font-weight:bold;color:white">No user</div>';
			}else{
				while ($dtn=mysqli_fetch_assoc($query)) {
					echo '
					<a href="#" id="select_user_for_message'.$dtn["unique_id"].'" class="select_user_for_message" data-id="'.$dtn["unique_id"].'">
						<div style="cursor:pointer;border-radius: 50%;" class="user_box d-flex p-1" id="src_usr_for_msg">
							<div style="position:relative">
								<img class="rounded-circle" data-toggle="tooltip" data-placement="bottom" title="'.$dtn["name"].'" width="40px" height="40px" src="images/'.$dtn["profile"].'">
								<span class="active_icon_create"><span>
							</div>
						</div>
					</a>
					';
				}
			}
		}
	}
	//show user for chat area
	if (isset($_REQUEST["show_usr_for_cht_area"])) {
		if ($_REQUEST["show_usr_for_cht_area"]=="show user for chat area") {
			$id=$_REQUEST["usr_id"];
			$output="";
			$query=mysqli_query($conn,"SELECT * FROM msgs INNER JOIN users ON users.unique_id=msgs.sent_user_id WHERE msgs.sent_user_id='$current_user' AND msgs.receive_user_id='$id' OR msgs.sent_user_id='$id' AND msgs.receive_user_id='$current_user' ");
			if (mysqli_num_rows($query) == 0) {
				$output= "<h3>No Chat Available</h3>";
			}else{
				while ($show_inf=mysqli_fetch_assoc($query)) {
				
					if ($show_inf['sent_user_id']==$current_user) {
						
						$output= '
						<div class="sent_msg" style="
									position: relative;display: flex;
									justify-content: flex-end;">
								<p style="
									box-shadow: 1px 2px 3px #021a1e26,-1px -1px 1px #021a1e1f;
									padding: 1px 7px 2px 7px;
									border-radius: 10px 10px 0px 10px;
									width: fit-content;
									max-width:80%;
									height:fit-content;
									font-size: 17px;
									margin: 7px 26px 0px 0px;">
									'.$show_inf["msg"].'
								</p>
								<img style="
									position: absolute;
									right: 0px;
									top: 26px;" 
									width="25px" height="25px" class="rounded-circle z-depth-2" src="images/'.$show_inf["profile"].'">
							</div>
						';
					}else{
						$output= '
						<div class="receive_msg">
	      						<img width="25px" height="25px" class="rounded-circle z-depth-2" src="images/'.$show_inf["profile"].'">
	      						<p style="
	      						margin: 9px 0px 0px 0px;
							    margin-top: 9px;
							    margin-left: 0px;
								background: #ffab46;
								padding: 1px 7px 2px 7px;
								border-radius: 0px 15px 15px 15px;
								width: fit-content;
								font-size: 17px;
								box-shadow: 1px 2px 3px #021a1e2b,-1px -1px 1px #021a1e1c;
								margin-left: 22px;
								max-width:80%;
								height:fit-content;
								margin-top: -3px;">
								'.$show_inf["msg"].'
								</p>
	      					</div>
						';
					}
					echo $output;
				
				}
			}

			}
		}

//Show user details
if (isset($_REQUEST["show_user_details"])) {
	if ($_REQUEST["show_user_details"]=="Show user details") {
		$id=$_REQUEST["usr_id"];
		$query=mysqli_query($conn,"SELECT * FROM users WHERE unique_id='$id' ");
		$info=mysqli_fetch_assoc($query);
		$qury=mysqli_query($conn,"SELECT * FROM users WHERE NOT unique_id='$current_user' ");
		$inf=mysqli_fetch_assoc($qury);
		$button='';
			$uid=$inf["unique_id"];
			$status=get_request_status($conn,$current_user,$id);
			if ($status=="Confirm") {
				$button.='
						<a href="#msg_box" data-toggle="pill" style="color:#0f2e4fb5;font-weight:bold" class="nav-link msg btn btn-primary">
							Message
						</a>
				';
			}else{
				$button.='
						
				';
			}
		$output='
			<div style="position:relative;">
				<img src="images/'.$info["cover_photo"].'" id="cover_photo_img" class="img-fluid rounded" alt="Responsive image">
			</div>
			<div class="d-flex profile_header_container row">
				<div class="profile-image profile_img_div">
					<div style="position:relative;width:fit-content;margin:auto">
						<img src="images/'.$info["profile"].'" id="pro_img" class="img-fluid rounded-circle" alt="Responsive image">
					</div>
					<div id="profile_name_div">
						<strong class="profile_name">
							'.$info["name"].'
						</strong>
					</div>
				</div>
				<div style="display: grid;grid-template-columns: 1fr 1fr;grid-gap: 9px;">
					<a href="user_profile.php" style="color:#0f2e4fb5;font-weight:bold" class="nav-link btn btn-primary" id="preview_usr_profile" data-id="'.$info["unique_id"].'">
						View Full
					</a>
					'.$button.'
				</div>
			</div>
		';
		echo $output;

	}
}
//Show Friend Suggestions
if (isset($_REQUEST["show_frd_suggestions"])) {
	if ($_REQUEST["show_frd_suggestions"]=="Show Friend suggestion") {
		$query=mysqli_query($conn,"SELECT * FROM users WHERE NOT unique_id='$current_user' AND user_type='user' ");
		while ($info=mysqli_fetch_assoc($query)) {
			$uid=$info["unique_id"];
			$status=get_request_status($conn,$current_user,$uid);
			if ($status=="Pending") {
				$button='<button id="frd_rqst_btn" disabled class="btn btn-primary frd_rqst_btn" data-id="" data-toggle="tooltip" data-placement="top" title="Click to send friend request">Pending</button>';
			}elseif ($status=="Reject") {
				$button='<button id="frd_rqst_btn" disabled class="btn btn-primary frd_rqst_btn" data-id="" data-toggle="tooltip" data-placement="top" title="Click to send friend request">Rejected</button>
				';
			}elseif($status=="Confirm"){
				$button='<button class="btn btn-primary" >Friend <i class="bi bi-person-check"></i></button>';
			}else{
				$button='
					<button id="frd_rqst_btn'.$info["unique_id"].'" class="btn btn-primary frd_rqst_btn" data-id="'.$info["unique_id"].'" data-toggle="tooltip" data-placement="top" title="Click to send friend request">Add Friend</button>
				';
			}
			echo '
				<div class="usr_for_frd_rqst_box justify-content-space-between p-2 border d-flex mb-1"  >
					<div class="d-flex align-items-center" id="user_div" data-id="'.$info["unique_id"].'" >
						<img data-toggle="tooltip" data-placement="top" title="Click to view '.$info["name"].',s profile details" src="images/'.$info["profile"].'" class="img-fluid rounded-circle" alt="Responsive image" style="width:40px;height:40px;object-fit:cover">
						<div>
							<strong class="ml-2"><a href="#" data-toggle="tooltip" data-placement="top" title="Click to view '.$info["name"].',s profile details">'.$info["name"].'</a></strong>
							<p class="ml-2">'.$info["address"].'</p>
						</div>
					</div>
					<div>
					'.$button.'
					</div>
				</div>
			';
		}
	}
}
//sent friend request
if (isset($_REQUEST["frd_request"])) {
	if ($_REQUEST["frd_request"]=="sent friend request") {
		$id=$_REQUEST["usr_id"];
		$query=mysqli_query($conn,"INSERT INTO frd (sent_rqst_usr_id,receive_rqst_usr_id,status) VALUES ('$current_user','$id','Pending') ");
		if ($query) {
			$query_for_notification=mysqli_query($conn,"INSERT INTO notifications (notification_usr_id,activity,notification_content_id,content_type) VALUES ('$current_user','Friend Request','$id','profile') ");
			if ($query_for_notification) {
				echo "friend request sent";
			}
		}else{
			echo "Friend Request sending failed";
		}
	}
}

//Show friend request
if (isset($_REQUEST["friend_requests_badge_count"])) {
	if ($_REQUEST["friend_requests_badge_count"]="Friend Request badge count") {
		$query=mysqli_query($conn,"SELECT * FROM frd WHERE receive_rqst_usr_id='$current_user' AND status='Pending'");
		echo mysqli_num_rows($query);
	}
}
//Show friend request
if (isset($_REQUEST["show_frd_rqst"])) {
	if ($_REQUEST["show_frd_rqst"]="Show Friend Request") {
		$query=mysqli_query($conn,"SELECT * FROM frd WHERE receive_rqst_usr_id='$current_user' AND status='Pending'");
			if (mysqli_num_rows($query) > 0) {
			while ($data=mysqli_fetch_assoc($query)) {
				$usr_id=$data['sent_rqst_usr_id'];
				$query2=mysqli_query($conn,"SELECT * FROM users WHERE unique_id='$usr_id' ");
				while ($usr_dt=mysqli_fetch_assoc($query2)) {
				echo '
				<div class="shadow-sm p-2 rounded">
					<div class="usr_for_frd_rqst_box justify-content-space-between p-2 border d-flex mb-1"  >
						<div class="d-flex align-items-center" id="user_div" data-id="" >
							<img data-toggle="tooltip" data-placement="top" title="Click to view  profile details" src="images/'.$usr_dt["profile"].'" class="img-fluid rounded-circle" alt="Responsive image" style="width:40px;height:40px;object-fit:cover">
							<strong class="ml-2" id="full_view_btn" data-id="'.$usr_dt["unique_id"].'"><a href="#" data-toggle="tooltip" data-placement="top" title="Click to view profile details">'.$usr_dt["name"].'</a></strong>
						</div>
						<div>
							<button id="accpt_rqst_btn'.$usr_dt["unique_id"].'" class="btn btn-primary accpt_rqst_btn" data-id="'.$usr_dt["unique_id"].'" data-toggle="tooltip" data-placement="top" title="Click to accept friend request">
								Accept
							</button>
							<button id="reject_rqst_btn'.$usr_dt["unique_id"].'" class="btn btn-primary reject_rqst_btn" data-id="'.$usr_dt["unique_id"].'" data-toggle="tooltip" data-placement="top" title="Click to reject friend request">
								Reject
							</button>
						</div>
					</div>
				</div>
				';
				}

			}
		}else{
		}

	}
}
//Accept Friend Request
if (isset($_REQUEST["accept_frd_request"])) {
	if ($_REQUEST["accept_frd_request"]="accept friend request") {
		$id=$_REQUEST["usr_id"];
		$query=mysqli_query($conn,"UPDATE frd SET status='Confirm' WHERE sent_rqst_usr_id='$id' AND receive_rqst_usr_id='$current_user' AND status='Pending' ");
		if ($query) {
			$query_for_follow=mysqli_query($conn,"INSERT INTO follow (sender_id,reciever_id) VALUES ('$id','$current_user') ");
			if ($query_for_follow) {
				$query_for_follow2=mysqli_query($conn,"INSERT INTO follow (sender_id,reciever_id) VALUES ('$current_user','$id') ");
				if ($query_for_follow2) {
					echo "Accepted";
				}
			}else{
				echo "Following failed";
			}
		}else{
			echo "Failed";
		}
	}
}
if (isset($_REQUEST["reject_frd_request"])) {
	if ($_REQUEST["reject_frd_request"]="reject friend request") {
		$id=$_REQUEST["usr_id"];
		$query=mysqli_query($conn,"DELETE FROM frd WHERE sent_rqst_usr_id='$id' AND receive_rqst_usr_id='$current_user' AND status='Pending' ");
		if ($query) {
			echo "Rejected";
		}else{
			echo "Failed";
		}
	}
}
//Get current user Friends
if (isset($_REQUEST["get_friends"])) {
	if ($_REQUEST["get_friends"]="Get current user friends") {
		$query2=mysqli_query($conn,"SELECT * FROM users INNER JOIN frd ON frd.sent_rqst_usr_id=users.unique_id OR frd.receive_rqst_usr_id=users.unique_id WHERE (frd.sent_rqst_usr_id='$current_user' OR frd.receive_rqst_usr_id='$current_user') AND users.unique_id != '$current_user' AND frd.status='Confirm' GROUP BY users.name ORDER BY frd.frd_id DESC");
			if ($query2) {
			while ($usr_data=mysqli_fetch_assoc($query2)) {
				echo '
					
						<div style="cursor:pointer;" class="user_box d-flex p-2 rounded" id="src_usr_for_msg">
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
	}
}
//Get any user Friends
if (isset($_REQUEST["get_any_usr_friends"])) {
	if ($_REQUEST["get_any_usr_friends"]="Get any user friends") {
		$usr_id=$_REQUEST["id"];
		$query2=mysqli_query($conn,"SELECT * FROM users INNER JOIN frd ON frd.sent_rqst_usr_id=users.unique_id OR frd.receive_rqst_usr_id=users.unique_id WHERE (frd.sent_rqst_usr_id='$usr_id' OR frd.receive_rqst_usr_id='$usr_id') AND users.unique_id != '$usr_id' AND frd.status='Confirm' GROUP BY users.name ORDER BY frd.frd_id DESC");
			if (mysqli_num_rows($query2) > 0) {
			while ($usr_data=mysqli_fetch_assoc($query2)) {
				echo '
					<a href="user_profile.php" data-id="'.$usr_data["unique_id"].'" id="preview_usr_profile">
						<div style="cursor:pointer;" class="user_box d-flex p-2 rounded" id="src_usr_for_msg">
							<div>
								<img class="rounded-circle " width="35px" height="35px" src="images/'.$usr_data["profile"].'">
							</div>
							<div>
								<strong id="full_view_btn" class="ml-2" data-id="'.$usr_data["unique_id"].'" style="font-size:15px">
									'.$usr_data["name"].'
								</strong>
								<p class="ml-2 mb-0">'.$usr_data["address"].'</p>
							</div>
						</div>
					</a>
				';
			}
		}else{
			echo "Friend List Empty!";
		}
	}
}
//Get any user Follower
if (isset($_REQUEST["get_any_usr_follower"])) {
	if ($_REQUEST["get_any_usr_follower"]="Get any user follower") {
		$usr_id=$_REQUEST["id"];
		$query=mysqli_query($conn,"SELECT * FROM follow WHERE reciever_id='$usr_id' ");
		if (mysqli_num_rows($query) > 0) {
			while ($usr_dt=mysqli_fetch_assoc($query)) {
				$id= $usr_dt['sender_id'];
				$query2=mysqli_query($conn,"SELECT * FROM users WHERE unique_id='$id' ");
				if ($query2) {
					while ($usr_inf=mysqli_fetch_assoc($query2)) {
						echo '
							<a href="user_profile.php" data-id="'.$usr_inf["unique_id"].'" id="preview_usr_profile">
								<div style="cursor:pointer;" class="user_box d-flex p-2 rounded" id="src_usr_for_msg">
									<div>
										<img class="rounded-circle " width="35px" height="35px" src="images/'.$usr_inf["profile"].'">
									</div>
									<div>
										<strong id="full_view_btn" class="ml-2" data-id="'.$usr_inf["unique_id"].'" style="font-size:15px">
											'.$usr_inf["name"].'
										</strong>
										<p class="ml-2 mb-0">'.$usr_inf["address"].'</p>
									</div>
								</div>
							</a>
						';
					}
				}
			}
		}else{
			echo "You have no follower.!";
		}
	}
}

//Get any user Friends count
if (isset($_REQUEST["get_any_usr_friends_count"])) {
	if ($_REQUEST["get_any_usr_friends_count"]="Get any user follower count") {
		$usr_id=$_REQUEST["id"];
		$query=mysqli_query($conn,"SELECT * FROM users INNER JOIN frd ON frd.sent_rqst_usr_id=users.unique_id OR frd.receive_rqst_usr_id=users.unique_id WHERE (frd.sent_rqst_usr_id='$usr_id' OR frd.receive_rqst_usr_id='$usr_id') AND users.unique_id != '$usr_id' AND frd.status='Confirm' GROUP BY users.name ORDER BY frd.frd_id DESC");
		if ($query) {
			echo mysqli_num_rows($query);
		}
	}
}

//Get any user Follower count
if (isset($_REQUEST["get_any_usr_follower_count"])) {
	if ($_REQUEST["get_any_usr_follower_count"]="Get any user friends count") {
		$usr_id=$_REQUEST["id"];
		$query=mysqli_query($conn,"SELECT * FROM follow WHERE reciever_id='$usr_id' ");
		if ($query) {
			echo mysqli_num_rows($query);
		}
	}
}

//Get current user Timeline Posts
if (isset($_REQUEST["get_timeline_post"])) {
	if ($_REQUEST["get_profile_post"]="Get timeline posts") {
			$query=mysqli_query($conn,"SELECT * FROM posts INNER JOIN users ON users.unique_id=posts.post_user_id LEFT JOIN follow ON follow.reciever_id=posts.post_user_id WHERE (follow.sender_id='$current_user' OR posts.post_user_id='$current_user') AND posts.post_owner='0'  GROUP BY posts.post_id ORDER BY posts.post_id DESC;");
			if (mysqli_num_rows($query) > 0) {
				while ($data=mysqli_fetch_assoc($query)) {
					$post_user_status= $data["post_user_status"];
					$sts_for_group_or_profile="posted a status...";
					if ($post_user_status=="profile upload") {
						$sts_for_group_or_profile="updated profile picture.";
					}elseif($post_user_status=="cover upload"){
						$sts_for_group_or_profile="updated cover photo.";
					}else{
						$sts_for_group_or_profile="posted a status...";
					}
					$count_react=count_reacts($conn,$data['post_code']);
					$count_comment=get_comment_count($conn,$data['post_code']);
					$name=get_user($conn,$data["post_user_id"]);
					$like_btn="";
					$edit_btn="";
					$like_btn_status= if_like_or_not($conn,$data['post_code'],$current_user);
					if ($like_btn_status=="Like") {
						$like_btn='
							<button data-id="'.$data["post_code"].'" id="like_btn" class="post_footer_btn ml-1 col-lg-3 col-md-3 col-sm-3">
								<img class="icon_all like_icon" src="emojis/like_color.png">
								<span id="icon_name_for_responsive">Liked</span>
							</button>';
					}elseif ($like_btn_status=="Love") {
						$like_btn='
							<button data-id="'.$data["post_code"].'" id="like_btn" class="post_footer_btn ml-1 col-lg-3 col-md-3 col-sm-3">
								<img class="icon_all heart_icon" src="emojis/heart.png">
								<span id="icon_name_for_responsive">Love</span>
							</button>';
					}elseif ($like_btn_status=="Haha") {
						$like_btn='
							<button data-id="'.$data["post_code"].'" id="like_btn" class="post_footer_btn ml-1 col-lg-3 col-md-3 col-sm-3">
								<img class="icon_all laughing_icon" src="emojis/laughing.png">
								<span id="icon_name_for_responsive">Haha</span>
							</button>';
					}elseif ($like_btn_status=="Wow") {
						$like_btn='
						<button data-id="'.$data["post_code"].'" id="like_btn" class="post_footer_btn ml-1 col-lg-3 col-md-3 col-sm-3">
							<img id="wow_icon'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icon_all wow_icon" src="emojis/wow.png">
							<span id="icon_name_for_responsive">Wow</span>
						</button>';
					}elseif ($like_btn_status=="Sad") {
						$like_btn='
						<button data-id="'.$data["post_code"].'" id="like_btn" class="post_footer_btn ml-1 col-lg-3 col-md-3 col-sm-3">
							<img id="sad_icon'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icon_all sad_icon" src="emojis/sad.png">
							<span id="icon_name_for_responsive">Sad</span>
						</button>';
					}elseif ($like_btn_status=="Angry") {
						$like_btn='
						<button data-id="'.$data["post_code"].'" id="like_btn" class="post_footer_btn ml-1 col-lg-3 col-md-3 col-sm-3">
							<img id="angry_icon'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icon_all angry_icon" src="emojis/angry.png">
							<span id="icon_name_for_responsive">Angry</span>
						</button>';
					}else{
						$like_btn='<button data-id="'.$data["post_code"].'" id="like_btn" class="post_footer_btn ml-1 col-lg-3 col-md-3 col-sm-3">
										<i class="bi bi-hand-thumbs-up"></i>
										<span id="icon_name_for_responsive">Like</span>
									</button>';
					}
					if ($data['post_user_id'] == $current_user) {
						$edit_btn='
						<button type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
							<i class="bi bi-three-dots-vertical"></i>
						</button>
						';
					}
					if (!empty($data["post_image"]=="")) {
						echo '
						<div class="post_create_box post_edit_box" id="post_edit_box'.$data["post_code"].'">
							<div class=" d-flex" style="justify-content:space-between" >
								<h4>
									Edit post
								</h4>
						      <button class="edit_container_close_btn btn btn-secondary" data-id="'.$data["post_code"].'" id="edit_container_close_btn'.$data["post_code"].'">
						      	Close
						      </button>
						    </div>
						    <form action="" id="post_edit_form" data-id="'.$data["post_code"].'" onsubmit="return false">
						        <input type="text" class="pst_cntnt" name="pst_cntnt" value="'.$data["post_content"].'" placeholder="Edit post..."></textarea>
						        <input type="file" style="margin-top: 5px;" class="pst_img"  name="pst_img">
						        <input type="hidden" value="'.$data["post_code"].'" name="post_id">
						        <img src="images/spinner.gif"  class="mb-2 post_spinner"  style="display:none" alt="">
						        <div class="alert alert-success" style="display:none;margin-top:2%;" id="alert_for_post_edit" role="alert"></div>
						        <input type="submit"class="post_btn" value="Edit">
						    </form>
						</div>
						<div class="post_box p-0 mt-3">
								<div class="post_header" style="border-bottom: 1px solid #ccc;padding: 5px;display: grid;grid-template-columns: 3fr auto; ">
									<div id="timeline_header_left" style="padding:0" class="">
										<div class="">
											<div class="d-flex">
												<img src="images/'.$data["profile"].'" width="40px" height="40px" class="rounded-circle">
												<a href="user_profile.php" id="preview_usr_profile" data-id="'.$data["unique_id"].'">
													<input type="text" id="for_preview_user" style="height: 0px;width: 0px;position: absolute;top: -10000px;">
													<strong id="" style="margin-left: 5px;cursor:pointer;" data-id="'.$data["unique_id"].'">'.$name.'</strong>
													<p style="font-size: 12px;margin:0px;display:inline;">'.$sts_for_group_or_profile.'</p>
												</a>
												
										</div>
										<p style="margin-left: 5px;font-size: 11px;color: #040404a3;margin-bottom: 0px;">
											'.facebook_time_ago($data["post_time"]).'
										</p>
									</div>
								</div>

									<div id="timeline_header_right" class="dropdown">
										'.$edit_btn.'
										<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
										<li><a class="dropdown-item pst_edit_btn" style="cursor:pointer" data-id="'.$data["post_code"].'" id="pst_edit_btn'.$data["post_code"].'">Edit</a></li>
										<li><a class="dropdown-item pst_delete_btn" style="cursor:pointer" data-id="'.$data["post_code"].'" id="pst_delete_btn'.$data["post_code"].'">Delete</a></li>
										</ul>
									</div>
								</div>

							
							<p class="post_texts m-1" style="min-height:50px">
							'.$data["post_content"].'
							</p>
							<div class="reactor_user_show_box" id="reactor_user_show_box'.$data["post_code"].'">
								<div class="d-flex" style="justify-content:space-between;border-bottom:1px solid #ccc">
									<h4>Reactors</h4>
									<button class="btn btn-secondary" id="reactor_close_btn'.$data["post_code"].'" style="padding:2px"><i class="bi bi-x"></i></button>
								</div>
								<div id="reacts_count_user_box">
									<ul class="nav nav-tabs" style="margin-top: -9px;display: grid;grid: 29px/auto auto auto auto auto auto auto;">
										<li class="for_reactor nav-item  m-1">
											<button class=" nav-link p-1 m-1 active" data-toggle="pill" href="#all_react'.$data["post_code"].'">All <span id="all_react_count'.$data["post_code"].' style="margin-left:2px""></span></button>
										</li>
										<li class="for_reactor nav-item  m-1">
											<button class=" nav-link p-1 m-1" data-toggle="pill" id="likedbtn'.$data["post_code"].'" href="#liked'.$data["post_code"].'"><img width="12px" height="12px" class="" src="emojis/like_color.png"><span style="margin-left:2px" id="like_count'.$data["post_code"].'"></span></button>
										</li>
										<li class="for_reactor nav-item m-1">
											<button id="lovebtn'.$data["post_code"].'" class=" nav-link p-1 m-1" data-toggle="tab" href="#love'.$data["post_code"].'"><img width="12px" height="12px" class="" src="emojis/heart.png"><span style="margin-left:2px" id="love_count'.$data["post_code"].'"></span></button>
										</li>
										<li class="for_reactor nav-item m-1">
											<button id="hahabtn'.$data["post_code"].'" class="  nav-link p-1 m-1" data-toggle="tab" href="#haha'.$data["post_code"].'"><img width="12px" height="12px" class="" src="emojis/laughing.png"><span style="margin-left:2px" id="haha_count'.$data["post_code"].'"></span></button>
										</li>
										<li class="for_reactor nav-item m-1">
											<button id="wowbtn'.$data["post_code"].'" class="  nav-link p-1 m-1" data-toggle="tab" href="#wow'.$data["post_code"].'"><img width="12px" height="12px" class="" src="emojis/wow.png"><span style="margin-left:2px" id="wow_count'.$data["post_code"].'"></span></button>
										</li>
										<li class="for_reactor nav-item m-1">
											<button id="sadbtn'.$data["post_code"].'" class=" nav-link p-1 m-1" data-toggle="tab" href="#sad'.$data["post_code"].'"><img width="12px" height="12px" class="" src="emojis/sad.png"><span style="margin-left:2px" id="sad_count'.$data["post_code"].'"></span></button>
										</li>
										<li class="for_reactor nav-item m-1">
											<button id="angrybtn'.$data["post_code"].'" class=" nav-link p-1 m-1" data-toggle="tab" href="#angry'.$data["post_code"].'"><img width="12px" height="12px" class="" src="emojis/angry.png"><span style="margin-left:2px" id="angry_count'.$data["post_code"].'"></span></button>
										</li>
									</ul>
								</div>
								<div id="reactor_user_show_box'.$data["post_code"].'" >
									<div class="tab-content">
										<div id="all_react'.$data["post_code"].'" class="active tab-pane">
											<img src="images/spinner.gif" id="spinner_for_load_react_user'.$data["post_code"].'"  class="mb-2 post_show_spinner"  style="display:none" alt="">
										</div>
										<div id="liked'.$data["post_code"].'" class="tab-pane"></div>
										<div id="love'.$data["post_code"].'" class="tab-pane fade"></div>
										<div id="haha'.$data["post_code"].'" class="tab-pane fade"></div>
										<div id="wow'.$data["post_code"].'" class="tab-pane fade"></div>
										<div id="sad'.$data["post_code"].'" class="tab-pane fade"></div>
										<div id="angry'.$data["post_code"].'" class="tab-pane fade"></div>
									</div>
								</div>
							</div>
							<div id="like_count_and_other_container" class="d-flex" style="justify-content:space-between">
								<span id="reactors_showBtn'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="reactors_showBtn">'.$count_react.' user Reacted</span>
								<span >Comments '.$count_comment.'</span>
								<span ></span>
								<span ></span>
							</div>
							<div id="icons_container'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icons_container p-2">
								<img id="like_icon'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icon_all like_icon" src="emojis/like_color.png">
								<img id="heart_icon'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icon_all heart_icon" src="emojis/heart.png">
								<img id="laughing_icon'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icon_all laughing_icon" src="emojis/laughing.png">
								<img id="wow_icon'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icon_all wow_icon" src="emojis/wow.png">
								<img id="sad_icon'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icon_all sad_icon" src="emojis/sad.png">
								<img id="angry_icon'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icon_all angry_icon" src="emojis/angry.png">
							</div>
							<div class="footer_content p-1 d-flex" style="justify-content:space-between">
								'.$like_btn.'
								<button id="comnt_btn" data-id="'.$data["post_code"].'" class="post_footer_btn comnt_btn col-lg-3 col-md-3 col-sm-3 ml-1">
									<i class="bi bi-chat-right-dots"></i>
									<span id="icon_name_for_responsive" class="comment_responsive">Comment</span>
								</button>
								<div id="comment_section'.$data["post_code"].'" class="comment_section">
									<div class="d-flex" style="justify-content:space-between;border-bottom:1px solid #ccc">
										<h5>Comments<h5>
										<span class="btn btn-secondary" style="padding:3px" data-id="'.$data["post_code"].'" id="comment_close_btn"><i class="bi bi-x"></i></span>
									</div>
									<div style="min-height: 70px;max-height: 200px;overflow: auto;margin-top:5px;margin-bottom:3px" id="comment_box'.$data["post_code"].'"></div>
									<div id="comment_form">
										<form class="d-flex" id="commment_post_form" data-id="'.$data["post_code"].'" onsubmit="return false">
											<button class="back_post_comment btn btn-primary p-1 ml-1" data-id="'.$data["post_code"].'" id="back_post_comment'.$data["post_code"].'" style="font-size: 9px;display:none" type="button">Post comment</button>
											<input type="text" id="cmnt_area'.$data["post_code"].'" class="form-control" placeholder="Post a comment.." >
											<input type="hidden" id="cmnt_id'.$data["post_code"].'" value="0" >
											<button class="btn btn-primary p-1 ml-1" type="submit">Post</button>
										</form>
									</div>
								</div>
								<button  class="post_footer_btn col-lg-3 col-md-3 col-sm-3 ml-1">
									<i class="bi bi-share"></i>
									<span id="icon_name_for_responsive">Share</span>
								</button>
							</div>
						</div>
						';
					}else{
						echo '
						<div class="post_create_box post_edit_box" id="post_edit_box'.$data["post_code"].'">
							<div class=" d-flex" style="justify-content:space-between" >
								<h4>
									Edit post
								</h4>
						      <button class="edit_container_close_btn btn btn-secondary" data-id="'.$data["post_code"].'" id="edit_container_close_btn'.$data["post_code"].'">
						      	Close
						      </button>
						    </div>
						    <form action="" id="post_edit_form" data-id="'.$data["post_code"].'" onsubmit="return false">
						        <input type="text" class="pst_cntnt" name="pst_cntnt" value="'.$data["post_content"].'" placeholder="Edit post..."></textarea>
						        <input type="file" style="margin-top: 5px;" class="pst_img"  name="pst_img">
						        <input type="hidden" value="'.$data["post_code"].'" name="post_id">
						        <img src="images/spinner.gif"  class="mb-2 post_spinner"  style="display:none" alt="">
						        <div class="alert alert-success" style="display:none;margin-top:2%;" id="alert_for_post_edit" role="alert"></div>
						        <input type="submit"class="post_btn" value="Edit">
						    </form>
						</div>
						</div>
							<div class="post_box p-0 mt-3">
						
								<div class="post_header" style="border-bottom: 1px solid #ccc;padding: 5px;display: grid;grid-template-columns: 3fr auto; ">
									<div id="timeline_header_left" style="padding:0" class="">
										<div class="">
											<div class="d-flex">
												<img src="images/'.$data["profile"].'" width="40px" height="40px" class="rounded-circle">
												<a href="user_profile.php" id="preview_usr_profile" data-id="'.$data["unique_id"].'">
													<input type="text" id="for_preview_user" style="height: 0px;width: 0px;position: absolute;top: -10000px;">
													<strong id="" style="margin-left: 5px;cursor:pointer;" data-id="'.$data["unique_id"].'">'.$name.'</strong>
													<p style="font-size: 12px;margin:0px;display:inline;">'.$sts_for_group_or_profile.'</p>
												</a>
												
										</div>
										<p style="margin-left: 5px;font-size: 11px;color: #040404a3;margin-bottom: 0px;">
											'.facebook_time_ago($data["post_time"]).'
										</p>
									</div>
								</div>

								<div id="timeline_header_right" class="dropdown">
									'.$edit_btn.'
									<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
										<li><a class="dropdown-item pst_edit_btn" style="cursor:pointer"  data-id="'.$data["post_code"].'" id="pst_edit_btn'.$data["post_code"].'">Edit</a></li>
										<li><a class="dropdown-item pst_delete_btn" style="cursor:pointer"  data-id="'.$data["post_code"].'" id="pst_delete_btn'.$data["post_code"].'">Delete</a></li>
									</ul>
								</div>
							</div>
						
							

							<p class="post_texts m-1">
							'.$data["post_content"].'
							</p>
							<img src="images/'.$data["post_image"].'" style="width:100%;height:100%" class="img-thumbnail">
							<div class="reactor_user_show_box" id="reactor_user_show_box'.$data["post_code"].'">
								<div class="d-flex" style="justify-content:space-between;border-bottom:1px solid #ccc">
									<h4>Reactors</h4>
									<button class="btn btn-secondary" id="reactor_close_btn'.$data["post_code"].'" style="padding:4px"><i class="bi bi-x"></i></button>
								</div>
								<div id="reacts_count_user_box">
									<ul class="nav nav-tabs" style="margin-top: -9px;display: grid;grid: 29px/auto auto auto auto auto auto auto;">
										<li class="for_reactor nav-item  m-1">
											<button class=" nav-link p-1 m-1 active" data-toggle="pill" href="#all_react'.$data["post_code"].'">All <span style="margin-left:2px" id="all_react_count'.$data["post_code"].'"></span></button>
										</li>
										<li class="for_reactor nav-item  m-1">
											<button class=" nav-link p-1 m-1" data-toggle="pill" id="likedbtn'.$data["post_code"].'" href="#liked'.$data["post_code"].'"><img width="12px" height="12px" class="" src="emojis/like_color.png"><span style="margin-left:2px" id="like_count'.$data["post_code"].'"></span></button>
										</li>
										<li class="for_reactor nav-item m-1">
											<button id="lovebtn'.$data["post_code"].'" class=" nav-link p-1 m-1" data-toggle="tab" href="#love'.$data["post_code"].'"><img width="12px" height="12px" class="" src="emojis/heart.png"><span style="margin-left:2px" id="love_count'.$data["post_code"].'"></span></button>
										</li>
										<li class="for_reactor nav-item m-1">
											<button id="hahabtn'.$data["post_code"].'" class="  nav-link p-1 m-1" data-toggle="tab" href="#haha'.$data["post_code"].'"><img width="12px" height="12px" class="" src="emojis/laughing.png"><span style="margin-left:2px" id="haha_count'.$data["post_code"].'"></span></button>
										</li>
										<li class="for_reactor nav-item m-1">
											<button id="wowbtn'.$data["post_code"].'" class="  nav-link p-1 m-1" data-toggle="tab" href="#wow'.$data["post_code"].'"><img width="12px" height="12px" class="" src="emojis/wow.png"><span style="margin-left:2px" id="wow_count'.$data["post_code"].'"></span></button>
										</li>
										<li class="for_reactor nav-item m-1">
											<button id="sadbtn'.$data["post_code"].'" class=" nav-link p-1 m-1" data-toggle="tab" href="#sad'.$data["post_code"].'"><img width="12px" height="12px" class="" src="emojis/sad.png"><span style="margin-left:2px" id="sad_count'.$data["post_code"].'"></span></button>
										</li>
										<li class="for_reactor nav-item m-1">
											<button id="angrybtn'.$data["post_code"].'" class=" nav-link p-1 m-1" data-toggle="tab" href="#angry'.$data["post_code"].'"><img width="12px" height="12px" class="" src="emojis/angry.png"><span style="margin-left:2px" id="angry_count'.$data["post_code"].'"></span></button>
										</li>
									</ul>
								</div>
								<div id="reactor_user_show_box'.$data["post_code"].'" >
									<div class="tab-content">
										<div id="all_react'.$data["post_code"].'" class="active tab-pane">
											<img src="images/spinner.gif" id="spinner_for_load_react_user'.$data["post_code"].'"  class="mb-2 post_show_spinner"  style="display:none" alt="">
										</div>
										<div id="liked'.$data["post_code"].'" class="tab-pane"></div>
										<div id="love'.$data["post_code"].'" class="tab-pane fade"></div>
										<div id="haha'.$data["post_code"].'" class="tab-pane fade"></div>
										<div id="wow'.$data["post_code"].'" class="tab-pane fade"></div>
										<div id="sad'.$data["post_code"].'" class="tab-pane fade"></div>
										<div id="angry'.$data["post_code"].'" class="tab-pane fade"></div>
									</div>
								</div>
							</div>
							<div id="like_count_and_other_container" style="display:flex;justify-content:space-between">
								<span id="reactors_showBtn'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="reactors_showBtn">'.$count_react.' user Reacted</span>
								<span >Comments '.$count_comment.'</span>
								<span ></span>
								<span ></span>
							</div>
							<div id="icons_container'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icons_container p-2">
								<img id="like_icon'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icon_all like_icon" src="emojis/like_color.png">
								<img id="heart_icon'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icon_all heart_icon" src="emojis/heart.png">
								<img id="laughing_icon'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icon_all laughing_icon" src="emojis/laughing.png">
								<img id="wow_icon'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icon_all wow_icon" src="emojis/wow.png">
								<img id="sad_icon'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icon_all sad_icon" src="emojis/sad.png">
								<img id="angry_icon'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icon_all angry_icon" src="emojis/angry.png">
							</div>
							<div class="footer_content p-1 d-flex" style="justify-content:space-between">
								'.$like_btn.'
								<button id="comnt_btn" data-id="'.$data["post_code"].'" class="post_footer_btn comnt_btn col-lg-3 col-md-3 col-sm-3 ml-1">
									<i class="bi bi-chat-right-dots"></i>
									<span id="icon_name_for_responsive" class="comment_responsive">Comment</span>
								</button>
								<div id="comment_section'.$data["post_code"].'" class="comment_section">
									<div class="d-flex" style="justify-content:space-between;border-bottom:1px solid #ccc">
										<h5>Comments<h5>
										<span class="btn btn-secondary" style="padding:3px" data-id="'.$data["post_code"].'" id="comment_close_btn"><i class="bi bi-x"></i></span>
									</div>
									<div style="min-height: 70px;max-height: 200px;overflow: auto;margin-top:5px;margin-bottom:3px" id="comment_box'.$data["post_code"].'"></div>
									<div id="comment_form">
										<form class="d-flex" id="commment_post_form" data-id="'.$data["post_code"].'" onsubmit="return false">
											<button class="back_post_comment btn btn-primary p-1 ml-1" data-id="'.$data["post_code"].'" id="back_post_comment'.$data["post_code"].'" style="font-size: 9px;display:none" type="button">Post comment</button>
											<input type="text" id="cmnt_area'.$data["post_code"].'" class="form-control" placeholder="Post a comment.." >
											<input type="hidden" id="cmnt_id'.$data["post_code"].'" value="0" >
											<button class="btn btn-primary p-1 ml-1" type="submit">Post</button>
										</form>
									</div>
								</div>
								<button class="post_footer_btn col-lg-3 col-md-3 col-sm-3 ml-1">
									<i class="bi bi-share"></i>
									<span id="icon_name_for_responsive">Share</span>
								</button>
							</div>
						</div>
						';
					}

				}
			}else{
				echo '<div style="background: white;margin: 5px;box-shadow: 1px 1px 2px #ccc;border-radius: 8px;padding: 10px;">You have no post</div>';
			}
		}
	}

//This is for group all posts
if (isset($_REQUEST["this_is_for_group_all_posts"])) {
	if ($_REQUEST["this_is_for_group_all_posts"]=="this is for group all posts") {
		$profile_user_id=$_REQUEST["id"];
		$query_for_group_post=mysqli_query($conn,"SELECT * FROM posts WHERE post_owner='$profile_user_id' ORDER BY post_id DESC");
		if ($query_for_group_post) {
			if (mysqli_num_rows($query_for_group_post)>0) {
				while ($group_info=mysqli_fetch_assoc($query_for_group_post)) {
					$edit_btn="";
					$post_user_status= $group_info["post_user_status"];
					$sts_for_group_or_profile="posted a status...";
					if ($post_user_status=="group cover upload") {
						$sts_for_group_or_profile="updated group cover photo.";
					}else{
						$sts_for_group_or_profile="posted a status...";
					}
					$post_user_id= $group_info['post_user_id'];
					$user_name=get_user($conn,$post_user_id);
					$usr_id_for_group_posts=$group_info['post_user_id'];
					$query_for_group_posts_user=mysqli_query($conn,"SELECT * FROM users WHERE unique_id='$usr_id_for_group_posts'");
					if ($query_for_group_posts_user) {
						while ($data=mysqli_fetch_assoc($query_for_group_posts_user)) {
							$count_react=count_reacts($conn,$group_info['post_code']);
							$count_comment=get_comment_count($conn,$group_info['post_code']);
							$name=get_user($conn,$group_info["post_user_id"]);
							$like_btn="";
							$like_btn_status= if_like_or_not($conn,$group_info['post_code'],$current_user);
							if ($like_btn_status=="Like") {
								$like_btn='
									<button data-id="'.$group_info["post_code"].'" id="like_btn" class="post_footer_btn ml-1 col-lg-3 col-md-3 col-sm-3">
										<img class="icon_all like_icon" src="emojis/like_color.png">
										<span id="icon_name_for_responsive">Liked</span>
									</button>';
							}elseif ($like_btn_status=="Love") {
								$like_btn='
									<button data-id="'.$group_info["post_code"].'" id="like_btn" class="post_footer_btn ml-1 col-lg-3 col-md-3 col-sm-3">
										<img class="icon_all heart_icon" src="emojis/heart.png">
										<span id="icon_name_for_responsive">Love</span>
									</button>';
							}elseif ($like_btn_status=="Haha") {
								$like_btn='
									<button data-id="'.$group_info["post_code"].'" id="like_btn" class="post_footer_btn ml-1 col-lg-3 col-md-3 col-sm-3">
										<img class="icon_all laughing_icon" src="emojis/laughing.png">
										<span id="icon_name_for_responsive">Haha</span>
									</button>';
							}elseif ($like_btn_status=="Wow") {
								$like_btn='
								<button data-id="'.$group_info["post_code"].'" id="like_btn" class="post_footer_btn ml-1 col-lg-3 col-md-3 col-sm-3">
									<img id="wow_icon'.$group_info["post_code"].'" data-id="'.$group_info["post_code"].'" class="icon_all wow_icon" src="emojis/wow.png">
									<span id="icon_name_for_responsive">Wow</span>
								</button>';
							}elseif ($like_btn_status=="Sad") {
								$like_btn='
								<button data-id="'.$group_info["post_code"].'" id="like_btn" class="post_footer_btn ml-1 col-lg-3 col-md-3 col-sm-3">
									<img id="sad_icon'.$group_info["post_code"].'" data-id="'.$group_info["post_code"].'" class="icon_all sad_icon" src="emojis/sad.png">
									<span id="icon_name_for_responsive">Sad</span>
								</button>';
							}elseif ($like_btn_status=="Angry") {
								$like_btn='
								<button data-id="'.$group_info["post_code"].'" id="like_btn" class="post_footer_btn ml-1 col-lg-3 col-md-3 col-sm-3">
									<img id="angry_icon'.$group_info["post_code"].'" data-id="'.$group_info["post_code"].'" class="icon_all angry_icon" src="emojis/angry.png">
									<span id="icon_name_for_responsive">Angry</span>
								</button>';
							}else{
								$like_btn='<button data-id="'.$group_info["post_code"].'" id="like_btn" class="post_footer_btn ml-1 col-lg-3 col-md-3 col-sm-3">
												<i class="bi bi-hand-thumbs-up"></i>
												<span id="icon_name_for_responsive">Like</span>
											</button>';
							}
							if ($group_info['post_user_id'] == $current_user) {
								$edit_btn='
								<button type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
									<i class="bi bi-three-dots-vertical"></i>
								</button>
								';
							}
							if (!empty($group_info["post_image"]=="")) {
								$output='
								<div class="post_create_box post_edit_box" id="post_edit_box'.$group_info["post_code"].'">
									<div class=" d-flex" style="justify-content:space-between" >
										<h4>
											Edit post
										</h4>
								      <button class="edit_container_close_btn btn btn-secondary" data-id="'.$group_info["post_code"].'" id="edit_container_close_btn'.$group_info["post_code"].'">
								      	Close
								      </button>
								    </div>
								    <form action="" id="post_edit_form" data-id="'.$group_info["post_code"].'" onsubmit="return false">
								        <input type="text" class="pst_cntnt" name="pst_cntnt" value="'.$group_info["post_content"].'" placeholder="Edit post..."></textarea>
								        <input type="file" style="margin-top: 5px;" class="pst_img"  name="pst_img">
								        <input type="hidden" value="'.$group_info["post_code"].'" name="post_id">
								        <img src="images/spinner.gif"  class="mb-2 post_spinner"  style="display:none" alt="">
								        <div class="alert alert-success" style="display:none;margin-top:2%;" id="alert_for_post_edit" role="alert"></div>
								        <input type="submit"class="post_btn" value="Edit">
								    </form>
								</div>
								<div class="post_box mt-1" id="post_box'.$data["unique_id"].'"  style="border: 1px solid #eaeaea;border-radius: 5px;">
									<div class="post_header d-flex justify-content-space-between" style="border-bottom: 1px solid #ccc;padding: 5px;">
										<div id="timeline_header_left" style="padding:0" class="col-lg-11 col-md-11 col-sm-11 d-flex align-items-center">
											<div class="d-flex">
												<img src="images/'.$data["profile"].'" width="40px" height="40px" class="rounded-circle">
												<div>
													<strong style="margin-left: 5px;cursor:pointer;" data-id="'.$data["unique_id"].'"><a href="user_profile.php" id="preview_usr_profile">'.$data["name"].'</a></strong>
													<p style="margin-bottom: -7px;margin-top: -7px;margin-left: 5px;font-size: 11px;color: #040404a3;">'.facebook_time_ago($group_info["post_time"]).'</p>
												</div>
												<div>
													<p style="font-size: 12px;margin-left: 4px;margin-top: 3px;">'.$sts_for_group_or_profile.'</p>
												</div>
											</div>
										</div>

										<div id="timeline_header_right" class="dropdown col-lg-1 col-md-1 col-sm-1">
										'.$edit_btn.'
										<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
											<li><a class="dropdown-item pst_edit_btn" style="cursor:pointer" data-id="'.$group_info["post_code"].'" id="pst_edit_btn'.$group_info["post_code"].'">Edit</a></li>
											<li><a class="dropdown-item pst_delete_btn" style="cursor:pointer" data-id="'.$group_info["post_code"].'" id="pst_delete_btn'.$group_info["post_code"].'">Delete</a></li>
										</ul>
										</div>
									</div>
									<p class="post_texts m-2" style="min-height:50px">
									'.$group_info["post_content"].'
									</p>
									<div class="reactor_user_show_box" id="reactor_user_show_box'.$group_info["post_code"].'">
										<div class="d-flex" style="justify-content:space-between;border-bottom:1px solid #ccc">
											<h4>Reactors</h4>
											<button class="btn btn-secondary" id="reactor_close_btn'.$group_info["post_code"].'" style="padding:2px"><i class="bi bi-x"></i></button>
										</div>
										<div id="reacts_count_user_box">
											<ul class="nav nav-tabs" style="margin-top: -9px;display: grid;grid: 29px/auto auto auto auto auto auto auto;">
												<li class="for_reactor nav-item  m-1">
												<button class=" nav-link p-1 m-1 active" data-toggle="pill" href="#all_react'.$group_info["post_code"].'">All <span id="all_react_count'.$group_info["post_code"].' style="margin-left:2px""></span></button>
												</li>
												<li class="for_reactor nav-item  m-1">
												<button class=" nav-link p-1 m-1" data-toggle="pill" id="likedbtn'.$group_info["post_code"].'" href="#liked'.$group_info["post_code"].'"><img width="12px" height="12px" class="" src="emojis/like_color.png"><span style="margin-left:2px" id="like_count'.$group_info["post_code"].'"></span></button>
												</li>
												<li class="for_reactor nav-item m-1">
												<button id="lovebtn'.$group_info["post_code"].'" class=" nav-link p-1 m-1" data-toggle="tab" href="#love'.$group_info["post_code"].'"><img width="12px" height="12px" class="" src="emojis/heart.png"><span style="margin-left:2px" id="love_count'.$group_info["post_code"].'"></span></button>
												</li>
												<li class="for_reactor nav-item m-1">
												<button id="hahabtn'.$group_info["post_code"].'" class="  nav-link p-1 m-1" data-toggle="tab" href="#haha'.$group_info["post_code"].'"><img width="12px" height="12px" class="" src="emojis/laughing.png"><span style="margin-left:2px" id="haha_count'.$group_info["post_code"].'"></span></button>
												</li>
												<li class="for_reactor nav-item m-1">
												<button id="wowbtn'.$group_info["post_code"].'" class="  nav-link p-1 m-1" data-toggle="tab" href="#wow'.$group_info["post_code"].'"><img width="12px" height="12px" class="" src="emojis/wow.png"><span style="margin-left:2px" id="wow_count'.$group_info["post_code"].'"></span></button>
												</li>
												<li class="for_reactor nav-item m-1">
												<button id="sadbtn'.$group_info["post_code"].'" class=" nav-link p-1 m-1" data-toggle="tab" href="#sad'.$group_info["post_code"].'"><img width="12px" height="12px" class="" src="emojis/sad.png"><span style="margin-left:2px" id="sad_count'.$group_info["post_code"].'"></span></button>
												</li>
												<li class="for_reactor nav-item m-1">
												<button id="angrybtn'.$group_info["post_code"].'" class=" nav-link p-1 m-1" data-toggle="tab" href="#angry'.$group_info["post_code"].'"><img width="12px" height="12px" class="" src="emojis/angry.png"><span style="margin-left:2px" id="angry_count'.$group_info["post_code"].'"></span></button>
												</li>
											</ul>
										</div>
										<div id="reactor_user_show_box'.$group_info["post_code"].'" >
											<div class="tab-content">
												<div id="all_react'.$group_info["post_code"].'" class="active tab-pane">
													<img src="images/spinner.gif" id="spinner_for_load_react_user'.$group_info["post_code"].'"  class="mb-2 post_show_spinner"  style="display:none" alt="">
												</div>
												<div id="liked'.$group_info["post_code"].'" class="tab-pane"></div>
												<div id="love'.$group_info["post_code"].'" class="tab-pane fade"></div>
												<div id="haha'.$group_info["post_code"].'" class="tab-pane fade"></div>
												<div id="wow'.$group_info["post_code"].'" class="tab-pane fade"></div>
												<div id="sad'.$group_info["post_code"].'" class="tab-pane fade"></div>
												<div id="angry'.$group_info["post_code"].'" class="tab-pane fade"></div>
											</div>
										</div>
									</div>
									<div id="like_count_and_other_container" class="d-flex" style="justify-content:space-between">
										<span id="reactors_showBtn'.$group_info["post_code"].'" data-id="'.$group_info["post_code"].'" class="reactors_showBtn">'.$count_react.' user Reacted</span>
										<span >Comments '.$count_comment.'</span>
										<span ></span>
										<span ></span>
									</div>
									<div id="icons_container'.$group_info["post_code"].'" data-id="'.$group_info["post_code"].'" class="icons_container p-2">
										<img id="like_icon'.$group_info["post_code"].'" data-group_id="'.$profile_user_id.'" data-id="'.$group_info["post_code"].'" class="icon_all like_icon" src="emojis/like_color.png">
										<img id="heart_icon'.$group_info["post_code"].'" data-group_id="'.$profile_user_id.'" data-id="'.$group_info["post_code"].'" class="icon_all heart_icon" src="emojis/heart.png">
										<img id="laughing_icon'.$group_info["post_code"].'" data-group_id="'.$profile_user_id.'" data-id="'.$group_info["post_code"].'" class="icon_all laughing_icon" src="emojis/laughing.png">
										<img id="wow_icon'.$group_info["post_code"].'" data-group_id="'.$profile_user_id.'" data-id="'.$group_info["post_code"].'" class="icon_all wow_icon" src="emojis/wow.png">
										<img id="sad_icon'.$group_info["post_code"].'" data-group_id="'.$profile_user_id.'" data-id="'.$group_info["post_code"].'" class="icon_all sad_icon" src="emojis/sad.png">
										<img id="angry_icon'.$group_info["post_code"].'" data-group_id="'.$profile_user_id.'" data-id="'.$group_info["post_code"].'" class="icon_all angry_icon" src="emojis/angry.png">
									</div>
									<div class="footer_content p-1 d-flex" style="justify-content:space-between">
										'.$like_btn.'
										<button id="comnt_btn" data-id="'.$group_info["post_code"].'" class="post_footer_btn comnt_btn col-lg-3 col-md-3 col-sm-3 ml-1">
											<i class="bi bi-chat-right-dots"></i>
											<span id="icon_name_for_responsive" class="comment_responsive">Comment</span>
										</button>
										<div id="comment_section'.$group_info["post_code"].'" class="comment_section">
											<div class="d-flex" style="justify-content:space-between;border-bottom:1px solid #ccc">
												<h5>Comments<h5>
												<span class="btn btn-secondary" style="padding:3px" data-id="'.$group_info["post_code"].'" id="comment_close_btn"><i class="bi bi-x"></i></span>
											</div>
											<div style="min-height: 70px;max-height: 200px;overflow: auto;margin-top:5px;margin-bottom:3px" id="comment_box'.$group_info["post_code"].'"></div>
											<div id="comment_form">
												<form class="d-flex" id="commment_post_form" data-id="'.$group_info["post_code"].'" onsubmit="return false">
													<button class="back_post_comment btn btn-primary p-1 ml-1" data-id="'.$group_info["post_code"].'" id="back_post_comment'.$group_info["post_code"].'" style="font-size: 9px;display:none" type="button">Post comment</button>
													<input type="text" id="cmnt_area'.$group_info["post_code"].'" class="form-control" placeholder="Post a comment.." >
													<input type="hidden" id="cmnt_id'.$group_info["post_code"].'" value="0" >
													<button class="btn btn-primary p-1 ml-1" type="submit">Post</button>
												</form>
											</div>
										</div>
										<button  class="post_footer_btn col-lg-3 col-md-3 col-sm-3 ml-1">
											<i class="bi bi-share"></i>
											<span id="icon_name_for_responsive">Share</span>
										</button>
								</div>
								</div>
								';
							}else{
							$output='
									<div class="post_create_box post_edit_box" id="post_edit_box'.$group_info["post_code"].'">
										<div class=" d-flex" style="justify-content:space-between" >
											<h4>
												Edit post
											</h4>
									      <button class="edit_container_close_btn btn btn-secondary" data-id="'.$group_info["post_code"].'" id="edit_container_close_btn'.$group_info["post_code"].'">
									      	Close
									      </button>
									    </div>
									    <form action="" id="post_edit_form" data-id="'.$group_info["post_code"].'" onsubmit="return false">
									        <input type="text" class="pst_cntnt" name="pst_cntnt" value="'.$group_info["post_content"].'" placeholder="Edit post..."></textarea>
									        <input type="file" style="margin-top: 5px;" class="pst_img"  name="pst_img">
									        <input type="hidden" value="'.$group_info["post_code"].'" name="post_id">
									        <img src="images/spinner.gif"  class="mb-2 post_spinner"  style="display:none" alt="">
									        <div class="alert alert-success" style="display:none;margin-top:2%;" id="alert_for_post_edit" role="alert"></div>
									        <input type="submit"class="post_btn" value="Edit">
									    </form>
									</div>
									<div class="post_box mt-1" id="post_box'.$group_info["post_code"].'" style="border: 1px solid #eaeaea;border-radius: 5px;">
										<div class="post_header d-flex justify-content-space-between" style="border-bottom: 1px solid #ccc;padding: 5px;">
											<div id="timeline_header_left" style="padding:0" class="col-lg-11 col-md-11 col-sm-11 d-flex align-items-center">
												<div class="d-flex">
													<img src="images/'.$data["profile"].'" width="40px" height="40px" class="rounded-circle">
													<div>
														<strong id="full_view_btn" style="margin-left: 5px;cursor:pointer;" data-id="'.$data["unique_id"].'"><a href="user_profile.php" id="preview_usr_profile">'.$data["name"].'</a></strong>
														<p style="margin-bottom: -7px;margin-top: -7px;margin-left: 5px;font-size: 11px;color: #040404a3;">'.facebook_time_ago($group_info["post_time"]).'</p>
													</div>
													<div>
														<p style="font-size: 12px;margin-left: 4px;margin-top: 3px;">'.$sts_for_group_or_profile.'</p>
													</div>
												</div>
											</div>

											<div id="timeline_header_right" class="dropdown col-lg-1 col-md-1 col-sm-1">
												'.$edit_btn.'
												<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
													<li><a class="dropdown-item pst_edit_btn" style="cursor:pointer" data-id="'.$group_info["post_code"].'" id="pst_edit_btn'.$group_info["post_code"].'">Edit</a></li>
													<li><a class="dropdown-item pst_delete_btn" style="cursor:pointer" data-id="'.$group_info["post_code"].'" id="pst_delete_btn'.$group_info["post_code"].'">Delete</a></li>
												</ul>
											</div>
										</div>
										<p class="post_texts m-2">
										'.$group_info["post_content"].'
										</p>
										<img src="images/'.$group_info["post_image"].'" class="img-thumbnail">
										<div class="reactor_user_show_box" id="reactor_user_show_box'.$group_info["post_code"].'">
											<div class="d-flex" style="justify-content:space-between;border-bottom:1px solid #ccc">
												<h4>Reactors</h4>
												<button class="btn btn-secondary" id="reactor_close_btn'.$group_info["post_code"].'" style="padding:2px"><i class="bi bi-x"></i></button>
											</div>
											<div id="reacts_count_user_box">
												<ul class="nav nav-tabs" style="margin-top: -9px;display: grid;grid: 29px/auto auto auto auto auto auto auto;">
													<li class="for_reactor nav-item  m-1">
													<button class=" nav-link p-1 m-1 active" data-toggle="pill" href="#all_react'.$group_info["post_code"].'">All <span style="margin-left:2px" id="all_react_count'.$group_info["post_code"].'"></span></button>
													</li>
													<li class="for_reactor nav-item  m-1">
													<button class=" nav-link p-1 m-1" data-toggle="pill" id="likedbtn'.$group_info["post_code"].'" href="#liked'.$group_info["post_code"].'"><img width="12px" height="12px" class="" src="emojis/like_color.png"><span style="margin-left:2px" id="like_count'.$group_info["post_code"].'"></span></button>
													</li>
													<li class="for_reactor nav-item m-1">
													<button id="lovebtn'.$group_info["post_code"].'" class=" nav-link p-1 m-1" data-toggle="tab" href="#love'.$group_info["post_code"].'"><img width="12px" height="12px" class="" src="emojis/heart.png"><span style="margin-left:2px" id="love_count'.$group_info["post_code"].'"></span></button>
													</li>
													<li class="for_reactor nav-item m-1">
													<button id="hahabtn'.$group_info["post_code"].'" class="  nav-link p-1 m-1" data-toggle="tab" href="#haha'.$group_info["post_code"].'"><img width="12px" height="12px" class="" src="emojis/laughing.png"><span style="margin-left:2px" id="haha_count'.$group_info["post_code"].'"></span></button>
													</li>
													<li class="for_reactor nav-item m-1">
													<button id="wowbtn'.$group_info["post_code"].'" class="  nav-link p-1 m-1" data-toggle="tab" href="#wow'.$group_info["post_code"].'"><img width="12px" height="12px" class="" src="emojis/wow.png"><span style="margin-left:2px" id="wow_count'.$group_info["post_code"].'"></span></button>
													</li>
													<li class="for_reactor nav-item m-1">
													<button id="sadbtn'.$group_info["post_code"].'" class=" nav-link p-1 m-1" data-toggle="tab" href="#sad'.$group_info["post_code"].'"><img width="12px" height="12px" class="" src="emojis/sad.png"><span style="margin-left:2px" id="sad_count'.$group_info["post_code"].'"></span></button>
													</li>
													<li class="for_reactor nav-item m-1">
													<button id="angrybtn'.$group_info["post_code"].'" class=" nav-link p-1 m-1" data-toggle="tab" href="#angry'.$group_info["post_code"].'"><img width="12px" height="12px" class="" src="emojis/angry.png"><span style="margin-left:2px" id="angry_count'.$group_info["post_code"].'"></span></button>
													</li>
												</ul>
											</div>
											<div id="reactor_user_show_box'.$group_info["post_code"].'" >
												<div class="tab-content">
													<div id="all_react'.$group_info["post_code"].'" class="active tab-pane">
														<img src="images/spinner.gif" id="spinner_for_load_react_user'.$group_info["post_code"].'"  class="mb-2 post_show_spinner"  style="display:none" alt="">
													</div>
													<div id="liked'.$group_info["post_code"].'" class="tab-pane"></div>
													<div id="love'.$group_info["post_code"].'" class="tab-pane fade"></div>
													<div id="haha'.$group_info["post_code"].'" class="tab-pane fade"></div>
													<div id="wow'.$group_info["post_code"].'" class="tab-pane fade"></div>
													<div id="sad'.$group_info["post_code"].'" class="tab-pane fade"></div>
													<div id="angry'.$group_info["post_code"].'" class="tab-pane fade"></div>
												</div>
											</div>
										</div>
										<div id="like_count_and_other_container" class="d-flex" style="justify-content:space-between">
											<span id="reactors_showBtn'.$group_info["post_code"].'" data-id="'.$group_info["post_code"].'" class="reactors_showBtn">'.$count_react.' user Reacted</span>
											<span >Comments '.$count_comment.'</span>
											<span ></span>
											<span ></span>
										</div>
										<div id="icons_container'.$group_info["post_code"].'" data-id="'.$group_info["post_code"].'" class="icons_container p-2">
											<img id="like_icon'.$group_info["post_code"].'" data-group_id="'.$profile_user_id.'" data-id="'.$group_info["post_code"].'" class="icon_all like_icon" src="emojis/like_color.png">
											<img id="heart_icon'.$group_info["post_code"].'" data-group_id="'.$profile_user_id.'" data-id="'.$group_info["post_code"].'" class="icon_all heart_icon" src="emojis/heart.png">
											<img id="laughing_icon'.$group_info["post_code"].'" data-group_id="'.$profile_user_id.'" data-id="'.$group_info["post_code"].'" class="icon_all laughing_icon" src="emojis/laughing.png">
											<img id="wow_icon'.$group_info["post_code"].'" data-group_id="'.$profile_user_id.'" data-id="'.$group_info["post_code"].'" class="icon_all wow_icon" src="emojis/wow.png">
											<img id="sad_icon'.$group_info["post_code"].'" data-group_id="'.$profile_user_id.'" data-id="'.$group_info["post_code"].'" class="icon_all sad_icon" src="emojis/sad.png">
											<img id="angry_icon'.$group_info["post_code"].'" data-group_id="'.$profile_user_id.'" data-id="'.$group_info["post_code"].'" class="icon_all angry_icon" src="emojis/angry.png">
										</div>
										<div class="footer_content p-1 d-flex" style="justify-content:space-between">
											'.$like_btn.'
											<button id="comnt_btn" data-id="'.$group_info["post_code"].'" class="post_footer_btn comnt_btn col-lg-3 col-md-3 col-sm-3 ml-1">
												<i class="bi bi-chat-right-dots"></i>
												<span id="icon_name_for_responsive" class="comment_responsive">Comment</span>
											</button>
											<div id="comment_section'.$group_info["post_code"].'" class="comment_section">
												<div class="d-flex" style="justify-content:space-between;border-bottom:1px solid #ccc">
													<h5>Comments<h5>
													<span class="btn btn-secondary" style="padding:3px" data-id="'.$group_info["post_code"].'" id="comment_close_btn"><i class="bi bi-x"></i></span>
												</div>
												<div style="min-height: 70px;max-height: 200px;overflow: auto;margin-top:5px;margin-bottom:3px" id="comment_box'.$group_info["post_code"].'"></div>
												<div id="comment_form">
													<form class="d-flex" id="commment_post_form" data-id="'.$group_info["post_code"].'" onsubmit="return false">
														<button class="back_post_comment btn btn-primary p-1 ml-1" data-id="'.$group_info["post_code"].'" id="back_post_comment'.$group_info["post_code"].'" style="font-size: 9px;display:none" type="button">Post comment</button>
														<input type="text" id="cmnt_area'.$group_info["post_code"].'" class="form-control" placeholder="Post a comment.." >
														<input type="hidden" id="cmnt_id'.$group_info["post_code"].'" value="0" >
														<button class="btn btn-primary p-1 ml-1" type="submit">Post</button>
													</form>
												</div>
											</div>
											<button class="post_footer_btn col-lg-3 col-md-3 col-sm-3 ml-1">
												<i class="bi bi-share"></i>
												<span id="icon_name_for_responsive">Share</span>
											</button>
										</div>
									</div>
									</div>
								';
							}
						}
					}

					echo $output;
				}
			}else{
				echo $output= '<div style="background: white;margin: 5px;box-shadow: 1px 1px 2px #ccc;border-radius: 8px;padding: 10px;">No post in this group</div>';
			}

		}
	}
}
//Follow System
if (isset($_REQUEST["insert_follow"])) {
	if ($_REQUEST["insert_follow"]=="insert follow") {
		$id=$_REQUEST["id"];
		$query_for_follow=mysqli_query($conn,"INSERT INTO follow (sender_id,reciever_id) VALUES ('$current_user','$id') ");
		if ($query_for_follow) {
			echo "Following";
		}else{
			echo "Following failed";
		}
	}
}
//Unfollow System
if (isset($_REQUEST["delete_follow"])) {
	if ($_REQUEST["delete_follow"]=="delete follow") {
		$id=$_REQUEST["id"];
		$query_for_follow=mysqli_query($conn,"DELETE FROM follow WHERE sender_id='$current_user' AND reciever_id='$id'");
		if ($query_for_follow) {
			echo "Unfollow";
		}else{
			echo "Unfollow failed";
		}
	}
}
//Create a Group
if (isset($_REQUEST["create_group"])) {
	if ($_REQUEST["create_group"]=="Create a group") {
		$group_name=$_REQUEST["group_name"];
		if (!empty($group_name)) {
			$unqId_for_group=uniqid();
			$query=mysqli_query($conn,"INSERT INTO users (unique_id,name,owner,profile,cover_photo,user_type) VALUES ('$unqId_for_group','$group_name','$current_user','group_default.jpg','group_cover_default.jpg','group')");
			if ($query) {
				$query_group_info=mysqli_query($conn,"INSERT INTO group_members (member_user_id,group_id,role,creator) VALUES ('$current_user','$unqId_for_group','Admin','$current_user')");
				if ($query_group_info) {
					echo "Created your group";
				}
			}
		}else{
			echo "Group name required";
		}
	}
}
//Show all groups
if (isset($_REQUEST['show_all_groups'])) {
	if ($_REQUEST['show_all_groups']=="show all groups") {
		$query_for_show_group=mysqli_query($conn,"SELECT * FROM users WHERE user_type='group' ");
		if ($query_for_show_group) {
			while ($data=mysqli_fetch_assoc($query_for_show_group)) {
				$name=get_user($conn,$data['owner']);
				echo '
				<a href="group.php" class="group_view_btn" id="group_view_btn" data-id="'.$data["unique_id"].'">
					<div class="usr_for_frd_rqst_box mb-1">
						<div class="d-flex align-items-center" >
							<img style="width: 140px;height: 70px;object-fit: cover;border-radius: 7px;" data-toggle="tooltip" data-placement="top" title="Click to view '.$data["name"].',s profile details" src="images/'.$data["cover_photo"].'" >
							<div>
								<strong class="ml-2" style="font-size:25px">'.$data["name"].'</strong>
								<p class="ml-2 mb-0">'.$data["group_type"].'</p>
								<p class="ml-2">Creator : <strong>'.$name.'</strong></p>
							</div>
						</div>
						<div>
						
						</div>
					</div>
				</a>
				';
			}
		}
	}
}
//Sent group join request
if (isset($_REQUEST["Sent_group_join_request"])) {
	if ($_REQUEST["Sent_group_join_request"]=="Sent group join request") {
		$id=$_REQUEST["usr_id"];
		$query1=mysqli_query($conn,"SELECT * FROM group_requests WHERE group_Id='$id' AND userId='$current_user'  ");
		if ($query1) {
			$count=mysqli_num_rows($query1);
			if ($count>0) {
				echo "Already Sent Request";
			}else{
				$query2=mysqli_query($conn,"INSERT INTO group_requests (group_Id,userId) VALUES ('$id','$current_user') ");
				if ($query2) {
					echo "Sent Request";
				}else{
					echo "failed";
				}
			}
		}else{
			$query2=mysqli_query($conn,"INSERT INTO group_requests (group_Id,userId) VALUES ('$id','$current_user') ");
				if ($query2) {
					echo "Sent Request";
				}else{
					echo "failed";
				}
		}
		
	}
}
//Group join request count
if (isset($_REQUEST["this_is_for_join_rqst_count"])) {
	if ($_REQUEST["this_is_for_join_rqst_count"]=="this is for join rqst count") {
		$group_id= $_REQUEST["group_id"];
		$query_for_join_rqst_count=mysqli_query($conn,"SELECT * FROM group_requests WHERE group_Id='$group_id' AND disabled='0' ");
		if ($query_for_join_rqst_count) {
			echo mysqli_num_rows($query_for_join_rqst_count);
		}
	}
}
//Accept Group Join Request
if (isset($_REQUEST["accpt_group_join_request"])) {
	if ($_REQUEST["accpt_group_join_request"]=="Accept group join request") {
		$id=$_REQUEST["usr_id"];
		$grp_id=$_REQUEST["grp_id"];
		$accpt_query=mysqli_query($conn,"UPDATE group_requests SET disabled='1' WHERE group_Id='$grp_id' AND userId='$id' ");
		if ($accpt_query) {
			$member_insert_query=mysqli_query($conn,"INSERT INTO group_members (member_user_id,group_id,role,disabled) VALUES ('$id','$grp_id','Member','0')");
			if ($member_insert_query) {
				echo "Approved";
			}
		}
	}
}
//Group member count
if (isset($_REQUEST["group_member_count"])) {
	if ($_REQUEST["group_member_count"]=="this is for group member count") {
		$group_id= $_REQUEST["group_id"];
		$query_for_join_rqst_count=mysqli_query($conn,"SELECT * FROM group_members WHERE group_id='$group_id' ");
		if ($query_for_join_rqst_count) {
			echo mysqli_num_rows($query_for_join_rqst_count);
		}
	}
}
//Group members
if (isset($_REQUEST["group_members"])) {
	if ($_REQUEST["group_members"]=="this is for groups") {
		$group_id= $_REQUEST["group_id"];
		$admin_sts= get_admin_sts($conn,$group_id,$current_user);
		$button='';
		$query=mysqli_query($conn,"SELECT * FROM group_members WHERE group_id='$group_id' ");
		if ($query) {
			while ($info=mysqli_fetch_assoc($query)) {
			$request_user_id=$info['member_user_id'];
			$query_for_show_usr_inf=mysqli_query($conn,"SELECT * FROM users WHERE unique_id='$request_user_id'");
			if ($query_for_show_usr_inf) {
				while ($usr_inf=mysqli_fetch_assoc($query_for_show_usr_inf)) {
					echo '
							<div class="d-flex align-items-center p-2" style="width:100%;border: 1px solid #cccc;justify-content:space-between;margin-top: 2px;" id="user_div" data-id="'.$usr_inf['unique_id'].'" >
								<div>
									<img data-toggle="tooltip" data-placement="top" title="Click to view '.$usr_inf['name'].',s profile details" src="images/'.$usr_inf['profile'].'" class="img-fluid rounded-circle" alt="Responsive image" style="width:40px;height:40px;object-fit:cover">
									<div style="margin-left: -8px;line-height: 18px;">
										<strong class="ml-2"><a href="user_profile.php" id="preview_usr_profile" data-id="'.$usr_inf['unique_id'].'">'.$usr_inf['name'].'</a></strong>
										<p class="ml-2">'.$info['role'].'</p>
									</div>
								</div>
								'?><?php 
							if ($admin_sts=="Admin") {
									$button='
										<div class="dropdown">
											<button class="btn btn-info dropdown-toggle" style="padding:4px;font-size:12px;display:inline;margin-left:7px;" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											Edit
											</button>
											<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
											<a class="dropdown-item" href="#">Member</a>
											<a class="dropdown-item" href="#">Moderator</a>
											<a class="dropdown-item" href="#">Admin</a>
											</div>
										</div>

									';
								}elseif ($admin_sts=="Moderator") {
									if ($info['role']=="Admin") {
										$button='';
									}else{
										$button='
										<div class="dropdown">
											<button class="btn btn-info dropdown-toggle" style="padding:4px;font-size:12px;display:inline;margin-left:7px;" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											Edit
											</button>
											<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
											<a class="dropdown-item" href="#">Moderator</a>
											</div>
										</div>

									';
									}
									
								}else{
									$button='';
								}
								echo $button;
								echo "</div>";

								'
							
					';
				}
			}
			}
		}
	}
}

//Group invite
if (isset($_REQUEST["group_invite_current_user_followers"])) {
	if ($_REQUEST["group_invite_current_user_followers"] == "group invite current user followers") {
		$grp_id= $_REQUEST["grp_id"];
		$query=mysqli_query($conn,"SELECT * FROM follow WHERE reciever_id='$current_user' ");
		$button="";
		if(mysqli_num_rows($query) > 0){
			while ($data=mysqli_fetch_assoc($query)) {
				$usr_id=$data['sender_id'];
				$usr_query=mysqli_query($conn,"SELECT * FROM users WHERE unique_id='$usr_id' ");
				if ($usr_query) {
					while ($usr_data=mysqli_fetch_assoc($usr_query)) {
						$ID=$usr_data['unique_id'];
						$query_for_check_already_member_or_not=mysqli_query($conn,"SELECT * FROM group_members WHERE member_user_id='$ID' AND (role='Member' OR role='Admin' OR role='Moderator') ");
						if (mysqli_num_rows($query_for_check_already_member_or_not) == 1) {
							$mmbr_sts=mysqli_fetch_assoc($query_for_check_already_member_or_not);
							$sts_txt=$mmbr_sts['role'];
							if ($sts_txt=="Admin") {
								$button='
									<button class="btn btn-primary" >Admin</button>
								';
							}elseif ($sts_txt=="Moderator") {
								$button='
									<button class="btn btn-primary" >Moderator</button>
								';
							}elseif ($sts_txt=="Member") {
								$button='
									<button class="btn btn-primary" >Member</button>
								';
							}else{
								$button='';
							}
							
						}else{
							$button='
								<button class="group_invite_insert_btn btn btn-primary" data-grp_id="'.$grp_id.'" data-id="'.$usr_data["unique_id"].'" id="group_invite_insert_btn'.$usr_data['unique_id'].'">Invite</button>
							';
						}
						echo '
						<div style="display: flex;justify-content: space-between;align-items: center;" data-id="'.$usr_data["unique_id"].'">
								<div style="cursor:pointer;width:100%" class="user_box d-flex p-2 rounded" id="src_usr_for_msg">
									<div>
										<img class="rounded-circle " width="35px" height="35px" src="images/'.$usr_data["profile"].'">
									</div>
									<div>
										<a href="user_profile.php" data-id="'.$usr_data["unique_id"].'" id="preview_usr_profile" >
											<strong id="full_view_btn" class="ml-2" data-id="'.$usr_data["unique_id"].'" style="font-size:15px">
											'.$usr_data["name"].'
											</strong>
										</a>
										<p class="ml-2 mb-0">'.$usr_data["address"].'</p>
									</div>
								</div>
								'.$button.'
							</div>
							';
					}
				}
			}
		}else{
			echo "you have no follower";
		}
	}
}

//Group invite insert
if (isset($_REQUEST["this_is_for_insert_group_invite"])) {
	if ($_REQUEST["this_is_for_insert_group_invite"]=="this is for insert group invite") {
		$usr_id=$_REQUEST["usr_id"];
		$grp_id=$_REQUEST["grp_id"];
		$query1=mysqli_query($conn,"SELECT * FROM group_invite WHERE invited_group_id='$grp_id' AND invited_usr_id='$usr_id' ");
		if (mysqli_num_rows($query1) == 0) {
			$query2=mysqli_query($conn,"INSERT INTO group_invite (invited_group_id,invited_usr_id,disabled,inviter) VALUES ('$grp_id','$usr_id','0','$current_user') ");
			if ($query2) {
				echo "invited";
			}else{
				echo "failed";
			}
		}else{
			echo "already invited";
		}
		
	}
}
//this_is_for_group_inviters
if (isset($_REQUEST['this_is_for_group_inviters'])) {
	if ($_REQUEST['this_is_for_group_inviters']=="this is for group inviters") {
		$query=mysqli_query($conn,"SELECT * FROM group_invite WHERE invited_usr_id='$current_user' AND disabled='0' ");
		if (mysqli_num_rows($query) > 0) {
			while ($grp_inf=mysqli_fetch_assoc($query)) {
				$usr_id=$grp_inf['inviter'];
				$grp_id=$grp_inf['invited_group_id'];
				$query_grp=mysqli_query($conn,"SELECT * FROM users WHERE unique_id='$grp_id' ");
				$grp_inf=mysqli_fetch_assoc($query_grp);
				$query_usr=mysqli_query($conn,"SELECT * FROM users WHERE unique_id='$usr_id' ");
				$usr_inf=mysqli_fetch_assoc($query_usr);
				echo '
					<div style="display: flex;justify-content: space-between;align-items: center;" >
						<div>
							<div style="cursor:pointer;width:100%" class="user_box d-flex p-2 rounded" >
								<div class="col-7">
									<div>
										<img class="rounded-circle " width="35px" height="35px" src="images/'.$usr_inf["profile"].'">
									</div>
									<div>
										<a href="user_profile.php" data-id="'.$usr_inf['unique_id'].'" id="preview_usr_profile" >
											<strong id="full_view_btn" class="ml-0" data-id="'.$usr_inf["unique_id"].'" style="font-size:15px">
											'.$usr_inf["name"].'
											</strong>
										</a>
									</div>
								<p class="ml-0 mb-0" style="line-height: 1;font-size: 12px;opacity: 0.7;">invited you to join</p>
								</div>
								
								<div class="col-5">
									<img src="images/'.$grp_inf['cover_photo'].'" style="width:100%;object-fit:cover;height:35px;border-radius:10px"/>
									<a href="group.php" data-id="'.$grp_inf['unique_id'].'" class="group_view_btn" id="group_view_btn" >
										<strong>
											'.$grp_inf['name'].'
										</strong>
									</a>
								</div>
							</div>
							<button class="group_invite_accept_btn btn btn-primary w-100 p-0 m-1" id="group_invite_accept_btn'.$usr_inf["unique_id"].'" data-group_id="'.$grp_inf["unique_id"].'" data-usr_id="'.$usr_inf["unique_id"].'" style="position:relative;bottom:0px;">Accept</button>
							<button class="group_invite_reject_btn btn btn-danger w-100 p-0 m-1" id="group_invite_reject_btn'.$usr_inf["unique_id"].'" data-group_id="'.$grp_inf["unique_id"].'" data-usr_id="'.$usr_inf["unique_id"].'" style="position:relative;bottom:0px;">Reject</button>
						</div>
						
					</div>
					';
			}
		}else{
			echo "No group invites";
		}
	}
}
//search follower for invite
if (isset($_REQUEST['this_is_for_follower_search'])) {
	if ($_REQUEST['this_is_for_follower_search']=="this is for follower search") {
		$input=$_REQUEST['src_input'];
		$grp_id=$_REQUEST['grp_id'];
		$query=mysqli_query($conn,"SELECT * FROM users INNER JOIN follow ON users.unique_id=follow.sender_id WHERE users.name LIKE '%$input%' AND users.unique_id !='$current_user' AND users.unique_id !='$grp_id' AND follow.reciever_id='$current_user'  LIMIT 1 ");
		if (mysqli_num_rows($query) > 0) {
					while ($usr_data=mysqli_fetch_assoc($query)) {
						$ID=$usr_data['unique_id'];
						$query_for_check_already_member_or_not=mysqli_query($conn,"SELECT * FROM group_members WHERE member_user_id='$ID' AND (role='Member' OR role='Admin' OR role='Moderator') ");
						if (mysqli_num_rows($query_for_check_already_member_or_not) == 1) {
							$mmbr_sts=mysqli_fetch_assoc($query_for_check_already_member_or_not);
							$sts_txt=$mmbr_sts['role'];
							if ($sts_txt=="Admin") {
								$button='
									<button class="btn btn-primary" >Admin</button>
								';
							}elseif ($sts_txt=="Moderator") {
								$button='
									<button class="btn btn-primary" >Moderator</button>
								';
							}elseif ($sts_txt=="Member") {
								$button='
									<button class="btn btn-primary" >Member</button>
								';
							}else{
								$button='';
							}
							
						}else{
							$button='
								<button class="group_invite_insert_btn btn btn-primary" data-grp_id="'.$grp_id.'" data-id="'.$usr_data["unique_id"].'" id="group_invite_insert_btn'.$usr_data['unique_id'].'">Invite</button>
							';
						}
						echo '
						<div style="display: flex;justify-content: space-between;align-items: center;" data-id="'.$usr_data["unique_id"].'">
								<div style="cursor:pointer;width:100%" class="user_box d-flex p-2 rounded" id="src_usr_for_msg">
									<div>
										<img class="rounded-circle " width="35px" height="35px" src="images/'.$usr_data["profile"].'">
									</div>
									<div>
										<a href="user_profile.php" data-id="'.$usr_data["unique_id"].'" id="preview_usr_profile" >
											<strong id="full_view_btn" class="ml-2" data-id="'.$usr_data["unique_id"].'" style="font-size:15px">
											'.$usr_data["name"].'
											</strong>
										</a>
										<p class="ml-2 mb-0">'.$usr_data["address"].'</p>
									</div>
								</div>
								'.$button.'
							</div>
							';
					}
				
		}else{
			echo "No follower found";
		}
	}
}


//Accept group invite
if (isset($_REQUEST["this_is_for_group_invite_accept"])) {
	if ($_REQUEST["this_is_for_group_invite_accept"]=="this is for group invite accept") {
		echo $grp_id=$_REQUEST["grp_id"];
		$query=mysqli_query($conn,"UPDATE group_invite SET disabled='1' WHERE invited_group_id='$grp_id' AND invited_usr_id='$current_user' AND disabled='0' ");
		if ($query) {
			$insert_grp_member_query=mysqli_query($conn,"INSERT INTO group_members (member_user_id,group_id,role) VALUES ('$current_user','$grp_id','Member') ");
			if ($insert_grp_member_query) {
				echo "joined";
			}

		}else{
			echo "failed";
		}
		
	}
}


//Insert a like
if (isset($_REQUEST["insert_like"])) {
	if ($_REQUEST["insert_like"]=="insert a like") {
		$id=$_REQUEST["id"];
		$query1=mysqli_query($conn,"SELECT * FROM likes WHERE liked_user_id='$current_user' AND liked_post_id='$id' AND like_status='Like' ");
		if ($query1) {
			if (mysqli_num_rows($query1) > 0) {
				$query2=mysqli_query($conn,"DELETE FROM likes WHERE liked_user_id='$current_user' AND liked_post_id='$id' ");
				if ($query2) {
					echo "unliked";
				}
			}else{
			$query5=mysqli_query($conn,"SELECT * FROM likes WHERE liked_user_id='$current_user' AND liked_post_id='$id' ");
			if ($query5) {
				if (mysqli_num_rows($query5) == 1) {
					$query3=mysqli_query($conn,"UPDATE likes SET like_status='Like' WHERE liked_user_id='$current_user' AND liked_post_id='$id' ");
					if ($query3) {
						$query_for_notification=mysqli_query($conn,"INSERT INTO notifications (notification_usr_id,activity,notification_content_id,content_type) VALUES ('$current_user','like','$id','post') ");
							if ($query_for_notification) {
								echo "liked";
							}
					}
				}else{
						$query4=mysqli_query($conn,"INSERT INTO likes (liked_user_id,liked_post_id,like_status) VALUES ('$current_user','$id','Like')");
						if ($query4) {
							$query_for_notification=mysqli_query($conn,"INSERT INTO notifications (notification_usr_id,activity,notification_content_id,content_type) VALUES ('$current_user','like','$id','post') ");
							if ($query_for_notification) {
								echo "liked";
							}
						}
					}
			}
		}

		}
		
	}
}
//Insert a heart
if (isset($_REQUEST["insert_heart"])) {
	if ($_REQUEST["insert_heart"]=="insert a heart") {
		$id=$_REQUEST["id"];
		$query1=mysqli_query($conn,"SELECT * FROM likes WHERE liked_user_id='$current_user' AND liked_post_id='$id' AND like_status='Love' ");
		if ($query1) {
			if (mysqli_num_rows($query1) > 0) {
				$query2=mysqli_query($conn,"DELETE FROM likes WHERE liked_user_id='$current_user' AND liked_post_id='$id' ");
				if ($query2) {
					echo "unreact";
				}
			}else{
			$query5=mysqli_query($conn,"SELECT * FROM likes WHERE liked_user_id='$current_user' AND liked_post_id='$id' ");
			if ($query5) {
				if (mysqli_num_rows($query5) == 1) {
					$query3=mysqli_query($conn,"UPDATE likes SET like_status='Love' WHERE liked_user_id='$current_user' AND liked_post_id='$id' ");
					if ($query3) {
						$query_for_notification=mysqli_query($conn,"INSERT INTO notifications (notification_usr_id,activity,notification_content_id,content_type) VALUES ('$current_user','love','$id','post') ");
							if ($query_for_notification) {
								echo "Loved";
							}
					}
				}else{
						$query4=mysqli_query($conn,"INSERT INTO likes (liked_user_id,liked_post_id,like_status) VALUES ('$current_user','$id','Love')");
						if ($query4) {
							$query_for_notification=mysqli_query($conn,"INSERT INTO notifications (notification_usr_id,activity,notification_content_id,content_type) VALUES ('$current_user','love','$id','post') ");
							if ($query_for_notification) {
								echo "Loved";
							}
						}
					}
			}
		}

		}
		
	}
}
//Insert a laughing_icon
if (isset($_REQUEST["insert_laughing"])) {
	if ($_REQUEST["insert_laughing"]=="insert a laughing") {
		$id=$_REQUEST["id"];
		$query1=mysqli_query($conn,"SELECT * FROM likes WHERE liked_user_id='$current_user' AND liked_post_id='$id' AND like_status='Haha' ");
		if ($query1) {
			if (mysqli_num_rows($query1) > 0) {
				$query2=mysqli_query($conn,"DELETE FROM likes WHERE liked_user_id='$current_user' AND liked_post_id='$id' ");
				if ($query2) {
					echo "unreact";
				}
			}else{
			$query5=mysqli_query($conn,"SELECT * FROM likes WHERE liked_user_id='$current_user' AND liked_post_id='$id' ");
			if ($query5) {
				if (mysqli_num_rows($query5) == 1) {
					$query3=mysqli_query($conn,"UPDATE likes SET like_status='Haha' WHERE liked_user_id='$current_user' AND liked_post_id='$id' ");
					if ($query3) {
							$query_for_notification=mysqli_query($conn,"INSERT INTO notifications (notification_usr_id,activity,notification_content_id,content_type) VALUES ('$current_user','haha','$id','post') ");
							if ($query_for_notification) {
								echo "Reacted";
							}
					}
				}else{
						$query4=mysqli_query($conn,"INSERT INTO likes (liked_user_id,liked_post_id,like_status) VALUES ('$current_user','$id','Haha')");
						if ($query4) {
							$query_for_notification=mysqli_query($conn,"INSERT INTO notifications (notification_usr_id,activity,notification_content_id,content_type) VALUES ('$current_user','haha','$id','post') ");
							if ($query_for_notification) {
								echo "Reacted";
							}
						}
					}
			}
		}

		}
		
	}
}
//Insert a wow_icon
if (isset($_REQUEST["insert_wow"])) {
	if ($_REQUEST["insert_wow"]=="insert a wow") {
		$id=$_REQUEST["id"];
		$query1=mysqli_query($conn,"SELECT * FROM likes WHERE liked_user_id='$current_user' AND liked_post_id='$id' AND like_status='Wow' ");
		if ($query1) {
			if (mysqli_num_rows($query1) > 0) {
				$query2=mysqli_query($conn,"DELETE FROM likes WHERE liked_user_id='$current_user' AND liked_post_id='$id' ");
				if ($query2) {
					echo "unreact";
				}
			}else{
			$query5=mysqli_query($conn,"SELECT * FROM likes WHERE liked_user_id='$current_user' AND liked_post_id='$id' ");
			if ($query5) {
				if (mysqli_num_rows($query5) == 1) {
					$query3=mysqli_query($conn,"UPDATE likes SET like_status='Wow' WHERE liked_user_id='$current_user' AND liked_post_id='$id' ");
					if ($query3) {
						$query_for_notification=mysqli_query($conn,"INSERT INTO notifications (notification_usr_id,activity,notification_content_id,content_type) VALUES ('$current_user','wow','$id','post') ");
							if ($query_for_notification) {
								echo "Reacted";
							}
					}
				}else{
						$query4=mysqli_query($conn,"INSERT INTO likes (liked_user_id,liked_post_id,like_status) VALUES ('$current_user','$id','Wow')");
						if ($query4) {
							$query_for_notification=mysqli_query($conn,"INSERT INTO notifications (notification_usr_id,activity,notification_content_id,content_type) VALUES ('$current_user','wow','$id','post') ");
							if ($query_for_notification) {
								echo "Reacted";
							}
						}
					}
			}
		}

		}
		
	}
}
//Insert a sad_icon
if (isset($_REQUEST["insert_sad"])) {
	if ($_REQUEST["insert_sad"]=="insert a sad") {
		$id=$_REQUEST["id"];
		$query1=mysqli_query($conn,"SELECT * FROM likes WHERE liked_user_id='$current_user' AND liked_post_id='$id' AND like_status='Sad' ");
		if ($query1) {
			if (mysqli_num_rows($query1) > 0) {
				$query2=mysqli_query($conn,"DELETE FROM likes WHERE liked_user_id='$current_user' AND liked_post_id='$id' ");
				if ($query2) {
					echo "unreact";
				}
			}else{
			$query5=mysqli_query($conn,"SELECT * FROM likes WHERE liked_user_id='$current_user' AND liked_post_id='$id' ");
			if ($query5) {
				if (mysqli_num_rows($query5) == 1) {
					$query3=mysqli_query($conn,"UPDATE likes SET like_status='Sad' WHERE liked_user_id='$current_user' AND liked_post_id='$id' ");
					if ($query3) {
						$query_for_notification=mysqli_query($conn,"INSERT INTO notifications (notification_usr_id,activity,notification_content_id,content_type) VALUES ('$current_user','sad','$id','post') ");
							if ($query_for_notification) {
								echo "Reacted";
							}
					}
				}else{
						$query4=mysqli_query($conn,"INSERT INTO likes (liked_user_id,liked_post_id,like_status) VALUES ('$current_user','$id','Sad')");
						if ($query4) {
							$query_for_notification=mysqli_query($conn,"INSERT INTO notifications (notification_usr_id,activity,notification_content_id,content_type) VALUES ('$current_user','sad','$id','post') ");
							if ($query_for_notification) {
								echo "Reacted";
							}
						}
					}
			}
		}

		}
		
	}
}
//Insert a angry_icon
if (isset($_REQUEST["insert_angry"])) {
	if ($_REQUEST["insert_angry"]=="insert a angry") {
		$id=$_REQUEST["id"];
		$query1=mysqli_query($conn,"SELECT * FROM likes WHERE liked_user_id='$current_user' AND liked_post_id='$id' AND like_status='Angry' ");
		if ($query1) {
			if (mysqli_num_rows($query1) > 0) {
				$query2=mysqli_query($conn,"DELETE FROM likes WHERE liked_user_id='$current_user' AND liked_post_id='$id' ");
				if ($query2) {
					echo "unreact";
				}
			}else{
			$query5=mysqli_query($conn,"SELECT * FROM likes WHERE liked_user_id='$current_user' AND liked_post_id='$id' ");
			if ($query5) {
				if (mysqli_num_rows($query5) == 1) {
					$query3=mysqli_query($conn,"UPDATE likes SET like_status='Angry' WHERE liked_user_id='$current_user' AND liked_post_id='$id' ");
					if ($query3) {
							$query_for_notification=mysqli_query($conn,"INSERT INTO notifications (notification_usr_id,activity,notification_content_id,content_type) VALUES ('$current_user','angry','$id','post') ");
							if ($query_for_notification) {
								echo "Reacted";
							}
					}
				}else{
						$query4=mysqli_query($conn,"INSERT INTO likes (liked_user_id,liked_post_id,like_status) VALUES ('$current_user','$id','Angry')");
						if ($query4) {
							$query_for_notification=mysqli_query($conn,"INSERT INTO notifications (notification_usr_id,activity,notification_content_id,content_type) VALUES ('$current_user','angry','$id','post') ");
							if ($query_for_notification) {
								echo "Reacted";
							}
						}
					}
			}
		}

		}
		
	}
}

//Show_reactor_users_all_react
if (isset($_REQUEST["Show_reactor_users_all_react"])) {
	if ($_REQUEST["Show_reactor_users_all_react"]=="Show reactor users all_react") {
		$id=$_REQUEST["id"];
		$query=mysqli_query($conn,"SELECT * FROM likes WHERE liked_post_id='$id' ");
		$name="";
		if ($query) {
			if (mysqli_num_rows($query) > 0) {
				while ($data=mysqli_fetch_assoc($query)) {
					$sts=$data["like_status"];
					$usrId=$data['liked_user_id'];
					$query2=mysqli_query($conn,"SELECT * FROM users WHERE unique_id='$usrId' ");
					if ($query2) {
						while ($usr_inf=mysqli_fetch_assoc($query2)) {
							echo '
								<div class="d-flex align-items-center" style="position:relative;width: 100%;padding-right: 4px;padding-left: 2px;border: 1px solid #ccc;margin: 2px 2px 2px 2px;border-radius: 3px;" id="user_div" data-id="'.$usr_inf['unique_id'].'" >
				      				<img data-toggle="tooltip" data-placement="top" title="Click to view '.$usr_inf['unique_id'].',s profile details" src="images/'.$usr_inf['profile'].'" class="img-fluid rounded-circle" alt="Responsive image" width="30px" height="30px">
				      				<img src="emojis/'.$sts.'.png" style="position: absolute;top: 52%;left: 3%;" class="img-fluid rounded-circle" alt="Responsive image" width="15px" height="15px">
				      				<div style="height: 34px;width: 100%;font-size: 10px;margin-top: 3px;">
					      				<strong class="ml-2"><a href="#" id="full_view_btn" data-id="'.$usr_inf['unique_id'].'">'.$usr_inf['name'].'</a></strong>
					      				<p class="ml-2">'.$usr_inf['address'].'</p>
				      				</div>
				      			</div>
							';
						}
					}
				}
			}else{
				echo "No React";
			}
		}
	}
}
//Show_reactor_users_like
if (isset($_REQUEST["Show_reactor_users_like"])) {
	if ($_REQUEST["Show_reactor_users_like"]=="Show reactor users like") {
		$id=$_REQUEST["id"];
		$query=mysqli_query($conn,"SELECT * FROM likes WHERE liked_post_id='$id' AND like_status='Like' ");
		$name="";
		if ($query) {
			if (mysqli_num_rows($query) > 0) {
				while ($data=mysqli_fetch_assoc($query)) {
					$usrId=$data['liked_user_id'];
					$sts=$data["like_status"];
					$query2=mysqli_query($conn,"SELECT * FROM users WHERE unique_id='$usrId' ");
					if ($query2) {
						while ($usr_inf=mysqli_fetch_assoc($query2)) {
							echo '
								<div class="d-flex align-items-center" style="position:relative;width: 100%;padding-right: 4px;padding-left: 2px;border: 1px solid #ccc;margin: 2px 2px 2px 2px;border-radius: 3px;" id="user_div" data-id="'.$usr_inf['unique_id'].'" >
				      				<img data-toggle="tooltip" data-placement="top" title="Click to view '.$usr_inf['unique_id'].',s profile details" src="images/'.$usr_inf['profile'].'" class="img-fluid rounded-circle" alt="Responsive image" width="30px" height="30px">
				      				<img src="emojis/'.$sts.'.png" style="position: absolute;top: 52%;left: 3%;" class="img-fluid rounded-circle" alt="Responsive image" width="15px" height="15px">
				      				<div style="height: 34px;width: 100%;font-size: 10px;margin-top: 3px;">
					      				<strong class="ml-2"><a href="#" id="full_view_btn" data-id="'.$usr_inf['unique_id'].'">'.$usr_inf['name'].'</a></strong>
					      				<p class="ml-2">'.$usr_inf['address'].'</p>
				      				</div>
				      			</div>
							';
						}
					}
				}
			}else{
				echo "No React";
			}
		}
	}
}
//Show_reactor_users_love
if (isset($_REQUEST["Show_reactor_users_love"])) {
	if ($_REQUEST["Show_reactor_users_love"]=="Show reactor users love") {
		$id=$_REQUEST["id"];
		$query=mysqli_query($conn,"SELECT * FROM likes WHERE liked_post_id='$id' AND like_status='Love' ");
		$name="";
		if ($query) {
			if (mysqli_num_rows($query) > 0) {
				while ($data=mysqli_fetch_assoc($query)) {
					$usrId=$data['liked_user_id'];
					$sts=$data["like_status"];
					$query2=mysqli_query($conn,"SELECT * FROM users WHERE unique_id='$usrId' ");
					if ($query2) {
						while ($usr_inf=mysqli_fetch_assoc($query2)) {
							echo '
								<div class="d-flex align-items-center" style="position:relative;width: 100%;padding-right: 4px;padding-left: 2px;border: 1px solid #ccc;margin: 2px 2px 2px 2px;border-radius: 3px;" id="user_div" data-id="'.$usr_inf['unique_id'].'" >
				      				<img data-toggle="tooltip" data-placement="top" title="Click to view '.$usr_inf['unique_id'].',s profile details" src="images/'.$usr_inf['profile'].'" class="img-fluid rounded-circle" alt="Responsive image" width="30px" height="30px">
				      				<img src="emojis/'.$sts.'.png" style="position: absolute;top: 52%;left: 3%;" class="img-fluid rounded-circle" alt="Responsive image" width="15px" height="15px">
				      				<div style="height: 34px;width: 100%;font-size: 10px;margin-top: 3px;">
					      				<strong class="ml-2"><a href="#" id="full_view_btn" data-id="'.$usr_inf['unique_id'].'">'.$usr_inf['name'].'</a></strong>
					      				<p class="ml-2">'.$usr_inf['address'].'</p>
				      				</div>
				      			</div>
							';
						}
					}
				}
			}else{
				echo "No React";
			}
		}
	}
}
//Show_reactor_users_Haha
if (isset($_REQUEST["Show_reactor_users_haha"])) {
	if ($_REQUEST["Show_reactor_users_haha"]=="Show reactor users haha") {
		$id=$_REQUEST["id"];
		$query=mysqli_query($conn,"SELECT * FROM likes WHERE liked_post_id='$id' AND like_status='Haha' ");
		$name="";
		if ($query) {
			if (mysqli_num_rows($query) > 0) {
				while ($data=mysqli_fetch_assoc($query)) {
					$usrId=$data['liked_user_id'];
					$sts=$data["like_status"];
					$query2=mysqli_query($conn,"SELECT * FROM users WHERE unique_id='$usrId' ");
					if ($query2) {
						while ($usr_inf=mysqli_fetch_assoc($query2)) {
							echo '
								<div class="d-flex align-items-center" style="position:relative;width: 100%;padding-right: 4px;padding-left: 2px;border: 1px solid #ccc;margin: 2px 2px 2px 2px;border-radius: 3px;" id="user_div" data-id="'.$usr_inf['unique_id'].'" >
				      				<img data-toggle="tooltip" data-placement="top" title="Click to view '.$usr_inf['unique_id'].',s profile details" src="images/'.$usr_inf['profile'].'" class="img-fluid rounded-circle" alt="Responsive image" width="30px" height="30px">
				      				<img src="emojis/'.$sts.'.png" style="position: absolute;top: 52%;left: 3%;" class="img-fluid rounded-circle" alt="Responsive image" width="15px" height="15px">
				      				<div style="height: 34px;width: 100%;font-size: 10px;margin-top: 3px;">
					      				<strong class="ml-2"><a href="#" id="full_view_btn" data-id="'.$usr_inf['unique_id'].'">'.$usr_inf['name'].'</a></strong>
					      				<p class="ml-2">'.$usr_inf['address'].'</p>
				      				</div>
				      			</div>
							';
						}
					}
				}
			}else{
				echo "No React";
			}
		}
	}
}
//Show_reactor_users_wow
if (isset($_REQUEST["Show_reactor_users_wow"])) {
	if ($_REQUEST["Show_reactor_users_wow"]=="Show reactor users wow") {
		$id=$_REQUEST["id"];
		$query=mysqli_query($conn,"SELECT * FROM likes WHERE liked_post_id='$id' AND like_status='Wow' ");
		$name="";
		if ($query) {
			if (mysqli_num_rows($query) > 0) {
				while ($data=mysqli_fetch_assoc($query)) {
					$usrId=$data['liked_user_id'];
					$sts=$data["like_status"];
					$query2=mysqli_query($conn,"SELECT * FROM users WHERE unique_id='$usrId' ");
					if ($query2) {
						while ($usr_inf=mysqli_fetch_assoc($query2)) {
							echo '
								<div class="d-flex align-items-center" style="position:relative;width: 100%;padding-right: 4px;padding-left: 2px;border: 1px solid #ccc;margin: 2px 2px 2px 2px;border-radius: 3px;" id="user_div" data-id="'.$usr_inf['unique_id'].'" >
				      				<img data-toggle="tooltip" data-placement="top" title="Click to view '.$usr_inf['unique_id'].',s profile details" src="images/'.$usr_inf['profile'].'" class="img-fluid rounded-circle" alt="Responsive image" width="30px" height="30px">
				      				<img src="emojis/'.$sts.'.png" style="position: absolute;top: 52%;left: 3%;" class="img-fluid rounded-circle" alt="Responsive image" width="15px" height="15px">
				      				<div style="height: 34px;width: 100%;font-size: 10px;margin-top: 3px;">
					      				<strong class="ml-2"><a href="#" id="full_view_btn" data-id="'.$usr_inf['unique_id'].'">'.$usr_inf['name'].'</a></strong>
					      				<p class="ml-2">'.$usr_inf['address'].'</p>
				      				</div>
				      			</div>
							';
						}
					}
				}
			}else{
				echo "No React";
			}
		}
	}
}
//Show_reactor_users_sad
if (isset($_REQUEST["Show_reactor_users_sad"])) {
	if ($_REQUEST["Show_reactor_users_sad"]=="Show reactor users sad") {
		$id=$_REQUEST["id"];
		$query=mysqli_query($conn,"SELECT * FROM likes WHERE liked_post_id='$id' AND like_status='Sad' ");
		$name="";
		if ($query) {
			if (mysqli_num_rows($query) > 0) {
				while ($data=mysqli_fetch_assoc($query)) {
					$usrId=$data['liked_user_id'];
					$sts=$data["like_status"];
					$query2=mysqli_query($conn,"SELECT * FROM users WHERE unique_id='$usrId' ");
					if ($query2) {
						while ($usr_inf=mysqli_fetch_assoc($query2)) {
							echo '
								<div class="d-flex align-items-center" style="position:relative;width: 100%;padding-right: 4px;padding-left: 2px;border: 1px solid #ccc;margin: 2px 2px 2px 2px;border-radius: 3px;" id="user_div" data-id="'.$usr_inf['unique_id'].'" >
				      				<img data-toggle="tooltip" data-placement="top" title="Click to view '.$usr_inf['unique_id'].',s profile details" src="images/'.$usr_inf['profile'].'" class="img-fluid rounded-circle" alt="Responsive image" width="30px" height="30px">
				      				<img src="emojis/'.$sts.'.png" style="position: absolute;top: 52%;left: 3%;" class="img-fluid rounded-circle" alt="Responsive image" width="15px" height="15px">
				      				<div style="height: 34px;width: 100%;font-size: 10px;margin-top: 3px;">
					      				<strong class="ml-2"><a href="#" id="full_view_btn" data-id="'.$usr_inf['unique_id'].'">'.$usr_inf['name'].'</a></strong>
					      				<p class="ml-2">'.$usr_inf['address'].'</p>
				      				</div>
				      			</div>
							';
						}
					}
				}
			}else{
				echo "No React";
			}
		}
	}
}
//Show_reactor_users_sad
if (isset($_REQUEST["Show_reactor_users_angry"])) {
	if ($_REQUEST["Show_reactor_users_angry"]=="Show reactor users angry") {
		$id=$_REQUEST["id"];
		$query=mysqli_query($conn,"SELECT * FROM likes WHERE liked_post_id='$id' AND like_status='Angry' ");
		$name="";
		if ($query) {
			if (mysqli_num_rows($query) > 0) {
				while ($data=mysqli_fetch_assoc($query)) {
					$usrId=$data['liked_user_id'];
					$sts=$data["like_status"];
					$query2=mysqli_query($conn,"SELECT * FROM users WHERE unique_id='$usrId' ");
					if ($query2) {
						while ($usr_inf=mysqli_fetch_assoc($query2)) {
							echo '
								<div class="d-flex align-items-center" style="position:relative;width: 100%;padding-right: 4px;padding-left: 2px;border: 1px solid #ccc;margin: 2px 2px 2px 2px;border-radius: 3px;" id="user_div" data-id="'.$usr_inf['unique_id'].'" >
				      				<img data-toggle="tooltip" data-placement="top" title="Click to view '.$usr_inf['unique_id'].',s profile details" src="images/'.$usr_inf['profile'].'" class="img-fluid rounded-circle" alt="Responsive image" width="30px" height="30px">
				      				<img src="emojis/'.$sts.'.png" style="position: absolute;top: 52%;left: 3%;" class="img-fluid rounded-circle" alt="Responsive image" width="15px" height="15px">
				      				<div style="height: 34px;width: 100%;font-size: 10px;margin-top: 3px;">
					      				<strong class="ml-2"><a href="#" id="full_view_btn" data-id="'.$usr_inf['unique_id'].'">'.$usr_inf['name'].'</a></strong>
					      				<p class="ml-2">'.$usr_inf['address'].'</p>
				      				</div>
				      			</div>
							';
						}
					}
				}
			}else{
				echo "No React";
			}
		}
	}
}


if (isset($_REQUEST["for_react_counter"])) {
	if ($_REQUEST["for_react_counter"]=="for react counter") {
		$id=$_REQUEST["id"];
		$query=mysqli_query($conn,"SELECT * FROM likes WHERE liked_post_id='$id'");
		$query1=mysqli_query($conn,"SELECT * FROM likes WHERE liked_post_id='$id' AND like_status='Like' ");
		$query2=mysqli_query($conn,"SELECT * FROM likes WHERE liked_post_id='$id' AND like_status='Love' ");
		$query3=mysqli_query($conn,"SELECT * FROM likes WHERE liked_post_id='$id' AND like_status='Haha' ");
		$query4=mysqli_query($conn,"SELECT * FROM likes WHERE liked_post_id='$id' AND like_status='Wow' ");
		$query5=mysqli_query($conn,"SELECT * FROM likes WHERE liked_post_id='$id' AND like_status='Sad' ");
		$query6=mysqli_query($conn,"SELECT * FROM likes WHERE liked_post_id='$id' AND like_status='Angry' ");
		$myObj["all_react"]= mysqli_num_rows($query);
		$myObj["like"]= mysqli_num_rows($query1);
		$myObj["love"]= mysqli_num_rows($query2);
		$myObj["haha"]= mysqli_num_rows($query3);
		$myObj["wow"]= mysqli_num_rows($query4);
		$myObj["sad"]= mysqli_num_rows($query5);
		$myObj["angry"]= mysqli_num_rows($query6);
		$myJSON = json_encode($myObj);
		echo $myJSON;
	}
}
//comment section
if (isset($_REQUEST["this_is_for_comment_post"])) {
	if ($_REQUEST["this_is_for_comment_post"]=="This is for comment post") {
		$comment_text=$_REQUEST["comment_text"];
		if (!empty($comment_text)) {
			$post_id=$_REQUEST["post_id"];
			$parent_id=$_REQUEST["comment_id"];
			$query=mysqli_query($conn,"INSERT INTO comment (comment_parent_id,comment_usr_id,comment_post_id,comment_text) VALUES ('$parent_id','$current_user','$post_id','$comment_text') ");
			if ($query) {
				$query_for_notification=mysqli_query($conn,"INSERT INTO notifications (notification_usr_id,activity,notification_content_id,content_type) VALUES ('$current_user','comment','$post_id','post') ");
				if ($query_for_notification) {
					echo "commented";
				}
				
			}else{
				echo "failed";
			}
		}
	}
}
if (isset($_REQUEST["this_is_for_all_comments_show"])) {
	if ($_REQUEST["this_is_for_all_comments_show"]=="This is for all comment show") {
		$post_id=$_REQUEST["post_id"];
		$output="";
		$query=mysqli_query($conn,"SELECT * FROM comment WHERE comment_post_id='$post_id' AND comment_parent_id='0' ");
		if (mysqli_num_rows($query) > 0) {
			while ($data=mysqli_fetch_assoc($query)) {
				$usr_id=$data['comment_usr_id'];
				$replies_count=get_comment_replies($conn,$data['comment_id']);
				$query_for_user=mysqli_query($conn,"SELECT * FROM users WHERE unique_id='$usr_id' ");
				if ($query_for_user) {
					while ($usr_info=mysqli_fetch_assoc($query_for_user)) {
						$output.= '
						<div class="d-flex align-items-center m-1" id="user_div" data-id="'.$usr_info["unique_id"].'" >
							<div>
								<img data-toggle="tooltip" style="margin-top: -25px;width: 30px;" data-placement="top" title="Click to view '.$usr_info["name"].',s profile details" src="images/'.$usr_info["profile"].'" class="img-fluid rounded-circle" alt="Responsive image" width="40px" height="40px">
							</div>
							<div>
								<div style="width: fit-content;margin-left: 3px;background: #aaaaaa2b;border-radius: 10px;padding: 6px;height: fit-content;position: relative;padding-top: 0px;padding-bottom: 2px;">
									<div class="d-flex">
										<span style="font-size: 12px;position: relative;font-weight: 700;" id="full_view_btn" data-toggle="tooltip" data-placement="top" title="Click to view '.$usr_info["name"].',s profile details" data-id="'.$usr_info["unique_id"].'">'.$usr_info["name"].'
										</span><p style="font-size: 9px;margin-top: 3px;margin-left: 8px;">'.facebook_time_ago($data["comment_time"]).'</p>
									</div>
									<p style="margin-bottom: 2px;margin-top: -22px;font-weight: 500;font-size:14px">'.$data["comment_text"].'</p>
								</div>
								<div class="d-flex" style="font-size: 10px;height:15px;margin-left: 7px;margin-top: -2px;">
									<p style="margin-left: 3px;cursor:pointer">Like</p>
									<p  id="reply'.$data["comment_post_id"].'" data-name="'.$usr_info["name"].'" data-id="'.$data["comment_id"].'"  style="margin-left: 3px;cursor:pointer">Reply</p>
									<p  id="replies'.$data["comment_post_id"].'" data-toggle="collapse" href="#replies'.$data["comment_id"].'" data-id="'.$data["comment_id"].'"  style="margin-left: 3px;cursor:pointer">Replies '.$replies_count.'</p>
								</div>
							</div>
						</div>
						<div id="replies'.$data["comment_id"].'" class="collapse"></div>
						';
					}
				}

			}
		}else{
			$output.= "No comment in this post..";
		}
	echo $output;

	}
}
//Get Replies
if (isset($_REQUEST["this_is_for_get_replies"])) {
	if ($_REQUEST["this_is_for_get_replies"]=="This is for get replies") {
		$replies_id=$_REQUEST["reply_id"];
		$query=mysqli_query($conn,"SELECT * FROM comment WHERE comment_parent_id='$replies_id' ");
		if (mysqli_num_rows($query) > 0) {
			while ($data=mysqli_fetch_assoc($query)) {
				$usr_id=$data['comment_usr_id'];
				$query_for_user=mysqli_query($conn,"SELECT * FROM users WHERE unique_id='$usr_id' ");
				while ($usr_info=mysqli_fetch_assoc($query_for_user)) {
					echo '
						<div class="d-flex align-items-center" style="margin-left:48px;margin-top:4px" id="user_div" data-id="'.$usr_info["unique_id"].'" >
							<div>
								<img data-toggle="tooltip" style="margin-top: -25px;width: 30px;" data-placement="top" title="Click to view '.$usr_info["name"].',s profile details" src="images/'.$usr_info["profile"].'" class="img-fluid rounded-circle" alt="Responsive image" width="40px" height="40px">
							</div>
							<div>
								<div style="width: fit-content;margin-left: 3px;background: #aaaaaa2b;border-radius: 10px;padding: 6px;height: fit-content;position: relative;padding-top: 0px;padding-bottom: 2px;">
									<div class="d-flex">
										<span style="font-size: 12px;position: relative;font-weight: 700;" id="full_view_btn" data-toggle="tooltip" data-placement="top" title="Click to view '.$usr_info["name"].',s profile details" data-id="'.$usr_info["unique_id"].'">'.$usr_info["name"].'
										</span><p style="font-size: 9px;margin-top: 3px;margin-left: 8px;">'.facebook_time_ago($data["comment_time"]).'</p>
									</div>
									<p style="margin-bottom: 2px;margin-top: -22px;font-weight: 500;font-size:14px">'.$data["comment_text"].'</p>
									<div class="d-flex" style="font-size: 10px;height:15px;margin-top: -2px;">
										<p  id="reply'.$data["comment_post_id"].'" data-name="'.$usr_info["name"].'" data-id="'.$data["comment_parent_id"].'"  style="margin-left: 3px;cursor:pointer">Reply</p>
									</div>
								</div>

							</div>
						</div>
					';
				}
			}
		}else{
			echo "No Replies";
		}
	}
}

//Notification
if (isset($_REQUEST["this_is_for_notificatoin"])) {
	if ($_REQUEST["this_is_for_notificatoin"]=="this is for notification") {
		$query=mysqli_query($conn,"SELECT * FROM notifications INNER JOIN posts ON notifications.notification_content_id=posts.post_code WHERE posts.post_user_id='$current_user' ORDER BY notification_id DESC ");
		$output="";
		if (mysqli_num_rows($query) > 0) {
			echo '<div style="width:100%;max-height:300px;overflow:auto">';
			while ($data=mysqli_fetch_assoc($query)) {
				$usrId= $data['notification_usr_id'];
				$activity="";
				$react_icon="";
				if ($data['activity'] == 'comment') {
					$activity='commented your post.';
				}elseif ($data['activity'] == "Post") {
					$activity=' posted an status.';
				}elseif ($data['activity'] == "like") {
					$activity='liked your photo';
					$react_icon='<img style="position: absolute;left: 10%;top: 22%;" width="15px" height="15px" src="emojis/like.png">';
				}elseif ($data['activity'] == "love") {
					$activity='loved your photo';
					$react_icon='<img style="position: absolute;left: 10%;top: 22%;" width="15px" height="15px" src="emojis/heart.png">';
				}elseif ($data['activity'] == "wow") {
					$activity='wow react on your photo';
					$react_icon='<img style="position: absolute;left: 10%;top: 22%;" width="15px" height="15px" src="emojis/wow.png">';
				}elseif ($data['activity'] == "haha") {
					$activity='haha react on your photo';
					$react_icon='<img style="position: absolute;left: 10%;top: 22%;" width="15px" height="15px" src="emojis/laughing.png">';
				}elseif ($data['activity'] == "sad") {
					$activity='sad react on your photo';
					$react_icon='<img style="position: absolute;left: 10%;top: 22%;" width="15px" height="15px" src="emojis/sad.png">';
				}elseif ($data['activity'] == "angry") {
					$activity='angry react on your photo';
					$react_icon='<img style="position: absolute;left: 10%;top: 22%;" width="15px" height="15px" src="emojis/angry.png">';
				}
				$usr_query=mysqli_query($conn,"SELECT * FROM users WHERE unique_id='$usrId'");
				while ($usr_data=mysqli_fetch_assoc($usr_query)) {
					$noty_nm="";
					$noty_pst_img="";
					if ($usr_data["unique_id"] == $current_user) {
						$noty_nm= "you";
					}else{
						$noty_nm= $usr_data["name"];
					}
					if ($data["post_image"] !=="") {
						$noty_pst_img='<img width="30px" height="30px" src="images/'.$data["post_image"].'">';
					}
					echo '
					<li>
						<a href="single_post.php?pst_code='.$data['post_code'].'" class="dropdown-item" style="position:relative"  >
							<img width="25px" height="25px" style="border-radius:25px" src="images/'.$usr_data["profile"].'">'.$react_icon.' '.$noty_nm.' '.$activity.'</br>
							<p style="font-size:9px"><span style="font-size:12px">Post: </span>'.$data['post_content'].'</p>
							'.$noty_pst_img.'
						</a>
					</li>
					';
				}
			}
			echo "</div>";
		}else{
			echo "No Notification yet..";
		}
		echo '<div style="max-height:200px;overflow:auto">';
		echo "Friend Request";
		$query_frd_rqst=mysqli_query($conn,"SELECT * FROM notifications WHERE notification_content_id='$current_user' ORDER BY notification_id DESC");
		while ($fetch_frd_rqst=mysqli_fetch_assoc($query_frd_rqst)) {
			$frd_rqst_usr_id=$fetch_frd_rqst['notification_usr_id'];
			if ($fetch_frd_rqst['activity'] == 'Friend Request') {
					$activity=' sent you a friend request.';
					$react_icon='<i style="position: absolute;left: 10%;top: 22%;" width="15px" height="15px" class="bi bi-person-check"></i>';
				}
			$usr_query2=mysqli_query($conn,"SELECT * FROM users WHERE unique_id='$frd_rqst_usr_id'");
			while ($usr_data2=mysqli_fetch_assoc($usr_query2)) {
				echo'
				<li>
					<a class="dropdown-item" href="#frd" style="position:relative" data-toggle="tab" >
						<img width="25px" height="25px" style="border-radius:25px" src="images/'.$usr_data2["profile"].'">'.$react_icon.' '.$usr_data2["name"].' '.$activity.'</br>
					</a>
				</li>
				';
			}
		}
		echo "</div>";
	}
}
//Group join request
if(isset($_REQUEST["this_is_for_group_join_requests"])){
	if($_REQUEST["this_is_for_group_join_requests"]=="this is for group join requests"){
		$id=$_REQUEST["id"];
		$query1=mysqli_query($conn,"SELECT * FROM group_requests WHERE group_Id='$id' AND disabled='0' ");
		if ($query1) {
			while ($data=mysqli_fetch_assoc($query1)) {
				$request_user_id=$data['userId'];
				$query_for_show_usr_inf=mysqli_query($conn,"SELECT * FROM users WHERE unique_id='$request_user_id'");
				if ($query_for_show_usr_inf) {
					if (mysqli_num_rows($query_for_show_usr_inf) > 0) {
						while ($usr_inf=mysqli_fetch_assoc($query_for_show_usr_inf)) {
						echo '
								<div class="d-flex align-items-center col-lg-4 col-md-4 col-sm-6" style="width: fit-content;padding-right: 4px;padding-left: 2px;border: 1px solid #ccc;margin: 2px 2px 2px 2px;border-radius: 3px;" id="user_div" data-id="'.$usr_inf['unique_id'].'" >
									<img data-toggle="tooltip" data-placement="top" title="Click to view '.$usr_inf['unique_id'].',s profile details" src="images/'.$usr_inf['profile'].'" class="img-fluid rounded-circle" alt="Responsive image" width="40px" height="40px">
									<div>
										<strong class="ml-2"><a href="group.php" id="preview_usr_profile" data-id="'.$usr_inf['unique_id'].'">'.$usr_inf['name'].'</a></strong>
										<p class="ml-2">'.$usr_inf['address'].'</p>
									</div>
									<input type="text" id="join_approve_group_id" hidden data-id="'.$id.'">
									<button class="btn btn-success" id="join_approve_btn" data-id="'.$usr_inf['unique_id'].'" style="padding:4px;font-size:12px;display:inline;margin-left:7px;">Approve</button>
									<button class="btn btn-danger" id="join_reject_btn" data-id="'.$usr_inf['unique_id'].'"  style="padding:4px;font-size:12px;display:inline;margin-left:7px;">Reject</button>
									
								</div>
							';
						}
					}else{
						echo mysqli_num_rows($query_for_show_usr_inf);
					}
				}
			}
		}
	}
}

//Show last message users
if (isset($_REQUEST["this_is_for_last_message_user"])) {
	if ($_REQUEST["this_is_for_last_message_user"] == "this is for last message user") {
		$query_for_user=mysqli_query($conn,"SELECT * FROM users WHERE NOT unique_id='$current_user' ");
		$you="";
		if($query_for_user){
			while ($usr_inf=mysqli_fetch_assoc($query_for_user)) {
				$user_id=$usr_inf['unique_id'];
				$query=mysqli_query($conn,"SELECT * FROM msgs WHERE (receive_user_id='$current_user' OR sent_user_id='$current_user') AND (receive_user_id='$user_id' OR sent_user_id='$user_id') ORDER BY msg_id DESC LIMIT 1;");
				if(mysqli_num_rows($query) > 0){
					while ($data=mysqli_fetch_assoc($query)) {
						if ($data['sent_user_id']==$current_user) {
							$you="<strong>you : </strong>";
						}else{
							$you="";
						}
						echo '
							<a href="#" id="select_user_for_message'.$usr_inf["unique_id"].'" data-id="'.$usr_inf["unique_id"].'" class="select_user_for_message">
								<div class="d-flex mb-1 border align-items-center" id="user_div" data-id="'.$usr_inf["unique_id"].'" >
									<img src="images/'.$usr_inf["profile"].'" class="ml-2 img-fluid rounded-circle" alt="Responsive image" style="width:40px;height:40px;object-fit:cover">
									<div>
										<strong class="ml-2">'.$usr_inf["name"].'</strong>
										<p class="ml-2">'.$you.' '.$data["msg"].'</p>
									</div>
								</div>
							</a>
						';
					}
				}
			}
		}
	}
}

//This is for select user for message
if (isset($_REQUEST["this_is_for_select_user_for_message"])) {
	if ($_REQUEST["this_is_for_select_user_for_message"]=="This is for selecet user for message") {
		$id=$_REQUEST["id"];
		$query=mysqli_query($conn,"SELECT * FROM users WHERE unique_id='$id' ");
		$inf=mysqli_fetch_assoc($query);

		echo '
		
		<div class="col-12 container-fluid rounded" style="height: 520px;padding:0px">
			<div class="chat_area_header container-fluid p-2 shadow-sm d-flex " style="justify-content: space-between;align-items: center;" >
				<a href="#" id="chat_back_btn" data-id="'.$inf['unique_id'].'">
					<button id="chat_close_btn" class="btn btn-outline-primary p-2">
						<i class="bi bi-arrow-left"></i>
					</button>
				</a>
				<div class="d-flex align-items-center" >
					<img class="rounded-circle p-1" width="35px" height="35px" src="images/'.$inf['profile'].'">
					<strong>'.$inf['name'].'</strong>
					<img class="typing_gif" src="typing.gif">
				</div>
				<button class="btn btn-outline-primary p-2"><i class="bi bi-info-circle"></i>
				</button>
			</div>
			<div id="chat_area" style="overflow: auto;min-height: 300px;max-height: 417px;width: 100%;height: 100%; "></div>
		
			<div class="chat_area_footer" style="position: absolute;width: 100%;bottom: 0px;left: 2px;">
				<form onsubmit="return false" style="display:flex" id="sent_msg_btn'.$inf['unique_id'].'" data-id="'.$inf['unique_id'].'" class="sent_msg_btn" class="p-2 input-group rounded">
					<input class="form-control " id="btn_sent'.$inf['unique_id'].'" data-id="'.$inf['unique_id'].'" placeholder="Chat with '.$inf['name'].'....." name="msg_input_field" placeholder="" type="text" >
					<input class="form-control" name="usr_id" value="" style="display:none" type="text" >
					<button type="submit" class="btn btn-light border">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send" viewBox="0 0 16 16">
						<path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576 6.636 10.07Zm6.787-8.201L1.591 6.602l4.339 2.76 7.494-7.493Z"/>
					</svg>
					</button>
				</form>
			</div>
			</div>
		
		</div>
		';
	}
}

//This is for single post show
if (isset($_REQUEST["this_is_for_show_single_post"])) {
	if ($_REQUEST["this_is_for_show_single_post"]=="this is for show single_post") {
		$pst_id=$_REQUEST['pst_id'];
		$query=mysqli_query($conn,"SELECT * FROM posts INNER JOIN users WHERE post_code='$pst_id' ");
		$data=mysqli_fetch_assoc($query);
		$post_user_status= $data["post_user_status"];
		$sts_for_group_or_profile="posted a status...";
		if ($post_user_status=="profile upload") {
			$sts_for_group_or_profile="updated profile picture.";
		}elseif($post_user_status=="cover upload"){
			$sts_for_group_or_profile="updated cover photo.";
		}else{
			$sts_for_group_or_profile="posted a status...";
		}
		$count_react=count_reacts($conn,$data['post_code']);
		$count_comment=get_comment_count($conn,$data['post_code']);
		$name=get_user($conn,$data["post_user_id"]);
		$user_image=get_user_image($conn,$data["post_user_id"]);
		$user_id=get_user_id($conn,$data["post_user_id"]);
		$like_btn="";
		$like_btn_status= if_like_or_not($conn,$data['post_code'],$current_user);
		if ($like_btn_status=="Like") {
			$like_btn='
				<button data-id="'.$data["post_code"].'" id="like_btn" class="post_footer_btn ml-1 col-lg-3 col-md-3 col-sm-3">
					<img class="icon_all like_icon" src="emojis/like_color.png">
					<span id="icon_name_for_responsive">Liked</span>
				</button>';
		}elseif ($like_btn_status=="Love") {
			$like_btn='
				<button data-id="'.$data["post_code"].'" id="like_btn" class="post_footer_btn ml-1 col-lg-3 col-md-3 col-sm-3">
					<img class="icon_all heart_icon" src="emojis/heart.png">
					<span id="icon_name_for_responsive">Love</span>
				</button>';
		}elseif ($like_btn_status=="Haha") {
			$like_btn='
				<button data-id="'.$data["post_code"].'" id="like_btn" class="post_footer_btn ml-1 col-lg-3 col-md-3 col-sm-3">
					<img class="icon_all laughing_icon" src="emojis/laughing.png">
					<span id="icon_name_for_responsive">Haha</span>
				</button>';
		}elseif ($like_btn_status=="Wow") {
			$like_btn='
			<button data-id="'.$data["post_code"].'" id="like_btn" class="post_footer_btn ml-1 col-lg-3 col-md-3 col-sm-3">
				<img id="wow_icon'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icon_all wow_icon" src="emojis/wow.png">
				<span id="icon_name_for_responsive">Wow</span>
			</button>';
		}elseif ($like_btn_status=="Sad") {
			$like_btn='
			<button data-id="'.$data["post_code"].'" id="like_btn" class="post_footer_btn ml-1 col-lg-3 col-md-3 col-sm-3">
				<img id="sad_icon'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icon_all sad_icon" src="emojis/sad.png">
				<span id="icon_name_for_responsive">Sad</span>
			</button>';
		}elseif ($like_btn_status=="Angry") {
			$like_btn='
			<button data-id="'.$data["post_code"].'" id="like_btn" class="post_footer_btn ml-1 col-lg-3 col-md-3 col-sm-3">
				<img id="angry_icon'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icon_all angry_icon" src="emojis/angry.png">
				<span id="icon_name_for_responsive">Angry</span>
			</button>';
		}else{
			$like_btn='<button data-id="'.$data["post_code"].'" id="like_btn" class="post_footer_btn ml-1 col-lg-3 col-md-3 col-sm-3">
							<i class="bi bi-hand-thumbs-up"></i>
							<span id="icon_name_for_responsive">Like</span>
						</button>';
		}
		if (!empty($data["post_image"]=="")) {
			echo '
			<div class="post_box mt-1"  style="border: 1px solid #eaeaea;border-radius: 5px;">
				<a href="single_post.php?pst_code='.$data["post_code"].'">
					<div class="post_header d-flex justify-content-space-between" style="border-bottom: 1px solid #ccc;padding: 5px;">
						<div id="timeline_header_left" style="padding:0" class="col-lg-11 col-md-11 col-sm-11 d-flex align-items-center">
							<div class="d-flex">
								<img src="images/'.$user_image.'" width="40px" height="40px" class="rounded-circle">
								<div>
									<a>
										<strong id="" style="margin-left: 5px;" data-id="'.$data["unique_id"].'">'.$name.'</strong>
									</a>
									<p style="margin-bottom: -7px;margin-top: -7px;margin-left: 5px;font-size: 11px;color: #040404a3;">'.facebook_time_ago($data["post_time"]).'</p>
								</div>
								<div>
									<p style="font-size: 12px;margin-left: 4px;margin-top: 3px;">'.$sts_for_group_or_profile.'</p>
								</div>
							</div>
						</div>

						<div id="timeline_header_right" class="dropdown col-lg-1 col-md-1 col-sm-1">
							<button type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
							<i class="bi bi-three-dots-vertical"></i>
							</button>
							<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
							<li><a class="dropdown-item" href="#">Edit</a></li>
							<li><a class="dropdown-item" href="#">Delete</a></li>
							</ul>
						</div>
					</div>
				</a>
				
				<p class="post_texts m-2" style="min-height:50px">
				'.$data["post_content"].'
				</p>
				<div class="reactor_user_show_box" id="reactor_user_show_box'.$data["post_code"].'">
					<div class="d-flex" style="justify-content:space-between;border-bottom:1px solid #ccc">
						<h4>Reactors</h4>
						<button class="btn btn-secondary" id="reactor_close_btn'.$data["post_code"].'" style="padding:2px"><i class="bi bi-x"></i></button>
					</div>
					<div id="reacts_count_user_box">
						<ul class="nav nav-tabs" style="margin-top: -9px;display: grid;grid: 29px/auto auto auto auto auto auto auto;">
							<li class="for_reactor nav-item  m-1">
								<button class=" nav-link p-1 m-1 active" data-toggle="pill" href="#all_react'.$data["post_code"].'">All <span id="all_react_count'.$data["post_code"].' style="margin-left:2px""></span></button>
							</li>
							<li class="for_reactor nav-item  m-1">
								<button class=" nav-link p-1 m-1" data-toggle="pill" id="likedbtn'.$data["post_code"].'" href="#liked'.$data["post_code"].'"><img width="12px" height="12px" class="" src="emojis/like_color.png"><span style="margin-left:2px" id="like_count'.$data["post_code"].'"></span></button>
							</li>
							<li class="for_reactor nav-item m-1">
								<button id="lovebtn'.$data["post_code"].'" class=" nav-link p-1 m-1" data-toggle="tab" href="#love'.$data["post_code"].'"><img width="12px" height="12px" class="" src="emojis/heart.png"><span style="margin-left:2px" id="love_count'.$data["post_code"].'"></span></button>
							</li>
							<li class="for_reactor nav-item m-1">
								<button id="hahabtn'.$data["post_code"].'" class="  nav-link p-1 m-1" data-toggle="tab" href="#haha'.$data["post_code"].'"><img width="12px" height="12px" class="" src="emojis/laughing.png"><span style="margin-left:2px" id="haha_count'.$data["post_code"].'"></span></button>
							</li>
							<li class="for_reactor nav-item m-1">
								<button id="wowbtn'.$data["post_code"].'" class="  nav-link p-1 m-1" data-toggle="tab" href="#wow'.$data["post_code"].'"><img width="12px" height="12px" class="" src="emojis/wow.png"><span style="margin-left:2px" id="wow_count'.$data["post_code"].'"></span></button>
							</li>
							<li class="for_reactor nav-item m-1">
								<button id="sadbtn'.$data["post_code"].'" class=" nav-link p-1 m-1" data-toggle="tab" href="#sad'.$data["post_code"].'"><img width="12px" height="12px" class="" src="emojis/sad.png"><span style="margin-left:2px" id="sad_count'.$data["post_code"].'"></span></button>
							</li>
							<li class="for_reactor nav-item m-1">
								<button id="angrybtn'.$data["post_code"].'" class=" nav-link p-1 m-1" data-toggle="tab" href="#angry'.$data["post_code"].'"><img width="12px" height="12px" class="" src="emojis/angry.png"><span style="margin-left:2px" id="angry_count'.$data["post_code"].'"></span></button>
							</li>
						</ul>
					</div>
					<div id="reactor_user_show_box'.$data["post_code"].'" >
						<div class="tab-content">
							<div id="all_react'.$data["post_code"].'" class="active tab-pane">
								<img src="images/spinner.gif" id="spinner_for_load_react_user'.$data["post_code"].'"  class="mb-2 post_show_spinner"  style="display:none" alt="">
							</div>
							<div id="liked'.$data["post_code"].'" class="tab-pane"></div>
							<div id="love'.$data["post_code"].'" class="tab-pane fade"></div>
							<div id="haha'.$data["post_code"].'" class="tab-pane fade"></div>
							<div id="wow'.$data["post_code"].'" class="tab-pane fade"></div>
							<div id="sad'.$data["post_code"].'" class="tab-pane fade"></div>
							<div id="angry'.$data["post_code"].'" class="tab-pane fade"></div>
						</div>
					</div>
				</div>
				<div id="like_count_and_other_container" class="d-flex" style="justify-content:space-between">
					<span id="reactors_showBtn'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="reactors_showBtn">'.$count_react.' user Reacted</span>
					<span >Comments '.$count_comment.'</span>
					<span ></span>
					<span ></span>
				</div>
				<div id="icons_container'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icons_container p-2">
					<img id="like_icon'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icon_all like_icon" src="emojis/like_color.png">
					<img id="heart_icon'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icon_all heart_icon" src="emojis/heart.png">
					<img id="laughing_icon'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icon_all laughing_icon" src="emojis/laughing.png">
					<img id="wow_icon'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icon_all wow_icon" src="emojis/wow.png">
					<img id="sad_icon'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icon_all sad_icon" src="emojis/sad.png">
					<img id="angry_icon'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icon_all angry_icon" src="emojis/angry.png">
				</div>
				<div class="footer_content p-1 d-flex" style="justify-content:space-between">
					'.$like_btn.'
					<button id="comnt_btn" data-id="'.$data["post_code"].'" class="post_footer_btn comnt_btn col-lg-3 col-md-3 col-sm-3 ml-1">
						<i class="bi bi-chat-right-dots"></i>
						<span id="icon_name_for_responsive" class="comment_responsive">Comment</span>
					</button>
					<div id="comment_section'.$data["post_code"].'" class="comment_section">
						<div class="d-flex" style="justify-content:space-between;border-bottom:1px solid #ccc">
							<h5>Comments<h5>
							<span class="btn btn-secondary" style="padding:3px" data-id="'.$data["post_code"].'" id="comment_close_btn"><i class="bi bi-x"></i></span>
						</div>
						<div style="min-height: 70px;max-height: 200px;overflow: auto;margin-top:5px;margin-bottom:3px" id="comment_box'.$data["post_code"].'"></div>
						<div id="comment_form">
							<form class="d-flex" id="commment_post_form" data-id="'.$data["post_code"].'" onsubmit="return false">
								<button class="back_post_comment btn btn-primary p-1 ml-1" data-id="'.$data["post_code"].'" id="back_post_comment'.$data["post_code"].'" style="font-size: 9px;display:none" type="button">Post comment</button>
								<input type="text" id="cmnt_area'.$data["post_code"].'" class="form-control" placeholder="Post a comment.." >
								<input type="hidden" id="cmnt_id'.$data["post_code"].'" value="0" >
								<button class="btn btn-primary p-1 ml-1" type="submit">Post</button>
							</form>
						</div>
					</div>
					<button  class="post_footer_btn col-lg-3 col-md-3 col-sm-3 ml-1">
						<i class="bi bi-share"></i>
						<span id="icon_name_for_responsive">Share</span>
					</button>
				</div>
			</div>
			';
		}else{
			echo '
			<div class="post_box mt-1"  style="border: 1px solid #eaeaea;border-radius: 5px;">
			<a href="single_post.php?pst_code='.$data["post_code"].'">
				<div class="post_header d-flex justify-content-space-between" style="border-bottom: 1px solid #ccc;padding: 5px;">
					<div id="timeline_header_left" style="padding:0" class="col-lg-11 col-md-11 col-sm-11 d-flex align-items-center">
						<div class="d-flex">
							<img src="images/'.$user_image.'" width="40px" height="40px" class="rounded-circle">
							<div>
							<a >
							<input type="text" id="for_preview_user" style="height: 0px;width: 0px;position: absolute;top: -10000px;">
									<strong id="" style="margin-left: 5px;cursor:pointer;">'.$name.'</strong>
							</a>
							<p style="margin-bottom: -7px;margin-top: -7px;margin-left: 5px;font-size: 11px;color: #040404a3;">
								'.facebook_time_ago($data["post_time"]).'
							</p>
							</div>
							<div>
								<p style="font-size: 12px;margin-left: 4px;margin-top: 3px;">'.$sts_for_group_or_profile.'</p>
							</div>
						</div>
					</div>

					<div id="timeline_header_right" class="dropdown col-lg-1 col-md-1 col-sm-1">
						<button type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
						<i class="bi bi-three-dots-vertical"></i>
						</button>
						<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
						<li><a class="dropdown-item" href="#">Edit</a></li>
						<li><a class="dropdown-item" href="#">Delete</a></li>
						</ul>
					</div>
				</div>
			</a>
				

				<p class="post_texts m-2">
				'.$data["post_content"].'
				</p>
				<img src="images/'.$data["post_image"].'" class="img-thumbnail">
				<div class="reactor_user_show_box" id="reactor_user_show_box'.$data["post_code"].'">
					<div class="d-flex" style="justify-content:space-between;border-bottom:1px solid #ccc">
						<h4>Reactors</h4>
						<button class="btn btn-secondary" id="reactor_close_btn'.$data["post_code"].'" style="padding:4px"><i class="bi bi-x"></i></button>
					</div>
					<div id="reacts_count_user_box">
						<ul class="nav nav-tabs" style="margin-top: -9px;display: grid;grid: 29px/auto auto auto auto auto auto auto;">
							<li class="for_reactor nav-item  m-1">
								<button class=" nav-link p-1 m-1 active" data-toggle="pill" href="#all_react'.$data["post_code"].'">All <span style="margin-left:2px" id="all_react_count'.$data["post_code"].'"></span></button>
							</li>
							<li class="for_reactor nav-item  m-1">
								<button class=" nav-link p-1 m-1" data-toggle="pill" id="likedbtn'.$data["post_code"].'" href="#liked'.$data["post_code"].'"><img width="12px" height="12px" class="" src="emojis/like_color.png"><span style="margin-left:2px" id="like_count'.$data["post_code"].'"></span></button>
							</li>
							<li class="for_reactor nav-item m-1">
								<button id="lovebtn'.$data["post_code"].'" class=" nav-link p-1 m-1" data-toggle="tab" href="#love'.$data["post_code"].'"><img width="12px" height="12px" class="" src="emojis/heart.png"><span style="margin-left:2px" id="love_count'.$data["post_code"].'"></span></button>
							</li>
							<li class="for_reactor nav-item m-1">
								<button id="hahabtn'.$data["post_code"].'" class="  nav-link p-1 m-1" data-toggle="tab" href="#haha'.$data["post_code"].'"><img width="12px" height="12px" class="" src="emojis/laughing.png"><span style="margin-left:2px" id="haha_count'.$data["post_code"].'"></span></button>
							</li>
							<li class="for_reactor nav-item m-1">
								<button id="wowbtn'.$data["post_code"].'" class="  nav-link p-1 m-1" data-toggle="tab" href="#wow'.$data["post_code"].'"><img width="12px" height="12px" class="" src="emojis/wow.png"><span style="margin-left:2px" id="wow_count'.$data["post_code"].'"></span></button>
							</li>
							<li class="for_reactor nav-item m-1">
								<button id="sadbtn'.$data["post_code"].'" class=" nav-link p-1 m-1" data-toggle="tab" href="#sad'.$data["post_code"].'"><img width="12px" height="12px" class="" src="emojis/sad.png"><span style="margin-left:2px" id="sad_count'.$data["post_code"].'"></span></button>
							</li>
							<li class="for_reactor nav-item m-1">
								<button id="angrybtn'.$data["post_code"].'" class=" nav-link p-1 m-1" data-toggle="tab" href="#angry'.$data["post_code"].'"><img width="12px" height="12px" class="" src="emojis/angry.png"><span style="margin-left:2px" id="angry_count'.$data["post_code"].'"></span></button>
							</li>
						</ul>
					</div>
					<div id="reactor_user_show_box'.$data["post_code"].'" >
						<div class="tab-content">
							<div id="all_react'.$data["post_code"].'" class="active tab-pane">
								<img src="images/spinner.gif" id="spinner_for_load_react_user'.$data["post_code"].'"  class="mb-2 post_show_spinner"  style="display:none" alt="">
							</div>
							<div id="liked'.$data["post_code"].'" class="tab-pane"></div>
							<div id="love'.$data["post_code"].'" class="tab-pane fade"></div>
							<div id="haha'.$data["post_code"].'" class="tab-pane fade"></div>
							<div id="wow'.$data["post_code"].'" class="tab-pane fade"></div>
							<div id="sad'.$data["post_code"].'" class="tab-pane fade"></div>
							<div id="angry'.$data["post_code"].'" class="tab-pane fade"></div>
						</div>
					</div>
				</div>
				<div id="like_count_and_other_container" style="display:flex;justify-content:space-between">
					<span id="reactors_showBtn'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="reactors_showBtn">'.$count_react.' user Reacted</span>
					<span >Comments '.$count_comment.'</span>
					<span ></span>
					<span ></span>
				</div>
				<div id="icons_container'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icons_container p-2">
					<img id="like_icon'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icon_all like_icon" src="emojis/like_color.png">
					<img id="heart_icon'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icon_all heart_icon" src="emojis/heart.png">
					<img id="laughing_icon'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icon_all laughing_icon" src="emojis/laughing.png">
					<img id="wow_icon'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icon_all wow_icon" src="emojis/wow.png">
					<img id="sad_icon'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icon_all sad_icon" src="emojis/sad.png">
					<img id="angry_icon'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icon_all angry_icon" src="emojis/angry.png">
				</div>
				<div class="footer_content p-1 d-flex" style="justify-content:space-between">
					'.$like_btn.'
					<button id="comnt_btn" data-id="'.$data["post_code"].'" class="post_footer_btn comnt_btn col-lg-3 col-md-3 col-sm-3 ml-1">
						<i class="bi bi-chat-right-dots"></i>
						<span id="icon_name_for_responsive" class="comment_responsive">Comment</span>
					</button>
					<div id="comment_section'.$data["post_code"].'" class="comment_section">
						<div class="d-flex" style="justify-content:space-between;border-bottom:1px solid #ccc">
							<h5>Comments<h5>
							<span class="btn btn-secondary" style="padding:3px" data-id="'.$data["post_code"].'" id="comment_close_btn"><i class="bi bi-x"></i></span>
						</div>
						<div style="min-height: 70px;max-height: 200px;overflow: auto;margin-top:5px;margin-bottom:3px" id="comment_box'.$data["post_code"].'"></div>
						<div id="comment_form">
							<form class="d-flex" id="commment_post_form" data-id="'.$data["post_code"].'" onsubmit="return false">
								<button class="back_post_comment btn btn-primary p-1 ml-1" data-id="'.$data["post_code"].'" id="back_post_comment'.$data["post_code"].'" style="font-size: 9px;display:none" type="button">Post comment</button>
								<input type="text" id="cmnt_area'.$data["post_code"].'" class="form-control" placeholder="Post a comment.." >
								<input type="hidden" id="cmnt_id'.$data["post_code"].'" value="0" >
								<button class="btn btn-primary p-1 ml-1" type="submit">Post</button>
							</form>
						</div>
					</div>
					<button class="post_footer_btn col-lg-3 col-md-3 col-sm-3 ml-1">
						<i class="bi bi-share"></i>
						<span id="icon_name_for_responsive">Share</span>
					</button>
				</div>
			</div>
			';
		}

	}
}

//This is for user post
if (isset($_REQUEST["this_is_for_any_user_posts"])) {
	if ($_REQUEST["this_is_for_any_user_posts"]=="This is for any user posts") {
		$profile_user_id =$_REQUEST["id"];

		$query_for_group_post=mysqli_query($conn,"SELECT * FROM posts WHERE (posts.post_user_id='$profile_user_id' AND posts.post_owner='0') OR (posts.post_owner='$profile_user_id') ORDER BY post_id DESC; ");
		if ($query_for_group_post) {
			if (mysqli_num_rows($query_for_group_post)>0) {
				while ($group_info=mysqli_fetch_assoc($query_for_group_post)) {
					$post_user_status= $group_info["post_user_status"];
					$sts_for_group_or_profile="posted a status...";
					if ($post_user_status=="group cover upload") {
						$sts_for_group_or_profile="updated group cover photo.";
					}else{
						$sts_for_group_or_profile="posted a status...";
					}
					$post_user_id= $group_info['post_user_id'];
					$user_name=get_user($conn,$post_user_id);
					$usr_id_for_group_posts=$group_info['post_user_id'];
					$query_for_group_posts_user=mysqli_query($conn,"SELECT * FROM users WHERE unique_id='$usr_id_for_group_posts'");
					if ($query_for_group_posts_user) {
						while ($data=mysqli_fetch_assoc($query_for_group_posts_user)) {
							$count_react=count_reacts($conn,$group_info['post_code']);
							$count_comment=get_comment_count($conn,$group_info['post_code']);
							$name=get_user($conn,$group_info["post_user_id"]);
							$like_btn="";
							$like_btn_status= if_like_or_not($conn,$group_info['post_code'],$current_user);
							if ($like_btn_status=="Like") {
								$like_btn='
									<button data-id="'.$group_info["post_code"].'" id="like_btn" class="post_footer_btn ml-1 col-lg-3 col-md-3 col-sm-3">
										<img class="icon_all like_icon" src="emojis/like_color.png">
										<span id="icon_name_for_responsive">Liked</span>
									</button>';
							}elseif ($like_btn_status=="Love") {
								$like_btn='
									<button data-id="'.$group_info["post_code"].'" id="like_btn" class="post_footer_btn ml-1 col-lg-3 col-md-3 col-sm-3">
										<img class="icon_all heart_icon" src="emojis/heart.png">
										<span id="icon_name_for_responsive">Love</span>
									</button>';
							}elseif ($like_btn_status=="Haha") {
								$like_btn='
									<button data-id="'.$group_info["post_code"].'" id="like_btn" class="post_footer_btn ml-1 col-lg-3 col-md-3 col-sm-3">
										<img class="icon_all laughing_icon" src="emojis/laughing.png">
										<span id="icon_name_for_responsive">Haha</span>
									</button>';
							}elseif ($like_btn_status=="Wow") {
								$like_btn='
								<button data-id="'.$group_info["post_code"].'" id="like_btn" class="post_footer_btn ml-1 col-lg-3 col-md-3 col-sm-3">
									<img id="wow_icon'.$group_info["post_code"].'" data-id="'.$group_info["post_code"].'" class="icon_all wow_icon" src="emojis/wow.png">
									<span id="icon_name_for_responsive">Wow</span>
								</button>';
							}elseif ($like_btn_status=="Sad") {
								$like_btn='
								<button data-id="'.$group_info["post_code"].'" id="like_btn" class="post_footer_btn ml-1 col-lg-3 col-md-3 col-sm-3">
									<img id="sad_icon'.$group_info["post_code"].'" data-id="'.$group_info["post_code"].'" class="icon_all sad_icon" src="emojis/sad.png">
									<span id="icon_name_for_responsive">Sad</span>
								</button>';
							}elseif ($like_btn_status=="Angry") {
								$like_btn='
								<button data-id="'.$group_info["post_code"].'" id="like_btn" class="post_footer_btn ml-1 col-lg-3 col-md-3 col-sm-3">
									<img id="angry_icon'.$group_info["post_code"].'" data-id="'.$group_info["post_code"].'" class="icon_all angry_icon" src="emojis/angry.png">
									<span id="icon_name_for_responsive">Angry</span>
								</button>';
							}else{
								$like_btn='<button data-id="'.$group_info["post_code"].'" id="like_btn" class="post_footer_btn ml-1 col-lg-3 col-md-3 col-sm-3">
												<i class="bi bi-hand-thumbs-up"></i>
												<span id="icon_name_for_responsive">Like</span>
											</button>';
							}
							if (!empty($group_info["post_image"]=="")) {
								$output='
								<div class="posts_container mt-1" id="post_box'.$data["unique_id"].'">
									<div class="post_header d-flex justify-content-space-between" style="border-bottom: 1px solid #ccc;padding: 5px;">
										<div id="timeline_header_left" style="padding:0" class="col-lg-11 col-md-11 col-sm-11 d-flex align-items-center">
											<div class="d-flex">
												<img src="images/'.$data["profile"].'" width="40px" height="40px" class="rounded-circle">
												<div>
													<strong style="margin-left: 5px;cursor:pointer;" data-id="'.$data["unique_id"].'"><a href="user_profile.php" id="preview_usr_profile">'.$data["name"].'</a></strong>
													<p style="margin-bottom: -7px;margin-top: -7px;margin-left: 5px;font-size: 11px;color: #040404a3;">'.facebook_time_ago($group_info["post_time"]).'</p>
												</div>
												<div>
													<p style="font-size: 12px;margin-left: 4px;margin-top: 3px;">'.$sts_for_group_or_profile.'</p>
												</div>
											</div>
										</div>

										<div id="timeline_header_right" class="dropdown col-lg-1 col-md-1 col-sm-1">
										<button type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
											<i class="bi bi-three-dots-vertical"></i>
										</button>
										<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
											<li><a class="dropdown-item" href="#">Edit</a></li>
											<li><a class="dropdown-item" href="#">Delete</a></li>
										</ul>
										</div>
									</div>
									<p class="post_texts m-2" style="min-height:50px">
									'.$group_info["post_content"].'
									</p>
									<div class="reactor_user_show_box" id="reactor_user_show_box'.$group_info["post_code"].'">
										<div class="d-flex" style="justify-content:space-between;border-bottom:1px solid #ccc">
											<h4>Reactors</h4>
											<button class="btn btn-secondary" id="reactor_close_btn'.$group_info["post_code"].'" style="padding:2px"><i class="bi bi-x"></i></button>
										</div>
										<div id="reacts_count_user_box">
											<ul class="nav nav-tabs" style="margin-top: -9px;display: grid;grid: 29px/auto auto auto auto auto auto auto;">
												<li class="for_reactor nav-item  m-1">
												<button class=" nav-link p-1 m-1 active" data-toggle="pill" href="#all_react'.$group_info["post_code"].'">All <span id="all_react_count'.$group_info["post_code"].' style="margin-left:2px""></span></button>
												</li>
												<li class="for_reactor nav-item  m-1">
												<button class=" nav-link p-1 m-1" data-toggle="pill" id="likedbtn'.$group_info["post_code"].'" href="#liked'.$group_info["post_code"].'"><img width="12px" height="12px" class="" src="emojis/like_color.png"><span style="margin-left:2px" id="like_count'.$group_info["post_code"].'"></span></button>
												</li>
												<li class="for_reactor nav-item m-1">
												<button id="lovebtn'.$group_info["post_code"].'" class=" nav-link p-1 m-1" data-toggle="tab" href="#love'.$group_info["post_code"].'"><img width="12px" height="12px" class="" src="emojis/heart.png"><span style="margin-left:2px" id="love_count'.$group_info["post_code"].'"></span></button>
												</li>
												<li class="for_reactor nav-item m-1">
												<button id="hahabtn'.$group_info["post_code"].'" class="  nav-link p-1 m-1" data-toggle="tab" href="#haha'.$group_info["post_code"].'"><img width="12px" height="12px" class="" src="emojis/laughing.png"><span style="margin-left:2px" id="haha_count'.$group_info["post_code"].'"></span></button>
												</li>
												<li class="for_reactor nav-item m-1">
												<button id="wowbtn'.$group_info["post_code"].'" class="  nav-link p-1 m-1" data-toggle="tab" href="#wow'.$group_info["post_code"].'"><img width="12px" height="12px" class="" src="emojis/wow.png"><span style="margin-left:2px" id="wow_count'.$group_info["post_code"].'"></span></button>
												</li>
												<li class="for_reactor nav-item m-1">
												<button id="sadbtn'.$group_info["post_code"].'" class=" nav-link p-1 m-1" data-toggle="tab" href="#sad'.$group_info["post_code"].'"><img width="12px" height="12px" class="" src="emojis/sad.png"><span style="margin-left:2px" id="sad_count'.$group_info["post_code"].'"></span></button>
												</li>
												<li class="for_reactor nav-item m-1">
												<button id="angrybtn'.$group_info["post_code"].'" class=" nav-link p-1 m-1" data-toggle="tab" href="#angry'.$group_info["post_code"].'"><img width="12px" height="12px" class="" src="emojis/angry.png"><span style="margin-left:2px" id="angry_count'.$group_info["post_code"].'"></span></button>
												</li>
											</ul>
										</div>
										<div id="reactor_user_show_box'.$group_info["post_code"].'" >
											<div class="tab-content">
												<div id="all_react'.$group_info["post_code"].'" class="active tab-pane">
													<img src="images/spinner.gif" id="spinner_for_load_react_user'.$group_info["post_code"].'"  class="mb-2 post_show_spinner"  style="display:none" alt="">
												</div>
												<div id="liked'.$group_info["post_code"].'" class="tab-pane"></div>
												<div id="love'.$group_info["post_code"].'" class="tab-pane fade"></div>
												<div id="haha'.$group_info["post_code"].'" class="tab-pane fade"></div>
												<div id="wow'.$group_info["post_code"].'" class="tab-pane fade"></div>
												<div id="sad'.$group_info["post_code"].'" class="tab-pane fade"></div>
												<div id="angry'.$group_info["post_code"].'" class="tab-pane fade"></div>
											</div>
										</div>
									</div>
									<div id="like_count_and_other_container" class="d-flex" style="justify-content:space-between">
										<span id="reactors_showBtn'.$group_info["post_code"].'" data-id="'.$group_info["post_code"].'" class="reactors_showBtn">'.$count_react.' user Reacted</span>
										<span >Comments '.$count_comment.'</span>
										<span ></span>
										<span ></span>
									</div>
									<div id="icons_container'.$group_info["post_code"].'" data-id="'.$group_info["post_code"].'" class="icons_container p-2">
										<img id="like_icon'.$group_info["post_code"].'" data-group_id="'.$profile_user_id.'" data-id="'.$group_info["post_code"].'" class="icon_all like_icon" src="emojis/like_color.png">
										<img id="heart_icon'.$group_info["post_code"].'" data-group_id="'.$profile_user_id.'" data-id="'.$group_info["post_code"].'" class="icon_all heart_icon" src="emojis/heart.png">
										<img id="laughing_icon'.$group_info["post_code"].'" data-group_id="'.$profile_user_id.'" data-id="'.$group_info["post_code"].'" class="icon_all laughing_icon" src="emojis/laughing.png">
										<img id="wow_icon'.$group_info["post_code"].'" data-group_id="'.$profile_user_id.'" data-id="'.$group_info["post_code"].'" class="icon_all wow_icon" src="emojis/wow.png">
										<img id="sad_icon'.$group_info["post_code"].'" data-group_id="'.$profile_user_id.'" data-id="'.$group_info["post_code"].'" class="icon_all sad_icon" src="emojis/sad.png">
										<img id="angry_icon'.$group_info["post_code"].'" data-group_id="'.$profile_user_id.'" data-id="'.$group_info["post_code"].'" class="icon_all angry_icon" src="emojis/angry.png">
									</div>
									<div class="footer_content p-1 d-flex" style="justify-content:space-between">
										'.$like_btn.'
										<button id="comnt_btn" data-id="'.$group_info["post_code"].'" class="post_footer_btn comnt_btn col-lg-3 col-md-3 col-sm-3 ml-1">
											<i class="bi bi-chat-right-dots"></i>
											<span id="icon_name_for_responsive" style="font-size:12px" class="comment_responsive">Comment</span>
										</button>
										<div id="comment_section'.$group_info["post_code"].'" class="comment_section">
											<div class="d-flex" style="justify-content:space-between;border-bottom:1px solid #ccc">
												<h5>Comments<h5>
												<span class="btn btn-secondary" style="padding:3px" data-id="'.$group_info["post_code"].'" id="comment_close_btn"><i class="bi bi-x"></i></span>
											</div>
											<div style="min-height: 70px;max-height: 200px;overflow: auto;margin-top:5px;margin-bottom:3px" id="comment_box'.$group_info["post_code"].'"></div>
											<div id="comment_form">
												<form class="d-flex" id="commment_post_form" data-id="'.$group_info["post_code"].'" onsubmit="return false">
													<button class="back_post_comment btn btn-primary p-1 ml-1" data-id="'.$group_info["post_code"].'" id="back_post_comment'.$group_info["post_code"].'" style="font-size: 9px;display:none" type="button">Post comment</button>
													<input type="text" id="cmnt_area'.$group_info["post_code"].'" class="form-control" placeholder="Post a comment.." >
													<input type="hidden" id="cmnt_id'.$group_info["post_code"].'" value="0" >
													<button class="btn btn-primary p-1 ml-1" type="submit">Post</button>
												</form>
											</div>
										</div>
										<button  class="post_footer_btn col-lg-3 col-md-3 col-sm-3 ml-1">
											<i class="bi bi-share"></i>
											<span id="icon_name_for_responsive">Share</span>
										</button>
								</div>
								</div>
								';
							}else{
							$output ='
									<div class="post_box mt-1" id="post_box'.$group_info["post_code"].'" style="border: 1px solid #eaeaea;border-radius: 5px;">
										<div class="post_header d-flex justify-content-space-between" style="border-bottom: 1px solid #ccc;padding: 5px;">
											<div id="timeline_header_left" style="padding:0" class="col-lg-11 col-md-11 col-sm-11 d-flex align-items-center">
												<div class="d-flex">
													<img src="images/'.$data["profile"].'" width="40px" height="40px" class="rounded-circle">
													<div>
														<strong id="full_view_btn" style="margin-left: 5px;cursor:pointer;" data-id="'.$data["unique_id"].'"><a href="user_profile.php" id="preview_usr_profile">'.$data["name"].'</a></strong>
														<p style="margin-bottom: -7px;margin-top: -7px;margin-left: 5px;font-size: 11px;color: #040404a3;">'.facebook_time_ago($group_info["post_time"]).'</p>
													</div>
													<div>
														<p style="font-size: 12px;margin-left: 4px;margin-top: 3px;">'.$sts_for_group_or_profile.'</p>
													</div>
												</div>
											</div>

											<div id="timeline_header_right" class="dropdown col-lg-1 col-md-1 col-sm-1">
												<button type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
													<i class="bi bi-three-dots-vertical"></i>
												</button>
												<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
													<li><a class="dropdown-item" href="#">Edit</a></li>
													<li><a class="dropdown-item" href="#">Delete</a></li>
												</ul>
											</div>
										</div>
										<p class="post_texts m-2">
										'.$group_info["post_content"].'
										</p>
										<img src="images/'.$group_info["post_image"].'" style="width:100%;height:100%" class="img-thumbnail">
										<div class="reactor_user_show_box" id="reactor_user_show_box'.$group_info["post_code"].'">
											<div class="d-flex" style="justify-content:space-between;border-bottom:1px solid #ccc">
												<h4>Reactors</h4>
												<button class="btn btn-secondary" id="reactor_close_btn'.$group_info["post_code"].'" style="padding:2px"><i class="bi bi-x"></i></button>
											</div>
											<div id="reacts_count_user_box">
												<ul class="nav nav-tabs" style="margin-top: -9px;display: grid;grid: 29px/auto auto auto auto auto auto auto;">
													<li class="for_reactor nav-item  m-1">
													<button class=" nav-link p-1 m-1 active" data-toggle="pill" href="#all_react'.$group_info["post_code"].'">All <span style="margin-left:2px" id="all_react_count'.$group_info["post_code"].'"></span></button>
													</li>
													<li class="for_reactor nav-item  m-1">
													<button class=" nav-link p-1 m-1" data-toggle="pill" id="likedbtn'.$group_info["post_code"].'" href="#liked'.$group_info["post_code"].'"><img width="12px" height="12px" class="" src="emojis/like_color.png"><span style="margin-left:2px" id="like_count'.$group_info["post_code"].'"></span></button>
													</li>
													<li class="for_reactor nav-item m-1">
													<button id="lovebtn'.$group_info["post_code"].'" class=" nav-link p-1 m-1" data-toggle="tab" href="#love'.$group_info["post_code"].'"><img width="12px" height="12px" class="" src="emojis/heart.png"><span style="margin-left:2px" id="love_count'.$group_info["post_code"].'"></span></button>
													</li>
													<li class="for_reactor nav-item m-1">
													<button id="hahabtn'.$group_info["post_code"].'" class="  nav-link p-1 m-1" data-toggle="tab" href="#haha'.$group_info["post_code"].'"><img width="12px" height="12px" class="" src="emojis/laughing.png"><span style="margin-left:2px" id="haha_count'.$group_info["post_code"].'"></span></button>
													</li>
													<li class="for_reactor nav-item m-1">
													<button id="wowbtn'.$group_info["post_code"].'" class="  nav-link p-1 m-1" data-toggle="tab" href="#wow'.$group_info["post_code"].'"><img width="12px" height="12px" class="" src="emojis/wow.png"><span style="margin-left:2px" id="wow_count'.$group_info["post_code"].'"></span></button>
													</li>
													<li class="for_reactor nav-item m-1">
													<button id="sadbtn'.$group_info["post_code"].'" class=" nav-link p-1 m-1" data-toggle="tab" href="#sad'.$group_info["post_code"].'"><img width="12px" height="12px" class="" src="emojis/sad.png"><span style="margin-left:2px" id="sad_count'.$group_info["post_code"].'"></span></button>
													</li>
													<li class="for_reactor nav-item m-1">
													<button id="angrybtn'.$group_info["post_code"].'" class=" nav-link p-1 m-1" data-toggle="tab" href="#angry'.$group_info["post_code"].'"><img width="12px" height="12px" class="" src="emojis/angry.png"><span style="margin-left:2px" id="angry_count'.$group_info["post_code"].'"></span></button>
													</li>
												</ul>
											</div>
											<div id="reactor_user_show_box'.$group_info["post_code"].'" >
												<div class="tab-content">
													<div id="all_react'.$group_info["post_code"].'" class="active tab-pane">
														<img src="images/spinner.gif" id="spinner_for_load_react_user'.$group_info["post_code"].'"  class="mb-2 post_show_spinner"  style="display:none" alt="">
													</div>
													<div id="liked'.$group_info["post_code"].'" class="tab-pane"></div>
													<div id="love'.$group_info["post_code"].'" class="tab-pane fade"></div>
													<div id="haha'.$group_info["post_code"].'" class="tab-pane fade"></div>
													<div id="wow'.$group_info["post_code"].'" class="tab-pane fade"></div>
													<div id="sad'.$group_info["post_code"].'" class="tab-pane fade"></div>
													<div id="angry'.$group_info["post_code"].'" class="tab-pane fade"></div>
												</div>
											</div>
										</div>
										<div id="like_count_and_other_container" class="d-flex" style="justify-content:space-between">
											<span id="reactors_showBtn'.$group_info["post_code"].'" data-id="'.$group_info["post_code"].'" class="reactors_showBtn">'.$count_react.' user Reacted</span>
											<span >Comments '.$count_comment.'</span>
											<span ></span>
											<span ></span>
										</div>
										<div id="icons_container'.$group_info["post_code"].'" data-id="'.$group_info["post_code"].'" class="icons_container p-2">
											<img id="like_icon'.$group_info["post_code"].'" data-group_id="'.$profile_user_id.'" data-id="'.$group_info["post_code"].'" class="icon_all like_icon" src="emojis/like_color.png">
											<img id="heart_icon'.$group_info["post_code"].'" data-group_id="'.$profile_user_id.'" data-id="'.$group_info["post_code"].'" class="icon_all heart_icon" src="emojis/heart.png">
											<img id="laughing_icon'.$group_info["post_code"].'" data-group_id="'.$profile_user_id.'" data-id="'.$group_info["post_code"].'" class="icon_all laughing_icon" src="emojis/laughing.png">
											<img id="wow_icon'.$group_info["post_code"].'" data-group_id="'.$profile_user_id.'" data-id="'.$group_info["post_code"].'" class="icon_all wow_icon" src="emojis/wow.png">
											<img id="sad_icon'.$group_info["post_code"].'" data-group_id="'.$profile_user_id.'" data-id="'.$group_info["post_code"].'" class="icon_all sad_icon" src="emojis/sad.png">
											<img id="angry_icon'.$group_info["post_code"].'" data-group_id="'.$profile_user_id.'" data-id="'.$group_info["post_code"].'" class="icon_all angry_icon" src="emojis/angry.png">
										</div>
										<div class="footer_content p-1 d-flex" style="justify-content:space-between">
											'.$like_btn.'
											<button id="comnt_btn" data-id="'.$group_info["post_code"].'" class="post_footer_btn comnt_btn col-lg-3 col-md-3 col-sm-3 ml-1">
												<i class="bi bi-chat-right-dots"></i>
												<span id="icon_name_for_responsive" class="comment_responsive">Comment</span>
											</button>
											<div id="comment_section'.$group_info["post_code"].'" class="comment_section">
												<div class="d-flex" style="justify-content:space-between;border-bottom:1px solid #ccc">
													<h5>Comments<h5>
													<span class="btn btn-secondary" style="padding:3px" data-id="'.$group_info["post_code"].'" id="comment_close_btn"><i class="bi bi-x"></i></span>
												</div>
												<div style="min-height: 70px;max-height: 200px;overflow: auto;margin-top:5px;margin-bottom:3px" id="comment_box'.$group_info["post_code"].'"></div>
												<div id="comment_form">
													<form class="d-flex" id="commment_post_form" data-id="'.$group_info["post_code"].'" onsubmit="return false">
														<button class="back_post_comment btn btn-primary p-1 ml-1" data-id="'.$group_info["post_code"].'" id="back_post_comment'.$group_info["post_code"].'" style="font-size: 9px;display:none" type="button">Post comment</button>
														<input type="text" id="cmnt_area'.$group_info["post_code"].'" class="form-control" placeholder="Post a comment.." >
														<input type="hidden" id="cmnt_id'.$group_info["post_code"].'" value="0" >
														<button class="btn btn-primary p-1 ml-1" type="submit">Post</button>
													</form>
												</div>
											</div>
											<button class="post_footer_btn col-lg-3 col-md-3 col-sm-3 ml-1">
												<i class="bi bi-share"></i>
												<span id="icon_name_for_responsive">Share</span>
											</button>
										</div>
									</div>
								';
							}
						}
					}

					
				}
			}else{
				echo $output= '<div style="background: white;margin: 5px;box-shadow: 1px 1px 2px #ccc;border-radius: 8px;padding: 10px;>No post available</div>';
			}
			echo $output;
		}
	}
}

//thi_is_for_group_member_requests
if (isset($_REQUEST["thi_is_for_group_member_requests"])) {
	if ($_REQUEST["thi_is_for_group_member_requests"]=="this is for group member requests") {
		$id=$_REQUEST["id"];
		$query=mysqli_query($conn,"SELECT * FROM group_requests WHERE group_Id='$id' AND disabled='0' ");
		if (mysqli_num_rows($query)) {
			while ($group_data=mysqli_fetch_assoc($query)) {
				$usrid= $group_data["userId"];
				$query_for_show_usr_inf=mysqli_query($conn,"SELECT * FROM users WHERE unique_id='$usrid'");
				if ($query_for_show_usr_inf) {
					if (mysqli_num_rows($query_for_show_usr_inf) > 0) {
						while ($usr_inf=mysqli_fetch_assoc($query_for_show_usr_inf)) {
						echo '
								<div style="width:100%;padding: 8px;border: 1px solid #ccc;margin: 2px 2px 2px 2px;border-radius: 3px;" id="user_div" data-id="'.$usr_inf['unique_id'].'" >
									<img data-toggle="tooltip" data-placement="top" title="Click to view '.$usr_inf['unique_id'].',s profile details" src="images/'.$usr_inf['profile'].'" class="img-fluid rounded-circle" alt="Responsive image" style="width:40px;height:40px;object-fit:cover">
									<div style="line-height:1.1">
										<strong><a href="group.php" id="preview_usr_profile" data-id="'.$usr_inf['unique_id'].'">'.$usr_inf['name'].'</a></strong>
										<p>'.$usr_inf['address'].'</p>
									</div>
									<div style="margin-top: -13px;">
										<input type="text" id="join_approve_group_id" hidden data-id="'.$id.'">
										<button class="btn btn-success" id="join_approve_btn" data-id="'.$usr_inf['unique_id'].'" style="padding:4px;font-size:12px;display:inline;">Approve</button>
										<button class="btn btn-danger" id="join_reject_btn" data-id="'.$usr_inf['unique_id'].'"  style="padding:4px;font-size:12px;display:inline;">Reject</button>
									</div>									
								</div>
							';
						}
					}
				}
			}
		}else{
			echo "No pending request available now.";
		}
	}
}

//Delete post
if (isset($_REQUEST["this_is_for_delete_post"])) {
	if ($_REQUEST["this_is_for_delete_post"]=="this is for delete post") {
		$id=$_REQUEST["id"];
		$ob=new post();
		echo $ob->delete_post($conn,$id);
	}
}






   ?>
