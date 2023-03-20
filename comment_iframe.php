<!doctype html>
<html>
    <body>
        <?php
            /*
            ?>
            <!-- Load Facebook SDK for JavaScript -->
            <div id="fb-root"></div>
            <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.6";
            fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>


            <div style="min-height: 300px;margin-top: -70px;">
            <?php
            if(isset($_GET['postUrl'])){
            ?>
            <div class="fb-post" data-href="<?php echo @$_GET['link'];?>" data-width="500" data-show-text="true"></div>
            <?php
            }
            else{
            ?><div class="fb-comment-embed" data-href="<?php echo @$_GET['link'];?>" data-width="270" data-include-parent="false"></div><?php
            }
            ?>
            */
        ?>
        <iframe src="https://www.facebook.com/plugins/comment_embed.php?href=https%3A%2F%2Fwww.facebook.com%2FGrameenphone%2Fposts%2F1647070878641693%3A0%3Fcomment_id%3D1224878134270585%26reply_comment_id%3D1203262156394123%26include_parent%3Dfalse" width="270" height="300" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
        <!--<iframe src="https://www.facebook.com/plugins/comment_embed.php?href=https%3A%2F%2Fwww.facebook.com%2Fskydive.system%2Fposts%2F302489516814745%3Fcomment_id%3D302565853473778%26reply_comment_id%3D302574760139554&include_parent=false" width="560" height="120" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>-->
        <!--<iframe src="<?php echo $_GET['link'];?>&include_parent=false" width="270" height="300" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>-->
        </div>
    </body>

</html>