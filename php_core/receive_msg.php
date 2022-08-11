<?php
include("db.php");
session_start();
$current_user=$_SESSION['user_id'];

	if (isset($_REQUEST['get_msg'])) {
		$usr_id=$_REQUEST['data_id'];
		$sql="SELECT * FROM msgs WHERE receive_user_id='$current_user' AND sent_user_id='$usr_id' OR receive_user_id='$usr_id' AND sent_user_id='$current_user'";
		$query=mysqli_query($conn,$sql);
		while($data=mysqli_fetch_assoc($query)) {
			if ($data['receive_user_id']==$current_user) {
				echo '

	      					<div class="receive_msg">
	      						<img width="25px" height="25px" class="rounded-circle z-depth-2" src="images/default.png">
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
								margin-top: -3px;">
								'.$data["msg"].'
								</p>
	      					</div>

				';
			}else{
				echo '

							<div class="sent_msg" style="
									position: relative;display: flex;
									justify-content: flex-end;">
								<p style="
									box-shadow: 1px 2px 3px #021a1e26,-1px -1px 1px #021a1e1f;
									padding: 1px 7px 2px 7px;
									border-radius: 10px 10px 0px 10px;
									width: fit-content;
									font-size: 17px;
									margin: 7px 26px 0px 0px;">
									'.$data["msg"].'
								</p>
								<img style="
									position: absolute;
									right: 0px;
									top: 26px;" 
									width="25px" height="25px" class="rounded-circle z-depth-2" src="images/default.png">
							</div>

				';
			}
		}
	}

?>