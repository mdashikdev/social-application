
 <?php 
 	session_start();
	$current_user=$_SESSION['user_id'];
 	include("php_core/function_core.php");
 	include("php_core/db.php");
	if (isset($_REQUEST["id"])) {
		if ($id=$_REQUEST["id"]!==$current_user) {
			$id=$_REQUEST["id"];
			$query=mysqli_query($conn,"SELECT * FROM users WHERE unique_id='$id' ");
			if ($query) {
					$data=mysqli_fetch_assoc($query);
					$frds=get_user_frds($conn,$data['unique_id']);
					$status=get_request_status($conn,$current_user,$data['unique_id']);
					$get_follow_status=get_who_follow($conn,$current_user,$data['unique_id']);
					if ($get_follow_status=="Following") {
						$follow_btn='
							<button class="btn btn-outline-primary unfollow_btn" data-toggle="tooltip" title="If you unfollow this user so that you cant this user posts.!" style="width:100%;margin-top:2px" data-id="'.$data['unique_id'].'" id="unfollow_btn'.$data["unique_id"].'" >Unfollow <i class="bi bi-person-dash"></i></button>
						';
					}else{
						$follow_btn='
							<button class="btn btn-outline-primary follow_btn" style="width:100%;margin-top:2px" data-id="'.$data['unique_id'].'" id="follow_btn'.$data["unique_id"].'" >Follow <i class="bi bi-person-plus"></i></button>
						';
					}
					$button="";
					if ($status=="Pending") {
					$button='	<button class="dropdown btn btn-primary" style="" type="button" data-bs-toggle="dropdown" aria-expanded="false">
								  	Pending <i class="bi bi-clock"></i>
								</button>
								<ul class="dropdown-menu" style="padding:1px" id="notification_dropdown">
								  	'.$follow_btn.'
								</ul>';
		      			}elseif($status=="Confirm"){
		      				$button='
									  <button class="dropdown btn btn-primary" style="" type="button" data-bs-toggle="dropdown" aria-expanded="false">
									  	Friend <i class="bi bi-person-check"></i>
									  </button>
									  <ul class="dropdown-menu" style="padding:1px" id="notification_dropdown">
									  	'.$follow_btn.'
									  </ul>
		      				';



		      			}else{
		      				$button='
		      						<button class="dropdown btn btn-primary" style="" type="button" data-bs-toggle="dropdown" aria-expanded="false">
									  	Add Friend <i class="bi bi-person-plus"></i>
									  </button>
									  <ul class="dropdown-menu" style="padding:1px" id="notification_dropdown">
									  	<button style="width:100%" id="frd_rqst_btn'.$data["unique_id"].'"  class="btn btn-outline-primary frd_rqst_btn" data-id="'.$data["unique_id"].'" data-toggle="tooltip" data-placement="top" title="Click to send friend request">Add Friend <i class="bi bi-person-plus"></i></button>
									  	'.$follow_btn.'
									  </ul>
		      							
		      				';
		      			}
		      	}





			?>



<div>
<div class="profile_header_wrapper">
	<div class="any_usr_container">
		<div></div>
		<div class="any_usr_center">
			<div style="position:relative;">
				<img src="images/<?php echo $data['cover_photo']; ?>" id="cover_photo_img" class="img-fluid rounded" alt="Responsive image">
			</div>
			<div class="profile_header_container">
				<div style="display: grid;grid-template-columns: 1fr 1fr;">
					<div class="profile-image profile_img_div">
						<div style="position:relative;width: fit-content;">
							<img src="images/<?php echo $data['profile']; ?>" id="pro_img" class="img-fluid rounded-circle" alt="Responsive image">
						</div>
					</div>
					<div>
						<div id="profile_name_div">
							<strong class="profile_name">
								<?php echo $data['name']; ?>
							</strong>
							<p class="ml-2 mb-0" id="user_frd_count"></p>
						</div>
					</div>
				</div>
				<div class="user_btn_bo">
					<div>
						
						<?php 
							echo $button;
						?>
							<a style="color:#fff;" class="msg btn btn-primary" data-toggle="collapse" href="#msg">
								Message
							</a>
					</div>
				</div>
				<div class="header_bottom">
					<ul class="nav nav-pills" style="display:grid;grid-template-columns:1fr 1fr 1fr">
					    <li class="m-1">
					    	<button class="nav-link active" data-toggle="pill" href="#timeline" data-id="<?php echo $data["unique_id"]; ?>" id="grp_discussion_load" style="padding: 2px 12px 4px 12px;border-radius: 6px;">Timeline</button>
					    </li>
					    <li class="m-1">
					    	<button class="nav-link" id="user_frd_btn" data-id="<?php echo $data['unique_id']; ?>" data-toggle="pill" href="#user_frd" style="border-radius: 6px;padding: 2px 12px 4px 12px;position: relative;">Friends <span style="    position: absolute;padding: 4px;right: -6px;font-size: 9px;" class="badge badge-primary mb-0" id="user_frd_count_frd_list"></span></button>
					    </li>
					    <li class="m-1">
					    	<button class="nav-link" id="user_follower_btn" data-id="<?php echo $data['unique_id']; ?>" data-toggle="pill" href="#user_follower" style="border-radius: 6px;padding: 2px 12px 4px 12px;position: relative;">Follwer <span style="    position: absolute;padding: 4px;right: -6px;font-size: 9px;" class="badge badge-primary mb-0" id="user_follower_count_frd_list"></span></button>
					    </li>
					</ul>
				</div>
			</div>
		</div>
		<div></div>
	</div>
</div>
	<div class="profile_post_section">
		<div class="any_usr_left"></div>
		<div class="frd_timeline_container">
			<div class="tab-content">
				<div class="tab-pane active posts_sec " id="timeline">
					<div class="post_create">
						<div>
							<div class="post_box">
								<h5>Post to <?php echo $data['name']; ?>'s timeline</h5>
								<form action="" id="post_form" onsubmit="return false">
		                            <textarea id="" class="form-control" name="pst_cntnt" placeholder="Create a post..."></textarea>
		                            <input type="file" name="pst_img" class="form-control mt-1">
		                            <input type="text" id="owner" hidden name="owner" value="<?php echo $data['unique_id']; ?>" class="form-control mt-1">
		                            <img src="images/spinner.gif"  class="mb-2 post_spinner"  style="display:none" alt="">
		                            <div class="alert alert-success" style="display:none;margin-top:2%;" id="alert_for_post_create" role="alert"></div>
		                            <input type="submit"class="btn btn-primary mt-1 w-100 post_btn" value="Post">
		                        </form>
							</div>
						</div>
					</div>
					<div class="posts">
						<div class="">
				        	<div class="posts_container" id="load_page_btn" style="cursor:pointer;"><i class="bi bi-arrow-clockwise"></i>Load Page</div>
				        	<div class="posts_containers"></div>
				        </div>
					</div>
				</div>
				<div class="tab-pane fade" id="user_frd" style="background: white;margin: 5px;box-shadow: 1px 1px 2px #ccc;border-radius: 8px;padding: 10px;"></div>
				<div class="tab-pane fade" id="user_follower" style="background: white;margin: 5px;box-shadow: 1px 1px 2px #ccc;border-radius: 8px;padding: 10px;"></div>
			</div>
			<div class="info_sec">
				<h5>Info</h5>
			</div>
		</div>
	</div>
</div>










<?php	}else{
	$query=mysqli_query($conn,"SELECT * FROM users WHERE unique_id='$current_user' ");
	$data=mysqli_fetch_assoc($query);
	$button='<div class="btn btn-primary" data-toggle="collapse" href="#profile_update">Edit profile</div>';
	 ?>

<div>
<div class="profile_header_wrapper">
	<div class="any_usr_container">
		<div></div>
		<div class="any_usr_center">
			<div style="position:relative;">
				<img src="images/<?php echo $data['cover_photo']; ?>" id="cover_photo_img" class="img-fluid rounded" alt="Responsive image">
				<button type="button"  data-toggle="tooltip" data-placement="top" title="Click to change cover photo" class="btn btn-secondary col-3 cover_icon">
					<i class="bi bi-image"></i>
				</button>
			</div>
			<div class="cover_change_input_div shadow">
				<form onsubmit="return false" class="form-control" style="border:none;" id="cover_photo_form">
					<input type="file"  class="" hidden id="cover_photo_input" name="cover_photo">
					<button type="submit" class="btn btn-primary upload_btn_covr col-8" >
						<div id="spinner_covr" class="spinner-border text-secondary" style="display:none;width: 20px;height: 20px;" role="status">
						  <span class="sr-only">Loading...</span>
						</div>
						Upload Cover
					</button>
					<button type="button"  data-toggle="tooltip" data-placement="top" title="Click to cancel cover change" class="cover_hide_btn btn btn-danger">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
						  <path fill-rule="evenodd" d="M13.854 2.146a.5.5 0 0 1 0 .708l-11 11a.5.5 0 0 1-.708-.708l11-11a.5.5 0 0 1 .708 0Z"/>
						  <path fill-rule="evenodd" d="M2.146 2.146a.5.5 0 0 0 0 .708l11 11a.5.5 0 0 0 .708-.708l-11-11a.5.5 0 0 0-.708 0Z"/>
						</svg>
					</button>
				</form>
			</div>
			<div class="profile_header_container">
				<div style="display: grid;grid-template-columns: 1fr 1fr;">
					<div class="profile-image profile_img_div">
						<div style="position:relative;width: fit-content;">
							<img src="images/<?php echo $data['profile']; ?>" id="pro_img" class="img-fluid rounded-circle" alt="Responsive image">
							<button type="button" style="position: absolute;border-radius: 20px;bottom: 8%;right: -1%;cursor: pointer;"  data-toggle="tooltip" data-placement="top" title="Click to change profile photo" class="btn btn-secondary col-3 profile_icon">
								<i class="bi bi-image"></i>
							</button>
						</div>
					</div>
					<form onsubmit="return false" runat="server" class="form-control shadow" style="border:none;" id="profile_photo_form">
						<input type="file" accept="image/*" hidden id="profile_photo_input" name="profile_photo"  name="">
						<input type="text"  class="form-control" name="profile_caption" id="profile_caption" placeholder="Say something about your profile picture" name="cover_caption">
						<img src="#" class="profile_selected_img_box" style="width: 150px;height: 150px;border-radius: 50%;object-fit: cover;margin: auto;"/>
						<button type="submit" class="btn mt-2 btn-primary upload_btn_profile col-8" >
							<div id="spinner_pro" class="spinner-border text-secondary" style="display:none;width: 20px;height: 20px;" role="status">
							  <span class="sr-only">Loading...</span>
							</div>

							Upload Profile
						</button>
						<button type="button"  data-toggle="tooltip" data-placement="top" title="Click to cancel profile change" class="profile_hide_btn btn btn-danger">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
							  <path fill-rule="evenodd" d="M13.854 2.146a.5.5 0 0 1 0 .708l-11 11a.5.5 0 0 1-.708-.708l11-11a.5.5 0 0 1 .708 0Z"/>
							  <path fill-rule="evenodd" d="M2.146 2.146a.5.5 0 0 0 0 .708l11 11a.5.5 0 0 0 .708-.708l-11-11a.5.5 0 0 0-.708 0Z"/>
							</svg>
						</button>
					</form>
					<div>
						<div id="profile_name_div">
							<strong class="profile_name">
								<?php echo $data['name']; ?>
							</strong>
							<p class="ml-2 mb-0" id="user_frd_count"></p>
						</div>
					</div>
				</div>
				<div class="user_btn_bo">
					<div>
						
						<?php 
							echo $button;
						?>
					</div>
				</div>
				<div class="header_bottom">
					<ul class="nav nav-pills" style="display:grid;grid-template-columns:1fr 1fr 1fr">
					    <li class="m-1">
					    	<button class="nav-link active" data-toggle="pill" href="#timeline" data-id="<?php echo $data["unique_id"]; ?>" id="grp_discussion_load" style="padding: 2px 12px 4px 12px;border-radius: 6px;">Timeline</button>
					    </li>
					    <li class="m-1">
					    	<button class="nav-link" id="user_frd_btn" data-id="<?php echo $data['unique_id']; ?>" data-toggle="pill" href="#user_frd" style="border-radius: 6px;padding: 2px 12px 4px 12px;position: relative;">Friends <span style="    position: absolute;padding: 4px;right: -6px;font-size: 9px;" class="badge badge-primary mb-0" id="user_frd_count_frd_list"></span></button>
					    </li>
					    <li class="m-1">
					    	<button class="nav-link" id="user_follower_btn" data-id="<?php echo $data['unique_id']; ?>" data-toggle="pill" href="#user_follower" style="border-radius: 6px;padding: 2px 12px 4px 12px;position: relative;">Follwer <span style="    position: absolute;padding: 4px;right: -6px;font-size: 9px;" class="badge badge-primary mb-0" id="user_follower_count_frd_list"></span></button>
					    </li>
					</ul>
				</div>
				<div class="collapse rounded shadow p-2" id="profile_update">
					<h4>Update Profile..</h4>
						<form onsubmit="return false" id="profile_update_form">
						  <div class="form-row">
						    <div class="form-group col-md-6">
						      <label for="inputEmail4">Name</label>
						      <input type="text" name="nm" class="form-control" id="inputName4" value="<?php echo $data['name']; ?>" placeholder="Name">
						    </div>
						    <div class="form-group col-md-6">
						      <label for="inputPassword4">Email</label>
						      <input type="email" name="eml" class="form-control" value="" id="inputEmail4" placeholder="Email">
						    </div>
						  </div>
						  <div class="form-row">
						    <div class="form-group col-md-6">
						      <label for="inputEmail4">Password</label>
						      <input type="password" name="pwd" class="form-control" id="inputName4" placeholder="Password">
						    </div>
						    <div class="form-group col-md-6">
						      <label for="inputPassword4">Address</label>
						      <input type="text" name="addr" class="form-control" id="inputAddress4" placeholder="Address">
						    </div>
						  </div>
						  <button type="submit" id="updt_pro_btn" class="btn btn-primary">
						  	<div id="spinner_updt_pro" class="spinner-border text-success" style="display:none;width: 20px;height: 20px;font-size: 17px;" role="status">
								  <span class="sr-only">Loading...</span>
							</div>
						  	Update Profile
						  </button>
						</form>
					</div>
			</div>
		</div>
		<div></div>
	</div>
</div>
	<div class="profile_post_section">
		<div class="any_usr_left"></div>
		<div class="frd_timeline_container">
			<div class="tab-content">
				<div class="tab-pane active posts_sec " id="timeline">
					<div class="post_create">
						<div>
							<div class="post_box">
								<h5>Create a post</h5>
								<form action="" id="post_form" onsubmit="return false">
		                            <textarea id="" class="form-control" name="pst_cntnt" placeholder="Create a post..."></textarea>
		                            <input type="file" name="pst_img" class="form-control mt-1">
		                            <input type="text" id="owner" hidden name="owner" value="" class="form-control mt-1">
		                            <img src="images/spinner.gif"  class="mb-2 post_spinner"  style="display:none" alt="">
		                            <div class="alert alert-success" style="display:none;margin-top:2%;" id="alert_for_post_create" role="alert"></div>
		                            <input type="submit"class="btn btn-primary mt-1 w-100 post_btn" value="Post">
		                        </form>
							</div>
						</div>
					</div>
					<div class="posts">
						<div class="">
				        	<div class="posts_container" id="load_page_btn" style="cursor:pointer;"><i class="bi bi-arrow-clockwise"></i>Load Page</div>
				        	<div class="posts_containers"></div>
				        </div>
					</div>
				</div>
				<div class="tab-pane fade" id="user_frd" style="background: white;margin: 5px;box-shadow: 1px 1px 2px #ccc;border-radius: 8px;padding: 10px;"></div>
				<div class="tab-pane fade" id="user_follower" style="background: white;margin: 5px;box-shadow: 1px 1px 2px #ccc;border-radius: 8px;padding: 10px;"></div>
			</div>
			<div class="info_sec">
				<h5>Info</h5>
			</div>
		</div>
	</div>
</div>


<?php		}
		
	}
?>
