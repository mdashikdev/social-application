<?php
include("header.php");
include("php_core/function_core.php");

$query=mysqli_query($conn,"SELECT * FROM users WHERE unique_id='$current_user' ");
$inf=mysqli_fetch_assoc($query);
$eml=md5(sha1($inf['email']));
?>




<div id="header_for_desktop" class="header_main_wrapper w-90 rounded">
		<div class="header_left">
			<div class="input-group header_search_for_desktop">
				<input type="text" id="search_input" autocomplete="off" class="form-control rounded" placeholder="Search" aria-label="Search"aria-describedby="search-addon" />
				<button type="button" id="src_usr_btn" class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
				<button type="button" id="src_close_btn" style="display:none" class="btn btn-outline-primary"><i class="bi bi-x"></i></button>
			</div>
		</div>
		<div class="header_center" style="justify-content:center;position: relative;width: 70%;">
			<nav class="navbar p-0 navbar-expand-sm navbar-light " >
			    <div style="width: 100%" class="group_left_content" class="navbar p-0">
					  <!-- Nav tabs --> 
					  <ul class="nav nav-pills justify-content-center" style="width:100%;">
					    <li class="nav-item">
					      <a class="nav-link active home" data-toggle="pill" href="#home"><i class="bi bi-house mr-2"></i></a>
					    </li>
					    <li class="nav-item">
					      <a class="nav-link frd" data-toggle="pill" href="#frd"><i class="bi bi-person-plus"></i><span class="badge badge-success" id="frd_rqst_badge_box" style="font-size: 10px;position: absolute;top: 10%;"><span></a>
					    </li>
					    <li class="nav-item">
					      <a class="nav-link group" data-toggle="pill" href="#group"><i class="bi bi-people mr-2"></i></a>
					    </li>
					    <li class="nav-item">
					      <a class="nav-link sgstn" data-toggle="pill" href="#sgstn"><i class="bi bi-person-plus mr-2"></i></a>
					    </li>
					    <li class="nav-item" id="msg_btn_tab">
					      <a class="nav-link msg" data-toggle="pill" href="#msg_box"><i class="bi bi-chat-left "></i></a>
					    </li>
					    <div class="nav-item dropdown" id="notify_drpdwn">
							  <button class="dropdown-toggle" style="border: none;background: none;" type="button" data-bs-toggle="dropdown" aria-expanded="false">
							    <i class="bi bi-bell"></i>
							  </button>
							  <ul class="dropdown-menu" id="notification_dropdown"></ul>
							</div>
					  </ul>
			    </div>
			</nav>
		</div>
		<div class="header_right" style="width:100%;height: 100%;">
				<a href="user_profile.php" data-toggle="toltip" title="<?php echo $inf['name']; ?>" data-id="<?php echo $current_user ?>" id="preview_usr_profile">
					<img src="images/<?php echo $inf['profile']; ?>" style="object-fit: cover;" width="35px" height="35px" class="rounded-circle z-depth-2" alt="100x100" src="" data-holder-rendered="true">
				</a>
				<a href="php_core/logout.php?id=<?php echo $current_user ?>" style="text-decoration: none;">
					<button type="button" class="btn btn-outline-primary" style="padding: 0px 0px 2px 8px;" data-holder-rendered="true">
						<i class="bi bi-box-arrow-right mr-2"></i>
					</button>
				</a>
		</div>
</div>












 <!-- Search all user container -->
  <div id="search_box_container">Search</div>
<div id="profile_container"></div>
<div id="group_container"></div>

  <!-- Tab panes -->
  <div class="tab-content" id="tab_main_container">

    <div id="home" class="container-fluid tab-pane active">
      <?php 
       include("home_content.php");
      ?>

    </div>
    <div id="frd" class="container tab-pane fade">
      <div style="box-shadow: 1px 1px 2px #ccc;background: white;padding: 10px;border-radius: 5px;margin-top: 5px;">
      	<h4>
      		You have <span id="frd_badge_box"></span> Friend Requests
      	</h4>
      	<div class="col-lg-3"></div>
      	<div class="frd_rqst_container col-lg-6"></div>
      	<div class="col-lg-3"></div>
      </div>
    </div>
    <div id="group" class="tab-pane fade">
    	<div style="display: grid;grid-template-columns: 1.1fr 2.2fr 1.1fr;">
	    	<div style="background: white;margin: 5px;box-shadow: 1px 1px 2px #ccc;border-radius: 8px;padding: 10px;">
	    		<h5>Create a Group</h5>
	    		<input type="text" class="form-control" placeholder="Create a group.." id="grp_name">
	    		<input type="button" class="btn btn-primary mt-1" id="group_create_btn" value="Create">
	    	</div>
	      <div style="background: white;margin: 5px;box-shadow: 1px 1px 2px #ccc;border-radius: 8px;padding: 10px;">
	      	<h3>Groups</h3>
		      <img src="images/spinner.gif"  class="mb-2 group_spinner"  style="display:none" alt="">
		      <div id="grps_container"></div>
	      </div>
	      <div id="group_invites" style="background: white;margin: 5px;box-shadow: 1px 1px 2px #ccc;border-radius: 8px;padding: 10px;">
	      	Group invite
	      </div>    		
    	</div>

    </div>
    <div id="sgstn" class="container tab-pane fade">
      <div class="container rounded p-2" style="box-shadow: 1px 1px 2px #ccc;background: white;">
      	<div class="row">
	      	<div class="col-lg-6 col-md-6 col-sm-12" id="frd_suggestions_box">
	      	</div>
	      	<div class="col-lg-6 col-md-6 col-sm-12">
	      		<center>
	      			<h5 style="margin:0px">
	      				User Info
	      			</h5>
	      		</center>
	      		<div id="show_usr_details_container"></div>
	      	</div>
      	</div>
      </div>
    </div>
	 <div id="msg_box" style="background:white;box-shadow: 1px 1px 2px #ccc;" class="container tab-pane fade p-2 rounded">
		  <h3>Inbox</h3>
			<div class="row d-flex">
				<div class="d-flex">
					<div class="col-4 rounded" style="padding:0px;" id="messages_users_container">
						<div class="col-12 msnger_header_search">
							<div class="input-group">
								<input type="search" id="search_inp" style="background: none;" class="form-control" placeholder="Search user..." aria-label="Search"aria-describedby="search-addon" />
								<button id="search_close_btn" style="display:none" class="btn border">
									<i class="bi bi-x"></i>
								</button>
								<button id="search_btn" class="btn border">
									<i class="bi bi-search"></i>
								</button>
							</div>
				    </div>
				    <div id="left_container_for_search_user" class="shadow-sm" style="position: absolute;width:100%;background:white;z-index: 3;"></div>
						<div id="left_content_container" style="transform-origin:top;transition: 0.4s ease-in-out;background:white;">
							<div class="active_users">
								<div id="user_box" class="border my-1">
									<img src="images/spinner.gif" id="active_show_spinner" style="display:none" alt="">
								</div>
							</div>
							<div class="user_container border p-2"></div>
						</div>
					</div>
					<div class="border rounded" style="padding:0px;width: 100%;background: white;z-index: 3;" id="messages_box">
						<center>
							<h3>Select an user for chat..</h3>
						</center>
					</div>			
				</div>
			</div>
		</div>
</div>










<?php
include("footer.php");
?>
