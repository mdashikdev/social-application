$(document).ready(function() {

    //Register Ajax request
    $("#register_form").on("submit", function() {
        var form_data = new FormData(this);

        $.ajax({
            type: "POST",
            url: "php_core/register_file.php",
            data: form_data,
            contentType: false,
            processData: false,
            beforeSend:function(){
                $("#register_btn").prop("disabled",true);
            },
            success: function(data) {
                if (data == "Your Account Registered! Now you can Login your account...") {
                    $("#register_btn").prop("disabled",false);
                    $("#alert_for_login_register").html(data)
                    $("#register_form")[0].reset();
                    $("#alert_for_login_register_dngr").css("display", "none");
                    $("#alert_for_login_register").css("display", "block");
                    window.location.reload();
                } else {
                    $("#alert_for_login_register_dngr").html(data)
                    $("#alert_for_login_register_dngr").css("display", "block");
                }
            }
        })
    })

    //Login Ajax request
    $("#login_form").on("submit", function() {
            var form_data = new FormData(this);

            $.ajax({
                type: "POST",
                url: "php_core/login_file.php",
                data: form_data,
                contentType: false,
                processData: false,
                beforeSend:function(){
                    //$("#login_btn").prop("disabled",true);
                },
                success: function(data) {
                    if (data == "Congrats! You Are Successfully Logged In") {
                        $("#login_btn").prop("disabled",false);
                        $("#alert_for_login_register").html(data)
                        $("#alert_for_login_register_dngr").css("display", "none");
                        $("#alert_for_login_register").css("display", "block");
                        window.location.href = 'home.php';
                    } else {
                        $("#alert_for_login_register_dngr").html(data)
                        $("#alert_for_login_register_dngr").css("display", "block");
                    }

                }
            })
        })
        //search user
    $(document).on("keyup","#search_input", function() {
        var src_val = $("#search_input").val();
        if (src_val!="") {
            $("#search_box_container").css("transform","translatex(0px)");
            $("#src_close_btn").css("display","block");
            $("#src_usr_btn").css("display","none");
            $("#search_box_container").css("opacity","1");
            $("#src_close_btn").click(function(){
                $("#src_close_btn").css("display","none");
                $("#src_usr_btn").css("display","block");
                $("#search_input").val("");
                $("#search_box_container").css("transform","translatex(-270px)");
            })
            $.ajax({
                url: "php_core/function.php",
                type: "POST",
                data: { srs_input: "search", src_val },
                success: function(response) {
                    $("#search_box_container").html(response);
                }
            })
        }else{
            $("#search_box_container").css("transform","translatex(-270px)");
            $("#src_close_btn").css("display","none");
            $("#src_usr_btn").css("display","block");
            $("#search_box_container").css("opacity","0");
        }
    })

    //search user for message
    $("#search_inp").on("keyup", function() {
            var src_inp_val = $("#search_inp").val();
            if (src_inp_val == "") {
                $(".search_user_for_msg").css("transform", "scale(0)");
            } else {
                $(".search_user_for_msg").css("transform", "scale(1)");
                $.ajax({
                    url: "php_core/function.php",
                    type: "POST",
                    data: { search_inp: "search user for msg", search_val: src_inp_val },
                    success: function(response) {
                        $(".search_user_for_msg").html(response);
                    }
                })
            }

        })

    //show active users
    function active_user() {
        $.ajax({
            url: "php_core/function.php",
            type: "POST",
            data: { fnction_actv: "active status" },
            beforeSend: function() {
                $("#active_show_spinner").css("display", "block");
            },
            success: function(response) {
                $("#active_show_spinner").css("display", "none");
                $("#user_box").html(response);
            }
        })
    }
    setInterval(function() {
        active_user();
    }, 3000)


    //Send message button
    $(document).on("submit", ".sent_msg_btn", function() {
        var id = $(this).data("id");
        var txt = $("#btn_sent" + id).val();
        var uid = $("#btn_sent" + id).data("id");
        if (txt != "") {
            $.ajax({
                url: "php_core/sent_msg.php",
                type: "POST",
                data: { snt_msg: "sent message", usrId: uid, msg: txt },
                success: function(response) {
                    get_my_chats(id);
                    $("#sent_msg_btn" + id)[0].reset();
                }
            })

        };

    });

    function get_my_chats(id) {
        var id = $("#btn_sent" + id).data("id");
        $.ajax({
            url: "php_core/function.php",
            type: "POST",
            data: { show_usr_for_cht_area: "show user for chat area", usr_id: id },
            success: function(response) {
                $("#chat_area").html(response);
                if ($("#chat_area").hasClass("active")) {

                } else {
                   scrollBottom();
                }
            }
        });
    }


    //Click icon
    $(document).on("click",".cover_icon", function() {
        $("#cover_photo_input").click();
    })
    $(document).on("click",".profile_icon", function() {
            $("#profile_photo_input").click();
        })

    //select photo to work this
    $(document).on("change", "#cover_photo_input", function() {
        $(".cover_change_input_div").css("display", "block");
    })
    $(document).on("change","#profile_photo_input",function() {
            $("#profile_photo_form").css("display","block");
            const [file]=$(this).files;
            if (file) {
                $(".profile_selected_img_box").src = URL.createObjectURL(file)
              }
        })
        //cover hide button method
    $(document).on("click", ".cover_hide_btn", function() {
        $(".cover_change_input_div").css("display", "none");
    })
    $(document).on("click", ".profile_hide_btn", function() {
        $("#profile_photo_form").css("display", "none");
    })

    //Change cover photo
    $(document).on("submit", "#cover_photo_form", function() {
        var id = $(this).data("id");
        if ($("#cover_photo_input") == "") {
            alert("Please select a image!!");
        } else {
            var data = new FormData(this);
            $.ajax({
                url: "php_core/cover_photo.php",
                type: "POST",
                data: data,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $("#spinner_covr").css("display", "block");
                    $(".upload_btn_covr").prop('disabled', true);
                },
                success: function(response) {
                    $(".cover_change_input_div").css("display", "none");
                    if (response == "Posted your image") {
                        get_timeline_post();
                        group_content(id);
                    } else {
                        alert(response);
                    }
                }
            })
        }

    })

    //Change Profile Photo
    $(document).on("submit", "#profile_photo_form", function() {
        if ($("#profile_photo_input") == "") {
            alert("Please select a image!!");
        } else {
            var data = new FormData(this);
            $.ajax({
                url: "php_core/profile.php",
                type: "POST",
                data: data,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $("#spinner_pro").css("display", "block");
                    $(".upload_btn_profile").prop('disabled', true);
                },
                success: function(response) {
                    $("#spinner_pro").css("display", "none");
                    $("#profile_photo_form").css("display", "none");
                    get_timeline_post();
                    window.location.reload();
                }
            })
        }

    })

    //Profile Update Form
    $(document).on("submit","#profile_update_form", function() {
        var form_data = new FormData(this);
        $.ajax({
            url: "php_core/update_profile.php",
            type: "POST",
            data: form_data,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $("#updt_pro_btn").prop('disabled', true);
            },
            success: function(response) {
                $("#updt_pro_btn").prop('disabled', false);
                $("#profile_update_form")[0].reset();
                window.location.reload();
            }
        })
    })
    //group update form
    $(document).on("submit","#group_update_form", function() {
        var form_data = new FormData(this);
        $.ajax({
            url: "php_core/update_group.php",
            type: "POST",
            data: form_data,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $("#updt_grp_btn").prop('disabled', true);
            },
            success: function(response) {
                if (response=="This is current name") {
                    $("#updt_grp_btn").prop('disabled', false);
                    alert(response);
                }else{
                    $("#updt_grp_btn").prop('disabled', false);
                    $("#group_update_form")[0].reset();
                    window.location.reload();
                }
            }
        })
    })

    //Show Details User
    $(document).on("click", "#user_div", function() {
        var id = $(this).data("id");
        $.ajax({
            url: "php_core/function.php",
            type: "POST",
            data: { show_user_details: "Show user details", usr_id: id },
            success: function(response) {
                $("#show_usr_details_container").html(response);
            }
        })
    })

    //Group members
    function group_members(id) {
        var id = id;
        $.ajax({
            url: "php_core/function.php",
            type: "POST",
            data: { group_members: "this is for groups", group_id: id },
            success: function(response) {
                $("#people").html(response);
            }
        })
    }

    function group_posts(groupid) {
        var grpId = groupid;
        $.ajax({
            url: "php_core/function.php",
            type: "POST",
            data: { this_is_for_group_all_posts: "this is for group all posts", id: grpId },
            success: function(response) {
                $("#group_all_posts_container" + grpId).html(response);
            }
        })
    }

    //Changing group cover
    $(document).on("click", ".group_cover_icon", function() {
            var id = $(this).data("id");
            $("#group_cover_photo_input" + id).click();
            $(document).on("change","#group_cover_photo_input" + id,function() {
                $("#group_cover_photo_form" + id).css("display", "block");
            })
            $("#group_cover_hide_button" + id).on("click", function() {
                alert("selected");
                $("#group_cover_photo_change" + id).css("display", "none");
            })
            $(document).on("submit", "#group_cover_photo_form" + id, function() {
                var form_data = new FormData(this);
                $.ajax({
                    url: "php_core/group_cover.php",
                    type: "POST",
                    data: form_data,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $("#upload_btn_covr" + id).attr("disabled", "disabled");
                    },
                    success: function(response) {
                        window.location.reload();
                    }
                })
            })
        })
        //Sending friend request
    $(document).on("click", ".frd_rqst_btn", function() {
            var id = $(this).data("id");
            $.ajax({
                url: "php_core/function.php",
                type: "POST",
                data: { frd_request: "sent friend request", usr_id: id },
                beforeSend: function() {
                    $("#frd_rqst_btn" + id).prop('disabled', true);
                    $("#frd_rqst_btn" + id).html("Sending...");
                },
                success: function(response) {
                    $("#frd_rqst_btn" + id).html(response);
                }
            })
        })
        //Friend Suggestion Box
    function show_friend_suggestion() {
        $.ajax({
            url: "php_core/function.php",
            type: "POST",
            data: { show_frd_suggestions: "Show Friend suggestion" },
            success: function(response) {
                $("#frd_suggestions_box").html(response);
            }
        })
    }
    setInterval(function() {
        show_friend_suggestion();
    }, 2000)

    //show friend request
    function show_friend_requests() {
        $.ajax({
            url: "php_core/function.php",
            type: "POST",
            data: { show_frd_rqst: "Show Friend Request" },
            success: function(response) {
                $(".frd_rqst_container").html(response);
            }
        })
    }
    setInterval(function() {
            show_friend_requests();
        }, 2000)
        //Friend Badge count
    function friend_requests_badge_count() {
        $.ajax({
            url: "php_core/function.php",
            type: "POST",
            data: { friend_requests_badge_count: "Friend Request badge count" },
            success: function(response) {
                $("#frd_rqst_badge_box").html(response);
                $("#frd_rqst_badge_box_mobile").html(response);
                $("#frd_badge_box").html(response);
                $("#frd_follower_box").html(response);
            }
        })
    }
    setInterval(function() {
        friend_requests_badge_count();
    }, 500)

    //Accept Friend Request
    $(document).on("click", ".accpt_rqst_btn", function() {
            var id = $(this).data("id");
            $.ajax({
                url: "php_core/function.php",
                type: "POST",
                data: { accept_frd_request: "accept friend request", usr_id: id },
                beforeSend: function() {
                    $("#accpt_rqst_btn" + id).prop('disabled', true);
                    $("#accpt_rqst_btn" + id).html("Sending...");
                },
                success: function(response) {
                    $("#accpt_rqst_btn" + id).html(response);
                    get_timeline_post();
                }
            })
        })
        //Reject Friend Request
    $(document).on("click", ".reject_rqst_btn", function() {
            var id = $(this).data("id");
            $.ajax({
                url: "php_core/function.php",
                type: "POST",
                data: { reject_frd_request: "reject friend request", usr_id: id },
                beforeSend: function() {
                    $("#reject_rqst_btn" + id).prop('disabled', true);
                    $("#reject_rqst_btn" + id).html("Sending...");
                },
                success: function(response) {
                    $("#reject_rqst_btn" + id).html(response);
                }
            })
        })
    //Get current user Friends
    function get_friends() {
        $.ajax({
            url: "php_core/function.php",
            type: "POST",
            data: { get_friends: "Get current user friends" },
            success: function(response) {
                $(".friend_main").html(response);
            }
        })
    }

