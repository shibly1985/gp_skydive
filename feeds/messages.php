<div class="col-md-3 wall_left_sidebar">
    <audio id="audionotification" src="audio/new_comment.mp3" preload="auto"></audio>
    <audio id="msgnotification" src="audio/new_message.mp3" preload="auto"></audio>
    <script type="text/javascript">var wallPostType='m';</script>
    <?php
        //include("feeds/wall_post_in_post_activity.php");   
        include("feeds/messageLeftPanel.php");
    ?>
</div>
<div class="col-md-6">
    <div class="x_panel wall_post">
        <div class="x_title">
            <h2 id="wallPostTitle">Messages</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div id="message_show_box"></div>
            <div class="post">
                <div class="comment_container">                      
                    <div class="comment" id="main_comment"></div>
                </div>
                <div class="check_service_area">
                    <div class="check_service">
                        <div class="emoticon">
                            <a onclick="useImo(':)')"><i class="fa fa-smile-o" aria-hidden="true"></i></a>
                            <a onclick="useImo('(y)')"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
                            <a onclick="useImo('<3')"><i class="fa fa-heart" aria-hidden="true"></i></a>
                            <a onclick="useImo(':(')"><i class="fa fa-meh-o" aria-hidden="true"></i></a>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-default dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">
                                <span id="wallPostActionText">Action</span>
                            </button>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                <li><a href="javascript:void();" onclick="messageDone()"><i class="fa fa-check" aria-hidden="true"></i>Done</a></li>
                            </ul>
                        </div>
                        <div class="emoticon">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a data-toggle="modal" data-target=".attatchmentModal" id="traialClick">
                                <i class="fa fa-picture-o" aria-hidden="true"></i></a>
                            &nbsp;&nbsp;&nbsp;
                        </div>
                        <div class="close_service">&nbsp;&nbsp;<span id="messageCharacterCount">0</span>&nbsp;&nbsp;</div>
                        <div class="close_service" id="wallPostCloseCall">
                            <input type="radio" name="sendtype" id="wallPostClose" value="1">
                            <label for="wallPostClose">Close</label>
                        </div>
                        <div class="close_service" id="messageSendContinueCall">
                            <input type="radio" name="sendtype" id="messageSendContinue" value="2" checked="checked">
                            <label for="messageSendContinue">Continue</label>
                        </div>
                        <div class="close_service" id="messageSendWrapupCall">
                            <input type="radio" name="sendtype" id="messageSendWrapup" value="3">
                            <label for="messageSendWrapup">Wrap-up</label>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="mid" value="">
                <input type="hidden" id="sender_id" value="">
                <div style="display: none;" id="sender_name"><span></span></div>
                <textarea id="msgData" class="form-control messa_post" placeholder="Write your message" onkeypress="return messageReplyType(event);" name="chatText" type="text"></textarea><span id="messageSendignShow" style="display: none;">Sending</span>

                <a href="javascript:void();" onclick="messageReplyInit()" class="Send">Send</a>

            </div>
        </div>
    </div>
</div>
<?php
    include("feeds/wall_post_quick_response.php");
?>

<div class="modal fade bs-example-modal-md commonModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="modelTitle"></h4>
            </div>
            <div class="modal-body">
                <div class="post" id="modelBody">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="modelCancle">Close</button>
                <button type="button" class="btn btn-primary" id="modelSuccess">Save changes</button>
            </div>

        </div>
    </div>
</div>
<div style="display: none;">
    <a data-toggle="modal" id="model_show" data-target=".commonModal"><span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span></a>
    <div id="wrapup_lists">
        <ul class="check_service modal_checkbox" style="height: 300px;overflow-y: auto;overflow-x:hidden;">
            <li><h2>Wrapup</h2></li>
            <li>
                <div class="input-group modal_number">
                    <span class="input-group-btn">
                        <div class="btn btn-default">Number</div>
                    </span>
                    <label class="wrapUpNumberLabel"><input type="text" class="wrapUpNumber form-control" onkeypress="return messageReplyWrapupType(event)"></label>
                </div>
            </li>
            <div class="panel-group" id="accordion">
                <?php
                    $i=1;
                    $w_category= $db->selectAll($general->table(34),' WHERE isActive=1');

                    foreach($w_category as $wc){
                    ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $i?>">
                                    <?php echo $wc['wcTitle'] ?> <i class="indicator glyphicon glyphicon-chevron-down  pull-right"></i>
                                </a>
                            </h4>
                        </div>
                        <div id="collapse_<?php echo $i?>" class="panel-collapse collapse">
                            <div class="panel-body">
                                <ul>
                                    <?php
                                        $category = $db->selectAll($general->table(11),'where isActive=1 and wcID='.$wc['wcID']);
                                        foreach($category as $w){
                                        ?>
                                        <li>
                                            <input id="option<?=$w['wuID']?>" type="radio" name="wrapupType" value="<?php echo $w['wuID'];?>">
                                            <label for="option<?=$w['wuID']?>"><?php echo $w['wuID'];?> . <?php echo $w['wuTitle'];?></label>
                                        </li>
                                        <?php
                                        }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <?php
                        $i++;
                    }
                ?>

            </div>
        </ul>
    </div>
    <div id="Scentiment_list">        
        <ul class="check_service modal_checkbox">
            <li><h2>Scentiment</h2></li>
            <li>
                <input id="positive" type="radio" name="ScentimentType" value="<?php echo SCENTIMENT_TYPE_POSITIVE ?>">
                <label for="positive">&nbsp;&nbsp;<span><?php echo SCENTIMENT_TYPE_POSITIVE ?>. </span>Positive </label>
            </li>
            <li>
                <input id="nutral" type="radio" name="ScentimentType" value="<?php echo SCENTIMENT_TYPE_NUTRAL ?>">
                <label for="nutral">&nbsp;&nbsp;<span><?php echo SCENTIMENT_TYPE_NUTRAL ?>. </span>Nutral </label>
            </li>
            <li>
                <input id="negetive" type="radio" name="ScentimentType" value="<?php echo SCENTIMENT_TYPE_NEGETIVE ?>">
                <label for="negetive">&nbsp;&nbsp;<span><?php echo SCENTIMENT_TYPE_NEGETIVE ?>. </span>Negetive </label>
            </li>
            <li>
                <div class="input-group modal_number">
                    <span class="input-group-btn">
                        <div class="btn btn-default">Number</div>
                    </span>
                    <label><input type="text" class="ScentimentTypeNumber form-control" onkeypress="return messageReplyScentiment(event)"></label>
                </div>
            </li>
        </ul>
    </div>
    <div id="visitor_comment">
        <div class="comment_inner_wrapper">
            <div class="comment_header">
                <img src="images/man_icon2.png" class="post_profile_picture">
            </div>
            <div class="comment_body">
                <div class="comment_text">
                    <span class="evac_user sender_id"></span>
                    <span class="message"></span>
                    <img class="status_image photo" src="" alt="" style="display: none;"/>
                    <div class="multi_image"></div>
                </div>
                <div class="comment_social clearfix">
                    <div class="comment_actions">
                        <div class="action with_bull comment_time_from_now"><a href="" class="time" target="_blank"></a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    include("feeds/modal.php");
?>