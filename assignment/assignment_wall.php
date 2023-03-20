<?php
    $type=$social->getAssignType('w'); 
    $flowType=$social->getFlowType('w');
    $canBan=$db->permission(PER_BAN_USER);
    $canDelete=$db->permission(PER_WALL_DELETE);
?>
<script type="text/javascript">var wallPostType='w';</script>
<div class="col-md-12 assignment_radio check_service">
    <b style="margin-right:10px;">Flow Type</b> &nbsp;  :
    <input type="radio" name="flow" id="flow<?php echo WALL_POST_COMMENT_FLOW_FIFO;?>" value="<?php echo WALL_POST_COMMENT_FLOW_FIFO;?>" <?php echo $general->checked($flowType,WALL_POST_COMMENT_FLOW_FIFO);?>>
    <label for="flow<?php echo WALL_POST_COMMENT_FLOW_FIFO;?>">FIFO</label>

    <input type="radio" name="flow" id="flow<?php echo WALL_POST_COMMENT_FLOW_LIFO;?>" value="<?php echo WALL_POST_COMMENT_FLOW_LIFO;?>" <?php echo $general->checked($flowType,WALL_POST_COMMENT_FLOW_LIFO);?>>
    <label for="flow<?php echo WALL_POST_COMMENT_FLOW_LIFO;?>">LIFO</label>
</div>
<div class="col-md-12 assignment_radio check_service">
    <b>Assignment Type</b> &nbsp; : 
    <!--<input type="radio" name="type" id="type<?php echo ASSIGN_TYPE_AUTO;?>" value="<?php echo ASSIGN_TYPE_AUTO;?>" <?php echo $general->checked($type,ASSIGN_TYPE_AUTO);?>>
    <label for="type<?php echo ASSIGN_TYPE_AUTO;?>">Auto</label>-->

    <input type="radio" name="type" id="type<?php echo ASSIGN_TYPE_MANUAL;?>" value="<?php echo ASSIGN_TYPE_MANUAL;?>" <?php echo $general->checked($type,ASSIGN_TYPE_MANUAL);?>>
    <label for="type<?php echo ASSIGN_TYPE_MANUAL;?>">Manual</label>

    <input type="radio" name="type" id="type<?php echo ASSIGN_TYPE_HIGHBREED;?>" value="<?php echo ASSIGN_TYPE_HIGHBREED;?>" <?php echo $general->checked($type,ASSIGN_TYPE_HIGHBREED);?>>
    <label for="type<?php echo ASSIGN_TYPE_HIGHBREED;?>">Manual + Auto</label>
</div>
<div id="message_show_box"><?php show_msg();?></div>
<div id="manual_option" <?php if($type==ASSIGN_TYPE_AUTO){echo 'style="display: none;"';} ?>>
    <div class="col-md-4">
        <div class="x_panel assignment">
            <div class="x_title">
                <h2>
                    <div class="assignment_checkbox">
                        <input id="title" type="checkbox" name="check_all_threads" onClick="check('check_threads[]','check_all_threads')" value="select">
                        <label for="title">Threads</label>
                    </div>
                </h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a data-toggle="modal" data-target="#filterModal"><i class="fa fa-filter"></i></a>
                    </li>
                </ul>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <select onchange="assignAction(this.value)" class="col-md-1 form-control" style="height:25px; padding:0px;" id="assignAction">
                            <option value="">Action</option>
                            <option value="3">Done</option>
                            <option value="6">Hide</option>
                            <?php
                                if($canBan==true){
                                ?>
                                <option value="2">Ban</option>
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
                                <option value="5">Ban+Hide</option>
                                <?php
                                }
                            ?>
                        </select>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div id="ass_post" class="x_content">
                <?php
                    $rt=$social->assignmentData('w',50);
                    $names=$rt['names'];
                    $name_id=array();
                    foreach($names as $n){
                        $name_id[$n['id']]=$n['name'];
                    }
                    //$general->printArray($name_id);
                    $i=1;
                    foreach($rt['comments'] as $c){
                    ?>
                    <div class="assignment_checkbox" id="ass_<?=$c['comment_id']?>">
                        <input id="check_<?php echo $i; ?>" data-id="<?php echo $c['sender_id']?>" data-activity="<?php echo $c['activity']?>" type="checkbox" name="check_threads[]" value="<?php echo $c['comment_id'] ?>">
                        <label for="check_<?php echo $i++; ?>">
                            <div class="comment_inner_wrapper">
                                <div class="comment_header">
                                    <img src="https://graph.facebook.com/<?php echo $c['sender_id']?>/picture?type=square" class="post_profile_picture">
                                </div>
                                <div class="comment_body">
                                    <div class="comment_text">
                                        <span class="evac_user">
                                            <?php 
                                                echo $name_id[$c['sender_id']];
                                            ?>
                                        </span><br>
                                        <span><script>document.write(momentTime('<?php echo $c['created_time'];?>'));</script></span>
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
                        </label>
                    </div>
                    <?php
                    }
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="x_panel assignment">
            <div class="x_title">
                <div class="assignment_checkbox">
                    <input id="group_all" type="checkbox" onClick="check_group()" name="check_all_group" value="select">
                    <label for="group_all">Group</label>
                </div>
            </div>
            <div class="x_content">
                <?php 
                    $i=0;
                    $group=$db->allGroups();
                    foreach($group as $g){
                    ?>
                    <div class="assignment_checkbox">
                        <input id="check_group_<?php echo $g['ugID']; ?>" type="checkbox" onclick="group_member(<?=$g['ugID']?>)" name="check_group[]" value="select">
                        <label for="check_group_<?php echo $g['ugID']; ?>">
                            <div class="comment_inner_wrapper">
                                <div class="comment_body">
                                    <div class="comment_text">
                                        <span class="evac_user">
                                            <?php echo $g['ugTitle']; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                    <?php
                    }
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="x_panel assignment">
            <div class="x_title">
                <div class="assignment_checkbox">
                    <input id="title3" type="checkbox" onclick="check('check_user[]','check_all_user');" name="check_all_user" value="send">
                    <label for="title3">Users</label>
                </div>
            </div>
            <div class="x_content" id="group_member"> </div>
            <button class="btn btn-success" onclick="assignment_sub()">Save</button>
        </div>
    </div>

