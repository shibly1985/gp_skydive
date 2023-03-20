<div class="top_nav">
    <div class="nav_menu">
        <nav>
            <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>
            <ul class="nav navbar-nav navbar-left">
                <?php $cl='javascript:void()';if($db->modulePermission(142)){$cl='?'.MODULE_URLN.'=comments';}?>
                <li><a href="<?php echo $cl;?>" title="Comment Queue">CQ ( <span id="commentQueue">00.</span> )</a></li>
                <?php $cl='javascript:void()';if($db->modulePermission(155)){$cl='?'.MODULE_URLN.'=wall_post';}?>
                <li><a href="<?php echo $cl;?>" title="Wall Post Queue">WQ ( <span id="wallPostQue">00.</span> )</a></li>
                <?php
                    if(OPERATION_MESSAGE_ALLWO==true){
                    ?>
                    <?php $cl='javascript:void()';if($db->modulePermission(152)){$cl='?'.MODULE_URLN.'=messages';}?>
                    <li><a href="<?php echo $cl;?>" title="Message Queue">DM ( <span id="msgQue">00.</span> )</a></li>
                    <?php
                    }
                ?>
            </ul>

            <ul class="nav navbar-nav navbar-right">

                <li class="">
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <img src="" alt="" class="<?php echo $md5Email;?>">
                        <span class="name_mobile"><?php echo $userData['uFullName'] ?></span>
                        <span class=" fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                        <li><a href="?<?php echo MODULE_URLN;?>=profile"> Profile</a></li>
                        <li><a   href="?logout=1" >Logout</a></li>
                        <li><a data-toggle="modal" data-target="#logoutModal" href=""><i class="fa fa-sign-out pull-right"></i> Break</a></li>

                    </ul>
                </li>
                <li style="font-size:20px;">
                    <a href="javascript:void();" >
                        <span id="activityHour">00</span> :
                        <span id="activityMinute">00</span> :
                        <span id="activitySecond">00</span>
                    </a>
                </li>
                <li style="font-size:20px;">
                    <a href="javascript:void();"><span id="systemClock"></span></a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<div id="logoutModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Logout</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="control-label col-sm-2"></label>
                        <div class="col-sm-5">
                            <input type="radio" name="radioTrcker" id="break" value="1" checked="checked"><label for="break">&nbsp; Break</label>&nbsp;
                            <input type="radio" name="radioTrcker" id="leaving" value="0"><label for="leaving">&nbsp;Leaving</label>
                        </div>
                        <div class="col-sm-5">
                        </div>
                    </div>
                    <div class="form-group" id="reason" >
                        <div class="col-md-2">
                        </div>
                        <div class="col-sm-10">
                            <label class="control-label col-sm-2">Reason:</label>
                            <div class="col-sm-7">
                                <select name="reason" class="form-control" id="selectReason">
                                    <?php
                                        $break_reason = $db->selectAll($general->table(53));
                                        foreach($break_reason as $br){
                                        ?>
                                        <option value="<?php echo $br['ubrID'];?>"><?php echo $br['ubrTitle'];?></option>
                                        <?php
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" style="margin-top:35px ;">
                            <div class="col-sm-2">
                            </div>
                            <div class="col-sm-10">
                                <label class="control-label col-sm-2">Return:</label>
                                <div class="col-sm-7">
                                    <input type="text" name="textAppTime" id="textAppTime" class="form-control" style="" onclick="reasonColorChange()" placeholder="In Minutes Within 5 and 60">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2"></label>
                        <div class="col-sm-10">
                            <a  class="btn btn-sm btn-info" href="?logout=1" id="logoutbtn" style="display:none;">Logout</a>
                            <a  class="btn btn-sm btn-info" href="javascript:void(0);" id="logoutBreakbtn"  onclick="logoutForBreak();">Logout For Break</a>
                        </div>
                    </div>
                </div>            

            </div>
        </div>

    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        topNavUpdate();
        $('input:radio[name="radioTrcker"]').change(function(){
            var currentVal=$(this).val();
            if(currentVal == 1){
                $('#reason').show(200);
                $('#logoutbtn').hide();
                $('#logoutBreakbtn').show(); 
            }else{
                $('#reason').hide(200);
                $('#logoutbtn').show();
                $('#logoutBreakbtn').hide();
            }

        }); 
    });
        function topNavUpdate(){
            if(LOGDIN==1){
                $.ajax({
                    type:'POST',
                    url:ajUrl,
                    data:{topQueueUpdate:1},
                    success:function(data){
                        if(data.login==1){
                            $('#commentQueue').html(data.commentQueue);
                            $('#wallPostQue').html(data.wallPostQue);
                            $('#msgQue').html(data.msgQue);
                            $('#systemClock').html(data.systemClock);
                            <?php
                                if($_SERVER['SERVER_NAME']!=LOCAL_SERVER_NAME&&PROJECT!='gpc'){
                                ?>
                                setTimeout(function(){topNavUpdate();},20000);
                                <?php   
                                }
                            ?>
                        }
                        else{location.reload();}
                    }
                });
            }
        }
    function reasonColorChange(){
        $("#textReason").css("border-color","#ccc");
        $("#textAppTime").css("border-color","#ccc");
    }
</script>