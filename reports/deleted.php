<?php
    if(isset($_GET['date_range'])){
        $date_range=$_GET['date_range'];
    }
    else{
        $dr = date('d-m-Y 00:00:00');
        $dr2= date('d-m-Y 00:00:00');
        $date_range=$dr.'__'.$dr2;
    }

    $dates=explode('__',$date_range);
    //$general->printArray($dates);
    $from=strtotime($dates[0]);
    //echo $from;echo'<br>'; 
    $to=strtotime($dates[1]);
    if(date('H:i',$to)=='00:00'){
        $to=strtotime('+1 day',$to);
        $to=strtotime('-1 second',$to);
    }
    $type       = @$_GET['type'];   
    $key        = @$_GET['key'];   
    if($type!='cw'&&$type!='w'){
        $type='c';
        //$tbl  = 41; 
    }
    $ugID=0;
    if(isset($_GET['ug'])){
        $ugID    = intval($_GET['ug']);
    }

    $dateRangeVal=date('d-m-Y h:i A',$from).'__'.date('d-m-Y h:i A',$to);
    $dateRangeShow=date('d-m-Y h:i A',$from).' - '.date('d-m-Y h:i A',$to);
    $link=$pUrl.'&date_range='.$dateRangeVal;
?>
<script type="text/javascript">
    $(document).ready(function(){
        $('#reportrange').daterangepicker({
            timePicker: true,
            timePicker24Hour: true,
            opens: "right",
            autoApply: true,
            startDate: "<?php echo date('m/d/Y',$from);?>",
            endDate: "<?php echo date('m/d/Y',$to);?>"
            }, 
            function(start, end) {
                $('#reportrange span').html(start.format('DD-MM-YYYY hh:mm A') + ' - ' + end.format('DD-MM-YYYY hh:mm A'));
                $('#date_range').val(start.format('DD-MM-YYYY HH:mm')+'__'+end.format('DD-MM-YYYY HH:mm'));
        });
    });
