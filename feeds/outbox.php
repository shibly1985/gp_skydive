<?php
    if(isset($_GET['show'])){
        $date_range=$_GET['date_range'];
    }
    else{
        $dr = date('d-m-Y 00:00:00',YESTERDAY_TIME).'';
        $dr2= date('d-m-Y 00:00:00').'';
        $date_range=$dr.'__'.$dr2;
    }
    $dates=explode('__',$date_range); echo'<br>'; 
    //$general->printArray($dates);
    $from_date=strtotime($dates[0]);
    //echo $from_date;echo'<br>'; 
    $to_date=strtotime($dates[1]);
    if(date('h:i',$to_date)=='12:00'){
        $to_date=strtotime('+23 hour',$to_date);
        $to_date=strtotime('+59 minute',$to_date);
    }
    
    $type       = @$_GET['type'];   
    $key        = @$_GET['key'];   
    $link=$pUrl.'&date_range='.$date_range;
    if(empty($from_date)){
        $from_date =strtotime("-7 day", TODAY_TIME);
    }
    if(empty($to_date)){
        $to_date =TIME;
    }
    if(empty($type)){
        $type='c';
        $tbl  = 41; 
    }            
    if($type=='c'){$tbl  = 41;}
    elseif($type=='w'){$tbl  = 42;}
    elseif($type=='m'){$tbl  = 9;}
    $link.='&type='.$type;

    $ugID=0;
    if(isset($_GET['ug'])){
        $ugID    = intval($_GET['ug']);
    }

    $agQ='';
    if($ugID!=0){
        $users=$db->allUsers(' and ugID='.$ugID);
        if(!empty($users)){
            $general->arrayIndexChange($users,'uID');
            $agQ=' and replyBy in('.implode(',',array_keys($users)).')';
            $link.='&ug='.$ugID;
        }
        else{
            $agQ=' and replyBy=-1';
        }
    }
    else{
        
        if(isset($_GET['ag'])){
            if(intval($_GET['ag'])!=0){
                $agQ = ' and replyBy='.intval($_GET['ag']);
            }
        }
    }
    $dateRangeVal=date('d-m-Y h:i A',$from_date).'__'.date('d-m-Y h:i A',$to_date);
    $dateRangeShow=date('d-m-Y h:i A',$from_date).' - '.date('d-m-Y h:i A',$to_date);
    $users=$db->allUsers('order by uFullName asc');
    $general->arrayIndexChange($users,'uID');
?>
<script type="text/javascript">
    /*$(function() {
    var start = moment().subtract(1, 'days');
    var end = moment();
    function cb(start, end,label) {
    $('#reportrange span').html(start.format('DD-MM-YYYY HH:mm A') + ' - ' + end.format('DD-MM-YYYY HH:mm A'));
    $('#date_range').val(start.format('DD-MM-YYYY HH:mm A')+'__'+end.format('DD-MM-YYYY HH:mm A'));
    }

    $('#reportrange').daterangepicker({
    timePicker: true,
    timePicker24Hour: true,
    timePickerIncrement: 5,
    startDate: start,
    endDate: end,
    opens: "right",
    ranges: {
    'Today': [moment(), moment()],
    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
    'Last 7 Days': [moment().subtract(6, 'days'), moment()]
    }
    }, cb);

    cb(start, end);
    });*/
    $(document).ready(function(){
        $('#reportrange').daterangepicker({
            timePicker: true,
            opens: "right",
            autoApply: true,
            startDate: "<?php echo date('m/d/Y',$from_date);?>",
            endDate: "<?php echo date('m/d/Y',$to_date);?>"
            }, 
            function(start, end) {
                $('#reportrange span').html(start.format('DD-MM-YYYY hh:mm A') + ' - ' + end.format('DD-MM-YYYY hh:mm A'));
                $('#date_range').val(start.format('DD-MM-YYYY hh:mm A')+'__'+end.format('DD-MM-YYYY hh:mm A'));
        });
    });