</div>


 <?php
          
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
<script type="text/javascript">
    $(document).ready(function(){
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
<div id="filterModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Assignment Filter</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="control-label col-sm-2">Quantity:</label>
                        <div class="col-sm-10">
                            <select class="form-control"  id="selectQuantity" class=""><?php for($i=5;$i<=50;$i=$i+5){?><option <?php if($i==50){echo 'selected';} ?> value="<?php echo $i; ?>"><?php echo $i; ?></option><?php }?></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Date</label>
                        <div class="col-sm-5">
                            <input type="radio" checked="checked" name="radioDate" id="allDate" value="1"><label for="allDate">&nbsp; All</label>&nbsp;
                            <input type="radio" name="radioDate" id="customDate" value="0"><label for="customDate">&nbsp;Custom</label>
                        </div>
                        <div class="col-sm-5">
                        </div>
                    </div>
                    <div class="form-group" id="dateRangeArea" style="display: none;">
                        <div class="col-md-2">
                        </div>
                        <div class="col-sm-10">
                            <label class="control-label col-sm-2"></label>
                            <input type="hidden" id="date_range" name="date_range" value="<?php echo $dateRangeVal;?>">
                            <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span><?php echo $dateRangeShow;?></span> <b class="caret"></b>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2"></label>
                        <div class="col-sm-10">
                            <button onclick="filterAssign(0);" class="btn btn-sm btn-info">Filter</button>
                        </div>
                    </div>
                </div>            

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
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
        $('input:radio[name="flow"]').change(function(){
            if($(this).val() =='<?php echo WALL_POST_COMMENT_FLOW_FIFO;?>'){
                commentPostFlowType('<?php echo WALL_POST_COMMENT_FLOW_FIFO;?>','w');
            }else{
                commentPostFlowType('<?php echo WALL_POST_COMMENT_FLOW_LIFO;?>','w');
            }
        });
        $('input:radio[name="type"]').change(function(){
            var currentVal=$(this).val();
            if(currentVal == '<?php echo ASSIGN_TYPE_HIGHBREED ;?>'||currentVal == '<?php echo ASSIGN_TYPE_MANUAL ;?>'){
                $('#manual_option').show(700);
                $('#type_btn').hide(700);
            }else{
                $('#manual_option').hide(700);
                $('#type_btn').show(700);
            }
            commentAssignTypeChange(currentVal,'w');
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

    function check_group(){
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
        var tp='';
        var vl='';
        $('input[name="check_threads[]"]:checked').each(function() {
            tp=$(this).attr('data-activity');
            vl=this.value;
            threads.push(tp+'-'+vl);
        });
//        t(threads);return;
        var users=[];
        $('input[name="check_user[]"]:checked').each(function(){
            users.push(this.value);
        })
        if(users.length>0){
            var sendString='assignmentSubmit=w&threads='+threads+'&users='+users;
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
    }
</script>