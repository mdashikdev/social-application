<?php

include("php_core/db.php");
session_start();
if (isset($_SESSION['user_id'])) {
  $current_user=$_SESSION['user_id'];
}else{
  header("location:index.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>My Social</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="style.css">
  <link rel="stylesheet" type="text/css" href="new_style.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <style type="text/css">
    body{
      background: #F0F2F5;
    }
    #msg{
      height: 600px;
      background: white;
    }
    .search_user_for_msg{
      position: absolute;
      width: 93%;
      background: white;
      transform: scale(0);
      transition: 0.5s cubic-bezier(.68,-0.55,.27,1.55);
      transform-origin: top;
      z-index: 2;
    }
    .header_actv_status,#header_msgs{
      width: 100%;
      height: fit-content;
      border-bottom: 1px solid #0d6efd59;
    }
    #spinner{
      display: none;
      color: #f96106 !important;
      width: 100%;
      height: 100%;
      margin-left: 40%;
      margin-top: 40%;
      font-size: 4px;
      width: 70px;
      height: 70px;
    }
    #user_box{
      width: 100%;
    }
    #src_usr_for_msg{
      border-bottom: 1px solid #d5e9ff91;
    }
    .typing_gif{
        position: absolute;
        bottom: 50px;
        border-radius: 22px;
        left: 15px;
        transform: scale(0);
        transition: 0.3s cubic-bezier(.86,0,.07,1);
        transform-origin: left;
    }
    a{
      text-decoration: none;
      color: black;
    }
    a:hover{
      text-decoration: none;
    }
    img{
      cursor: pointer;
    }
    .usr_for_frd_rqst_box{
      justify-content: space-between;
      align-items: center;
    }
    #profile_page{
      background: white;
    }
    .profile_img_div{
      width: fit-content;
      height: fit-content;
      position: relative;
      border-radius: 30px;
      margin-top: -30px;
      object-fit: cover;
    }
    #pro_img{
      width: 120px;
      height: 120px;
    }
    #profile_name_div{
      display: grid;
    }
  .profile_name{
      font-size: 30px;
      font-weight: bold;
    }
  .cover_change_input_div{
    display: none;
    position: absolute;
    width: 260px;
    z-index: 3;
    top: 50%;
    left: 45%;
    background: white;
    padding-bottom: 14px;
    border-radius: 4px;
    }
    #profile_photo_form{
    display: none;
    position: absolute;
    width: 260px;
    height: fit-content;
    z-index: 3;
    top: 50%;
    left: 45%;
    background: white;
    padding-bottom: 14px;
    border-radius: 4px;
    }
  .profile_header_container{
      height: fit-content;
      display: grid;
      grid-template-columns:1.2fr 1fr;
      justify-items: start;
    }
    #chat_box_users_wrapper{
    padding: 0px;
    overflow-y: scroll;
    max-height: 500px;
    min-height: 450px;
}
.cover_icon{
    position: absolute;
    right: 1%;
    bottom: 1%;
    width: fit-content;
    height: fit-content;
}
#profile_update{;
    background: white;
}
#pro_img{
  width: 150px;
  height: 150px;
}


.icons_container{
  position: absolute;
  width: fit-content;
  box-shadow: 2px 2px 17px #cccccca1;
  border-radius: 27px 30px 31px 0px;
  margin-top: -20px;
  z-index: 4;
  background: white;
  transition: 0.3s cubic-bezier(.68,-0.55,.27,1.55);
  transform: scaleY(0);
  transform-origin: bottom;
  margin-left: 8px;
  animation: container_with_ani 0.3s cubic-bezier(.68,-0.55,.27,1.55);
}
.icon_all{
  border-radius: 30px;
  transition: 0.2s ease-in-out;
  width: 20px;
  height: 20px;
}
.icon_animation{
  position: relative;
  width: 40px;
  height: 40px;

}
.post_footer_btn{
  background: #ffffff0a;
  width: fit-content;
  border-radius: 5px;
  border:none;
  padding-bottom: 4px;
}
 #dropdownMenuButton1{
  background: white;
  margin-left: 35px;
  border: none;
 }
