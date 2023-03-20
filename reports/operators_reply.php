<?php
    //    ini_set('memory_limit', '20048M');
    //    $a=$db->selectAll($general->table(9));
    //    exit;
    $q=array();
    $type='c';
    $tbl=13;
    $pageTitle='Operators Reply Report <span>Comment</span>';
    if(isset($_GET['type'])){
        if($_GET['type']=='m'){$type='m'; $tbl=9;$pageTitle='Operators Reply Report <span>Message</span>';}
        elseif($_GET['type']=='w'){$type='w';$tbl=14;$pageTitle='Operators Reply Report <span>Wall</span>';}
    }
    if($type=='c'){
        $canDelete=$db->permission(PER_COMMENT_DELETE);    
    }
    else if($type=='w'){
        $canDelete=$db->permission(PER_WALL_DELETE);
    }
    else{
        $canDelete=false;
    }

    $wrapups=$db->selectAll($general->table(11),'order by wuTitle asc');$general->arrayIndexChange($wrapups,'wuID');
    $wrapupCat=$db->selectAll($general->table(34),'order by wcTitle asc');$general->arrayIndexChange($wrapupCat,'wcID');


    $users=$db->allUsers('order by uFullName asc');
    $general->arrayIndexChange($users,'uID');
    if(isset($_GET['date_range'])){
        $date_range=$_GET['date_range'];
        if($_GET['com']!=''){
            if($type=='w'){
                $q[]="(cc.message like'%".$_GET['com']."%' or ccp.message like'%".$_GET['com']."%')";
            }
            else{
                $q[]="cc.message like'%".$_GET['com']."%'";    
            }

        }
        if($_GET['rep']!=''){
            $q[]="c.message like'%".$_GET['rep']."%'";
        }
        if(intval($_GET['sc'])!=0){
            $q[]="c.scentiment =".intval($_GET['sc']);
        }
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
        $to_date=strtotime('+1 day',$to_date);
        $to_date=strtotime('-1 second',$to_date);
    }
    $uID=0;
    if(isset($_GET['ag'])){
        $uID    = intval($_GET['ag']);
    }
    $ugID=0;
    if(isset($_GET['ug'])){
        $ugID    = intval($_GET['ug']);
    }
    $wcID=0;
    if(isset($_GET['wc'])){
        $wcID    = intval($_GET['wc']);
    }
    if($wcID!=0){
        $cats=$db->selectAll($general->table(11),'where wcID='.$wcID);
        if(!empty($cats)){
            $general->arrayIndexChange($cats,'wuID');
            //$general->printArray($cats);
            $q[]='c.wuID in('.implode(',',array_keys($cats)).')';
            $link.='&wc='.$wcID;
        }
        else{
            $q[]='c.replyBy=-1';
        }
    }
    else{
        $wuID=intval(@$_GET['wr']);
        if($wuID!=0){
            $q[]="c.wuID =".$wuID;
        }
    }



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
            $q[]='c.replyBy=-1';
        }
    }
    $link.='&'.PAGE_INATION_CURRENT_PAGE_NAME.'=';
    /*if(isset($_GET['show'])){*/
    if(isset($_GET['ag'])){
        if($_GET['ag']==''){
            $q[]= 'c.replyBy!=0';
        }
        else{
            $q[] = 'c.replyBy='.intval($_GET['ag']);
        }
    }
    else{
        $q[] = 'c.replyBy<>0';
    }
    $q[]="c.replyTime between ".$from_date." and ".$to_date;
    $mq=$q;
    $q[]='c.replyed in(1,3)';
    $q[]="c.sender_id='".PAGE_ID."'";
    $sq="where ".implode(" and ",$q);
    if($type=='c'){
        $query  = "
        select 
        c.replyBy,c.comment_id,c.replyTime,c.message,c.target_c_id,c.post_id,c.parent_id,c.created_time,c.wuID,c.scentiment,
        cc.message as cmessage,cc.parent_id as cparent_id,cc.created_time as ccreated_time,cc.sender_id as sender_id,cc.assignTime as cassignTime,'c' as targetType
        from ".$general->table($tbl)." c 
        left join ".$general->table($tbl)." cc on c.target_c_id=cc.comment_id
        ".$sq."
        order by c.created_time desc";

    }
    elseif($type=='w'){
        $query  = "
        select 
        c.replyBy,c.comment_id,c.replyTime,c.message,c.target_c_id,c.post_id,c.parent_id,c.created_time,c.wuID,c.scentiment,
        ifnull(cc.message,ccp.message) as cmessage, 
        ifnull(cc.parent_id,ccp.post_id) as cparent_id, 
        ifnull(cc.created_time,ccp.created_time) as ccreated_time,
        ifnull(cc.assignTime,ccp.assignTime) as cassignTime,
        ifnull(cc.sender_id,ccp.sender_id) as sender_id,
        CASE WHEN ccp.sender_id IS NOT NULL THEN 'p' ELSE 'c' END AS targetType
        from ".$general->table($tbl)." c 
        left join ".$general->table($tbl)." cc on c.target_c_id=cc.comment_id
        left join ".$general->table(12)." ccp on (c.target_c_id=ccp.post_id or c.target_c_id=ccp.post_id_2)
        ".$sq."
        order by cc.created_time,ccp.created_time desc";
    }
    elseif($type=='m'){
        $mq[]="c.replyed=1";
        $mq[]="c.sendType=2";
        $sq="where ".implode(" and ",$mq);
        $query  = "select c.mid,c.replyBy,c.replyTime,".$general->mDec('text').",c.sendType,c.sendTime as created_time,c.targetSeq,c.sender_id,c.wuID,c.scentiment from ".$general->table($tbl)." c ".$sq.' order by c.sendTime desc';
    }
    $echo=$general->showQuery();
    //    if($from_date>=strtotime('-1 day',$to_date)){
    if($from_date>=strtotime('-1 day',$to_date)||$wcID!=0){
        $all_data=$db->fetchQuery($query,$echo);
        $pageination['start']=1;
        $totalComment=count($all_data);
    }
    else{
        //        echo microtime().' - '.__LINE__.'<br>';
        $cp=1;if(isset($_GET[PAGE_INATION_CURRENT_PAGE_NAME]))$cp=intval($_GET[PAGE_INATION_CURRENT_PAGE_NAME]);
        //echo $general->make_date(time(),'time');
        if($type=='m'){$pp=200;}else{$pp=200;}
        $pageination=$general->pagination_init_customQuery($query,$pp,$cp);
        //        echo microtime().' - '.__LINE__.'<br>';
        $totalComment=$pageination['total'];
        $all_data=$db->fetchQuery($query.$pageination['limit'],$echo);
        //        echo microtime().' - '.__LINE__.'<br>';
    }
    //echo $general->make_date(time(),'time');
