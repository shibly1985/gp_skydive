<?php
    $wrapups=$db->selectAll($general->table(11),'order by wuTitle asc');$general->arrayIndexChange($wrapups,'wuID');
    $wrapupCat=$db->selectAll($general->table(34),'order by wcTitle asc');$general->arrayIndexChange($wrapupCat,'wcID');
    $q=array();
    $type='c';
    $tbl=13;
    if(isset($_GET['type'])){
        if($_GET['type']=='m'){$type='m'; $tbl=9;}
        elseif($_GET['type']=='w'){$type='w';$tbl=12;}
        elseif($_GET['type']=='wc'){$type='wc';$tbl=14;}
    }
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
        <a href="<?php echo $pUrl;?>&type=w">
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
            <h2><?php echo $rModule['cmTitle'];?></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a href="<?php echo $pUrl;?>&add=1"><i class="fa fa-plus"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <?php
            if(isset($_GET['date_range'])){
                $date_range=$_GET['date_range'];
                if($_GET['com']!=''){
                    $q[]="c.message like'%".$_GET['com']."%'";
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
                $to_date=strtotime('+23 hour',$to_date);
                $to_date=strtotime('+59 minute',$to_date);
            }
            if(isset($_GET['ag'])){
                $uID    = intval($_GET['ag']);
            }
            else{
                $uID=0;
            }
            $ugID=0;
            if(isset($_GET['ug'])){
                $ugID    = intval($_GET['ug']);
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
                    <option value="">All Groups</option>
                    <?php
                        $groups=$db->allGroups('order by ugTitle asc');
                        foreach($groups as $g){?><option value="<?php echo $g['ugID'];?>" <?php echo $general->selected($g['ugID'],@$ugID);?>><?php echo $g['ugTitle'];?></option><?php } ?>
                </select>
            </div>
            <div class="form-group">
                <select name="ag" class="form-control select2">
                    <option value="">All User</option>
                    <?php
                        $users=$db->allUsers('order by uFullName asc');
                        foreach($users as $u){?><option value="<?php echo $u['uID'];?>" <?php echo $general->selected($u['uID'],@$uID);?>><?php echo $u['uFullName'];?></option><?php } ?>
                </select>
            </div>
            <div class="form-group">
                <input type="text" name="com" value="<?php echo @$_GET['com'];?>" class="form-control" placeholder="Comment">
            </div>

            <input type="submit" value="Show" name="show" class="btn btn-success btn-sm">
        </form>
        <?php
            $general->arrayIndexChange($users,'uID');
            $link=$pUrl.'&type='.$type.'&date_range='.urldecode($date_range).'&&show=Show';
            if($uID!=0){
                $link.='&ag='.$uID;
            }
            $link.='&'.PAGE_INATION_CURRENT_PAGE_NAME.'=';
            /*if(isset($_GET['show'])){*/
            if($ugID!=0){
                $gUsers=$db->allUsers(' and ugID='.$ugID);
                if(!empty($gUsers)){
                    $general->arrayIndexChange($gUsers,'uID');
                    $q[]='c.replyBy in('.implode(',',array_keys($gUsers)).')';
                    $link.='&ug='.$ugID;
                }
                else{
                    $q[]='c.replyBy=-1';
                }
            }
            elseif(isset($_GET['ag'])){
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
            $q[]='c.isDone=1';
            $q[]="c.replyTime between ".$from_date." and ".$to_date;
            $sq="where ".implode(" and ",$q);
            if($type=='c'){
                $query  = "
                select 
                c.replyBy,c.comment_id,c.replyTime,c.message,c.photo,c.post_id,c.parent_id,c.created_time,c.sender_id,c.wuID,c.scentiment
                from ".$general->table($tbl)." c
                ".$sq."
                order by c.created_time desc";

            }
            elseif($type=='m'){
                $query  = "select c.replyBy,c.replyTime,c.text,c.sendType,c.created_time from ".$general->table($tbl)." c where c.replyed=1 and c.sendType=2 and c.replyTime between ".$from_date." and ".$to_date;  
            }
            elseif($type=='w'){
                $query  = "
                select 
                c.replyBy,c.post_id,c.replyTime,c.message,c.link as photo,c.post_id,c.created_time,c.sender_id,,c.wuID,c.scentiment
                from ".$general->table($tbl)." c 
                ".$sq."
                order by c.created_time desc";
            }
            elseif($type=='wc'){
                $query  = "
                select 
                c.replyBy,c.comment_id,c.replyTime,c.message,c.photo,c.post_id,c.parent_id,c.created_time,c.sender_id,,c.wuID,c.scentiment
                from ".$general->table($tbl)." c 
                ".$sq."
                order by c.created_time desc";
            }
            if($from_date>=strtotime('-1 day',$to_date)){
                $all_data=$db->fetchQuery($query,$general->showQuery());
                $pageination['start']=1;
                $pageination['total']=count($all_data);
            }
            else{
                $cp=1;if(isset($_GET[PAGE_INATION_CURRENT_PAGE_NAME]))$cp=intval($_GET[PAGE_INATION_CURRENT_PAGE_NAME]);
                //echo $general->make_date(time(),'time');
                $pageination=$general->pagination_init_customQuery($query,200,$cp);
                $totalComment=$pageination['total'];
                $all_data=$db->fetchQuery($query.$pageination['limit'],$general->showQuery());
            }

            $senderNames=array();
            foreach($all_data as $ad){
                $senderNames[$ad['sender_id']]=$ad['sender_id'];
            }
            $senderNames=$social->getNamesByUserId($senderNames);
            $general->arrayIndexChange($senderNames,'id');
        ?>
        Total = <?php echo $pageination['total'];?> 
        <?php show_msg();?>
        <a class="btn btn-default" id="exportBtn" style="display: none;">Export</a>
        <table class="table table-striped table-bordered">
            <tr>
                <td>SL</td>
                <td style="width: 15%;">Agent</td>
                <td>Comments</td>
                <td>Scentiment</td>
                <td>Wrapup Caegory</td>
                <td>Wrapup</td>
            </tr>
            <?php
                //$general->printArray($senderNames);
                $i=$pageination['start'];
                $post_ids=array();
                $senderNames=array();
                foreach($all_data as $ad){
                    $senderNames[$ad['sender_id']]=$ad['sender_id'];
                }
                $senderNames=$social->getNamesByUserId($senderNames);
                $general->arrayIndexChange($senderNames,'id');
                //$ijk=1;
                $reporData=array(
                    'name'=>'Done_Report_'.date('d_m_Y',$from_date).'_'.date('d_m_Y',$to_date),
                    'title'=>array(
                        array('title'=>"SL"         ,'key'=>'s' ,'w'=>10    ,'hw'=> 3),
                        array('title'=>"Sender"     ,'key'=>'sn','w'=>25    ,'hw'=> 12),
                        array('title'=>"Comments"   ,'key'=>'c' ,'w'=>55),
                        array('title'=>"Create Time",'key'=>'ct','w'=>25    ,'hw'=> 15),
                        array('title'=>"Agent"      ,'key'=>'a' ,'w'=>25    ,'hw'=> 12),
                        array('title'=>"Done Time"  ,'key'=>'d' ,'w'=>25    ,'hw'=> 15),
                        array('title'=>"Scentiment"  ,'key'=>'sn','w'=>25    ,'hw'=> 15),
                        array('title'=>"Wrapup Caegory"  ,'key'=>'wc','w'=>20    ,'hw'=> 15),
                        array('title'=>"Wrapup"  ,'key'=>'wc','w'=>25    ,'hw'=> 15)
                    )
                );

                foreach($all_data as $ad){
                    
                    $data=array(
                        's' =>$i,
                        'sn'=>$senderNames[$ad['sender_id']]['name'],
                        'c' =>$general->content_show($ad['message']),
                        'ct'=>$general->make_date($ad['created_time'],'time'),
                        'a'=>@$users[$ad['replyBy']]['uFullName'],
                        'd'=>$general->make_date($ad['replyTime'],'time'),
                        'sn'=>$general->getScentimentName($ad['scentiment']),
                        'wc'=>$wrapupCat[$wrapups[$ad['wuID']]['wcID']]['wcTitle'],
                        'w'=>$wrapups[$ad['wuID']]['wuTitle']
                    );
                    $reporData['data'][]=$data;

                    $post_ids[$ad['post_id']]=$ad['post_id'];
                ?>
                <tr>
                    <td><?php echo $i++;?></td>
                    <td><b><?php echo @$users[$ad['replyBy']]['uFullName'];?></b><br><?php echo date('d-M-Y h:i:s A',$ad['replyTime']);?></td>
                    <td><b><?php echo $senderNames[$ad['sender_id']]['name'];?> : </b>
                        <?php
                            if($ad['photo']!=''){
                            ?><img src="<?php echo $ad['photo'];?>" style="max-height: 100px;max-width: 100px;"><?php
                            }
                        ?>
                        <?php echo $general->content_show($ad['message']);?><a href="javascript:void();" data-link="1" data-post_id="<?php echo $ad['post_id'];?>" data-parent_id="<?php echo $ad['parent_id'];?>" data-comment_id="<?php echo $ad['comment_id'];?>" data-moment_show="0"> -<?php echo date('d-M-Y h:i:s A',$ad['created_time']);?></a></td>
                        <td><?php echo $data['sn'];?></td>
                        <td><?php echo $data['wc'];?></td>
                        <td><?php echo $data['w'];?></td>
                </tr>
                <?php
                }
            ?>
        </table>
        <script type="text/javascript">
            <?php
                echo 'commentpostids='.json_encode($social->getPostPermalinkUrlBiIds($post_ids,$type)).';
                ';
                echo 'reportHead='.json_encode($reporData).';';?> 
            $(document).ready(function(){
                commentLinkCreate();
                $('#exportBtn').show();
                $("#exportBtn").click(function(){reportJsonToExcel(reportHead);});
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