#like_count_and_other_container{
  font-size: 11px;
  font-weight: 200;
  padding: 3px;
}
.reactors_showBtn{
  cursor: pointer;
}
.reactor_user_show_box,.comment_section{
  height: fit-content;
  background: white;
  position: absolute;
  z-index: 4;
  border-radius: 5px;
  box-shadow: 2px 2px 45px #ccc;
  padding: 7px;
  margin-top: -140px;
  transition: 0.3s cubic-bezier(.68,-0.55,.27,1.55);
  transform: scaleY(0);
  transform-origin: bottom;
}
.for_reactor{
  font-size: 10px;
}
.msnger_header_search{
  padding: 0px;
}
#user_box{
  width: 100%;
  display: flex;
  overflow: auto;
  max-height: 52px;
}
.active_icon_create{
  background: #00d700f2;
  width: 15px;
  position: absolute;
  height: 15px;
  border-radius: 10px;
  bottom: -2px;
  right: -2px;
  border: 3px solid white;
}
#messages_users_container{
  transition: 0.5s ease-in-out;
}
.header_container_for_mobile{
  display: none;
}
.main_container{
  display: grid;
  justify-content: center;
  grid-template-columns: 1fr 2fr 1fr;
}
.pst_cntnt{
  width: 100%;
  border: none;
  background: rgb(225 236 237 / 29%);
  border-radius: 6px;
  height: 50px;
  resize: none;
  transition: 0.3s ease-in;
}
.pst_img{
  width: 100%;
  height: 25px;
  background: #f7f7f7;
  border-radius: 5px;
  padding: 5px;
  font-size: 9px;
}
.post_btn{
  width: 20%;
  padding: 4px;
  margin-top: 5px;
  background: linear-gradient(175deg, #5bf7aa, #279bf7);
  border: none;
  border-radius: 11px;
  color: white;
  font-weight: bold;
}
.header_container_for_mobile{
  background: white;
  box-shadow: 5px 5px 14px #dfdfdfba;
  padding: 8px;
}
.pst_cntnt:focus{
  height: 70px;
  border: solid thin white;
  background: rgb(242 242 242 / 62%);
}
#header_for_desktop{
  box-shadow: 3px 3px 14px #d3d3d3ba;
}
.user_btn_box{
  display: grid;
  grid-template-columns: 1fr 1fr 1fr 1fr;
  grid-gap: 4px;
}
.rounded-circle {
    object-fit: cover;
}
.any_usr_container{
  display: grid;
  grid-template-columns: 1fr 2fr 1fr;
}
.any_usr_center{
  padding: 10px;
  background: white;
}
.any_usr_left{
  margin-top: 5px;
}
.user_btn_bo{
  display:grid;
  grid-template-rows:1fr;
  justify-self: end;
  align-items: end;
}
.profile_header_wrapper{
  background: white;
  border-bottom: solid thin #e3e3e3;
}
#profile_container{
  background: #F0F2F5;
}
.profile_post_section{
  display: grid;
  grid-template-columns: 1fr 2fr 1fr;
}
.frd_timeline_container{
  display: grid;
  grid-template-columns: 2fr 1fr;
}
.post_box,.info_sec,.posts_container,.post_create_box{
  background: white;
  margin: 5px;
  box-shadow: 1px 1px 2px #ccc;
  border-radius: 8px;
  padding: 10px;
}
#header_for_desktop{
  background: white;
}
.img-thumbnail{
  border: none;
}
.nav{
  display: grid;
  grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr;
  justify-items: center;
}
.nav-item{
  width: 100%;
  text-align: center;
}
.nav-link{
  transition: 0.2s ease-in;
}
.nav-link:hover{
  background: #e8e8e8ad;
  border-radius: 15px;  
}
.nav-pills .nav-link.active, .nav-pills .show > .nav-link{
  background: none;
  color: black;
  font-weight: bold;
  border-bottom:solid thin #0389d9 ;
  border-radius: 0px;
}
#notify_drpdwn{
  display: flex;
  margin: auto;
  transition: 0.2s ease-in;
}
#notify_drpdwn:hover{
  background: #e8e8e8ad;
  border-radius: 15px; 
}
#header_for_desktop{
  display: grid;
  grid-template-columns: .5fr 2fr 0.3fr;
  justify-items: center;
  align-items: center;
}
.header_righ{
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: space-around;
  align-items: center;
}
#search_box_container{
  position: absolute;
  box-shadow: 1px 1px 2px #ccc;
  width: fit-content;
  padding: 10px;
  background: white;
  left: 1%;
  min-width: 250px;
  border-radius: 5px;
  transition: .4s cubic-bezier(.86,0,.07,1);
  transform: translatex(-270px);
  opacity: 0;
  max-height: 500px;
  overflow: auto;
  z-index: 4;
}
.invite_section_wrapper{
  width: 50%;
  background: white;
  padding: 10px;
  position: absolute;
  z-index: 3;
  transform: translate(50%, 100%);
  box-shadow: 1px 1px 1px #6a6a6a78;
  border-radius: 9px;
  display: none;
}
.invite_section_wrapper .search{
  margin-bottom: 7px;
}
.invite_section_wrapper .users_section{
  border-top: 1px solid #ccc;
  background: white;
  margin: 5px;
  border-radius: 8px;
  padding: 10px;
}
.invite_section_wrapper input{
  width: 100%;
  border: solid thin #cccccc47;
  padding: 5px;
  border-radius: 7px;
  outline: none;
}
.invite_section_wrapper input:focus{
  outline: 2px solid #2e9fe733;
}
.post_edit_box{
  width: fit-content;
  margin: auto;
  position: absolute;
  z-index: 3;
  display: none;
}