//Get any user Friends
function get_any_user_friends(id) {
    var id=id;
    $.ajax({
        url: "php_core/function.php",
        type: "POST",
        data: { get_any_usr_friends: "Get any user friends",id:id },
        success: function(response) {
            $("#user_frd").html(response);
        }
    })
}
//Get any user Followers
function get_any_user_followers(id) {
    var id=id;
    $.ajax({
        url: "php_core/function.php",
        type: "POST",
        data: { get_any_usr_follower: "Get any user follower",id:id },
        success: function(response) {
            $("#user_follower").html(response);
        }
    })
}
$(document).on("click","#user_frd_btn",function(){
    var id=$(this).data("id");
    get_any_user_friends(id);
})

$(document).on("click","#user_follower_btn",function(){
    var id=$(this).data("id");
    get_any_user_followers(id);
})

//group invite current user followers
function group_invite_follower(grp_id) {
    var id=grp_id;
    $.ajax({
        url: "php_core/function.php",
        type: "POST",
        data: { group_invite_current_user_followers: "group invite current user followers",grp_id:id},
        success: function(response) {
            console.log(response);
            $(".users_section").html(response);
        }
    })
}
$(document).on("click",".group_invite_btn",function(){
    var grp_id=$(this).data("id");
    group_invite_follower(grp_id);
    $(".invite_section_wrapper").css("display","block");
})
$(document).on("click","#invite_container_close_btn",function(){
    $(".invite_section_wrapper").css("display","none");
})
//search follower for invite
$(document).on("keyup","#follower_search_for_invite",function(){
    var txt=$(this).val();
    var grp_id=$(this).data("id");
    if (txt=="") {
       group_invite_follower(grp_id);
    }else{
        $.ajax({
            url:"php_core/function.php",
            type:"POST",
            data:{this_is_for_follower_search:"this is for follower search",src_input:txt,grp_id:grp_id},
            success:function(response){
                $(".users_section").html(response);
            }
        })
        
    }
})


