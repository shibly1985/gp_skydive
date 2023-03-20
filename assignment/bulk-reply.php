<?php
    if(isset($_GET['type'])){
        if($_GET['type']=='c'){include 'bulk-reply-comment.php';}
        else if($_GET['type']=='w'){include 'bulk-reply-wall.php';}
    }
    else{
    ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo $rModule['cmTitle'];?></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content dashboard_design">
                <?php show_msg();?>
                <div class="row"> 
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <a href="<?php echo $pUrl;?>&type=c">
                            <div class="icon"><img src="images/user_2.png" alt=""></div>
                            <h3>Comment</h3>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <a href="<?php echo $pUrl;?>&type=w">
                            <div class="icon"><img src="images/request_icon.png" alt=""></div>
                            <h3>Wall Post</h3>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    }
?>