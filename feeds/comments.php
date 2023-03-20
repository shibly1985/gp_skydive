<?php
    $canBan=$db->permission(PER_BAN_USER);
    $canDelete=$db->permission(PER_COMMENT_DELETE);
    $wallPostType='c';
?>
<div class="col-md-3">
    <audio id="audionotification" src="audio/new_comment.mp3" preload="auto"></audio>
    <script type="text/javascript">var wallPostType='c';</script>
    <?php
        include("feeds/wall_post_in_post_activity.php");   
        include("feeds/wall_post_life_time_activity.php");
    ?>
</div>
<div class="col-md-6">
    <div class="x_panel wall_post">
        <div class="x_title">
            <h2 id="wallPostTitle">Comment</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content" style="border-bottom:1px solid #ddd;">
            <div class="post">
                <div class="post-content">
                    <div class="post_story clearfix">
                        <div class="provider_status_text" id="provider_status_text" style="float: left;width:49%;">
                            <img class="status_image photo" src="" alt="" style="display: none;" id="status_image"/>
                            <div id="status_text">..<img src="images/emo/smile.png"> ..</div>
                        </div>
                        <div class="provider_status_text" style="float: left;width: 49%;" id="commentFromFb"></div>
                    </div>
                </div>
                <a class="show_full_post" onclick="wholeThreadView();" href="javascript:void(0); "><span>Click for Whole Thread</span></a>                    
                <div id="message_show_box"><?php show_msg();?></div>
                <div class="comment_container">                      
                    <div class="comment" id="main_comment"></div>                      
                </div>
                <div class="check_service_area">
                    <div class="emoticon">
                        <a onclick="useImo(':)')"><i class="fa fa-smile-o" aria-hidden="true"></i></a>
                        <a onclick="useImo('(y)')"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
                        <!--<a onclick="useImo('<3')"><i class="fa fa-heart" aria-hidden="true"></i></a>-->
                        <a onclick="useImo(':(')"><i class="fa fa-meh-o" aria-hidden="true"></i></a>
                    </div>
                    <div class="dropdown">
                        <?php
                            $groups=$db->allGroups('and ugID!='.UGID);

                        ?>
                        <button class="btn btn-default dropdown-toggle" type="button" id="menu2" data-toggle="dropdown">
                            Transfer
                        </button>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu2">
                            <li><a href="javascript:void();" onclick="commentTransferPopUp()">Custom</a></li>
                            <?php
                                if(!empty($groups)){
                                    foreach($groups as $g){
                                    ?><li><a href="javascript:void();" onclick="commentPostTransfer('<?php echo $g['ugID'];?>')"><?php echo $g['ugTitle'];?></a></li><?php
                                    }

                                }
                            ?>
                        </ul>
                        <?php
                        ?>
                    </div>
                    <div class="emoticon">
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <a data-toggle="modal" data-target=".attatchmentModal" id="traialClick">
                            <i class="fa fa-picture-o" aria-hidden="true"></i></a>
                        &nbsp;&nbsp;&nbsp;
                    </div>
                    <div class="close_service">
                        <input type="checkbox" id="action_like" value="1"><label for="action_like">Like</label>
                        <input type="checkbox" id="action_hide" value="1"><label for="action_hide">Hide</label>
                        <?php
                            if($canBan==true){
                            ?>
                            <input type="checkbox" id="action_ban" value="1"><label for="action_ban">Ban</label>   
                            <?php
                            }
                        ?>
                        <input type="checkbox" id="priv_rep" value="1"><label for="priv_rep" title="Private Reply">Priv. Reply</label>    
                    </div>
                </div>
            </div>
            <input type="hidden" id="post_id" value="">
            <input type="hidden" id="comment_id" value="">
            <input type="hidden" id="sender_id" value="">
            <div style="display: none;" id="sender_name"><span></span></div>
            <textarea id="msgData" class="form-control" placeholder="Write your message" onkeypress="return commentReplyType(event);" name="chatText" type="text"></textarea>

            <div class="check_service_area" style="display: block; border:none; box-shadow: none;float: left;">
                <div class="check_service" style="margin-left:0;">
                    <?php
                        $check=intval($db->settingsValue('commentsInclude'));
                    ?>
                    <script type="text/javascript"><?php echo "var includCheckd=".$check;?></script>
                    <div style="display: none;">
                        <input id="include0" type="radio" name="wallPostInclude" value="0" data-value="" <?php echo $general->checked(0,$check);?>>
                        <label for="include0"  title="None"> N</label>
                        <input id="include1" type="radio" name="wallPostInclude" value="1" data-value="" <?php echo $general->checked(1,$check);?>>
                        <label for="include1" title="Customer"> C.</label>
                        <input id="include2" type="radio" name="wallPostInclude" value="2" data-value="<?php echo $userData['uDisplayName'];?>" <?php echo $general->checked(2,$check);?>>
                        <label for="include2" title="Me"> M</label>
                        <input id="include3" type="radio" name="wallPostInclude" value="3" <?php echo $general->checked(3,$check);?>>
                        <label for="include3" title="Include Both">B</label>
                    </div>
                </div>
                <div class="close_service">

                    <a href="javascript:void();" onclick="postCommentLike()"><i class="fa fa-thumbs-up" aria-hidden="true"></i> Like</a>
                    <a href="javascript:void();" onclick="wallPostHideDoneInit('d')"><i class="fa fa-check" aria-hidden="true"></i> Done</a>
                    <a href="javascript:void();" onclick="wallPostHideDoneInit('h')"><i class="fa fa-check" aria-hidden="true"></i> Hide + Done</a>
                    <?php
                        if($canDelete==true){
                        ?>
                        <a href="javascript:void();" onclick="removePostComment()"><i class="fa fa-times" aria-hidden="true"></i> Delete</a>
                        <?php
                        }

                        if($canBan==true){
                        ?>
                        <a href="javascript:void();" onclick="banUser()"><i class="fa fa-ban" aria-hidden="true"></i> Ban</a>
                        <?php
                        }
                    ?>
                    <span id="actionWorking" style="display: none;"></span>
                    <?php
                        if($canDelete==true){
                        ?>
                        <input type="checkbox" id="priv_rep_delete" value="1">
                        <label for="priv_rep_delete">Priv. Reply & delete</label>    
                        <?php
                        }
                    ?>
                </div>
            </div>
            <div class="close_service" id="wallPostCloseCall">
                <input type="checkbox" id="wallPostClose" value="send">
                <label for="wallPostClose" title="Save &amp; Colse">S &amp; C</label>
            </div>
            <div class="close_service" id="wallPostCloseStart" style="display: none;">
                <a href="javascript:void();" onclick="wallPostStart()">Start Again</a>
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
<!-- /modals -->
<script type="">
    $(document).ready(function(){
        $('#status_text').html('<button type="button" class="btn btn-success btn-primary" onclick="newWallPostCommentLoad();">Start Service</button>');
    });