//Invite insert
$(document).on("click",".group_invite_insert_btn",function(){
    var usr_id=$(this).data("id");
    var grp_id=$(this).data("grp_id");
    
    $.ajax({
        url:"php_core/function.php",
        type:"POST",
        data:{this_is_for_insert_group_invite:"this is for insert group invite",grp_id:grp_id,usr_id:usr_id},
        beforeSend:function(){
            $("#group_invite_insert_btn"+usr_id).html("Invited");
            $("#group_invite_insert_btn"+usr_id).attr("disabled",true);
        },
        success:function(response){
            alert(response);
        }
    })
})
//group inviters
function group_inviter(){
    $.ajax({
        url:"php_core/function.php",
        type:"POST",
        data:{this_is_for_group_inviters:"this is for group inviters"},
        success:function(response){
            $("#group_invites").html(response);
        }
    })
}

//accept group invite
$(document).on("click",".group_invite_accept_btn",function(){
    var usr_id=$(this).data("usr_id");
    var grp_id=$(this).data("group_id");
    $.ajax({
        url:"php_core/function.php",
        type:"POST",
        data:{this_is_for_group_invite_accept:"this is for group invite accept",grp_id:grp_id,},
        beforeSend:function(){
            $("#group_invite_accept_btn"+usr_id).attr("disabled",true);
        },
        success:function(response){
            $("#group_invite_accept_btn"+usr_id).attr("disabled",true);
            $("#group_invite_accept_btn"+usr_id).html(response);
        }
    })
})

setInterval(function(){
    group_inviter();
},500)

