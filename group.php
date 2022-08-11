 <?php 
 	session_start();
	$current_user=$_SESSION['user_id'];
 	include("php_core/function_core.php");
 	include("php_core/db.php");
 	include("php_core/time.php");
	if (isset($_REQUEST["id"])) {
	$profile_user_id=$_REQUEST["id"];
	$member_count= group_member_count($conn,$profile_user_id);
	$member_or_not_query=mysqli_query($conn,"SELECT * FROM group_members WHERE member_user_id='$current_user' AND group_id='$profile_user_id' ");
	$member_or_not="";
	if (mysqli_num_rows($member_or_not_query) > 0) {
		$member_or_not_fetch=mysqli_fetch_assoc($member_or_not_query);
		$member_or_not= $member_or_not_fetch["role"];
	}else{
		$member_or_not="";
	}			
	$query=mysqli_query($conn,"SELECT * FROM users WHERE unique_id='$profile_user_id' ");
	if ($query) {
		$data=mysqli_fetch_assoc($query);
		$frds=get_user_frds($conn,$data['unique_id']);
		$group_request_status=get_user_group_request_status($conn,$profile_user_id,$current_user);
		$admin_or_not=check_admin($conn,$profile_user_id,$current_user);
		$check_already_request_or_not=check_already_request_or_not($conn,$profile_user_id,$current_user);
		$follow_btn="";
		$invite_btn="";
		$upload_cover_btn="";
		$admin_button="";
		$button="";
		$admin_update_info="";
		if ($member_or_not=="Member") {
			$follow_btn="";
			$invite_btn='<button class="group_invite_btn btn btn-primary" data-id="'.$data["unique_id"].'" id="group_invite_btn'.$data['unique_id'].'">Invite</button>';
		}elseif($member_or_not=="Moderator"){
			$invite_btn='<button class="group_invite_btn btn btn-primary" data-id="'.$data["unique_id"].'" id="group_invite_btn'.$data['unique_id'].'">Invite</button>';
			$upload_cover_btn='
				<button type="button" data-id="'.$profile_user_id.'"  data-toggle="tooltip" data-placement="top" title="Click to change cover photo" class="btn btn-secondary col-3 group_cover_icon cover_icon">
						<i class="bi bi-image"></i>
				</button>';
			$admin_button='
			<button id="group_admin_toggler_icon" style="margin: 10px;padding: 5px;" class="btn btn-secondary"><i class="bi bi-three-dots-vertical"></i></button>
			<button id="group_admin_toggler_close_icon" style="display: none;margin: 10px;padding: 5px;" class="btn btn-secondary"><i class="bi bi-x"></i></button>
			';
		}elseif($group_request_status==0){
			$follow_btn='
				<button class="btn btn-primary m-1" data-id="'.$data['unique_id'].'" >Request Sent</button>
			';
		}else{
			$invite_btn="";
			$follow_btn="";
		}
		if ($admin_or_not=="Admin") {
			$admin_button='
				<button id="group_admin_toggler_icon" style="margin: 10px;padding: 5px;" class="btn btn-secondary"><i class="bi bi-three-dots-vertical"></i></button>
				<button id="group_admin_toggler_close_icon" style="display: none;margin: 10px;padding: 5px;" class="btn btn-secondary"><i class="bi bi-x"></i></button>
			';
			$upload_cover_btn='
				<button type="button" data-id="'.$profile_user_id.'"  data-toggle="tooltip" data-placement="top" title="Click to change cover photo" class="btn btn-secondary col-3 group_cover_icon cover_icon">
						<i class="bi bi-image"></i>
				</button>';
			$invite_btn='<button class="group_invite_btn btn btn-primary" data-id="'.$data["unique_id"].'" id="group_invite_btn'.$data['unique_id'].'" >Invite</button>';
			$admin_update_info='
				<button data-toggle="collapse" href="#group_update_container"  id="" data-id="'.$data['unique_id'].'" style="background: white;margin-top: 3px;border-radius: 6px;padding: 2px;width: 100%;">Update Group</button>
					<div class="collapse" style="background: white;overflow: auto;margin: 5px;max-height: 80%;box-shadow: 1px 1px 2px #ccc;border-radius: 8px;padding: 10px;" id="group_update_container">
						<form onsubmit="return false" id="group_update_form">
					    	<div class="form-group">
					      		<label>Update group name</label>
					      		<input type="text" name="nm" class="form-control" value="'.$data['name'].'" placeholder="New name">
					      		<input type="hidden" value="'.$data['unique_id'].'" name="grp_id">
					    	</div>
					  	<button type="submit" id="updt_grp_btn" class="btn btn-primary">
						  	<div id="spinner_updt_group" class="spinner-border text-success" style="display:none;width: 20px;height: 20px;font-size: 17px;" role="status">
								  <span class="sr-only">Loading...</span>
							</div>
					  	Update
					  	</button>
					</form>
				</div>
			';
		}elseif($admin_or_not=="Member"){
			$invite_btn='<button class="group_invite_btn btn btn-primary" data-id="'.$data["unique_id"].'" id="group_invite_btn'.$data['unique_id'].'">Invite</button>';
		}else{
			if ($member_or_not=="Member") {
				$button="";
			}elseif($member_or_not=="Moderator"){
				$button="";
			}else{
				$button='
				<div class="">
						<button hidden class="btn btn-primary"></button>
						'.$check_already_request_or_not.'
				</div>
			';
			}
			
		}

?>

<div class="invite_section_wrapper">
	<div class="search d-flex">
		<input type="text" id="follower_search_for_invite" data-id="<?php echo $profile_user_id; ?>" placeholder="search your followers.." name="">
		<button id="invite_container_close_btn" class="btn btn-primary"><i class="bi bi-x"></i></button>
	</div>
	<div class="users_section">all user</div>
</div>

<div>
<div class="profile_header_wrapper">
	<div class="any_usr_container">

		<div style="position: relative;">
			<?php echo $admin_button; ?>
			<div class="admin_panel" style="  position: absolute;
											  transform: translateX(-300px);
											  opacity: 0;
											  transition: all 0.4s ease-in-out 0s;
											  border-radius: 6px;
											  width: 90%;
											  margin-top: 5%;
											  margin-left: 2%;
											  padding: 8px;
											  border: thin solid rgba(202, 202, 202, 0.7);
											  height: 400px;
											  z-index: 4;
											  background: white;">
				<button data-toggle="collapse" href="#group_member_requests_container"  id="member_reqst_btn" data-id="<?php echo $data['unique_id']; ?>" style="background: white;margin-top: 3px;border-radius: 6px;padding: 2px;width: 100%;">Member Requests</button>
				<div class="collapse" style="background: white;overflow: auto;margin: 5px;max-height: 80%;box-shadow: 1px 1px 2px #ccc;border-radius: 8px;padding: 10px;" id="group_member_requests_container"></div>

				<?php echo $admin_update_info; ?>

			
			</div>
		</div>
		<div class="any_usr_center">
			<div style="position:relative;">
				<img src="images/<?php echo $data['cover_photo']; ?>" style="object-fit: cover;width: 100%;max-height: 350px;" id="cover_photo_img" class="img-fluid rounded" alt="Responsive image">
				<?php echo $upload_cover_btn; ?>
			</div>

			<form onsubmit="return false" class="form-control" data-id="<?php echo $profile_user_id ?>" style="border:none;display: none;" id="group_cover_photo_form<?php echo $profile_user_id ?>">
				<input type="file"  class="" hidden id="group_cover_photo_input<?php echo $profile_user_id ?>" name="cover_photo">
				<input type="hidden" class="" value="<?php echo $profile_user_id ?>" name="cover_photo_change_id">
				<input type="text" hidden name="owner" value="<?php echo $profile_user_id ?>" class="form-control mt-1">
				<button type="submit" style="width:fit-content" id="upload_btn_covr<?php echo $profile_user_id ?>" class="btn btn-primary upload_btn_covr col-8" >
					<div id="spinner_covr" class="spinner-border text-secondary" style="display:none;width: 20px;height: 20px;" role="status">
					  <span class="sr-only">Loading...</span>
					</div>
					Upload <?php echo $data['name'] ?> Group Cover Image..
				</button>
				<button type="button"  data-toggle="tooltip" data-placement="top" title="Click to cancel cover change" class="cover_hide_btn btn btn-danger" id="group_cover_hide_button'.$profile_user_id.'">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
					  <path fill-rule="evenodd" d="M13.854 2.146a.5.5 0 0 1 0 .708l-11 11a.5.5 0 0 1-.708-.708l11-11a.5.5 0 0 1 .708 0Z"/>
					  <path fill-rule="evenodd" d="M2.146 2.146a.5.5 0 0 0 0 .708l11 11a.5.5 0 0 0 .708-.708l-11-11a.5.5 0 0 0-.708 0Z"/>
					</svg>
				</button>
			</form>

			<div class="profile_header_container">
				<div style="display: grid;grid-template-columns: 1fr 1fr;">
					<div>
						<div id="profile_name_div">
							<strong class="profile_name" style="line-height: 1;">
								<?php echo $data['name']; ?>
							</strong>
							<p style="line-height: 1">Public Group. <span><?php echo $member_count; ?> Members</span></p>
						</div>
					</div>
				</div>
				<div class="user_btn_bo">
					<?php echo $invite_btn;
						  echo $button;
					 ?>
				</div>
			</div>
		<div class="header_bottom">
			<ul class="nav nav-pills" style="display:grid;grid-template-columns:1fr 1fr 1fr">
			    <li class="m-1">
			    	<button class="nav-link active" data-toggle="pill" href="#about" style="padding: 2px 12px 4px 12px;border-radius: 6px;">About</button>
			    </li>
			    <li class="m-1">
			    	<button class="nav-link" data-toggle="pill" href="#discussion" data-id="<?php echo $data["unique_id"]; ?>" id="grp_discussion_load" style="padding: 2px 12px 4px 12px;border-radius: 6px;">Discussion</button>
			    </li>
			    <li class="m-1">
			    	<button class="nav-link" id="members" data-id="<?php echo $data['unique_id']; ?>" data-toggle="pill" href="#people" style="border-radius: 6px;padding: 2px 12px 4px 12px;">People</button>
			    </li>
			</ul>
		</div>
		</div>
		<div></div>
	</div>
</div>
	<div class="profile_post_section">
		<div class="any_usr_left"></div>
		<div class="frd_timeline_container">
			<div class="tab-content">
				<div class="tab-pane active" style="background: white;margin: 5px;box-shadow: 1px 1px 2px #ccc;border-radius: 8px;padding: 10px;" id="about">About</div>
				<div class="posts_sec tab-pane fade " id="discussion">
					<div class="post_create">
						<div class="">
							<div class="post_box">
								<h3>Create a post</h3>
								<form action="" id="post_form" onsubmit="return false">
									<textarea id="" class="form-control" name="pst_cntnt" placeholder="Create a post..."></textarea>
									<input type="text" id="owner" hidden name="owner" value="<?php echo $profile_user_id; ?>" class="form-control mt-1">
									<input type="file" name="pst_img" class="form-control mt-1">
									<img src="images/spinner.gif"  class="mb-2 post_spinner"  style="display:none" alt="">
									<div class="alert alert-success" style="display:none;margin-top:2%;" id="alert_for_post_create" role="alert"></div>
									<input type="submit"class="btn btn-primary mt-1 w-100 post_btn" value="Post">
								</form>
							</div>
						</div>
					</div>
					<div class="posts">
						<div class="">
				        	<div class="posts_container" id="group_post_load_btn" style="cursor:pointer;"><i class="bi bi-arrow-clockwise"></i>Load Page</div>
				        	<div class="posts_containers" id="group_all_posts_container<?php echo $profile_user_id ?>"></div>
				        </div>
					</div>
				</div>
				<div class="tab-pane fade" style="background: white;margin: 5px;box-shadow: 1px 1px 2px #ccc;border-radius: 8px;padding: 10px;" id="people"></div>
			</div>
			<div class="info_sec">
				<h5>Info</h5>
			</div>
		</div>
	</div>
</div>


<?php
	}
}





?>