</script>
<div style="display: none;">
    <a data-toggle="modal" id="model_show" data-target=".commonModal"><span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span></a>
    <div id="wrapup_lists">
        <ul class="check_service modal_checkbox" style="height: 300px;overflow-y: auto;overflow-x:hidden;">
            <li><h2>Wrapup</h2></li>
            <li>
                <div class="input-group modal_number">
                    <span class="input-group-btn">
                        <div class="btn btn-default">Text</div>
                    </span>
                    <label><input type="text" id="wrapupSearch" class="form-control" onkeyup="wrapupSearch()"></label>
                </div>
            </li>
            <li>
                <div class="input-group modal_number">
                    <span class="input-group-btn">
                        <div class="btn btn-default">Number</div>
                    </span>
                    <label class="wrapUpNumberLabel"><input type="text" class="wrapUpNumber form-control" onkeyup="return commentReplyWrapupType(event)"></label>
                </div>
            </li>

            <li>
                <div class="panel-group" id="accordion">
                    <?php
                        $i=1;
                        $w_category= $db->selectAll($general->table(34),' WHERE isActive=1');

                        foreach($w_category as $wc){
                        ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $i?>" <?php /*echo $i==1?'aria-expanded="true"':'';*/?>>
                                        <?php echo $wc['wcTitle'] ?> <i class="indicator glyphicon glyphicon-chevron-down  pull-right"></i>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse_<?php echo $i?>" class="panel-collapse collapse <?php /*echo $i==1?'in':'';*/?>" <?php /*echo $i==1?'aria-expanded="true"':'';*/?>>
                                <div class="panel-body">
                                    <ul>
                                        <?php
                                            $category = $db->selectAll($general->table(11),'where isActive=1 and wcID='.$wc['wcID']);
                                            foreach($category as $w){
                                            ?>
                                            <li class="wrapupBody" id="wrapupBody<?php echo $w['wuID'];?>">
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
            </li>
        </ul>
    </div>
    <div id="Scentiment_list">        
        <ul class="check_service modal_checkbox">
            <li><h2>Scentiment</h2></li>
            <li>
                <div class="input-group modal_number">
                    <span class="input-group-btn">
                        <div class="btn btn-default">Number</div>
                    </span>
                    <label><input type="text" class="ScentimentTypeNumber form-control" onkeypress="return commentReplyScentiment(event)"></label>
                </div>
            </li>
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
        </ul>
    </div>
    <div id="whole_thread">
        <div class="post-content">
            <div class="post_story clearfix">
                <div class="provider_status_text">
                    <img src="" alt="" style="" class="whole_status_image">
                    <div class="whole_status_text"></div>
                </div>
            </div>
        </div>
        <a class="show_full_post" href="javascript:void(0); "><br></a>
        <div class="comment_container">                      
            <div class="comment whole_main_comment"></div>                                          
            <!--<div id="wholwThreadLoadMore" style="display: none"><a onclick="wholeThreadView()" id="wholwThreadLoadMoreA" href="javascript:void();">Load More</a></div>-->
        </div>
    </div>
    <div id="visitor_comment">
        <div class="comment_inner_wrapper">
            <div class="comment_header">
                <img src="images/man_icon2.png" class="post_profile_picture">
            </div>
            <div class="comment_body">
                <div class="comment_text">
                    <span class="evac_user sender_id"><span></span></span>
                    <span class="message"></span>
                    <img class="status_image photo" src="" alt="" style="display: none;"/>
                    <!--<img src="" alt="" style="display: none;" class="photo"/>-->
                </div>
                <div class="comment_social clearfix">
                    <div class="comment_actions">
                        <div class="action with_bull comment_time_from_now"><a href="" class="time" target="_blank"></a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="commentTransferInit">
        <div class="col-md-6">
            <div class="x_panel assignment">
                <?php 
                    $i=0;
                    $group=$db->allGroups();
                    foreach($group as $g){
                    ?>
                    <div class="assignment_checkbox">
                        <input data-input="<?php echo $g['ugID']; ?>" class="check_group_<?php echo $g['ugID']; ?>" type="checkbox" onclick="group_member(<?=$g['ugID']?>)" name="check_group[]" value="select">
                        <label data-label="<?php echo $g['ugID'];?>" for="check_group_<?php echo $g['ugID']; ?>">
                            <div class="comment_inner_wrapper">
                                <div class="comment_body">
                                    <div class="comment_text"><?php echo $g['ugTitle']; ?></div>
                                </div>
                            </div>
                        </label>
                    </div>
                    <?php
                    }
                ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="x_panel assignment">
                <div class="x_title">
                    <div class="assignment_checkbox">
                        <input id="title3" class="forAllmember" type="checkbox" onclick="checkMember(this.checked);" name="check_all_user" value="send">
                        <label for="title3">Users</label>
                    </div>
                </div>
                <div class="x_content group_member"> </div>
            </div>
        </div>
    </div>
</div>
<?php
    include("feeds/modal.php");
?>
<script type="">
    function group_member(ugID){
        var searchString='?ajax=1&assignmentGroupMember=1&ug_id='+ugID;
        jx.load(searchString,function(data){
            data = jQuery.parseJSON(data);
            $('.forAllmember').prop('checked',false);
            $('.checkMember').prop('checked',false);
            $(data.user_data).each(function(i,d){
                if($('#modelBody .check_group_'+ugID).is(":checked")){
                    var users = '<div id="members_'+d.id+'" class="assignment_checkbox"><input class="checkMember" id="checkMember_'+d.id+'" type="checkbox" name="check_user[]" value="'+d.id+'"><label for="checkMember_'+d.id+'"><div class="comment_inner_wrapper"><div class="comment_body"><div class="comment_text">'+d.name+'</div></div></div></label></div>';
                    $("#modelBody .group_member").append(users);
                }else {
                    $('#members_'+d.id).remove();
                }
            });

        }); 

    }
    function checkMember(status){
        if(status==true){
            $('.checkMember').prop('checked',true);
        }
        else{
            $('.checkMember').prop('checked',false);
        }
    }
</script>