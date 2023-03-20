<?php
    $type=$social->getAssignType('c'); 
    $flowType=$social->getFlowType('c');
    $canDelete=$db->permission(PER_COMMENT_DELETE);
    $canBan=$db->permission(PER_BAN_USER);
    $dr = date('d-m-Y 00:00:00',YESTERDAY_TIME).'';
    $dr2= date('d-m-Y 00:00:00').'';
    $date_range=$dr.'__'.$dr2;

    $dates=explode('__',$date_range);
    $from_date=strtotime($dates[0]);
    $to_date=strtotime($dates[1]);
    if(date('h:i',$to_date)=='12:00'){
        $to_date=strtotime('+23 hour',$to_date);
        $to_date=strtotime('+59 minute',$to_date);
    }
    $dateRangeVal=date('d-m-Y h:i A',$from_date).'__'.date('d-m-Y h:i A',$to_date);
    $dateRangeShow=date('d-m-Y h:i A',$from_date).' - '.date('d-m-Y h:i A',$to_date);
?>
<script type="text/javascript">var wallPostType='c';</script>
<div id="message_show_box"><?php show_msg();?></div>
<div id="manual_option">
    <div class="col-md-5">
        <div class="x_panel assignment">
            <div class="x_title">
                <h2>
                    <div class="assignment_checkbox">
                        <input id="title" type="checkbox" name="check_all_threads" onClick="check('check_threads[]','check_all_threads')" value="select">
                        <label for="title">Threads</label>
                    </div>
                </h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <select onchange="assignAction(this.value)" class="col-md-1 form-control" style="height:25px; padding:0px;" id="assignAction">
                            <option value="">Action</option>
                            <option value="3">Done</option>
                            <option value="6">Hide</option>
                            <?php
                                if($canBan==true){
                                ?>
                                <!--<option value="2">Ban</option>-->
                                <?php
                                }
                                if($canDelete==true){
                                ?>
                                <option value="1">Delete</option>
                                <?php
                                    if($canBan==true){
                                    ?>
                                    <option value="4">Ban+Delete</option>
                                    <?php
                                    }
                                }
                                if($canBan==true){
                                ?>
                                <!--<option value="5">Ban+Hide</option>-->
                                <?php
                                }
                            ?>
                        </select>
                    </li>
                </ul>

                <div class="clearfix"></div>
                <input class="form-control" style="height:25px; padding:0px 5px;" id="keyword" placeholder="Keyword" value="" type="text">
                <!--<input style="margin:5px 0" id="date_range" name="date_range" class="form-control" value="07-12-2016 12:00 AM__08-12-2016 11:59 PM" >-->
                <input type="hidden" id="date_range" name="date_range" value="<?php echo $dateRangeVal;?>">
                <div id="reportrange" class="pull-right sky_datepicker" >
                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                    <span><?php echo $dateRangeShow;?></span> <b class="caret"></b>
                </div>
                <button onclick="assignmentLoad();" class="btn btn-sm btn-info">Filter</button>
            </div>
            <div style="display: none;">
                <div id="commentBox">
                    <div class="assignment_checkbox commentParent bulk_reply_comment">
                        <div class="comment_inner_wrapper">
                            <input class="checkBoxInput" type="checkbox" name="check_threads[]" value="">
                            <label class="checkBoxLabel">
                                <div class="comment_header">
                                    <div class="userImg"></div>
                                    <span class="evac_user"></span>
                                </div>
                            </label>
                            <div class="comment_body">
                                <div class="comment_text">
                                    <p><span class="userMsg"></span></p>
                                    <a class="postTime" href="javascript:void();"></a>
                                </div>
                            </div>
                            <div class="comment_footer threadImg"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="ass_post" class="x_content">
                <?php
                    /*$rt=$social->assignmentData('c',50);
                    $names=$rt['names'];
                    $name_id=array();
                    foreach($names as $n){
                    $name_id[$n['id']]=$n['name'];
                    }
                    //$general->printArray($name_id);
                    $i=1;
                    $post_ids=array();
                    foreach($rt['comments'] as $c){
                    $post_ids[$c['post_id']]=$c['post_id'];
                    ?>
                    <div class="assignment_checkbox" id="ass_<?=$c['comment_id']?>">
                    <div class="comment_inner_wrapper">
                    <input id="check_<?php echo $i; ?>" data-id="<?php echo $c['sender_id']?>" type="checkbox" name="check_threads[]" value="<?php echo $c['comment_id'] ?>">
                    <label for="check_<?php echo $i++; ?>">
                    <div class="comment_header">
                    <img src="https://graph.facebook.com/<?php echo $c['sender_id']?>/picture?type=square" class="post_profile_picture"><span class="evac_user"><?php echo $name_id[$c['sender_id']];?></span>
                    </div>
                    </label>
                    <div class="comment_body">
                    <div class="comment_text">
                    <a href="javascript:void();" data-link="1" data-post_id="<?php echo $c['post_id'];?>" data-parent_id="<?php echo $c['parent_id'];?>" data-comment_id="<?php echo $c['comment_id'];?>" data-created_time="<?php echo $c['created_time'];?>">Time</a>

                    <!--<span><script>document.write(momentTime('<?php echo $c['created_time'];?>'));</script></span>-->
                    <p><span><?php echo $general->content_show($c['message']);?></span></p>
                    </div>
                    </div> 
                    <?php
                    if(!empty($c['photo'])){
                    ?>
                    <div class="comment_footer">
                    <img class="assing_comment_img" src="<?=$c['photo']?>">
                    </div>
                    <?php
                    }
                    ?>
                    </div>
                    </div>
                    <?php
                    }*/
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="x_panel assignment">
            <div class="x_title">
                <div class="assignment_checkbox">
                    Message
                </div>
            </div>
            <div class="x_content" style="border-bottom:1px solid #ddd;">
                <div class="post">
                    <div class="check_service_area">
                        <div class="emoticon">
                            <a onclick="useImo(':)')"><i class="fa fa-smile-o" aria-hidden="true"></i></a>
                            <a onclick="useImo('(y)')"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
                            <a onclick="useImo(':(')"><i class="fa fa-meh-o" aria-hidden="true"></i></a>
                        </div>
                        <div class="close_service">
                            <input type="checkbox" id="action_like" value="1">
                            <label for="action_like">Like</label>
                            <input type="checkbox" id="action_hide" value="1">
                            <label for="action_hide">Hide</label>
                            <input type="checkbox" id="action_ban" value="1">
                            <label for="action_ban">Ban</label>   
                            <input type="checkbox" id="priv_rep" value="1">
                            <label for="priv_rep" title="Private Reply">Priv. Reply</label>    
                        </div>
                    </div>
                </div>
                <input type="hidden" id="post_id" value="">
                <input type="hidden" id="comment_id" value="">
                <input type="hidden" id="sender_id" value="">
                <div style="display: none;" id="sender_name"><span></span></div>
                <textarea id="msgData" class="form-control" placeholder="Write your message" name="chatText" type="text"></textarea>

                <div class="check_service_area" style="display: block; border:none; box-shadow: none;float: left;">

                    <div class="close_service">
                        <input type="checkbox" id="priv_rep_delete" value="1">
                        <label for="priv_rep_delete">Priv. Reply & delete</label>    
                    </div>
                </div>
                <div class="col-md-11">
                    <div id="wrapup_lists">
                        <ul class="check_service modal_checkbox" style="height: 300px;overflow-y: auto;overflow-x:hidden;">
                            <li><h2>Wrapup</h2></li>
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
                                                            <li>
                                                                <input id="option<?=$w['wuID']?>" type="radio" name="wrapupType" value="<?php echo $w['wuID'];?>">
                                                                <label for="option<?=$w['wuID']?>"><?php echo $w['wuID'];?> . <?php echo $w['wuTitle'];?></label> </label>
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
                                <input type="submit" onclick="bulkSend()" class="btn btn-success pull-right" id="bulkSendBtn" value="Send">
                            </li>
                        </ul>
                    </div>
                </div>
                <div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function(){
                assignmentLoad();
                //        commentLinkCreate();
                $('#reportrange').daterangepicker({
                    timePicker: true,
                    opens: "right",
                    autoApply: true,
                    timePicker24Hour: true,
                    startDate: "<?php echo date('m/d/Y',$from_date);?>",
                    endDate: "<?php echo date('m/d/Y',$to_date);?>"
                    }, 
                    function(start, end) {
                        $('#reportrange span').html(start.format('DD-MM-YYYY hh:mm A') + ' - ' + end.format('DD-MM-YYYY hh:mm A'));
                        $('#date_range').val(start.format('DD-MM-YYYY HH:mm')+'__'+end.format('DD-MM-YYYY HH:mm'));
                });
            });
        </script>
        <script type="">
            $(document).ready(function(){
                $('input:radio[name="radioDate"]').change(function(){
                    //alert($(this).val());
                    if($(this).val() == 0){
                        $('#dateRangeArea').show(300);
                    }else{
                        $('#dateRangeArea').hide(300);
                    }
                });
            });
            function check(checkBoxesName,buttonName){
                var checkboxes = document.getElementsByName(checkBoxesName);
                var button = document.getElementsByName(buttonName);

                if(button.value == 'select'){
                    for (var i in checkboxes){
                        checkboxes[i].checked = 'FALSE';
                    }
                    button.value = 'deselect'
                }
                else if(button.value == 'deselect'){
                    for (var i in checkboxes){
                        checkboxes[i].checked = '';
                    }
                    button.value = 'select';
                }
                else{
                    for (var i in checkboxes){
                        checkboxes[i].checked = 'FALSE';
                    }
                    button.value = 'deselect' 
                }
            }

            /*function check_group(){
            check('check_group[]','check_all_group');

            var searchString=ajUrl+'&assignmentGroupMember=1'
            jx.load(searchString,function(data){
            data = jQuery.parseJSON(data);
            $(data.user_data).each(function(i,d){
            if($('#group_all').is(":checked")){
            var users = '<div id="members_'+d.id+'" class="assignment_checkbox"><input id="checkMember_'+d.id+'" type="checkbox" name="check_user[]" value="'+d.id+'"><label for="checkMember_'+d.id+'"><div class="comment_inner_wrapper"><div class="comment_header"><img src="images/man_icon2.png" class="post_profile_picture"></div><div class="comment_body"><div class="comment_text"><span class="evac_user">'+d.name+'</span></div></div></div></label></div>';
            $("#group_member").append(users);
            }else {
            $('#group_member').empty();
            }
            });

            }); 

            }
            function group_member(ugID){

            var searchString='?ajax=1&assignmentGroupMember=1&ug_id='+ugID;
            jx.load(searchString,function(data){
            data = jQuery.parseJSON(data);

            $(data.user_data).each(function(i,d){
            if($('#check_group_'+ugID).is(":checked")){
            var users = '<div id="members_'+d.id+'" class="assignment_checkbox"><input id="checkMember_'+d.id+'" type="checkbox" name="check_user[]" value="'+d.id+'"><label for="checkMember_'+d.id+'"><div class="comment_inner_wrapper"><div class="comment_header"><img src="images/man_icon2.png" class="post_profile_picture"></div><div class="comment_body"><div class="comment_text"><span class="evac_user">'+d.name+'</span></div></div></div></label></div>';
            $("#group_member").append(users);
            }else {
            $('#members_'+d.id).remove();
            }
            });

            }); 

            }
            function assignment_sub(){
            clearMessage();
            var threads=[]; 
            $('input[name="check_threads[]"]:checked').each(function() {
            threads.push(this.value);
            });
            var users=[];
            $('input[name="check_user[]"]:checked').each(function(){
            users.push(this.value);
            })
            if(users.length>0){
            var sendString='assignmentSubmit=c&threads='+threads+'&users='+users;
            $.ajax({
            type:'POST',
            url:ajUrl,
            data:sendString,
            success:function(data){
            if(data.status==1){
            var button = document.getElementsByName('check_all_group');
            button.value = 'deselect'
            check('check_group[]','check_all_group');


            var button = document.getElementsByName('check_all_user');
            button.value = 'deselect'
            check('check_user[]','check_all_user');

            assignmentCommentDestribute(data.comments);
            }   
            }
            });
            }
            else{
            creatMessageForHtml('Please select agents');
            }
            }*/
        </script>
    </div>
</div>