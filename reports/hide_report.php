<?php
    $wrapups=$db->selectAll($general->table(11),'order by wuTitle asc');$general->arrayIndexChange($wrapups,'wuID');
    $wrapupCat=$db->selectAll($general->table(34),'order by wcTitle asc');$general->arrayIndexChange($wrapupCat,'wcID');
    $q=array();
    $type='c';
    if(isset($_GET['type'])){
        if($_GET['type']=='w'){$type='w';}
        elseif($_GET['type']=='wc'){$type='wc';}
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
    <?php if(isset($type)){ ?><input type="hidden" name="type" value="<?php echo $type; ?>"><?php }?>
    <div class="form-group">
        <input type="hidden" id="date_range" name="date_range" value="<?php echo $dateRangeVal;?>">
        <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
            <span><?php echo $dateRangeShow;?></span> <b class="caret"></b>
        </div>
    </div>
    <div class="form-group">
        <select name="rangeType" class="form-control select2">
            <option value="0">Create Time</option>
            <option value="1">Hide Time</option>
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
    if(isset($_GET['ag'])){
        if($_GET['ag']!=''){
            $q[] = 'ch.uID='.intval($_GET['ag']);
        }
    }
    if(isset($_GET['rangeType'])){
        if($_GET['rangeType']==1){
            $q[]="ch.hideTime between ".$from_date." and ".$to_date;
        }
        else{
            $q[]="c.created_time between ".$from_date." and ".$to_date;    
        }
    }
    else{
        $q[]="c.created_time between ".$from_date." and ".$to_date;    
    }
    $sq="where ".implode(" and ",$q);
    if($type=='c'){
        $query="
        select 
        c.comment_id,c.post_id,c.sender_id,c.message,c.created_time,c.parent_id,c.wuID,c.scentiment,
        ch.hideTime,
        u.uFullName
        from ".$general->table(31)." ch
        left join ".$general->table(13)." c on c.comment_id=ch.comment_id
        left join ".$general->table(17)." u on u.uID=ch.uID
        ".$sq."
        order by c.created_time desc
        ";
    }
    elseif($type=='w'){
        $query="
        select 
        c.post_id,c.sender_id,c.message,c.created_time,'' as parent_id,c.post_id as comment_id,c.wuID,c.scentiment,
        ch.hideTime,
        u.uFullName
        from ".$general->table(35)." ch
        left join ".$general->table(12)." c on c.post_id=ch.post_id
        left join ".$general->table(17)." u on u.uID=ch.uID
        ".$sq."
        order by c.created_time desc
        ";
    }
    elseif($type='wc'){
        $query="
        select 
        c.post_id as comment_id,c.post_id,c.sender_id,c.message,c.created_time,c.parent_id,c.wuID,c.scentiment,
        ch.hideTime,
        u.uFullName
        from ".$general->table(32)." ch
        left join ".$general->table(14)." c on c.comment_id=ch.post_id
        left join ".$general->table(17)." u on u.uID=ch.uID
        ".$sq."
        order by c.created_time desc
        ";
    }

    if($from_date>=strtotime('-1 day',$to_date)){
        $all_data=$db->fetchQuery($query,$general->showQuery());
        $pageination['start']=1;
        $pageination['total']=count($all_data);
    }
    else{
        $cp=1;if(isset($_GET[PAGE_INATION_CURRENT_PAGE_NAME]))$cp=intval($_GET[PAGE_INATION_CURRENT_PAGE_NAME]);
        $pageination=$general->pagination_init_customQuery($query,200,$cp);
        $all_data=$db->fetchQuery($query.$pageination['limit'],$general->showQuery());
    }
    /*$cp=1;if(isset($_GET[PAGE_INATION_CURRENT_PAGE_NAME]))$cp=intval($_GET[PAGE_INATION_CURRENT_PAGE_NAME]);
    $pageination=$general->pagination_init_customQuery($query,100,$cp);
    $all_data=$db->fetchQuery($query.$pageination['limit']);*/
    //$all_export_data=$db->fetchQuery($query);
    $senderNames=array();
    /*foreach($all_export_data as $ad){
    $senderNames[$ad['sender_id']]=$ad['sender_id'];
    }*/
    $senderNames=$social->getNamesByUserId($senderNames);
    $general->arrayIndexChange($senderNames,'id');
    $reporData=array(
        'name'=>'Hide_Report_'.date('d_m_Y',$from_date).'_'.date('d_m_Y',$to_date),
        'title'=>array(
            array('title'=>"SL"             ,'key'=>'s' ,'w'=>10,'hw'=> 3),
            array('title'=>"Sender"         ,'key'=>'se','w'=>25,'hw'=>10),
            array('title'=>"Comments"       ,'key'=>'c' ,'w'=>55),
            array('title'=>"Create Time"    ,'key'=>'ct','w'=>20,'hw'=> 12),
            array('title'=>"Agent"          ,'key'=>'a' ,'w'=>25,'hw'=> 15),
            array('title'=>"Hide Time"      ,'key'=>'ht','w'=>25,'hw'=> 12),
            array('title'=>"Scentiment"  ,'key'=>'sn','w'=>25    ,'hw'=> 8),
            array('title'=>"Wrapup Caegory"  ,'key'=>'wc','w'=>20    ,'hw'=> 8),
            array('title'=>"Wrapup"  ,'key'=>'wc','w'=>25    ,'hw'=> 8)
        )
    );

    //$general->printArray($senderNames);
    $i=$pageination['start'];
    $senderNames=array();
    foreach($all_data as $ad){
        $senderNames[$ad['sender_id']]=$ad['sender_id'];
    }
    $senderNames=$social->getNamesByUserId($senderNames);
    $general->arrayIndexChange($senderNames,'id');
    //$ijk=1;
    foreach($all_data as $ad){
        $reporData['data'][]=array(
            's'=>$i++,
            'se'  =>$senderNames[$ad['sender_id']]['name'],
            'c'  =>$general->content_show($ad['message']),
            'ct'  =>$general->make_date($ad['created_time'],'time'),
            'a'  =>$ad['uFullName'],
            'ht'  =>$general->make_date($ad['created_time'],'time'),
            'sn'=>$general->getScentimentName($ad['scentiment']),
            'wc'=>$wrapupCat[$wrapups[$ad['wuID']]['wcID']]['wcTitle'],
            'w'=>$wrapups[$ad['wuID']]['wuTitle']
        );


    }

    $sReport->arrayReportTable($reporData);
    if($from_date<=strtotime('-1 day',$to_date)){
    ?>
    <ul class="pagination"><?php $general->pagination($pageination['currentPage'],$pageination['TotalPage'],$link);?></ul>
    <?php
    }
?>