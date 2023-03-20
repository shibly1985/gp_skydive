<?php

    if(isset($_GET['date_range'])){
        $date_range=$_GET['date_range'];
    }
    else{
        $dr = date('d-m-Y 00:00:00',YESTERDAY_TIME).'';
        $dr2= date('d-m-Y 00:00:00').'';
        $date_range=$dr.'__'.$dr2;
    }
    $dates=explode('__',$date_range);
    $from_date=strtotime($dates[0]);
    $to_date=strtotime($dates[1]);
    if(date('h:i',$to_date)=='12:00'){
        $to_date=strtotime('+23 hour',$to_date);
        $to_date=strtotime('+59 minute',$to_date);
    }

    $tType       = @$_GET['type'];   

    $key        = @$_GET['key'];   
    $link=$pUrl.'&date_range='.$date_range;
    if(empty($from_date)){
        $from_date =strtotime("-7 day", TODAY_TIME);
    }
    if(empty($to_date)){
        $to_date =TIME;
    }
    $type='c';
    $tbl  = 13; 
    $thisPageTitle='Sent Comment';                
    if($tType=='w'){$tbl= 14;$thisPageTitle='Sent Wall';$type='w';}
    elseif($tType=='m'){$tbl= 9;$thisPageTitle='Sent Message';$type='m';}
    $link.='&type='.$type;
    if($type=='c'){
        $canDelete=$db->permission(PER_COMMENT_DELETE);    
    }
    else if($type=='w'){
        $canDelete=$db->permission(PER_WALL_DELETE);
    }
    else{
        $canDelete=false;
    }
    $dateRangeVal=date('d-m-Y h:i A',$from_date).'__'.date('d-m-Y h:i A',$to_date);
    $dateRangeShow=date('d-m-Y h:i A',$from_date).' - '.date('d-m-Y h:i A',$to_date);
    $wrapups=$db->selectAll($general->table(11));$general->arrayIndexChange($wrapups,'wuID');
    $wrapupCat=$db->selectAll($general->table(34),'order by wcTitle asc');$general->arrayIndexChange($wrapupCat,'wcID');
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
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?php echo $thisPageTitle;?> <span id="totalDisplay"></span></h2>
            <div class="clearfix"></div>
        </div>

        <script type="text/javascript">var wallPostType='<?php echo $type;?>';</script>
        <form method="GET" action="" class="form-inline form_inline" style="margin-bottom: 10px;">
            <input type="hidden" name="<?php echo MODULE_URL;?>" value="<?php echo $pSlug;?>">
            <div class="form-group">
                <input type="hidden" id="date_range" name="date_range" value="<?php echo $dateRangeVal;?>">
                <div id="reportrange" class="pull-right sky_datepicker" >
                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                    <span><?php echo $dateRangeShow;?></span> <b class="caret"></b>
                </div>
            </div>
            <div class="form-group">
                <label for="key">Keyword:</label> 
                <input type="text" style="height:25px;" class="form-control" name="key" value="<?php echo @$_GET['key'];?>" id="key">
            </div>
            <input type="submit" value="Show" name="show" class="btn btn-success btn-sm">
        </form>
        <?php
            if($type=='c'){
                $q=array();
                $q[]="c.replyed=1";
                $q[]='c.replyBy='.UID;
                $q[]='c.sender_id='.PAGE_ID;
                $q[]="c.replyTime between ".$from_date." and ".$to_date;
                if($key!=''){
                    $q[]="c.message like '%".$key."%'";
                }
                $sq=" where ".implode(" and ",$q);
                $query  = "select 
                c.post_id,c.replyBy,c.comment_id,c.replyTime,c.message,c.parent_id,c.created_time,c.target_c_id,c.wuID
                from ".$general->table($tbl).' c '.$sq.'
                order by c.replyTime desc';
            }
            elseif($type=='w'){
                $q=array();
                $q[]="c.replyed=1";
                $q[]='c.replyBy='.UID;
                $q[]='c.sender_id='.PAGE_ID;
                $q[]="c.replyTime between ".$from_date." and ".$to_date;
                if($key!=''){
                    $q[]="(cc.message like'%".$_GET['com']."%' or ccp.message like'%".$_GET['com']."%')";
                    $q[]="c.message like '%".$key."%'";
                }
                $sq=" where ".implode(" and ",$q);
                $query  = "
                select 
                c.replyBy,c.comment_id,c.replyTime,c.message,c.target_c_id,c.post_id,c.parent_id,c.created_time,c.wuID,c.scentiment,
                ifnull(cc.message,ccp.message) as cmessage, 
                ifnull(cc.photo,ccp.link) as photo, 
                ifnull(cc.parent_id,ccp.post_id) as cparent_id, 
                ifnull(cc.created_time,ccp.created_time) as ccreated_time,
                ifnull(cc.sender_id,ccp.sender_id) as sender_id,
                if(cc.comment_id is null,'p','c') as targetType
                from ".$general->table($tbl)." c 
                left join ".$general->table($tbl)." cc on c.target_c_id=cc.comment_id
                left join ".$general->table(12)." ccp on (c.target_c_id=ccp.post_id or c.target_c_id=ccp.post_id_2)
                ".$sq."
                order by cc.created_time,ccp.created_time asc";

                //$query  = "select c.post_id,c.replyBy,c.comment_id,c.replyTime,c.message,c.parent_id,c.created_time,c.target_c_id  from ".$general->table($tbl)." c where c.replyed=1 and c.replyBy=".UID." and c.sender_id = ".PAGE_ID." and c.replyTime between ".$from_date." and ".$to_date; 
                //$query.=' order by c.replyTime desc';
            }
            elseif($type=='m'){
                $query  = "select c.replyBy,c.replyTime,".$general->mDec('c.text','text').",c.sendType,c.sendTime as created_time,c.targetSeq,c.sender_id,c.wuID,c.scentiment from ".$general->table($tbl)." c where c.replyed=1 and c.sendType=2 and c.replyTime between ".$from_date." and ".$to_date.' and c.replyBy='.UID.' order by c.sendTime desc';
            }
            $cp=1;if(isset($_GET[PAGE_INATION_CURRENT_PAGE_NAME]))$cp=intval($_GET[PAGE_INATION_CURRENT_PAGE_NAME]);

            $pageination    = $general->pagination_init_customQuery($query,100,$cp);
            $echo='No';
            if(isset($_GET['showq'])){$echo='s;';}
            $all_data       = $db->fetchQuery($query.$pageination['limit'],$echo);
        ?>

        <table class="table table-bordered fixtWidthReport">
            <tr>
                <td style="width: 3%;">SL</td>
                <td>Comments</td>
                <?php
                    if($type!='m'){
                    ?>
                    <td>Action</td>
                    <?php
                    }
                ?>
                <td style="width: 25%;">Reply</td>
                <td style="width: 7%;">Wrapup Category</td>
                <td style="width: 20%;">Wrap-up</td>
                <?php
                    if($type!='m'){
                    ?>
                    <td>Action</td>
                    <?php
                    }
                ?>
            </tr>
            <?php
                $serial=$pageination['start'];
                $post_ids=array();
                $senders=array();
                if($type=='m'){
                    $senderIds=array();
                    foreach($all_data as $ad){
                        $senderIds[$ad['sender_id']]=$ad['sender_id'];
                    }
                    $senderNames=$social->messageSendersName($senderIds);
                    foreach($all_data as $ad){
                        $senderName='';
                        if(array_key_exists($ad['sender_id'],$senderNames)){$senderName=$senderNames[$ad['sender_id']]['senderName'];}
                    ?>
                    <tr>
                        <td><?php echo $i++;?></td>
                        <td>
                            <?php 
                                //$general->printArray($ad);exit;
                                echo '<b>'.$senderName.'</b> :<br>';
                                $query="select targetSeq from ".$general->table($tbl)." where sender_id='".$ad['sender_id']."' and sendType=2 and targetSeq<".$ad['targetSeq']." order by targetSeq desc limit 1";
                                $a=$db->fetchQuery($query);
                                if(!empty($a)){
                                    $old=$a[0]['targetSeq'];
                                    $query="select mid,".$general->mDec('text').",sendTime,url from ".$general->table($tbl)." where sender_id='".$ad['sender_id']."' and sendType=1 and seq>".$old." and seq<=".$ad['targetSeq']." order by targetSeq desc";
                                    $a=$db->fetchQuery($query);
                                    if(!empty($a)){
                                        foreach($a as $ms){
                                        ?>
                                        <div class="eachmsg">
                                            <?php echo $ms['text'];?><br>
                                            <?php
                                                if($ms['url']!=''){
                                                ?><i><?php echo date('d-m-y h:i:s A',$ms['sendTime']);?> </i><?php        
                                                }
                                                else{
                                                ?><?php echo $ms['url'];?><?php        
                                                }
                                            ?>

                                        </div>
                                        <?php
                                        }
                                    }
                                }
                                else{
                                    $query="select mid,".$general->mDec('text').",sendTime,url from ".$general->table($tbl)." where sender_id='".$ad['sender_id']."' and sendType=1 and seq=".$ad['targetSeq']." order by targetSeq desc";
                                    $a=$db->fetchQuery($query);
                                    if(!empty($a)){
                                        foreach($a as $ms){
                                        ?>
                                        <div class="eachmsg">
                                            <?php echo $ms['text'];?><br>
                                            <?php
                                                if($ms['url']!=''){
                                                ?><i><?php echo date('d-m-y h:i:s A',$ms['sendTime']);?> </i><?php        
                                                }
                                                else{
                                                ?><?php echo $ms['url'];?><?php        
                                                }
                                            ?>
                                        </div>
                                        <?php
                                        }
                                    } 
                                }
                            ?>
                        </td>
                        <td><?php echo str_ireplace($key,'<b>'.$key.'<b>',$ad['text']); ?></td>
                        <td><?php echo $wrapupCat[$wrapups[$ad['wuID']]['wcID']]['wcTitle'];?></td>
                        <td><?php echo $wrapups[$ad['wuID']]['wuTitle'];?></td>
                    </tr>
                    <?php
                    }   
                }
                elseif($type=='c'){
                    $general->arrayContentShow($all_data);
                    foreach($all_data as $ad){
                        $post_ids[$ad['post_id']]=$ad['post_id'];
                        if($ad['target_c_id']!=''){
                            $comment=$db->get_rowData($general->table(13),"comment_id",$ad['target_c_id']);
                            $post_ids[$comment['post_id']]=$comment['post_id'];
                            $senders[$comment['sender_id']]=$comment['sender_id'];
                        }
                        $trId='rt_'.str_ireplace('$','',$ad['comment_id']);
                        $trId=str_ireplace('.','',$trId);
                    ?>
                    <tr id="<?php echo $trId;?>" class="cm_<?php echo $ad['comment_id'];?> cm_<?php echo $ad['target_c_id'];?>">
                        <td><?php echo $serial++;?></td>
                        <td>
                            <b><span class="name_<?php echo $comment['sender_id'];?>"></span><br></b>
                            <?php

                                if(!empty($comment)){
                                    if($comment['photo']!=''){
                                    ?><img src="<?php echo $comment['photo'];?>" style="max-height: 100px;max-width: 100px;"><?php
                                    }

                                    $m=str_ireplace('<','&#60;',$comment['message']);
                                    $m=str_ireplace('>','&#62;',$m);
                                    $m=str_ireplace($key,'<b>'.$key.'</b>',$m);
                                ?>
                                <div class="msgMin"><?php echo $general->word_limit($m,10); ?><br><a href="javascript:void();" onclick="commentFullView('<?php echo $trId;?>')">Full</a></div>
                                <div class="msgMax" style="display: none"><?php echo $m; ?><br><a href="javascript:void();" onclick="commentFullView('<?php echo $trId;?>')">Less</a></div>

                                <a href="javascript:void();" data-link="1" data-post_id="<?php echo $comment['post_id'];?>" data-parent_id="<?php echo $comment['parent_id'];?>" data-comment_id="<?php echo $comment['comment_id'];?>" data-created_time="<?php echo date('YmdHis',$comment['created_time']);?>">Time</a>
                                <?php
                                }
                            ?>
                        </td>
                        <td>
                            <div class="dropdown"><button class="btn btn-default dropdown-toggle" type="button" id="menu<?php echo $comment['comment_id'];?>" data-toggle="dropdown">
                                    Action
                                </button>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="menu<?php echo $comment['comment_id'];?>">
                                    <?php
                                        if($canDelete==true){
                                        ?>
                                        <li><a href="javascript:void();" onclick="sentCommentDelete('<?php echo $comment['comment_id'];?>','<?php echo $ad['comment_id'];?>','c')">Delete</a></li>
                                        <?php
                                        }
                                    ?>
                                    <li><a href="javascript:void();" onclick="sentCommentHide('<?php echo $comment['comment_id'];?>')">Hide</a></li>
                                </ul>
                            </div>
                        </td>
                        <td>
                            <?php
                                $m=str_ireplace('<','&#60;',$ad['message']);
                                $m=str_ireplace('>','&#62;',$m);
                                $m=str_ireplace($key,'<b>'.$key.'</b>',$m);
                            ?>

                            <div class="msgMin"><?php echo $general->word_limit($m,10);; ?><br><a href="javascript:void();" onclick="commentFullView('<?php echo $trId;?>')">Full</a></div>
                            <div class="msgMax" style="display: none;"><div class="replyHtml"><?php echo $m; ?></div><a href="javascript:void();" onclick="commentFullView('<?php echo $trId;?>')">Less</a></div>
                            <div class="replyEdit" style="display: none;"></div>
                            <a href="javascript:void();" data-link="1" data-post_id="<?php echo $ad['post_id'];?>" data-parent_id="<?php echo $ad['parent_id'];?>" data-comment_id="<?php echo $ad['comment_id'];?>" data-created_time="<?php echo date('YmdHis',$ad['created_time']);?>">-</a>
                        </td>
                        <td><?php echo $wrapupCat[$wrapups[$ad['wuID']]['wcID']]['wcTitle'];?></td>
                        <td><?php echo $wrapups[$ad['wuID']]['wuTitle'];?></td>
                        <td>
                            <?php
                                if(!preg_match('#^<b>Private</b>#',$ad['message'])){
                                ?>
                                <button onclick="show_edit_box('<?php echo $ad['comment_id']?>','c','<?php echo $trId;?>')" class="btn btn-sm btn-default edit_button">Action</button>
                                <?php
                                }
                                else{
                                    echo '<b>Private</b>';
                                }
                            ?>
                        </td>
                    </tr>
                    <?php
                    }
                }
                else{
                    $general->arrayContentShow($all_data);
                    //$general->printArray($all_data);
                    foreach($all_data as $ad){
                        $post_ids[$ad['post_id']]=$ad['post_id'];

                        $trId='rt_'.str_ireplace('$','',$ad['comment_id']);
                        $trId=str_ireplace('.','',$trId);
                    ?>
                    <tr id="<?php echo $trId;?>" class="cm_<?php echo $ad['target_c_id'];?> cm_<?php echo $ad['comment_id'];?>">
                        <td><?php echo $serial++;?></td>
                        <td>
                            <?php
                                if($ad['photo']!=''){
                                ?><img src="<?php echo $ad['photo'];?>" style="max-height: 100px;max-width: 100px;"><?php
                                }

                                $m=str_ireplace('<','&#60;',$ad['cmessage']);
                                $m=str_ireplace('>','&#62;',$m);
                                $m=str_ireplace($key,'<b>'.$key.'</b>',$m);
                            ?>
                            <div class="msgMin"><?php echo $general->word_limit($m,10); ?><br><a href="javascript:void();" onclick="commentFullView('<?php echo $trId;?>')">Full</a></div>
                            <div class="msgMax" style="display: none"><?php echo $m; ?><br><a href="javascript:void();" onclick="commentFullView('<?php echo $trId;?>')">Less</a></div>
                            <a href="javascript:void();" data-link="1" data-post_id="<?php echo $ad['post_id'];?>" data-parent_id="<?php echo $ad['cparent_id'];?>" data-comment_id="<?php echo $ad['target_c_id'];?>" data-created_time="<?php echo date('YmdHis',$ad['ccreated_time']);?>">-</a><?php echo $ad['type'];?>
                        </td>
                        <td>
                            <div class="dropdown"><button class="btn btn-default dropdown-toggle" type="button" id="menu<?php echo $comment['comment_id'];?>" data-toggle="dropdown">
                                    Action
                                </button>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="menu<?php echo $ad['target_c_id'];?>">
                                    <li><a href="javascript:void();" onclick="sentCommentDelete('<?php echo $ad['target_c_id'];?>','<?php echo $ad['target_c_id'];?>','<?php echo $ad['targetType'];?>')">Delete</a></li>
                                    <li><a href="javascript:void();" onclick="sentCommentHide('<?php echo $ad['target_c_id'];?>','<?php echo $ad['targetType'];?>')">Hide</a></li>
                                </ul></div>
                        </td>
                        <td>
                            <?php
                                $m=str_ireplace('<','&#60;',$ad['message']);
                                $m=str_ireplace('>','&#62;',$m);
                                $m=str_ireplace($key,'<b>'.$key.'</b>',$m);
                            ?>
                            <div class="msgMin"><?php echo $general->word_limit($m,10);; ?><br><a href="javascript:void();" onclick="commentFullView('<?php echo $trId;?>')">Full</a></div>
                            <div class="msgMax" style="display: none;"><div class="replyHtml"><?php echo $m; ?></div><a href="javascript:void();" onclick="commentFullView('<?php echo $trId;?>')">Less</a></div>
                            <div class="replyEdit" style="display: none;"></div>
                            <a href="javascript:void();" data-link="1" data-post_id="<?php echo $ad['post_id'];?>" data-parent_id="<?php echo $ad['parent_id'];?>" data-comment_id="<?php echo $ad['comment_id'];?>" data-created_time="<?php echo date('YmdHis',$ad['created_time']);?>">Time</a>
                        </td>
                        <td><?php echo $wrapupCat[$wrapups[$ad['wuID']]['wcID']]['wcTitle'];?></td>
                        <td><?php echo $wrapups[$ad['wuID']]['wuTitle'];?></td>
                        <td>
                            <?php
                                if(!preg_match('#^<b>Private</b>#',$ad['message'])){
                                ?>
                                <button onclick="show_edit_box('<?php echo $ad['comment_id']?>','<?php echo $ad['targetType'];?>','<?php echo $trId;?>')" class="btn btn-sm btn-default edit_button">Action</button>
                                <?php
                                }
                                else{
                                    echo '<b>Private</b>';
                                }
                            ?>
                        </td>
                    </tr>
                    <?php
                    }
                }
            ?>
        </table>
        <script type="text/javascript">
            <?php echo 'commentpostids='.json_encode($social->getPostPermalinkUrlBiIds($post_ids,$type)).';'; ?>
            <?php echo 'senders='.json_encode($social->getNamesByUserId($senders)).';'; ?>
            $(document).ready(function(){
                commentLinkCreate();
                setNames(senders);
            });
        </script>
        <ul class="pagination"><?php $general->pagination($pageination['currentPage'],$pageination['TotalPage'],$link.'&'.PAGE_INATION_CURRENT_PAGE_NAME.'=');?></ul>
        <div id="editDiv" style="display: none;">
            <textarea class="replyMessage form-control"></textarea>
            <input class="btn btn-sm btn-default replyCancle" type="button" value="Cancel">
            <?php
                if($canDelete==true){
                ?>
                <input class="btn btn-sm btn-danger replyDelete" type="button" value="Delete">
                <?php
                }
            ?>
            <input class="btn btn-sm btn-success replySend" type="button" value="Update">
        </div>
    </div>
</div>
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