//get any user frd count
function get_any_user_friends_count(id) {
    var id=id;
    $.ajax({
        url: "php_core/function.php",
        type: "POST",
        data: { get_any_usr_friends_count: "Get any user friends count",id:id },
        success: function(response) {
            if (response == 0) {
                $("#user_frd_count").html("");
                $("#user_frd_count_frd_list").html("0");
            }else if(response == 1){
                $("#user_frd_count").html(response + " Friend");
                $("#user_frd_count_frd_list").html(response);
            }else{
                $("#user_frd_count").html(response + " Friends");
                $("#user_frd_count_frd_list").html(response);
            }
        }
    })
}
//get any user follower count
function get_any_user_follower_count(id) {
    var id=id;
    $.ajax({
        url: "php_core/function.php",
        type: "POST",
        data: { get_any_usr_follower_count: "Get any user follower count",id:id },
        success: function(response) {
           $("#user_follower_count_frd_list").html(response);
        }
    })
}



    setInterval(function() {
            get_friends();
        }, 5000)

    //Post Create
    $(document).on("submit", "#post_form", function() {
            var form_data = new FormData(this);
            var id = $("#owner").val();
            $.ajax({
                type: "POST",
                url: "php_core/post.php",
                data: form_data,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $(".post_spinner").css("display", "block");
                },
                success: function(data) {
                    $(".post_spinner").css("display", "none");
                    $("#post_form")[0].reset();
                    if (data == "failed to post") {
                        alert(data);
                    } else {
                        $("#post_form")[0].reset();
                        get_timeline_post();
                        $("#alert_for_post_create").html(data);
                        $("#alert_for_post_create").css("display", "block");
                    }
                }
            })

        })

    //Show post for timeline
    function get_timeline_post() {
        $.ajax({
            url: "php_core/function.php",
            type: "POST",
            data: { get_timeline_post: "Get timeline posts" },
            beforeSend: function() {
                //$(".timeline_post_spinner").css("display","block");
            },
            success: function(response) {
                $(".timeline_post_spinner").css("display", "none");
                $(".posts_container_for_timeline").html(response);
            }
        })
    }
    get_timeline_post();
    //Follow system
    $(document).on("click", ".follow_btn", function() {
            var id = $(this).data("id");
            $.ajax({
                url: "php_core/function.php",
                type: "POST",
                data: { insert_follow: "insert follow", id: id },
                beforeSend: function() {
                    $("#follow_btn" + id).html("Following");
                    $("#follow_btn" + id).attr("disabled", "disabled");
                },
                success: function(response) {
                    get_timeline_post();
                }
            })
        })
        //Unfollow system
    $(document).on("click", ".unfollow_btn", function() {
            var id = $(this).data("id");
            $.ajax({
                url: "php_core/function.php",
                type: "POST",
                data: { delete_follow: "delete follow", id: id },
                beforeSend: function() {
                    $("#unfollow_btn" + id).html("Unfollowed");
                    $("#unfollow_btn" + id).attr("disabled", "disabled");
                },
                success: function(response) {
                    alert(response);
                    get_timeline_post();
                }
            })
        })
        //Create a group
    $("#group_create_btn").click(function() {
            var group_name = $("#grp_name").val();
            $.ajax({
                url: "php_core/function.php",
                type: "POST",
                data: { create_group: "Create a group", group_name: group_name },
                beforeSend: function() {
                    $("#group_create_btn").prop("disabled", true);
                },
                success: function(response) {
                    $("#grp_name").val("");
                    $("#group_create_btn").prop("disabled", false);
                    if (response == "Group name required") {
                        alert(response);
                    }else{
                        show_groups();
                    }
                }
            })
        })
        //Show all groups
    function show_groups() {
        $.ajax({
            url: "php_core/function.php",
            type: "POST",
            data: { show_all_groups: "show all groups" },
            success: function(response) {
                $(".group_spinner").css("display", "none");
                $("#grps_container").html(response);
            }
        })
    }
    setInterval(function(){
        show_groups();
    },1000)
    //Sent group join request
    $(document).on("click", "#group_request_sent_btn", function() {
            var id = $(this).data("id");
            $.ajax({
                url: "php_core/function.php",
                type: "POST",
                data: { Sent_group_join_request: "Sent group join request", usr_id: id },
                beforeSend: function() {
                    $("#group_request_sent_btn").attr('disabled', 'disabled');
                    $("#group_request_sent_btn").html("Sending...");
                },
                success: function(response) {
                    if (response == "Already Sent Request") {
                        alert(response);
                    } else {
                        $("#group_request_sent_btn").attr('disabled', 'disabled');
                        $("#group_request_sent_btn").html(response);
                    }
                }
            })

        })
        //Accept join request
    $(document).on("click", "#join_approve_btn", function() {
        var acc_id = $(this).data("id");
        var grp_id = $("#join_approve_group_id").data("id");
        $.ajax({
            url: "php_core/function.php",
            type: "POST",
            data: { accpt_group_join_request: "Accept group join request", usr_id: acc_id, grp_id: grp_id },
            beforeSend: function() {
                $("#join_approve_btn").attr('disabled', 'disabled');
                $("#join_approve_btn").html("Sending...");
            },
            success: function(response) {
                if (response == "Already Sent Request") {
                    alert(response);
                } else {
                    $("#join_approve_btn").attr('disabled', 'disabled');
                    $("#join_approve_btn").html(response);
                    group_members(id);
                }
            }
        })
    })


    $(document).on("mouseenter","#chat_area", function() {
        $("#chat_area").addClass("active");
    })
    $(document).on("mouseleave","#chat_area", function() {
        $("#chat_area").removeClass("active");
    })

    function scrollBottom() {
        $("#chat_area").scrollTop($('#chat_area')[0].scrollHeight);
    }
    //Insert Like
    $(document).on("click", ".like_icon", function() {
            var id = $(this).data("id");
            var grp_id = $(this).data("group_id");
            $.ajax({
                url: "php_core/function.php",
                type: "Post",
                data: { insert_like: "insert a like", id: id },
                success: function(response) {
                    get_timeline_post();
                    group_posts(grp_id);
                    show_single_post(id);
                }
            })
        })
        //Insert heart
    $(document).on("click", ".heart_icon", function() {
            var id = $(this).data("id");
            var grp_id = $(this).data("group_id");
            $.ajax({
                url: "php_core/function.php",
                type: "Post",
                data: { insert_heart: "insert a heart", id: id },
                success: function(response) {
                    get_timeline_post();
                    group_posts(grp_id);
                    show_single_post(id);
                }
            })
        })
        //Insert haha
    $(document).on("click", ".laughing_icon", function() {
            var id = $(this).data("id");
            var grp_id = $(this).data("group_id");
            $.ajax({
                url: "php_core/function.php",
                type: "Post",
                data: { insert_laughing: "insert a laughing", id: id },
                success: function(response) {
                    get_timeline_post();
                    group_posts(grp_id);
                    show_single_post(id);
                }
            })
        })
        //Insert wow_icon
    $(document).on("click", ".wow_icon", function() {
            var id = $(this).data("id");
            var grp_id = $(this).data("group_id");
            $.ajax({
                url: "php_core/function.php",
                type: "Post",
                data: { insert_wow: "insert a wow", id: id },
                success: function(response) {
                    get_timeline_post();
                    group_posts(grp_id);
                    show_single_post(id);
                }
            })
        })
        //Insert sad_icon
    $(document).on("click", ".sad_icon", function() {
            var id = $(this).data("id");
            var grp_id = $(this).data("group_id");
            $.ajax({
                url: "php_core/function.php",
                type: "Post",
                data: { insert_sad: "insert a sad", id: id },
                success: function(response) {
                    get_timeline_post();
                    group_posts(grp_id);
                    show_single_post(id);
                }
            })
        })
        //Insert angry_icon
    $(document).on("click", ".angry_icon", function() {
        var id = $(this).data("id");
        var grp_id = $(this).data("group_id");
        $.ajax({
            url: "php_core/function.php",
            type: "Post",
            data: { insert_angry: "insert a angry", id: id },
            success: function(response) {
                get_timeline_post();
                group_posts(grp_id)
                show_single_post(id);
            }
        })
    })

    //Show Reactors
    $(document).on("click", ".reactors_showBtn", function() {
        var id = $(this).data("id");
        $("#reactor_user_show_box" + id).css("transform", "scaleY(1)");
        //All Reacts
        $.ajax({
                url: "php_core/function.php",
                type: "POST",
                data: { Show_reactor_users_all_react: "Show reactor users all_react", id: id },
                success: function(response1) {
                    $all = $("#all_react" + id).html(response1);
                }
            })

       //React counter
        $.ajax({
                url: "php_core/function.php",
                type: "POST",
                dataType: "JSON",
                data: { for_react_counter: "for react counter", id: id },
                success: function(data) {
                    $("#all_react_count" + id).html(data.all_react);
                    $("#like_count" + id).html(data.like);
                    $("#love_count" + id).html(data.love);
                    $("#haha_count" + id).html(data.haha);
                    $("#wow_count" + id).html(data.wow);
                    $("#sad_count" + id).html(data.sad);
                    $("#angry_count" + id).html(data.angry);
                }
            })

        //Like
        $(document).on("click", "#likedbtn" + id, function() {
                $.ajax({
                    url: "php_core/function.php",
                    type: "POST",
                    data: { Show_reactor_users_like: "Show reactor users like", id: id },
                    beforeSend: function() {
                        $("#spinner_for_load_react_user" + id).css("display", "block");
                    },
                    success: function(response2) {
                        $("#spinner_for_load_react_user" + id).css("display", "none");
                        $("#liked" + id).html(response2);
                    }
                });
            })

        //Love
        $(document).on("click", "#lovebtn" + id, function() {
                $.ajax({
                    url: "php_core/function.php",
                    type: "POST",
                    data: { Show_reactor_users_love: "Show reactor users love", id: id },
                    success: function(response3) {
                        $("#love" + id).html(response3);
                    }
                })
            })
            //haha
        $(document).on("click", "#hahabtn" + id, function() {
                $.ajax({
                    url: "php_core/function.php",
                    type: "POST",
                    data: { Show_reactor_users_haha: "Show reactor users haha", id: id },
                    success: function(response4) {
                        $("#haha" + id).html(response4);
                    }
                })
            })
            //Wow
        $(document).on("click", "#wowbtn" + id, function() {
                $.ajax({
                    url: "php_core/function.php",
                    type: "POST",
                    data: { Show_reactor_users_wow: "Show reactor users wow", id: id },
                    success: function(response5) {
                        $("#wow" + id).html(response5);
                    }
                })
            })
            //Sad
        $(document).on("click", "#sadbtn" + id, function() {
                $.ajax({
                    url: "php_core/function.php",
                    type: "POST",
                    data: { Show_reactor_users_sad: "Show reactor users sad", id: id },
                    success: function(response6) {
                        $("#sad" + id).html(response6);
                    }
                })
            })
            //angry
        $(document).on("click", "#angrybtn" + id, function() {
            //Angry
            $.ajax({
                url: "php_core/function.php",
                type: "POST",
                data: { Show_reactor_users_angry: "Show reactor users angry", id: id },
                success: function(response7) {
                    $("#angry" + id).html(response7);
                }
            })
        })
        $(document).on("click", "#reactor_close_btn" + id, function() {
            $("#reactor_user_show_box" + id).css("transform", "scaleY(0)");
        })

    })



    $(document).on("mouseenter", "#like_btn", function() {
        var id = $(this).data("id");
        $("#icons_container" + id).css("transform", "scaleY(1)");
    })
    $(document).on("mouseenter", ".icons_container", function() {
        var id = $(this).data("id");
        $("#icons_container" + id).css("transform", "scaleY(1)");
    })
    $(document).on("mouseleave", "#like_btn", function() {
        var id = $(this).data("id");
        $("#icons_container" + id).css("transform", "scaleY(0)");
    })
    $(document).on("mouseleave", ".icons_container", function() {
        var id = $(this).data("id");
        $("#icons_container" + id).css("transform", "scaleY(0)");
    })


    //like_icon
    $(document).on("mouseenter", ".like_icon", function() {
        var id = $(this).data("id");
        $("#like_icon" + id).addClass('icon_animation');
    })
    $(document).on("mouseleave", ".like_icon", function() {
            var id = $(this).data("id");
            $("#like_icon" + id).removeClass('icon_animation');
        })
        //heart_icon
    $(document).on("mouseenter", ".heart_icon", function() {
        var id = $(this).data("id");
        $("#heart_icon" + id).addClass('icon_animation');
    })
    $(document).on("mouseleave", ".heart_icon", function() {
            var id = $(this).data("id");
            $("#heart_icon" + id).removeClass('icon_animation');
        })
        //laughing_icon
    $(document).on("mouseenter", ".laughing_icon", function() {
        var id = $(this).data("id");
        $("#laughing_icon" + id).addClass('icon_animation');
    })
    $(document).on("mouseleave", ".laughing_icon", function() {
            var id = $(this).data("id");
            $("#laughing_icon" + id).removeClass('icon_animation');
        })
        //wow_icon
    $(document).on("mouseenter", ".wow_icon", function() {
        var id = $(this).data("id");
        $("#wow_icon" + id).addClass('icon_animation');
    })
    $(document).on("mouseleave", ".wow_icon", function() {
            var id = $(this).data("id");
            $("#wow_icon" + id).removeClass('icon_animation');
        })
        //sad_icon
    $(document).on("mouseenter", ".sad_icon", function() {
        var id = $(this).data("id");
        $("#sad_icon" + id).addClass('icon_animation');
    })
    $(document).on("mouseleave", ".sad_icon", function() {
            var id = $(this).data("id");
            $("#sad_icon" + id).removeClass('icon_animation');
        })
        //angry_icon
    $(document).on("mouseenter", ".angry_icon", function() {
        var id = $(this).data("id");
        $("#angry_icon" + id).addClass('icon_animation');
    })
    $(document).on("mouseleave", ".angry_icon", function() {
            var id = $(this).data("id");
            $("#angry_icon" + id).removeClass('icon_animation');
        })
        //comment section
    $(document).on("click", ".comnt_btn", function() {
        var id = $(this).data("id");
        $("#comment_section" + id).css("transform", "scaleY(1)");
        show_all_comment(id);
        $(document).on("click", "#reply" + id, function() {
            var this_id = $(this).data("id");
            var name = $(this).data("name");
            $("#cmnt_area" + id).focus();
            $("#cmnt_area" + id).attr("placeholder", "Reply to " + name);
            $("#cmnt_area" + id).val(name);
            $("#back_post_comment" + id).css("display", "block");
            $("#cmnt_id" + id).val(this_id);
        })
        $(document).on("click", "#replies" + id, function() {
            var replies_id = $(this).data("id");
            $.ajax({
                url: "php_core/function.php",
                type: "POST",
                data: { this_is_for_get_replies: "This is for get replies", reply_id: replies_id },
                success: function(response) {
                    $("#replies" + replies_id).html(response);
                }
            })
        })

    })
    $(document).on("click", ".back_post_comment", function() {
            var id = $(this).data("id");
            $("#cmnt_id" + id).val('0');
            $("#back_post_comment" + id).css("display", "none");
            $("#cmnt_area" + id).attr("placeholder", "Post a comment..");
            $("#cmnt_area" + id).val("");
        })
        //Get Replies
    function get_replies(replie_id) {
        var replie_id = replie_id;

    }
    $(document).on("click", "#comment_close_btn", function() {
        var id = $(this).data("id");
        $("#comment_section" + id).css("transform", "scaleY(0)");
    })
    $(document).on("submit", "#commment_post_form", function() {
        var post_id = $(this).data("id");
        var comment_txt = $("#cmnt_area" + post_id).val();
        var comment_id = $("#cmnt_id" + post_id).val();
        $.ajax({
            url: "php_core/function.php",
            type: "POST",
            data: { this_is_for_comment_post: "This is for comment post", comment_text: comment_txt, post_id: post_id, comment_id: comment_id },
            success: function(response) {
                if (response == "failed") {
                    alert(response);
                } else {
                    $("#commment_post_form")[0].reset();
                    $("#cmnt_area" + post_id).val('');
                    $("#cmnt_id" + post_id).val('0');
                    show_all_comment(post_id);
                    get_timeline_post();
                    show_single_post(post_id);
                }
            }
        })
    })

    //show comment
    function show_all_comment(id) {
        var post_id = id;
        $.ajax({
            url: "php_core/function.php",
            type: "POST",
            data: { this_is_for_all_comments_show: "This is for all comment show", post_id: post_id },
            success: function(response) {
                $("#comment_box" + id).html(response);
            }
        })
    };

    function get_notification() {
        $.ajax({
            url: "php_core/function.php",
            type: "POST",
            data: { this_is_for_notificatoin: "this is for notification" },
            success: function(response) {
                $("#notification_dropdown").html(response);
                $("#notification_dropdown_mobile").html(response);
            }
        })
    }

    setInterval(function() {
        get_notification();
    }, 4000)

    //View Any Profile
    $(document).on("click", "#preview_usr_profile", function(e) {
            e.preventDefault();
            $("#profile_container").css("display", "block");
            $("#tab_main_container").css("display", "none");
            $("#group_container").css("display", "none");
            var url = $(this).attr("href");
            var id = $(this).data("id");
            $("#for_preview_user").focus();
            if ($("#for_preview_user").is(":focus")) {
                history.pushState(null, '', url);
            }
            $(document).on("click", "#back_btn", function() {
                history.pushState(null, '', "home.php");
                $("#profile_container").css("display", "none");
                $("#tab_main_container").css("display", "block");
            })
            $(document).on("blur", "#for_preview_user", function() {
                history.pushState(null, '', "home.php");
                $("#tab_main_container").css("display", "block");
            })
            page_content(url,id);
            posts(id);
            get_any_user_friends_count(id);
            get_any_user_follower_count(id);
            function page_content(url,id){
                var url=url;
                var id=id;
                $.ajax({
                    url: "content.php",
                    type: "GET",
                    data: { page: url, id: id },
                    success: function(response) {
                       $("#profile_container").html(response);
                    }
                })
            }

            $(document).on("click","#load_page_btn",function(){
                page_content(url,id);
                posts(id);
            })
        })
        function posts(id){
        var id=id;
            $.ajax({
                url:"php_core/function.php",
                type:"POST",
                data:{this_is_for_any_user_posts : "This is for any user posts",id:id},
                success:function(response){
                    $(".posts_containers").html(response);
                    console.log(response);
                }
            })
        }
        //View Group
    $(document).on("click", ".group_view_btn", function(e) {
        var id = $(this).data("id");
        var url = $(this).attr("href");
        $("#group_container").css("display", "block");
        $("#tab_main_container").css("display", "none");
        e.preventDefault();
        
        $("#for_preview_user").focus();
        if ($("#for_preview_user").is(":focus")) {
            history.pushState(null, '', url);
        }
        $(document).on("click", "#back_btn", function() {
            history.pushState(null, '', "home.php");
            $("#group_container").css("display", "none");
            $("#tab_main_container").css("display", "block");
        })
        $(document).on("blur", "#for_preview_user", function() {
            history.pushState(null, '', "home.php");
            $("#tab_main_container").css("display", "block");
        })
        group_posts(id);
        group_members(id);
        group_content(id, url);
        $(document).on("click","#group_post_load_btn",function(){
            group_posts(id);
        })
    })

    //Gorup Content
    function group_content(id, url) {
        var id = id;
        var url = url;
        $.ajax({
            url: "group_content.php",
            type: "GET",
            data: { page: url, id: id },
            success: function(response) {
                $("#group_container").html(response);
                group_join_rqsts_count(id);
                setInterval(() => {
                    group_join_rqsts_count(id);
                    join_requests(id);
                }, 500);
            }
        })
    }
    //Join requests show
    function join_requests(id) {
        var id = id;
        $.ajax({
            url: "php_core/function.php",
            type: "POST",
            data: { this_is_for_group_join_requests: "this is for group join requests", id: id },
            success: function(response) {
                $("#group_join_requests").html(response);
            }
        })
    }


    //Join Request count
    function group_join_rqsts_count(id) {
        var id = id;
        $.ajax({
            url: "php_core/function.php",
            type: "POST",
            data: { this_is_for_join_rqst_count: "this is for join rqst count", group_id: id },
            success: function(response) {
                $("#group_join_rqst_count").html(response);
            }
        })
    }
    $(document).on("click", ".home", function() {
        $("#group_container").css("display", "none");
        $("#profile_container").css("display", "none");
        history.pushState(null, '', "home.php");
        $("#tab_main_container").css("display", "block");
    })
    $(document).on("click", ".frd", function() {
        $("#group_container").css("display", "none");
        $("#profile_container").css("display", "none");
        history.pushState(null, '', "home.php");
        $("#tab_main_container").css("display", "block");
    })
    $(document).on("click", ".group", function() {
        $("#group_container").css("display", "none");
        $("#profile_container").css("display", "none");
        history.pushState(null, '', "home.php");
        $("#tab_main_container").css("display", "block");
    })
    $(document).on("click", ".sgstn", function() {
        $("#group_container").css("display", "none");
        $("#profile_container").css("display", "none");
        history.pushState(null, '', "home.php");
        $("#tab_main_container").css("display", "block");
    })
    $(document).on("click", ".msg", function() {
        $("#group_container").css("display", "none");
        $("#profile_container").css("display", "none");
        history.pushState(null, '', "home.php");
        $("#tab_main_container").css("display", "block");
    })

    //get last message users
    function get_last_message_users() {
        $.ajax({
            url: "php_core/function.php",
            type: "POST",
            data: { this_is_for_last_message_user: "this is for last message user" },
            success: function(response) {
                $(".user_container").html(response);
            }
        })
    }
   // setInterval(function(){
        get_last_message_users();
    //},400)


    //Select user For Message
    $(document).on("click", ".select_user_for_message", function(e) {
        e.preventDefault();
        var id = $(this).data("id");
        $("#messages_users_container").css("width","0px");
        $("#messages_box").css("display","block");
        $.ajax({
            url: "php_core/function.php",
            type: "POST",
            data: { this_is_for_select_user_for_message: "This is for selecet user for message", id: id },
            success: function(response) {
                $("#messages_box").html(response);
                chats(id);
            }
        })
    })

