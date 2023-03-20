<?php

    $q=array();
    $type='c';
    $tbl=13;
    if(isset($_GET['type'])){
        if($_GET['type']=='w'){$type='w';$tbl=14;}
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
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?php echo $rModule['cmTitle'];?></h2>
                    <div class="clearfix"></div>
                </div>
                <?php
                    if(isset($_GET['show'])){
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
                    if(isset($_GET['group'])){
                        $ugID    = intval($_GET['group']);
                    }
                    else{
                        $ugID=0;
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
                        <select name="ag" class="form-control select2">
                            <option value="">All Users</option>
                            <?php
                                $users=$db->allUsers('order by uFullName asc');
                                foreach($users as $u){?><option value="<?php echo $u['uID'];?>" <?php echo $general->selected($u['uID'],@$uID);?>><?php echo $u['uFullName'];?></option><?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="group" class="form-control select2">
                            <option value="">All Groups</option>
                            <?php
                                $groups=$db->allGroups('order by ugTitle asc');
                                foreach($groups as $g){?><option value="<?php echo $g['ugID'];?>" <?php echo $general->selected($g['ugID'],@$ugID);?>><?php echo $g['ugTitle'];?></option><?php } ?>
                        </select>
                    </div>
                    <input type="submit" value="Show" name="show" class="btn btn-success btn-sm">
                </form>
                <?php
                    $general->arrayIndexChange($users,'uID');
                    $link=$pUrl.'&type='.$type.'&date_range='.urldecode($date_range).'&&show=Show';
                    if($uID!=0){
                        $link.='&ag='.$uID;
                    }
                    if($ugID!=0){
                        $link.='&group='.$ugID;
                    }
                    $link.='&'.PAGE_INATION_CURRENT_PAGE_NAME.'=';
                    /*if(isset($_GET['show'])){*/
                    if(isset($_GET['ag'])){
                        if($_GET['ag']==''){
                            $q[]= 'c.uID!=0';
                        }
                        else{
                            $q[] = 'c.uID='.intval($_GET['ag']);
                        }
                    }
                    else{
                        $q[] = 'c.uID<>0';
                    } 
                    if(isset($_GET['group'])){
                        if($_GET['group']==''||$_GET['group']=='0'){
                            $q[]= 'c.ugID!=0';
                        }
                        else{
                            $ugID=intval($_GET['group']);
                            $sGroup=$db->allUsers(' and ugID='.$ugID);
                            if(!empty($sGroup)){
                                $general->arrayIndexChange($sGroup,'uID');
                                $q[]='c.uID in('.implode(',',array_keys($sGroup)).')';
                            }
                            else{
                                $q[]='c.uID=-1';
                            }
                            //$q[] = 'c.ugID='.intval($_GET['group']);
                        }
                    }
                    /*else{
                    $q[] = 'c.ugID<>0';
                    }*/
                    $q[]="c.transfer_time between ".$from_date." and ".$to_date;
                    $sq="where ".implode(" and ",$q);
                    if($type=='c'){
                        $query  = "
                        select 
                        c.transfer_time,c.uID,ca.created_time,ca.message,cu.ugTitle  
                        from ".$general->table(54)." c 
                        left join   ".$general->table(13)." ca on(ca.comment_id=c.comment_id) 
                        left join  ".$general->table(22)." cu on(cu.ugID = c.ugID)
                        ".$sq." order by c.transfer_time asc";
                    }
                    elseif($type=='m'){
                        $query  = "select c.replyBy,c.replyTime,c.text,c.sendType,c.created_time from ".$general->table($tbl)." c where c.replyed=1 and c.sendType=2 and c.replyTime between ".$from_date." and ".$to_date;  
                    }
                    elseif($type='w'){
                        $query  = "
                        select 
                        c.transfer_time,c.uID,pw.created_time,pw.message,cu.ugTitle  
                        from ".$general->table(56)." c 
                        left join   ".$general->table(12)." pw on(pw.post_id=c.post_id) 
                        left join  ".$general->table(22)." cu on(cu.ugID = c.ugID)
                        ".$sq." 
                        union
                        select 
                        c.transfer_time,c.uID,cw.created_time,cw.message,cu.ugTitle  
                        from ".$general->table(55)." c 
                        left join   ".$general->table(14)." cw on(cw.comment_id=c.comment_id) 
                        left join  ".$general->table(22)." cu on(cu.ugID = c.ugID)
                        ".$sq." order by transfer_time asc";
                    }
                    $reporData=array(
                        'name'=>'Transfer_Report_'.date('d_m_Y',$from_date).'_'.date('d_m_Y',$to_date),
                        'title'=>array(
                            array('title'=>"SL"             ,'key'=>'s'     ,'w'=>10    ,'hw'=> 3),
                            array('title'=>"Comments"       ,'key'=>'c'     ,'w'=>55),
                            array('title'=>"Agent"          ,'key'=>'a'     ,'w'=>20    ,'hw'=> 12),
                            array('title'=>"Agent Group"    ,'key'=>'ag'    ,'w'=>10    ,'hw'=> 12),
                            array('title'=>"Trans. Group"   ,'key'=>'tg'    ,'w'=>15    ,'hw'=> 12),
                            array('title'=>"Create Time"    ,'key'=>'ct'    ,'w'=>25    ,'hw'=> 15),
                            array('title'=>"Trans. Time"    ,'key'=>'tt'    ,'w'=>25    ,'hw'=> 15)
                        )
                    );
                    if($from_date>=strtotime('-1 day',$to_date)){
                        $all_data=$db->fetchQuery($query);
                        $pageination['start']=1;
                        $pageination['total']=count($all_data);
                    }
                    else{
                        $cp=1;if(isset($_GET[PAGE_INATION_CURRENT_PAGE_NAME]))$cp=intval($_GET[PAGE_INATION_CURRENT_PAGE_NAME]);
                        $pageination=$general->pagination_init_customQuery($query,100,$cp);
                        $all_data=$db->fetchQuery($query.$pageination['limit']);
                    }
                    $i=$pageination['start'];
                    $general->arrayIndexChange($groups,'ugID');
                    foreach($all_data as $data){
                        $reporData['data'][]=array(
                            's'=>$i++,
                            'c'  =>$general->content_show($data['message']),
                            'a'  =>$users[$data['uID']]['uFullName'],
                            'ag'  =>$groups[$users[$data['uID']]['ugID']]['ugTitle'],
                            'tg'  =>$data['ugTitle'],
                            'ct'  =>$general->make_date($data['created_time'],'time'),
                            'tt'  =>$general->make_date($data['transfer_time'],'time'),
                        );
                    }
                    $sReport->arrayReportTable($reporData);
                    if($from_date<=strtotime('-1 day',$to_date)){
                    ?>
                    <ul class="pagination"><?php $general->pagination($pageination['currentPage'],$pageination['TotalPage'],$link);?></ul>
                    <?php
                    }
                ?>
            </div>
        </div>
    </div>
</div>
<script>                                         
    <?php echo "var eIndex = ".json_encode($jArray).";";?>
    $("#exportTransfer").click(function(){
        reportExportToExcel(eIndex);
    });
</script>