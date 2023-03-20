<?php
    $q=array();
    $type='c';
    $tbl=13;
    $pageTitle='QA Comment';
    if(isset($_GET['type'])){
        if($_GET['type']=='m'){$type='m'; $tbl=9;$pageTitle='QA  Comment';}
        elseif($_GET['type']=='wp'){$type='wp';$tbl=14;$pageTitle='QA  Wall Post';}
        elseif($_GET['type']=='wc'){$type='wc';$tbl=14;$pageTitle='QA  Wall Comment';}
    }
    $users=$db->allUsers('order by uFullName asc');
    $general->arrayIndexChange($users,'uID');
    if(isset($_GET['show'])){
        $date_range=$_GET['date_range'];
    }
    else{
        $dr = date('d-m-Y 00:00:00',YESTERDAY_TIME).'';
        $dr2= date('d-m-Y 00:00:00',YESTERDAY_TIME).'';
        $date_range=$dr.'__'.$dr2;
    }

    $dates=explode('__',$date_range);
    $from_date=strtotime($dates[0]);
    $to_date=strtotime($dates[1]);
    if(date('h:i',$to_date)=='12:00'){
        $to_date=strtotime('+23 hour',$to_date);
        $to_date=strtotime('+59 minute',$to_date);
    }
    $uID=0;
    if(isset($_GET['ag'])){
        $uID    = intval($_GET['ag']);
    }
    $ugID=0;
    if(isset($_GET['ug'])){
        $ugID    = intval($_GET['ug']);
    }

    $wrapups=$db->selectAll($general->table(11));$general->arrayIndexChange($wrapups,'wuID');
    $wrapupCat=$db->selectAll($general->table(34));$general->arrayIndexChange($wrapupCat,'wcID');
    $dateRangeVal=date('d-m-Y h:i A',$from_date).'__'.date('d-m-Y h:i A',$to_date);
    $dateRangeShow=date('d-m-Y h:i A',$from_date).' - '.date('d-m-Y h:i A',$to_date);
    $link=$pUrl.'&type='.$type.'&date_range='.urldecode($date_range).'&&show=Show';
    if($uID!=0){
        $link.='&ag='.$uID;
    }
    if($ugID!=0){
        $users=$db->allUsers(' and ugID='.$ugID);
        if(!empty($users)){
            $general->arrayIndexChange($users,'uID');
            $q[]='c.replyBy in('.implode(',',array_keys($users)).')';
            $link.='&ug='.$ugID;
        }
        else{
            $q[]='c.replyBy=-1';// if any invalid group id then set no result
        }
    }
    $link.='&'.PAGE_INATION_CURRENT_PAGE_NAME.'=';
    /*if(isset($_GET['show'])){*/
    if(isset($_GET['wp'])){
        if($_GET['wp']!=''){
            $q[] = 'c.wuID='.intval($_GET['wp']);
        }
    }
    if(isset($_GET['ag'])){
        if($_GET['ag']==''){
            $q[]= 'c.replyBy!=0';
        }
        else{
            $q[] = 'c.replyBy='.intval($_GET['ag']);
        }
    }
    else{
        $q[] = 'c.replyBy!=0';
    }
    if(isset($_GET['ac'])){
        $ac=$_GET['ac'];
        if($ac=='rp'){
            $q[]="cc.message!=''";
        }
        elseif($ac=='d'){
            $q[]="c.isDone=1";
        }
        elseif($ac=='rm'){
            $q[]="c.replyed=3";
        }
        elseif($ac=='o'){
            $q[]="c.replyed=2";
        }
    }
    $q[]='c.replyed!=0';
    $q[]="c.sender_id!='".PAGE_ID."'";
    $q[]="c.replyTime between ".$from_date." and ".$to_date;
    $sq="where ".implode(" and ",$q);
    if($type=='c'){
        $query  = "
        select 
        c.replyBy,c.comment_id,c.replyTime,c.message,c.target_c_id,c.post_id,c.parent_id,c.created_time,c.assignTime,c.sender_id,c.isDone,c.replyed,c.wuID,c.scentiment,
        cc.message as cmessage,cc.parent_id as cparent_id,cc.created_time as ccreated_time
        from ".$general->table($tbl)." c 
        left join ".$general->table($tbl)." cc on cc.target_c_id=c.comment_id
        ".$sq."
        order by c.created_time desc";

    }
    elseif($type=='wc'){
        $query  = "
        select 
        c.replyBy,c.comment_id,c.replyTime,c.message,c.target_c_id,c.post_id,c.parent_id,c.created_time,c.assignTime,c.sender_id,c.isDone,c.replyed,c.wuID,c.scentiment,
        cc.message as cmessage,cc.parent_id as cparent_id,cc.created_time as ccreated_time
        from ".$general->table($tbl)." c 
        left join ".$general->table($tbl)." cc on cc.target_c_id=c.comment_id
        ".$sq."
        order by c.created_time desc";
    }
    elseif($type=='wp'){
        $query  = "
        select 
        c.replyBy,c.post_id as comment_id,c.replyTime,c.message,c.post_id as target_c_id,c.post_id,c.post_id as parent_id,c.isDone,c.replyed,c.created_time,c.assignTime,c.sender_id,c.wuID,c.scentiment,
        cc.message as cmessage,cc.parent_id as cparent_id,cc.created_time as ccreated_time
        from ".$general->table(12)." c 
        left join ".$general->table($tbl)." cc on cc.target_c_id=c.post_id
        ".$sq."
        order by c.created_time desc";
    }
    if($from_date>=strtotime('-1 day',$to_date)){
        $all_data=$db->fetchQuery($query);
        $totalComment=count($all_data);
        $pageination['start']=1;
    }
    else{
        $cp=1;if(isset($_GET[PAGE_INATION_CURRENT_PAGE_NAME]))$cp=intval($_GET[PAGE_INATION_CURRENT_PAGE_NAME]);
        //echo $general->make_date(time(),'time');
        $pageination=$general->pagination_init_customQuery($query,100,$cp);
        $totalComment=$pageination['total'];
        $all_data=$db->fetchQuery($query.$pageination['limit']);
    }
    //echo $general->make_date(time(),'time');