function chats(id){
    var id=id;
    intrvl=setInterval(function(){
        get_my_chats(id);
    },400)

$(document).on("click","#chat_back_btn",function(e){
    clearInterval(intrvl);
})
}


$(document).on("click","#chat_back_btn",function(e){
    var id=$(this).data("id");
    window.clearInterval(function(){
        get_my_chats(id);
    },200)

    var width=$(window).width();
    if (width <= 510) {
        $("#messages_users_container").addClass("col-12");
        $("#messages_users_container").css("width","100%");
        $("#messages_box").css("display","none");
        $("#messages_box").addClass("col-0");
    }else{
        $("#messages_box").html("<center><h3>Select an user for chat..</h3></center>");
        $("#messages_users_container").css("width","300px");
    }


})

//Search user for chat
$(document).on("focus","#search_inp",function(){
    $("#left_content_container").css("transform","scaleY(0)");
})
$(document).on("blur","#search_inp",function(){
    $("#left_content_container").css("transform","scaleY(1)");
})

$(document).on("keyup","#search_inp",function(){
    var src_txt=$(this).val();
    if (src_txt=="") {
        $("#search_close_btn").css("display","none");
        $("#search_btn").css("display","block");
        $("#left_content_container").css("transform","scaleY(1)");
        $("#left_container_for_search_user").css("transform","scaleY(0)");
    }else{
        $("#search_close_btn").css("display","block");
        $("#search_btn").css("display","none");
        $("#left_content_container").css("transform","scaleY(0)");
        $("#left_container_for_search_user").css("transform","scaleY(1)");
    }
    $("#search_close_btn").click(function(){
        $("#search_close_btn").css("display","none");
        $("#search_btn").css("display","block");
        $("#left_container_for_search_user").css("transform","scaleY(0)");
        $("#search_inp").val("");
    })
    
    $.ajax({
        url:"php_core/function.php",
        type:"POST",
        data:{this_is_for_search_user_for_chat:"this is for search user for chat",search_val:src_txt},
        success:function(response){
            $("#left_container_for_search_user").html(response);
        }
    })
})