?>
<div class="x_content">
    <div class="col-md-12 col-sm-12 col-xs-12">
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
                        name:'Operators_Reply',
                        title:[
                            {title:"SL",key:'s',w:10},
                            {title:"Agent",key:'a',w:30},
                            {title:"Sender",key:'sndr',w:50},
                            {title:"Comments",key:'c',w:75},
                            {title:"Comments Time",key:'ct',w:40},
                            {title:"Assign Time",key:'at',w:40},
                            {title:"Reply",key:'r',w:75},
                            {title:"Reply Time",key:'rt',w:40},
                            {title:"Scentiment",key:'sn',w:25},
                            {title:"Wrapup Caegory",key:'wc',w:20},
                            {title:"Wrapup",key:'w',w:25}
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
                        <div id="reportrange" class="pull-right sky_datepicker" >
                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                            <span><?php echo $dateRangeShow;?></span> <b class="caret"></b>
                        </div>
                    </div>
                    <div class="form-group ">
                        <select name="ug" class="form-control select2 ">
                            <option class="sky_textbox" value="">All Agent Group</option>
                            <?php
                                $groups=$db->allGroups();
                                foreach($groups as $g){?><option value="<?php echo $g['ugID'];?>" <?php echo $general->selected($g['ugID'],@$ugID);?>><?php echo $g['ugTitle'];?></option><?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="ag" class="form-control select2">
                            <option  class="sky_textbox" value="">All Agent</option>
                            <?php
                                foreach($users as $u){?><option value="<?php echo $u['uID'];?>" <?php echo $general->selected($u['uID'],@$uID);?>><?php echo $u['uFullName'];?> (<?php echo $u['uLoginName'];?>)</option><?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" name="com" value="<?php echo @$_GET['com'];?>" class="form-control" placeholder="Comment">
                    </div>

                    <div class="form-group ">
                        <select name="wc" class="form-control select2 ">
                            <option class="sky_textbox" value="">All Wrapup Category</option>
                            <?php
                                $cats=$db->selectAll($general->table(34),'where isActive=1');
                                foreach($cats as $g){?><option value="<?php echo $g['wcID'];?>" <?php echo $general->selected($g['wcID'],@$wcID);?>><?php echo $g['wcTitle'];?></option><?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="wr" class="form-control select2">
                            <option  class="sky_textbox" value="">All Wrapup</option>
                            <?php
                                foreach($wrapupCat as $wc){
                                ?><optgroup label="<?php echo $wc['wcTitle'];?>"><?php
                                    foreach($wrapups as $w){
                                        if($w['wcID']==$wc['wcID']){
                                        ?><option value="<?php echo $w['wuID'];?>" <?php echo $general->selected($w['wuID'],@$_GET['wr']);?>><?php echo $w['wuTitle'];?></option><?php
                                        }
                                    }
                                ?></optgroup><?php
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="sc" class="form-control select2">
                            <option  class="sky_textbox" value="">All Scentiment</option>
                            <option value="<?php echo SCENTIMENT_TYPE_POSITIVE;?>" <?php echo $general->selected(SCENTIMENT_TYPE_POSITIVE,@$_GET['sc']);?>><?php echo $general->getScentimentName(SCENTIMENT_TYPE_POSITIVE);?></option>
                            <option value="<?php echo SCENTIMENT_TYPE_NUTRAL;?> <?php echo $general->selected(SCENTIMENT_TYPE_NUTRAL,@$_GET['sc']);?>"><?php echo $general->getScentimentName(SCENTIMENT_TYPE_NUTRAL);?></option>
                            <option value="<?php echo SCENTIMENT_TYPE_NEGETIVE;?>" <?php echo $general->selected(SCENTIMENT_TYPE_NEGETIVE,@$_GET['sc']);?>><?php echo $general->getScentimentName(SCENTIMENT_TYPE_NEGETIVE);?></option>

                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" name="rep" value="<?php echo @$_GET['rep'];?>" class="form-control" placeholder="Reply">
                    </div>

                    <input type="submit" value="Show" name="show" class="btn btn-success btn-sm">
                </form>
                Total = <?php echo $totalComment;?> 
                <a style="display: none;" id="replyExport" class="btn btn-default">Export</a>
                <table class="table table-striped table-bordered fixtWidthReport">
                    <tr>
                        <td style="width: 5%;">SL</td>
                        <td style="width: 10%;">Agent</td>
                        <td >Comments</td>
                        <td style="width: 30%;">Reply</td>
                        <td style="width: 7%;">Wrapup Category</td>
                        <td style="width: 7%;">Wrapup</td>
                        <td style="width: 6%;">Sentiment</td>
                    </tr>
                    <?php

                        //$general->printArray($senderNames);
                        $i=$pageination['start'];
                        $post_ids=array();
                        if($type=='m'){
                            $senderIds=array();
                            //$general->printArray($all_data);
                            foreach($all_data as $ad){
                                $senderIds[$ad['sender_id']]=$ad['sender_id'];
                            }
                            //$general->printArray($senderIds);
                            $senderNames=$social->messageSendersName($senderIds);

                            //$general->printArray($senderNames);


                            $reporData=array();
                            foreach($all_data as $key => $ad){
                                $senderName='';
                                if(array_key_exists($ad['sender_id'],$senderNames)){$senderName=$senderNames[$ad['sender_id']]['senderName'];}
                                $rpMsg=array();
                                if(0){//কোন মেসেজের প্রেক্ষিতে রিপ্লাই দিল সেট আপাতত অফ রাখলাম
                                    $query="select targetSeq from ".$general->table($tbl)." where sender_id='".$ad['sender_id']."' and sendType=2 and targetSeq<".$ad['targetSeq']." order by targetSeq desc limit 1";
                                    $a=$db->fetchQuery($query,$echo);
                                    if(!empty($a)){
                                        $old=$a[0]['targetSeq'];
                                        $query="select mid,".$general->mDec('text').",url,sendTime from ".$general->table($tbl)." where sender_id='".$ad['sender_id']."' and sendType=1 and seq>".$old." and seq<=".$ad['targetSeq']." order by targetSeq desc";
                                        //$query="select mid,text,url,sendTime from ".$general->table($tbl)." where sender_id='".$ad['sender_id']."' and sendType=1 and seq>".$old." and seq<=".$ad['targetSeq']." order by targetSeq desc";
                                        $a=$db->fetchQuery($query,$echo);
                                        if(!empty($a)){
                                            foreach($a as $ms){
                                                //$rpMsg[]=$ms['text'];
                                                $rpMsg[]=array('i'=>$ms['mid'],'t'=>$ms['text'],'u'=>$ms['url'],'sendTime'=>$ms['sendTime']);
                                            }
                                        }

                                    }
                                    else{
                                        $query="select mid,".$general->mDec('text').",sendTime,wuID,scentiment from ".$general->table($tbl)." where sender_id='".$ad['sender_id']."' and sendType=1 and seq=".$ad['targetSeq']." order by targetSeq desc";
                                        //                                    $query="select mid,text,sendTime,wuID,scentiment from ".$general->table($tbl)." where sender_id='".$ad['sender_id']."' and sendType=1 and seq=".$ad['targetSeq']." order by targetSeq desc";
                                        $a=$db->fetchQuery($query,$echo);
                                        if(!empty($a)){
                                            foreach($a as $ms){
                                                $rpMsg[]=array('t'=>$ms['text'],'sendTime'=>$ms['sendTime']);
                                            }
                                        }
                                    }
                                }
                                $m='';
                                if(!empty($rpMsg)){
                                    $sr=1;
                                    //$general->printArray($rpMsg);
                                    foreach($rpMsg as $ms){
                                        if(count($rpMsg)==1){
                                            //$general->printArray($ms);
                                            $m.=$ms['t'];
                                        }
                                        else{
                                            if($sr==1){
                                                $m.=$sr."-> ".$ms['t'];
                                            }
                                            else{
                                                $m.="\n".$sr."-> ".$ms['t'];
                                            }
                                        }
                                        $sr++;
                                    }
                                }

                                $dd=array(
                                    's'=>$i,
                                    'a'=>@$users[$ad['replyBy']]['uFullName'],
                                    'sndr'=>$senderName,
                                    'c'=>$m,
                                    'ct'=>'',
                                    'at'=>'',
                                    'r'=>$ad['text'],
                                    'rt'=>'',
                                    'sn'=>$general->getScentimentName($ad['scentiment']),
                                    'wc'=>$wrapupCat[$wrapups[$ad['wuID']]['wcID']]['wcTitle'],
                                    'w'=>$wrapups[$ad['wuID']]['wuTitle']
                                );
                                foreach($rpMsg as $ms){
                                    //$m.=$ms['i'].'<br>'; 
                                    $m.='<div class="eachmsg">'.$ms['t'].'<br>';
                                    if($ms['u']!=''){
                                        $mu=json_decode($ms['u'],true);
                                        if(!empty($mu)){
                                            foreach($mu as $mmi){
                                                if($mmi['type']=='image'){
                                                    $m.='<img src="'.$mmi['url'].'" style="max-width:200px;"><br>';
                                                }
                                                else{
                                                    $m.=$mmi['type'].'<br>'; 
                                                }
                                            }
                                        }
                                        else{
                                            $m.='<img src="'.$ms['u'].'" style="max-width:200px;"><br>';
                                        }
                                    }
                                    $m.= date('d-m-y h:i:s A',$ms['sendTime']);
                                    $m.='</div>';
                                }
                                $trId='rt_'.str_ireplace('$','',$ad['mid']);
                                $trId=str_ireplace('.','',$trId);
                                $dd['id']=$trId;
                                $reporData[]=$dd;
                            ?>
                            <tr id="<?php echo $trId;?>">
                                <td>
                                    <?php echo $i++;?>
                                    <input type="hidden" class="rid" value="<?php echo $ad['sender_id'].'_'.$ad['targetSeq'];?>">
                                </td>
                                <td><?php echo $users[$ad['replyBy']]['uFullName'];?></td>
                                <td class="replyClass">
                                    <?php 
                                        //$general->printArray($rpMsg);
                                        //if($i>20)break;
                                        echo '<b>'.$senderName.'</b> :<br>';
                                        //echo '<b>'.$ad['sender_id'].'</b> :<br>';
                                        //echo microtime().'<br>';
                                    ?>
                                    <div class="msgMin"><span class="msgMint">-</span><br><a href="javascript:void();" onclick="commentFullView('<?php echo $trId;?>')">Full</a></div>
                                    <div class="msgMax" style="display: none"><span class="msgMaxt">-</span><br><a href="javascript:void();" onclick="commentFullView('<?php echo $trId;?>')">Less</a></div>
                                </td>
                                <td>
                                    <?php echo $ad['text']; ?><br>
                                    <?php echo date('d-m-y h:i:s A',$ad['created_time']);?> 
                                </td>
                                <td><?php echo $wrapupCat[$wrapups[$ad['wuID']]['wcID']]['wcTitle'];?></td>
                                <td><?php echo $wrapups[$ad['wuID']]['wuTitle'];?></td>
                                <td><?php echo $general->getScentimentName($ad['scentiment']);?></td>
                            </tr>
                            <?php
                            } 
                        }
                        else{
                            $senderNames=array();
                            foreach($all_data as $ad){
                                $senderNames[$ad['sender_id']]=$ad['sender_id'];
                            }
                            $senderNames=$social->getNamesByUserId($senderNames);
                            $general->arrayIndexChange($senderNames,'id');
                            //$ijk=1;
                            $reporData=array();
                            foreach($all_data as $ad){
                                $m=str_ireplace('<','&#60;',$ad['cmessage']);
                                $m=str_ireplace('>','&#62;',$m);
                                $rep=str_ireplace('<','&#60;',$ad['message']);
                                $rep=str_ireplace('>','&#62;',$rep);
                                $rep=str_ireplace('&#60;b&#62;Private&#60;/b&#62;','<b>Private</b>',$rep);

                                $commentTime=date('d-M-Y h:i:s A',$ad['ccreated_time']);
                                $replyTime=date('d-M-Y h:i:s A',$ad['created_time']);
                                $dd=array(
                                    's'=>$i,
                                    'a'=>@$users[$ad['replyBy']]['uFullName'],
                                    'sndr'=>$senderNames[$ad['sender_id']]['name'],
                                    'c'=>$m,
                                    'ct'=>$commentTime,
                                    'at'=>date('d-m-Y h:i:s A',$ad['cassignTime']),
                                    'r'=>$rep,
                                    'rt'=>$replyTime,
                                    'sn'=>$general->getScentimentName($ad['scentiment']),
                                    'wc'=>$wrapupCat[$wrapups[$ad['wuID']]['wcID']]['wcTitle'],
                                    'w'=>$wrapups[$ad['wuID']]['wuTitle'],
                                );
                                $reporData[]=$dd;
                                //if($ijk>2){break; }
                                //$ijk++;
                                $post_ids[$ad['post_id']]=$ad['post_id'];
                                $trId='rt_'.str_ireplace('$','',$ad['comment_id']);
                                $trId=str_ireplace('.','',$trId);
                            ?>
                            <tr id="<?php echo $trId;?>">
                                <td><?php echo $i++;?></td>
                                <td>
                                    <?php echo @$users[$ad['replyBy']]['uFullName'];?><br><br><?php echo date('h:i:s A',$ad['cassignTime']);?>
                                    <?php
                                        echo'<br>'; 
                                        echo $general->makeTimeAvgI($general->timestampDiffInArray($ad['cassignTime'],$ad['created_time'],true));
                                    ?>
                                </td>
                                <td>
                                    <b><?php echo $senderNames[$ad['sender_id']]['name'];?> : </b><br>
                                    <div class="msgMin"><?php echo $general->word_limit($m,10); ?><br><a href="javascript:void();" onclick="commentFullView('<?php echo $trId;?>')">Full</a></div>
                                    <div class="msgMax" style="display: none"><?php echo $m; ?><br><a href="javascript:void();" onclick="commentFullView('<?php echo $trId;?>')">Less</a></div>
                                    <div class="dropdown disinline">
                                        <a class="dropdown-toggle" href="javascript:void();" data-toggle="dropdown"><?php echo $commentTime?></a>
                                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu<?php echo $ad['target_c_id'];?>">
                                            <li>
                                                <a href="javascript:void();" id="menu<?php echo $ad['target_c_id'];?>" data-link="1" data-post_id="<?php echo $ad['post_id'];?>" data-parent_id="<?php echo $ad['cparent_id'];?>" data-comment_id="<?php echo $ad['target_c_id'];?>" data-moment_show="0">FB</a> 
                                            </li>
                                            <li><a href="javascript:void();" onclick="sentCommentHide('<?php echo $ad['target_c_id'];?>')">Hide</a></li>
                                            <?php
                                                if($canDelete==true){
                                                ?><li><a href="javascript:void();" onclick="sentCommentDelete('<?php echo $ad['comment_id'];?>','<?php echo $ad['target_c_id'];?>','c')">Delete</a></li><?php
                                                }
                                            ?>
                                        </ul>
                                    </div>
                                </td>
                                <td>
                                    <div class="msgMin"><?php echo $general->word_limit($rep,10);; ?><a href="javascript:void();" onclick="commentFullView('<?php echo $trId;?>')">Full</a></div>
                                    <div class="msgMax" style="display: none;"><div class="replyHtml"><?php echo $rep; ?></div><a href="javascript:void();" onclick="commentFullView('<?php echo $trId;?>')">Less</a></div>
                                    <div class="replyEdit" style="display: none;"></div>

                                    <a href="javascript:void();" data-link="1" data-post_id="<?php echo $ad['post_id'];?>" data-parent_id="<?php echo $ad['parent_id'];?>" data-comment_id="<?php echo $ad['comment_id'];?>" data-moment_show="0"><?php echo $replyTime?></a>
                                    <?php
                                        if(!preg_match('@^<b>Private</b>@',$rep)){
                                        ?>
                                        <button onclick="show_edit_box('<?php echo $ad['comment_id']?>','<?php echo $ad['targetType'];?>','<?php echo $trId;?>')" class="btn btn-sm btn-default edit_button disinline">Action</button>
                                        <?php
                                        }
                                    ?>

                                </td>
                                <td><?php echo $wrapupCat[$wrapups[$ad['wuID']]['wcID']]['wcTitle'];?></td>
                                <td><?php echo $wrapups[$ad['wuID']]['wuTitle'];?></td>
                                <td><?php echo $general->getScentimentName($ad['scentiment']);?></td>
                            </tr>
                            <?php
                            }
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
                        <?php 
                            if($type=='m'){
                            ?>
                            var i=0;
                            rpUsers=[];
                            $('.rid').each(function(){
                                var tID=$(this).closest('tr').attr('id');
                                var inf=$(this).val();
                                rpUsers[i]={id:tID,inf:inf}
                                i++;
                            });
                            t(rpUsers);
                            loadOperatorsReplySendersMessages(0);
                            <?php
                            }
                        ?>
                        commentLinkCreate();
                        $('#replyExport').show();
                        $("#replyExport").click(function(){
                            reportJsonToExcel(reportHead); 
                        });
                    });
                    function loadOperatorsReplySendersMessages(index){
                        //t(index)
                        if(rpUsers.length>index){
                            //t(rpUsers.length)
                            var cRq={};
                            var i=0;
                            for(i=index;i<index+10;i++){
                                if(typeof rpUsers[index] !== 'undefined') {
                                    cRq[i]=rpUsers[i];
                                }
                            }
                            t(cRq);
                            $.post(ajUrl+'&op',{loadOperatorsReplySendersMessages:cRq},function(data){
                                if(data.status==1){
                                    $.each(data.rt,function(tID,ac){
                                        //alert(tID+' '+ac.t)
                                        $('#'+tID+' .msgMint').html(ac.t);
                                        $('#'+tID+'.msgMaxt').html(ac.s);
                                        $.each(reportHead.data,function(key,value){
                                            if(value.id==tID){
                                                reportHead.data[key].c=ac.j;
                                            }
                                        });
                                    });
                                    loadOperatorsReplySendersMessages(i);

                                }
                            });

                        }
                        else{
                            //t('close '+index);
                        }

                    }
                </script>
                <?php

                    if($from_date<strtotime('-1 day',$to_date)||$type=='m'){
                    ?>
                    <ul class="pagination"><?php $general->pagination($pageination['currentPage'],$pageination['TotalPage'],$link);?></ul>
                    <?php
                    }
                ?>
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
    </div>
</div>