</script>
<?php
    $diff=$to-$from;
    if($diff > 82000||$to<strtotime('-3 hour')){
        $reportTable=$db->settingsValue('commentReportTable');
        $commentWallReportTable=$db->settingsValue('commentWallReportTable');
    }
    if($ugID!=0){
        $gUsers=$db->allUsers(' and ugID='.$ugID);
        if(!empty($gUsers)){
            $general->arrayIndexChange($gUsers,'uID');
            $q[]='d.uID in('.implode(',',array_keys($gUsers)).')';
            $link.='&ug='.$ugID;
        }
        else{
            $q[]='d.uID=-1';
        }
    }
    elseif(isset($_GET['ag'])){
        $ag=intval($_GET['ag']);
        if($ag>0){
            $q[] = 'd.uID='.$ag;
            $link.='&ag='.$ag;
        }
    }
    if($type=='c'){
        $q[]="c.replyed=3";
        $q[]="c.replyTime between ".$from." and ".$to;
        $sq="where ".implode(" and ",$q);
        $query="
        SELECT 
        c.comment_id, c.message,c.replyTime,c.created_time,
        (
        CASE WHEN d.comment_id 
        IS NULL THEN 'FB'
        ELSE u.uFullName
        END
        ) AS deletBy,
        (
        CASE WHEN d.comment_id 
        IS NULL THEN 'Unknown'
        ELSE d.remove_time
        END
        ) AS deletTime,
        s.name
        FROM ".$general->table($reportTable)." c
        LEFT JOIN ".$general->table(15)." d ON d.comment_id=c.comment_id
        left join ".$general->table(17)." u on u.uID=d.uID
        left join ".$general->table(33)." s on s.id=c.sender_id
        ".$sq."
        order by c.replyTime desc
        ";
    }
    elseif($type=='cw'){
        $q[]="c.replyed=3";
        $q[]="c.replyTime between ".$from." and ".$to;

        $sq="where ".implode(" and ",$q);
        $query="
        SELECT 
        c.comment_id, c.message,c.replyTime,c.created_time,
        (
        CASE WHEN d.comment_id 
        IS NULL THEN 'FB'
        ELSE u.uLoginName
        END
        ) AS deletBy,
        (
        CASE WHEN d.comment_id 
        IS NULL THEN 'Unknown'
        ELSE d.remove_time
        END
        ) AS deletTime
        FROM ".$general->table($commentWallReportTable)." c
        LEFT JOIN ".$general->table(26)." d ON d.comment_id=c.comment_id
        left join ".$general->table(17)." u on u.uID=d.sender_id
        ".$sq."
        order by c.replyTime desc
        ";
    }
    if($from>=strtotime('-1 day',$to)){
        $all_data=$db->fetchQuery($query);
        $pageination['start']=1;
        $pageination['total']=count($all_data);
    }
    else{
        $cp=1;if(isset($_GET[PAGE_INATION_CURRENT_PAGE_NAME]))$cp=intval($_GET[PAGE_INATION_CURRENT_PAGE_NAME]);
        //echo $general->make_date(time(),'time');
        $pageination=$general->pagination_init_customQuery($query,200,$cp);
        $totalComment=$pageination['total'];
        $all_data=$db->fetchQuery($query.$pageination['limit']);
    }
    $reporData=array(
        'name'=>'Delete_Report_'.date('d_m_Y',$from).'_'.date('d_m_Y',$to),
        'title'=>array(
            array('title'=>"SL"         ,'key'=>'s' ,'w'=>10    ,'hw'=> 3),
            array('title'=>"Sender"     ,'key'=>'sn','w'=>25    ,'hw'=> 12),
            array('title'=>"Comments"   ,'key'=>'c' ,'w'=>55),
            array('title'=>"Create Time",'key'=>'ct','w'=>25    ,'hw'=> 15),
            array('title'=>"Delete By"  ,'key'=>'a' ,'w'=>25    ,'hw'=> 12),
            array('title'=>"Delete Time",'key'=>'d' ,'w'=>25    ,'hw'=> 15)
        )
    );
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?php echo $rModule['cmTitle'];?> <span>(<?php echo count($all_data);?>)</span></h2>
            <div class="clearfix"></div>
        </div>
        <form method="GET" action="" class="form-inline form_inline" style="margin-bottom: 20px;">
            <?php echo URL_INFO;?>
            <input type="hidden" id="date_range" name="date_range" value="<?php echo $dateRangeVal;?>">
            <div class="form-group">
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
                    <option value="">All</option>
                    <?php
                        $users=$db->allUsers('order by uFullName asc');
                        foreach($users as $u){?><option value="<?php echo $u['uID'];?>" <?php echo $general->selected($u['uID'],@$_GET['ag']);?>><?php echo $u['uFullName'];?></option><?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label>Type:</label>
                <select name="type" class="form-control">
                    <option <?php echo $general->selected($type,'c');?> value="c">Comments</option>
                    <!--<option <?php echo $general->selected($type,'w');?>value="w">Wall post</option>
                    <option <?php echo $general->selected($type,'cw');?> value="cw">Wall comments</option>-->
                </select>
            </div>
            <input type="submit" value="Show" name="show" class="btn btn-success btn-sm">
        </form>

    </div>
</div>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><a class="btn btn-default" id="exportBtn">Export</a></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">

            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th style="width: 75%;">Comment</th>
                        <th>Deleted By</th>
                        <th>Create Time</th>
                        <th>Delete Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $i=$pageination['start'];
                        foreach($all_data as $ad){
                            $reporData['data'][]=array(
                                's' =>$i,
                                'sn'=>$ad['name'],
                                'c' =>$general->content_show($ad['message']),
                                'ct'=>$general->make_date($ad['created_time'],'time'),
                                'a' =>$ad['deletBy'],
                                'd' =>$general->make_date($ad['replyTime'],'time'),
                            );
                        ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><b><?php echo $ad['name']?> : </b><?php echo $ad['message'] ?></td>
                            <td><?php echo $ad['deletBy']?></td>
                            <td><?php echo $general->make_date($ad['created_time'],'time')?></td>
                            <td><?php echo $general->make_date($ad['replyTime'],'time')?></td>
                        </tr>
                        <?php
                        }
                    ?>
                </tbody>
            </table>
            <?php
                if($from<strtotime('-1 day',$to)){
                ?>
                <ul class="pagination"><?php $general->pagination($pageination['currentPage'],$pageination['TotalPage'],$link);?></ul>
                <?php
                }
            ?> 

        </div>
    </div>
</div>


<script type="text/javascript">
    <?php
        echo 'reportHead='.json_encode($reporData).';';
    ?>
    $(document).ready(function(){
        commentLinkCreate();
        $('#exportBtn').show();
        $("#exportBtn").click(function(){reportJsonToExcel(reportHead);});
    });
</script>