</script>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?php echo $rModule['cmTitle'];?><span id="totalDisplay"></span></h2>
            <div class="clearfix"></div>
        </div>
        <form method="GET" action="" class="form-inline form_inline" style="margin-bottom: 20px;">
            <input type="hidden" name="<?php echo MODULE_URL;?>" value="<?php echo $pSlug;?>">
            <input type="hidden" id="date_range" name="date_range" value="<?php echo $dateRangeVal;?>">
            <div class="form-group">
                <div id="reportrange" class="pull-right sky_datepicker" >
                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                    <span><?php echo $dateRangeShow;?></span> <b class="caret"></b>
                </div>
            </div>
            <div class="form-group">
                <select name="ug" class="form-control select2">
                    <option class="sky_textbox" value="">All Agent Group</option>
                    <?php
                        $groups=$db->allGroups();
                        foreach($groups as $g){?><option value="<?php echo $g['ugID'];?>" <?php echo $general->selected($g['ugID'],@$ugID);?>><?php echo $g['ugTitle'];?></option><?php } ?>
                </select>
            </div>
            <div class="form-group">
                <select name="ag" class="form-control select2">
                    <option class="sky_textbox" value="">All Agent</option>
                    <?php
                        foreach($users as $u){?><option value="<?php echo $u['uID'];?>" <?php echo $general->selected($u['uID'],@$uID);?>><?php echo $u['uFullName'];?> (<?php echo $u['uLoginName'];?>)</option><?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="key">Keyword:</label> 
                <input type="text" class="form-control sky_textmargin" name="key" value="<?php echo @$_GET['key'];?>" id="key">
            </div>
            <div class="form-group">
                <label>Type:</label>
                <select name="type" class="form-control sky_textmargin" style="padding:0px;">
                    <option <?php if($type=='c'){echo 'selected';}?> value="c">Comments</option>
                    <option <?php if($type=='w'){echo 'selected';}?> value="w">Wall post</option>
                    <option <?php if($type=='m'){echo 'selected';}?> value="m">Message</option>
                </select>
            </div>

            <input type="submit" value="Show" name="show" class="btn btn-success btn-sm">
        </form>
        <?php
            //   $query=$db->selectAll($general->table(41),'where sendSuccess=0 and totalTry<'.OUTBOX_MAX_RETRY);
            $query='';
            if($type=='c'){
                $query  = "select * from ".$general->table(41)." where sendSuccess=0 and replyTime between ".$from_date." and ".$to_date." and totalTry<".OUTBOX_MAX_RETRY;
                if($key!=''){
                    $query.=" and message like '%".$key."%'";
                }
                if($agQ!=''){
                    $query.=$agQ;
                }
                //$query.=' order by replyTime desc';
            }
            elseif($type=='w'){
                $query  = "select * from ".$general->table(42)." where sendSuccess=0  and replyTime between ".$from_date." and ".$to_date." and totalTry<".OUTBOX_MAX_RETRY;
                if($key!=''){
                    $query.=" and message like '%".$key."%'";
                }

                if($agQ!=''){
                    $query.=$agQ;
                }
            }
                $query.=' order by replyTime desc';
            $cp=1;if(isset($_GET[PAGE_INATION_CURRENT_PAGE_NAME]))$cp=intval($_GET[PAGE_INATION_CURRENT_PAGE_NAME]);
            //$all_data=$db->fetchQuery($query);
            $pageination    = $general->pagination_init_customQuery($query,100,$cp);
            $all_data       = $db->fetchQuery($query.$pageination['limit'],$general->showQuery());
        ?>

        <table class="table">
            <tr>
                <td>SL</td>
                <td style="width: 25%;">Comments</td>
                <td style="width: 25%;">Reply</td>
                <td>Reply By</td>
                <td>Created Time</td>
                <td>Error Code</td>
                <td style="width: 10%;">Error Message</td>
                <td>Tryed</td>
                <td>Try again</td>
            </tr>
            <?php
                $general->arrayContentShow($all_data);
                $serial=1;
                foreach($all_data as $ad){
                    $comment=$db->get_rowData($general->table(13),"comment_id",$ad['target_c_id']);
                    $post_ids[$comment['post_id']]=$comment['post_id'];
                    $general->arrayContentShow($comment);
                ?>
                <tr id="rp_<?php echo $ad['scqID'];?>">
                    <td><?php echo $serial++;?></td>
                    <td>
                        <?php if(isset($comment['message'])){echo $comment['message'];}?><br>
                        <a href="javascript:void();" data-link="1" data-post_id="<?php echo $comment['post_id'];?>" data-parent_id="<?php echo $comment['parent_id'];?>" data-comment_id="<?php echo $comment['comment_id'];?>" data-created_time="<?php echo date('YmdHis',$comment['created_time']);?>">Time</a>
                    </td>
                    <td><?php echo str_ireplace($key,'<b>'.$key.'</b>',$ad['message']); ?></td>    
                    <td><?php echo $users[$ad['replyBy']]['uFullName'];?></td>
                    <td><?php echo $general->make_date($ad['created_time'],'time'); ?></td>
                    <td class="errorCode"><?php echo $ad['errorCode'];?></td>
                    <td class="errorMessage"><?php echo $ad['errorMessage'];?></td>
                    <td class="totalTry"><?php echo $ad['totalTry'];?></td>
                    <td class="tryAgain">
                        <a href="javascript:void();" onclick="queueCleare('c','<?php echo $ad['scqID'];?>')">Try Again</a>
                    </td>
                </tr>
                <?php
                }
            ?>
        </table>
        <ul class="pagination"><?php $general->pagination($pageination['currentPage'],$pageination['TotalPage'],$link.'&'.PAGE_INATION_CURRENT_PAGE_NAME.'=');?></ul>
    </div>
</div>
<script type="text/javascript">
    <?php
        echo 'commentpostids='.json_encode($social->getPostPermalinkUrlBiIds($post_ids,$type)).';';
    ?>
    $(document).ready(function(){commentLinkCreate();});
</script>

<script type="text/javascript">
    $(document).ready(function(){
        <?php
            if($pageination['total']>0){
            ?>
            var total = '<?php echo $pageination['total']?>';
            <?php
            }
            else{
            ?>
            var total = 0;
            <?php
            }
        ?>
        $("#totalDisplay").html(" ("+total+")");

    });
</script>