?>
<div class="x_content">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="animated flipInY col-lg-2 col-md-2 col-sm-3 col-xs-12">
            <div class="tile-stats senti_box">
                <a href="<?php echo $pUrl;?>&type=c">
                    <h3><i class="fa fa-comments-o"></i> Comments</h3>
                </a>
            </div>
        </div>
        <div class="animated flipInY col-lg-2 col-md-2 col-sm-3 col-xs-12">
            <div class="tile-stats senti_box">
                <a href="<?php echo $pUrl;?>&type=wp">
                    <h3><i class="fa fa-comments-o"></i> Wall Post</h3>
                </a>
            </div>
        </div>
        <div class="animated flipInY col-lg-2 col-md-2 col-sm-3 col-xs-12">
            <div class="tile-stats senti_box">
                <a href="<?php echo $pUrl;?>&type=wc">
                    <h3><i class="fa fa-comments-o"></i> Wall Comment</h3>
                </a>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?php echo $pageTitle;?></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a href="<?php echo $pUrl;?>&add=1"><i class="fa fa-plus"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <script>
                    var wallPostType='<?php echo $type;?>';
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
                    var reportHead={
                        name:'QA',
                        title:[
                            {title:"SL",key:'s',w:10},
                            {title:"Comments",key:'c',w:75},
                            {title:"Comments Time",key:'ct',w:23},
                            {title:"Reply",key:'r',w:75},
                            {title:"Reply By",key:'rb',w:30},
                            {title:"Assign Time",key:'at',w:25},
                            {title:"Reply Time",key:'rt',w:23},
                            {title:"HT",key:'aht',w:23},
                            {title:"RT",key:'art',w:23},
                            {title:"Wrapup Caegory",key:'wc',w:20},
                            {title:"Wrap-up",key:'w',w:25},
                            {title:"Scentiment",key:'sc',w:25},
                            {title:"Action",key:'ac',w:25},
                        ],
                        data:[]
                    };

                </script>
                <form method="GET" action="" class="form-inline form_inline" style="margin-bottom: 20px;">
                    <?php echo URL_INFO;?>
                    <?php
                        if(isset($type)){
                        ?>
                        <input type="hidden" name="type" value="<?php echo $type; ?>">
                        <?php
                        }
                    ?>
                    <div class="form-group">
                        <input type="hidden" id="date_range" name="date_range" value="<?php echo $dateRangeVal;?>">
                        <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                            <span><?php echo $dateRangeShow;?></span> <b class="caret"></b>
                        </div>
                    </div>
                    <div class="form-group">
                        <select name="ug" class="form-control select2">
                            <option value="">All Agent Group</option>
                            <?php
                                $groups=$db->allGroups();
                                foreach($groups as $g){?><option value="<?php echo $g['ugID'];?>" <?php echo $general->selected($g['ugID'],@$ugID);?>><?php echo $g['ugTitle'];?></option><?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="ag" class="form-control select2">
                            <option value="">All Agent</option>
                            <?php
                                foreach($users as $u){?><option value="<?php echo $u['uID'];?>" <?php echo $general->selected($u['uID'],@$uID);?>><?php echo $u['uFullName'];?> (<?php echo $u['uLoginName'];?>)</option><?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="wp" class="form-control select2">
                            <option value="">All Wrap-up</option>
                            <?php
                                $wrap=$db->selectAll($general->table(11),'order by wuTitle asc');
                                foreach($wrap as $w){?><option value="<?php echo $w['wuID'];?>" <?php echo $general->selected($w['wuID'],@$_GET['wp']);?>><?php echo $w['wuTitle'];?> (<?php echo $w['wuID'];?>)</option><?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="ac" class="form-control select2">
                            <option value="a">All Action</option>
                            <option value="rp" <?php echo $general->selected('rp',@$_GET['ac']);?>>Replyed</option>
                            <option value="d" <?php echo $general->selected('d',@$_GET['ac']);?>>Done</option>
                            <option value="rm" <?php echo $general->selected('rm',@$_GET['ac']);?>>Removed</option>
                            <option value="o" <?php echo $general->selected('o',@$_GET['ac']);?>>Outbox</option>
                        </select>
                    </div>

                    <input type="submit" value="Show" name="show" class="btn btn-success btn-sm">
                </form>
                Total = <?php echo $totalComment;?> 
                <a style="display: none;" id="replyExport" class="btn btn-default">Export</a>
                <table class="table table-striped table-bordered fixtWidthReport">
                    <tr>
                        <td style="width: 5%;">SL</td>
                        <td style="width: 30%;">Comments</td>
                        <td>Reply</td>
                        <td style="width: 7%;">Wrapup Category</td>
                        <td style="width: 7%;">Wrap-up</td>
                        <td style="width: 7%;">Scentiment</td>
                        <td style="width: 7%;">Action</td>
                        <td style="width: 7%;">AHT<br>ART</td>
                    </tr>
                    <?php
                        $i=$pageination['start'];
                        $post_ids=array();

                        $senderNames=array();
                        //$general->printArray($all_data);
                        foreach($all_data as $ad){
                            $senderNames[$ad['sender_id']]=$ad['sender_id'];
                        }
                        $senderNames=$social->getNamesByUserId($senderNames);
                        $general->arrayIndexChange($senderNames,'id');
                        //$ijk=1;
                        $reporData=array();
                        foreach($all_data as $ad){
                            $m  = str_ireplace('<','&#60;',$ad['message']);
                            $m  = str_ireplace('>','&#62;',$m);
                            $rep= str_ireplace('<','&#60;',$ad['cmessage']);
                            $rep= str_ireplace('>','&#62;',$rep);
                            $rep= str_ireplace('&#60;b&#62;Private&#60;/b&#62;','<b>Private</b>',$rep);

                            $commentTime=date('d-M-Y h:i:s A',$ad['created_time']);
                            if(intval($ad['assignTime'])>1){
                                $assignTime=date('d-M-Y h:i:s A',$ad['assignTime']);
                            }
                            else{
                                $assignTime='';
                            }
                            if(intval($ad['ccreated_time'])<1){
                                $replyTime=date('d-M-Y h:i:s A',$ad['replyTime']);
                            }
                            else{
                                $replyTime=date('d-M-Y h:i:s A',$ad['ccreated_time']);    
                            }

                            $action='';
                            if($ad['isDone']==1){
                                $action.="Done\n <br>";
                            }
                            if($ad['replyed']==3){
                                $action.="Removed\n <br>";
                            }
                            if($ad['replyed']==2){
                                $action.="Outbox\n <br>";
                            }
$ht=$general->makeTimeAvgI($general->timestampDiffInArray($ad['assignTime'],$ad['replyTime'],true));
$rt=$general->makeTimeAvgI($general->timestampDiffInArray($ad['created_time'],$ad['replyTime'],true));
                            $dd=array(
                                's'=>$i,
                                'c'=>$m,
                                'ct'=>$commentTime,
                                'r'=>$rep,
                                'rb'=>@$users[$ad['replyBy']]['uFullName'],
                                'at'=>$assignTime,
                                'rt'=>$replyTime,
                                'aht'=>$ht,
                                'rt'=>$rt,
                                    'wc'=>$wrapupCat[$wrapups[$ad['wuID']]['wcID']]['wcTitle'],
                                'w'=>$wrapups[$ad['wuID']]['wuTitle'],
                                'sc'=>$general->getScentimentName($ad['scentiment']),
                                'ac'=>$action,
                            );
                            $reporData[]=$dd;
                            //if($ijk>2){break; }
                            //$ijk++;
                            $post_ids[$ad['post_id']]=$ad['post_id'];
                        ?>
                        <tr>
                            <td><?php echo $i++;?></td>
                            <td>
                                <?php echo $m; ?><br>
                                <a href="javascript:void();" id="menu<?php echo $ad['comment_id'];?>" data-link="1" data-post_id="<?php echo $ad['post_id'];?>" data-parent_id="<?php echo $ad['parent_id'];?>" data-comment_id="<?php echo $ad['comment_id'];?>" data-moment_show="0"><?php echo $commentTime?></a> 
                            </td>
                            <td>
                                <b><?php echo @$users[$ad['replyBy']]['uFullName'];?></b>
                                <?php
                                    if($rep!=''){
                                        echo ': '. $rep;
                                    ?>
                                    <br><?php echo $assignTime;?> - <a href="javascript:void();" data-link="1" data-post_id="<?php echo $ad['post_id'];?>" data-parent_id="<?php echo $ad['comment_id'];?>" data-comment_id="<?php echo $ad['target_c_id'];?>" data-moment_show="0"><?php echo $replyTime?></a>
                                    <?php
                                    }
                                ?>
                            </td>
                            <td><?php echo $wrapupCat[$wrapups[$ad['wuID']]['wcID']]['wcTitle'];?></td>
                            <td><?php echo $wrapups[$ad['wuID']]['wuTitle'];?></td>
                            <td><?php echo $general->getScentimentName($ad['scentiment']);?></td>
                            <td><?php echo $action;?></td>
                            <td><?php echo $ht;?><br>
                            <?php echo $rt;?></td>
                        </tr>
                        <?php
                        }
                    ?>
                </table>
                <script type="text/javascript">
                    <?php
                        echo 'commentpostids='.json_encode($social->getPostPermalinkUrlBiIds($post_ids,$type)).';
                        ';
                        echo 'reportHead.data='.json_encode($reporData).';';
                    ?>

                    $(document).ready(function(){
                        commentLinkCreate();
                        $('#replyExport').show();
                        $("#replyExport").click(function(){
                            reportJsonToExcel(reportHead); 
                        });
                    });
                </script>
                <?php

                    if($from_date<strtotime('-1 day',$to_date)){
                    ?>
                    <ul class="pagination"><?php $general->pagination($pageination['currentPage'],$pageination['TotalPage'],$link);?></ul>
                    <?php
                    }
                ?>
            </div>
        </div>
    </div>
</div>