@keyframes icon_animate {
  from{
    width: 25px;
    height: 25px;
  }
  to{
    width: 35px;
    height: 35px;
  }
}
@media screen and (max-width: 730px) {
  .profile_header_container{
    display: grid;
    grid-template-columns: 1fr;
  }
}
@media screen and (max-width: 580px) {
  .main_container{
    grid-template-columns: 1fr;
  }
  .any_usr_container{
    grid-template-columns: 1fr;
  }
  .user_btn_bo{
    justify-self: start;
  }
  #timeline_header_left{
    width: 85%;
  }
  .profile_name{
    font-size: 15px;
    text-align: left;
    margin-left: 4px;
  }
  #profile_name_div p{
    font-size: 12px;
  }
  .post_create_box{
    box-shadow: 1px 1px 4px #ccc;
  }
  #timeline_header_right{
    width: 15%;
  }
  #pro_img{
    width: 80px;
    height: 80px;
    object-fit: cover;
    border: 3px solid white;
  }

  #msg{
    top: 27%;
    width: 98%;
  }
  strong{
    font-size: 12px;
  }
  .header_container_for_mobile{
    display: block;
  }
 }
#reacts_count_user_box{
  overflow-y: auto;
}
#reacts_count_user_box::-webkit-scrollbar {
    width: 0px;
    height: 0px;
}
 @media screen and (max-width: 510px) {
  .user_btn_box{
    grid-template-columns: 1fr 1fr;
  }

  #left_content_container{
    width: 100%;
  }
  #messages_box{
    width: 0px;
  }
 } 
  @media screen and (max-width: 410px) {
  #icon_name_for_responsive{
    display: none;
  }

 @media screen and (max-width: 992px) {
  .comment_responsive{
    display: none;
  }
  #dropdownMenuButton1{
    margin-left: 10px;
  }
  .group_left_content{
    position: initial;
  }
 }


*::-webkit-scrollbar{
  width: 5px;
}
*::-webkit-scrollbar-track{
  background: #f1f1f1;
}
*::-webkit-scrollbar-thumb{
  border-radius: 15px;
  background: #0d6efd;
}



  </style>
</head>
<body>

