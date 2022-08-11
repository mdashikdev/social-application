<?php 
$query_for_group_post=mysqli_query($conn,"SELECT * FROM posts WHERE post_owner='$profile_user_id' ORDER BY post_id DESC");
if ($query_for_group_post) {
if (mysqli_num_rows($query_for_group_post)>0) {
    while ($group_info=mysqli_fetch_assoc($query_for_group_post)) {
        $post_user_id= $group_info['post_user_id'];
        $user_name=get_user($conn,$post_user_id);
        $usr_id_for_group_posts=$group_info['post_user_id'];
        $query_for_group_posts_user=mysqli_query($conn,"SELECT * FROM users WHERE unique_id='$usr_id_for_group_posts'");
        if ($query_for_group_posts_user) {
            while ($data=mysqli_fetch_assoc($query_for_group_posts_user)) {
                $count_react=count_reacts($conn,$group_info['post_code']);
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
                        <img id="wow_icon'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icon_all wow_icon" src="emojis/wow.png">
                        <span id="icon_name_for_responsive">Wow</span>
                    </button>';
                }elseif ($like_btn_status=="Sad") {
                    $like_btn='
                    <button data-id="'.$group_info["post_code"].'" id="like_btn" class="post_footer_btn ml-1 col-lg-3 col-md-3 col-sm-3">
                        <img id="sad_icon'.$data["post_code"].'" data-id="'.$data["post_code"].'" class="icon_all sad_icon" src="emojis/sad.png">
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
                    <div class="post_box mt-1" id="post_box'.$data["unique_id"].'"  style="border: 1px solid #eaeaea;border-radius: 5px;">
                        <div class="post_header d-flex justify-content-space-between" style="border-bottom: 1px solid #ccc;padding: 5px;">
                            <div id="timeline_header_left" style="padding:0" class="col-lg-11 col-md-11 col-sm-11 d-flex align-items-center">
                                <div class="d-flex">
                                    <img src="images/'.$data["profile"].'" width="40px" height="40px" class="rounded-circle">
                                    <div>
                                        <strong id="full_view_btn" style="margin-left: 5px;cursor:pointer;" data-id="'.$data["unique_id"].'">'.$data["name"].'</strong>
                                        <p style="margin-bottom: -7px;margin-top: -7px;margin-left: 5px;font-size: 11px;color: #040404a3;">'.facebook_time_ago($group_info["post_time"]).'</p>
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
                        <div id="like_count_and_other_container">
                            <span id="reactors_showBtn'.$group_info["post_code"].'" data-id="'.$group_info["post_code"].'" class="reactors_showBtn">'.$count_react.' user Reacted</span>
                        </div>
                        <div id="icons_container'.$group_info["post_code"].'" data-id="'.$group_info["post_code"].'" class="icons_container p-2">
                            <img id="like_icon'.$group_info["post_code"].'" data-id="'.$group_info["post_code"].'" class="icon_all like_icon" src="emojis/like_color.png">
                            <img id="heart_icon'.$group_info["post_code"].'" data-id="'.$group_info["post_code"].'" class="icon_all heart_icon" src="emojis/heart.png">
                            <img id="laughing_icon'.$group_info["post_code"].'" data-id="'.$group_info["post_code"].'" class="icon_all laughing_icon" src="emojis/laughing.png">
                            <img id="wow_icon'.$group_info["post_code"].'" data-id="'.$group_info["post_code"].'" class="icon_all wow_icon" src="emojis/wow.png">
                            <img id="sad_icon'.$group_info["post_code"].'" data-id="'.$group_info["post_code"].'" class="icon_all sad_icon" src="emojis/sad.png">
                            <img id="angry_icon'.$group_info["post_code"].'" data-id="'.$group_info["post_code"].'" class="icon_all angry_icon" src="emojis/angry.png">
                        </div>
                        <div class="footer_content p-1 d-flex" style="justify-content:space-between">
                            '.$like_btn.'
                            <button class="post_footer_btn col-lg-3 col-md-3 col-sm-3 ml-1">
                                <i class="bi bi-chat-right-dots"></i>
                                <span id="icon_name_for_responsive" class="comment_responsive">Comment</span>
                            </button>
                            <button  class="post_footer_btn col-lg-3 col-md-3 col-sm-3 ml-1">
                                <i class="bi bi-share"></i>
                                <span id="icon_name_for_responsive">Share</span>
                            </button>
                        </div>
                    </div>
                    ';
                }else{
                $output='
                        <div class="post_box mt-1" id="post_box'.$group_info["post_code"].'" style="border: 1px solid #eaeaea;border-radius: 5px;">
                            <div class="post_header d-flex justify-content-space-between" style="border-bottom: 1px solid #ccc;padding: 5px;">
                                <div id="timeline_header_left" style="padding:0" class="col-lg-11 col-md-11 col-sm-11 d-flex align-items-center">
                                    <div class="d-flex">
                                        <img src="images/'.$data["profile"].'" width="40px" height="40px" class="rounded-circle">
                                        <div>
                                            <strong id="full_view_btn" style="margin-left: 5px;cursor:pointer;" data-id="'.$data["unique_id"].'">'.$name.'</strong>
                                            <p style="margin-bottom: -7px;margin-top: -7px;margin-left: 5px;font-size: 11px;color: #040404a3;">'.facebook_time_ago($group_info["post_time"]).'</p>
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
                            <div id="like_count_and_other_container">
                                <span id="reactors_showBtn'.$group_info["post_code"].'" data-id="'.$group_info["post_code"].'" class="reactors_showBtn">'.$count_react.' user Reacted</span>
                            </div>
                            <div id="icons_container'.$group_info["post_code"].'" data-id="'.$group_info["post_code"].'" class="icons_container p-2">
                                <img id="like_icon'.$group_info["post_code"].'" data-id="'.$group_info["post_code"].'" class="icon_all like_icon" src="emojis/like_color.png">
                                <img id="heart_icon'.$group_info["post_code"].'" data-id="'.$group_info["post_code"].'" class="icon_all heart_icon" src="emojis/heart.png">
                                <img id="laughing_icon'.$group_info["post_code"].'" data-id="'.$group_info["post_code"].'" class="icon_all laughing_icon" src="emojis/laughing.png">
                                <img id="wow_icon'.$group_info["post_code"].'" data-id="'.$group_info["post_code"].'" class="icon_all wow_icon" src="emojis/wow.png">
                                <img id="sad_icon'.$group_info["post_code"].'" data-id="'.$group_info["post_code"].'" class="icon_all sad_icon" src="emojis/sad.png">
                                <img id="angry_icon'.$group_info["post_code"].'" data-id="'.$group_info["post_code"].'" class="icon_all angry_icon" src="emojis/angry.png">
                            </div>
                            <div class="footer_content p-1 d-flex" style="justify-content:space-between">
                                '.$like_btn.'
                                <button class="post_footer_btn col-lg-3 col-md-3 col-sm-3 ml-1">
                                    <i class="bi bi-chat-right-dots"></i>
                                    <span id="icon_name_for_responsive" class="comment_responsive">Comment</span>
                                </button>
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

        echo $output;
    }
}else{
    echo $output= "No post in this group";
}

}