var width=$(window).width();
if (width <= 510) {
    $("#messages_users_container").addClass("col-12");
    $("#messages_users_container").css("width","100%");
    $("#messages_box").css("display","none");
    $("#messages_box").addClass("col-0");
}else{
    console.log("desktop");
}
function show_single_post(id){
    var pst_id=id;
    $.ajax({
        url:"php_core/function.php",
        type:"POST",
        data:{this_is_for_show_single_post:"this is for show single_post",pst_id:pst_id},
        success:function(response){
            $(".single_post_container").html(response);
        }
    })
}
if ($(".single_post_container")) {
    var single_pst_id=$(".single_post_container").data("id");
    show_single_post(single_pst_id);
}


//Get group members
$(document).on("click","#members",function(){
    var id=$(this).data("id");
    group_members(id);
})

//Get group member requests
$(document).on("click","#member_reqst_btn",function(){
    var id=$(this).data("id");
    $.ajax({
        url:"php_core/function.php",
        type:"POST",
        data:{thi_is_for_group_member_requests:"this is for group member requests",id:id},
        success:function(response){
            $("#group_member_requests_container").html(response);
            console.log(response);
        }
    })
})

//Group show group admin
$(document).on("click","#group_admin_toggler_icon",function(){
    $("#group_admin_toggler_icon").css("display","none");
    $("#group_admin_toggler_close_icon").css("display","block");
    $(".admin_panel").css("transform","translatex(0px)");
    $(".admin_panel").css("opacity","1");
})

