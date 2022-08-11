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
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      background: white;
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
	  cursor: pointer;
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
	  border-radius: 8px;
	  width: 30px;
	  height: 30px;
	  font-size: 17px;
	  background: #fff0;
	  margin-left: 35px;
	 }
	#like_count_and_other_container{
	  border-bottom: 1px solid #cccccc5e;
	  font-size: 11px;
	  font-weight: 200;
	  padding: 3px;
	}
	.reactors_showBtn{
	  cursor: pointer;
	}
	.reactor_user_show_box,.comment_section{
	  width: 93%;
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
  </style>
</head>
<body>


<?php 
if (isset($_REQUEST["pst_code"])) {
	include("php_core/db.php");
	include("php_core/function_core.php");
	include("php_core/time.php");
	$id=$_REQUEST["pst_code"];
?>

<div class="col-2">
	<a href="home.php">
		<button class="btn btn btn-outline-primary" id="back_btn"><i class="bi bi-arrow-left"></i></button>
	</a>
</div>
<div class="single_post_container p-3 col-8 shadow-lg" data-id="<?php echo $id; ?>"></div>
<div class="col-2"></div>



<?php }else{
	echo "Invalid Url";
}






include("footer.php");

 ?>