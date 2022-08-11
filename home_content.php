<div class="main_container">
    <div></div>
    <div>
        <div class="post_create_box">
            <h3>Create a post</h3>
            <form action="" id="post_form" onsubmit="return false">
                <textarea class="pst_cntnt" name="pst_cntnt" placeholder="Create a post..."></textarea>
                <input type="file" class="pst_img" name="pst_img">
                <img src="images/spinner.gif"  class="mb-2 post_spinner"  style="display:none" alt="">
                <div class="alert alert-success" style="display:none;margin-top:2%;" id="alert_for_post_create" role="alert"></div>
                <input type="submit"class="post_btn" value="Post">
            </form>
        </div>
        <div class="timeline_posts_container">
            <div class="posts_container" id="home_post_load_btn" style="cursor:pointer;"><i class="bi bi-arrow-clockwise"></i>Refresh Page</div>
            <div class="posts_container_for_timeline"></div>
        </div>
    </div>
    
</div>