$(document).on("click","#group_admin_toggler_close_icon",function(){
    $("#group_admin_toggler_close_icon").css("display","none");
    $("#group_admin_toggler_icon").css("display","block");
    $(".admin_panel").css("transform","translatex(-300px)");
    $(".admin_panel").css("opacity","0");
})

//Edit post
$(document).on("click",".pst_edit_btn",function(){
    var id=$(this).data("id");
    $("#post_edit_box"+id).css("display","block");
})

$(document).on("click",".edit_container_close_btn",function(){
    var id=$(this).data("id");
    $("#post_edit_box"+id).css("display","none");
})
$(document).on("submit","#post_edit_form",function(){
    var form_data=new FormData(this);
    $.ajax({
        url:"php_core/edit_post.php",
        type:"POST",
        data:form_data,
        contentType:false,
        processData:false,
        success:function(response){
            if (response == "Edited") {
                alert(response);
                get_timeline_post();
                $("#post_edit_box"+id).css("display","none");
            }else{
                alert(response);
            }
        }
    })
})

//delete post
$(document).on("click",".pst_delete_btn",function(){
    var id=$(this).data("id");
    if (confirm("Are you sure to delete this post.!")) {
        $.ajax({
            url:"php_core/function.php",
            type:"POST",
            data:{this_is_for_delete_post:"this is for delete post",id:id},
            success:function(response){
                get_timeline_post();
            }
        })
    }else{
        return false;
    }
})





function scrollToBottom() {
     $("#chat_area").animate({ scrollTop: $("#chat_area")[0].scrollHeight }, 1000);
}









})