<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><a href="<?php echo $pUrl;?>"><?php echo $rModule['cmTitle'];?></a></h2>
            <div class="clearfix"></div>
        </div>
        <?php
            if(isset($_GET['date_range'])){
                $date_range=$_GET['date_range'];
            }
            else{

                $dr = date('d-m-Y 00:00:00',YESTERDAY_TIME);
                $dr2= date('d-m-Y 00:00:00',YESTERDAY_TIME);
                $date_range=$dr.'__'.$dr2;
            }

            if(isset($_GET['group'])){
                $ugID    = intval($_GET['group']);
            }
            else{
                $ugID=0;
            }
            $dates=explode('__',$date_range);
            $from_date=strtotime($dates[0]);
            $from=$from_date;
            $to=strtotime($dates[1]);
            if(date('i:s',$to)=='59:00'){
                $to=strtotime('+1 minute',$to);
                $to=strtotime('-1 second',$to);
            }
            if(date('H:i',$to)=='00:00'){
                $to=strtotime('+1 day',$to);
                $to=strtotime('-1 second',$to);
            }
            $fTo=$to;//use for wall
//            echo $general->make_date($fTo,'time');echo '<br>'; 
            $sameDay=false;
            if(date('d-m-Y',$from)==date('d-m-Y',$to)){
                $sameDay=true;
            }
            $diff=$to-$from;
            $dateRangeVal=date('d-m-Y h:i A',$from_date).'__'.date('d-m-Y h:i A',$to);
            $dateRangeShow=date('d-m-Y h:i A',$from_date).' - '.date('d-m-Y h:i A',$to);
            $st_date = $dates[0];
            $ed_date = $dates[1];

            if($diff>82000||$to<strtotime('-3 hour')){
                $reportTable=$db->settingsValue('commentReportTable');
                $wallCommentReportTable=$db->settingsValue('commentWallReportTable');
            }
            else{
                $reportTable=13;
                $wallCommentReportTable=14;
            }
        ?>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#reportrange').daterangepicker({
                    opens: "right",
                    autoApply: true,
                    timePicker: true,
                    timePicker24Hour: true,
                    startDate: "<?php echo date('m/d/Y',$from_date);?>",
                    endDate: "<?php echo date('m/d/Y',$to);?>"
                    }, 
                    function(start, end) {
                        $('#reportrange span').html(start.format('DD-MM-YYYY hh:mm A') + ' - ' + end.format('DD-MM-YYYY hh:mm A'));
                        $('#date_range').val(start.format('DD-MM-YYYY HH:mm')+'__'+end.format('DD-MM-YYYY HH:mm'));
                });
            });
        </script>
        <form method="GET" class="form-inline form_inline" action="">
            <?php echo URL_INFO;?>
            <div class="form-group">
                <input type="hidden" id="date_range" name="date_range" value="<?php echo $dateRangeVal;?>">
                <div id="reportrange" class="pull-right sky_datepicker" >
                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                    <span><?php echo $dateRangeShow;?></span> <b class="caret"></b>
                </div>
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

    </div>
</div>
<?php
    if(OPERATION_MESSAGE_ALLWO==true){
        ?>
        <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Message</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <a class="btn btn-default" id="exportComments">Export</a>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th><?php echo $i++;?></th>
                        <th>
                            <?php
                                if($sameDay==true){
                                ?>Group<?php
                                }
                                else{
                                ?>Date<?php
                                }
                            ?>
                        </th>
                        <th>Total Message Received</th>
                        <th>Total Message Replied</th>
                        <!--<th>Total Query Replied within 15 min</th>-->
                        <th>ART</th>
                        <th>AHT</th>
                        <th>Response Rate</th>
                        <!--<th>SL</th>-->
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $sq='';
                        if($ugID!=0){
                            $sGroup=$db->allUsers(' and ugID='.$ugID);
                            if(!empty($sGroup)){
                                $general->arrayIndexChange($sGroup,'uID');
                                $uIDs=array_keys($sGroup);
                                $sq.=' and replyBy in('.implode(',',$uIDs).')';
                            }
                            else{
                                $uIDs=array(0);
                                $sq.=' and replyBy=-1';
                            }
                            //$q[] = 'c.ugID='.intval($_GET['group']);
                        }
                        if($sameDay==true){
                            $jArray=array(
                                'name'=>'Daily_Team_Performance_'.date('d_m_Y',$from).'_'.date('d_m_Y',$to),
                                'title'=>array(
                                    array('title'=>"Serial"                             ,'key'=>'s' ,'w'=>6    ,'hw'=> 3),
                                    array('title'=>"Date"                               ,'key'=>'d' ,'w'=>13    ,'hw'=> 3),
                                    array('title'=>"Group"                              ,'key'=>'g' ,'w'=>15    ,'hw'=> 3),
                                    array('title'=>"Total Query Received"               ,'key'=>'rc','w'=>25    ,'hw'=> 12),
                                    array('title'=>"Total Query Replied"                ,'key'=>'rp','w'=>25    ,'hw'=> 15),
                                    //array('title'=>"Total Query Replied within 15 min"  ,'key'=>'r1' ,'w'=>35   ,'hw'=> 12),
                                    array('title'=>"ART"                                ,'key'=>'rt' ,'w'=>10   ,'hw'=> 15),
                                    array('title'=>"AHT"                                ,'key'=>'ht' ,'w'=>10   ,'hw'=> 15),
                                    array('title'=>"Response Rate"                      ,'key'=>'rr' ,'w'=>10   ,'hw'=> 15),
                                    //array('title'=>"Service Level"                      ,'key'=>'sl' ,'w'=>10   ,'hw'=> 15),
                                )
                            );
                            $cacheKey='dalyTmPerSmDyMs_'.$from.'_'.$to;
                            $c=$db->reportCacheGet($cacheKey);
                            if($c!=false&&!isset($_GET['flush'])){
                                $jArray['data']=json_decode($c,true);
                            }
                            else{
                                $groups=$db->allGroups();
                                $qReceived=$sReport->messageIn($from,$to);
                                $serial=1;
                                foreach($groups as $g){
                                    $users=$db->allUsers(' and ugID ='.$g['ugID']);
                                    if(!empty($users)){
                                        $user=array();foreach($users as $u){$user[]=$u['uID'];}
                                        $ah=$sReport->ahtNartMsg($from,$to,$user);
                                        //if($g['ugID']==7){$echo='d';}else{$echo='No';}
                                        $general->showQuery();
                                        $totalReplyCount=$sReport->messageAdminActivity($from,$to,$user);
                                        //$totalReplyCount20Min=$sReport->commentsAdminActivity($from,$to,$user,array(0,60*15),true,$echo);
                                        /* if($qReceived>0&&$totalReplyCount>0){
                                        $rr=round(($totalReplyCount/$qReceived)*100,2);
                                        $sl=round(($totalReplyCount20Min/$totalReplyCount)*100,2);
                                        }
                                        else{
                                        $rr=0;
                                        $sl=0;
                                        }*/
                                        if($qReceived>0&&$totalReplyCount>0){
                                            $rr=str_pad(number_format(round(($totalReplyCount/$qReceived)*100,2),2),5,0,STR_PAD_LEFT);
                                            //$sl=str_pad(number_format(round(($totalReplyCount20Min/$totalReplyCount)*100,2),2),5,0,STR_PAD_LEFT);
                                        }
                                        else{
                                            $rr='00.00';
                                            //$sl='00.00';
                                        }
                                        $data = array(
                                            's'=>$serial++,
                                            'g' =>  $g['ugTitle'],
                                            'd' =>  date('d_m_Y',$from),
                                            'rc'=>  $qReceived,
                                            'rp'=>  $totalReplyCount,
                                            //'r1'=>  $totalReplyCount20Min,
                                            'rt'=>  $ah['art'],
                                            'ht'=>  $ah['aht'],
                                            'rr'=>  $rr."%",
                                            //'sl'=>  $sl."%"
                                        );
                                        $jArray['data'][] = $data;

                                    }
                                }
                                $expTime=strtotime('+ 1 hour');    
                                if($to<strtotime('-2 hour')){
                                    $expTime=strtotime('+ 6 month');    
                                }
                                $db->reportCacheSet($cacheKey,json_encode($jArray['data']),$expTime);
                            }
                            $tr=0;
                            //$tr20=0;
                            foreach($jArray['data'] as $data){
                                $tr+=$data['rp'];
                                //$tr20+=$data['r1'];
                            ?>
                            <tr>
                                <td><?php echo $data['s'];?></td>
                                <td><?php echo $data['g'];?></td>
                                <td><?php echo $data['rc'];?></td>
                                <td><?php echo $data['rp'];?></td>
                                <td><?php echo $data['rt'];?></td>
                                <td><?php echo $data['ht'];?></td>
                                <td><?php echo $data['rr'];?></td>
                            </tr>
                            <?php
                            }
                        }
                        else{
                            $i=0;
                            //$dates = range($from, $to,86400);
                            $jArray=array(
                                'name'=>'Daily_Team_Performance_'.date('d_m_Y',$from).'_'.date('d_m_Y',$to),
                                'title'=>array(
                                    array('title'=>"Serial"                             ,'key'=>'s' ,'w'=>6    ,'hw'=> 3),
                                    array('title'=>"Date"                               ,'key'=>'d' ,'w'=>13    ,'hw'=> 3),
                                    array('title'=>"Total Query Received"               ,'key'=>'rc','w'=>25    ,'hw'=> 12),
                                    array('title'=>"Total Query Replied"                ,'key'=>'rp','w'=>25    ,'hw'=> 15),
                                    //array('title'=>"Total Query Replied within 15 min"  ,'key'=>'r1' ,'w'=>35   ,'hw'=> 12),
                                    array('title'=>"ART"                                ,'key'=>'rt' ,'w'=>10   ,'hw'=> 15),
                                    array('title'=>"AHT"                                ,'key'=>'ht' ,'w'=>10   ,'hw'=> 15),
                                    array('title'=>"Response Rate"                      ,'key'=>'rr' ,'w'=>10   ,'hw'=> 15),
                                    //array('title'=>"Service Level"                      ,'key'=>'sl' ,'w'=>10   ,'hw'=> 15),
                                ),
                                'data'=>array()
                            );
                            $to_date=$to;
                            $serial=1;
                            while($from<=$to_date){
                                $to =strtotime('+1 day', $from);
                                $nextFrom=$to;
                                $to =strtotime('-1 second',$to);
                                $i++;
                                $cacheKey='dalyTmPer_'.$ugID.'_msg_'.$from.'_'.$to;
                                $c=$db->reportCacheGet($cacheKey);
                                if($c!=false&&!isset($_GET['flush'])){
                                    $data=json_decode($c,true);
                                }
                                else{
                                    //$com    =$db->selectAll($general->table($reportTable),' WHERE sender_id!='.PAGE_ID.' and created_time between '.$from. ' AND '.$to,' count(created_time) as t');
                                    $echo=$general->showQuery();
                                    $qReceived = $sReport->messageIn($from,$to);
                                    if($ugID==0){
                                        $ah=$sReport->ahtNartMsg($from,$to,0,$echo);
                                        $totalReplyCount=$sReport->messageAdminActivity($from,$to,0,$echo);
                                        //$totalReplyCount20Min=$sReport->commentsAdminActivity($from,$to,0,60*15);
                                    }
                                    else{
                                        var_dump($uIDs);
                                        $ah=$sReport->ahtNartMsg($from,$to,$uIDs,$echo);
                                        $totalReplyCount=$sReport->messageAdminActivity($from,$to,$uIDs,$echo);
                                        //$totalReplyCount20Min=$sReport->commentsAdminActivity($from,$to,$uIDs,60*15);
                                    }

                                    if($qReceived>0&&$totalReplyCount>0){
                                        $rr=str_pad(number_format(round(($totalReplyCount/$qReceived)*100,2),2),5,0,STR_PAD_LEFT);
                                        //$sl=str_pad(number_format(round(($totalReplyCount20Min/$totalReplyCount)*100,2),2),5,0,STR_PAD_LEFT);
                                    }
                                    else{
                                        $rr='00.00';
                                        //$sl='00.00';
                                    }
                                    $data = array(
                                        's' => $serial++,
                                        'd' => date('d-m-y',$from),
                                        'rc'=> $qReceived,
                                        'rp'=> $totalReplyCount,
                                        //'r1'=> $totalReplyCount20Min,
                                        'rt'=> $ah['art'],
                                        'ht'=> $ah['aht'],
                                        'rr'=> round(($totalReplyCount/$qReceived)*100,2)."%",
                                        //'sl'=> round(($totalReplyCount20Min/$totalReplyCount)*100,2)."%"
                                    );
                                    $expTime=strtotime('+ 1 hour');    
                                    if($to<strtotime('-2 hour')){
                                        $expTime=strtotime('+ 6 month');    
                                    }
                                    $db->reportCacheSet($cacheKey,json_encode($data),$expTime);
                                }


                                $jArray['data'][] = $data;
                                $from=$nextFrom;
                            }
                            $tr=0;
                            $tr20=0;
                            foreach($jArray['data'] as $data){
                                $tr+=$data['rp'];
                                $tr20+=$data['r1'];
                            ?>
                            <tr>
                                <td><?php echo $data['s'];?></td>
                                <td><?php echo $data['d'];?></td>
                                <td><?php echo $data['rc'];?></td>
                                <td><?php echo $data['rp'];?></td>
                                <td><?php echo $data['rt'];?></td>
                                <td><?php echo $data['ht'];?></td>
                                <td><?php echo $data['rr'];?></td>
                            </tr>
                            <?php
                            }
                        }
                    ?>
                    <tr>
                        <td colspan="3"><b>Total</b></td>
                        <td><?php echo $tr;?></td>
                        <td colspan="4"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
        <?php
    }
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Comments</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <a class="btn btn-default" id="exportComments">Export</a>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Serial</th>
                        <th>
                            <?php
                                if($sameDay==true){
                                ?>Group<?php
                                }
                                else{
                                ?>Date<?php
                                }
                            ?>
                        </th>
                        <th>Total Query Received</th>
                        <th>Total Query Replied</th>
                        <th>Total Query Replied within 15 min</th>
                        <th>ART</th>
                        <th>AHT</th>
                        <th>Response Rate</th>
                        <th>SL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $sq='';
                        if($ugID!=0){
                            $sGroup=$db->allUsers(' and ugID='.$ugID);
                            if(!empty($sGroup)){
                                $general->arrayIndexChange($sGroup,'uID');
                                $uIDs=array_keys($sGroup);
                                $sq.=' and replyBy in('.implode(',',$uIDs).')';
                            }
                            else{
                                $sq.=' and replyBy=-1';
                            }
                            //$q[] = 'c.ugID='.intval($_GET['group']);
                        }
                        if($sameDay==true){
                            $jArray=array(
                                'name'=>'Daily_Team_Performance_'.date('d_m_Y',$from).'_'.date('d_m_Y',$to),
                                'title'=>array(
                                    array('title'=>"Serial"                             ,'key'=>'s' ,'w'=>6    ,'hw'=> 3),
                                    array('title'=>"Date"                               ,'key'=>'d' ,'w'=>13    ,'hw'=> 3),
                                    array('title'=>"Group"                              ,'key'=>'g' ,'w'=>15    ,'hw'=> 3),
                                    array('title'=>"Total Query Received"               ,'key'=>'rc','w'=>25    ,'hw'=> 12),
                                    array('title'=>"Total Query Replied"                ,'key'=>'rp','w'=>25    ,'hw'=> 15),
                                    array('title'=>"Total Query Replied within 15 min"  ,'key'=>'r1' ,'w'=>35   ,'hw'=> 12),
                                    array('title'=>"ART"                                ,'key'=>'rt' ,'w'=>10   ,'hw'=> 15),
                                    array('title'=>"AHT"                                ,'key'=>'ht' ,'w'=>10   ,'hw'=> 15),
                                    array('title'=>"Response Rate"                      ,'key'=>'rr' ,'w'=>10   ,'hw'=> 15),
                                    array('title'=>"Service Level"                      ,'key'=>'sl' ,'w'=>10   ,'hw'=> 15),
                                )
                            );
                            $cacheKey='dalyTmPerSmDyCm_'.$from.'_'.$to;
                            $c=$db->reportCacheGet($cacheKey);
                            if($c!=false&&!isset($_GET['flush'])){
                                $jArray['data']=json_decode($c,true);
                            }
                            else{
                                $groups=$db->allGroups();
                                $qReceived=$sReport->commentsUserActivity($from,$to);
                                $serial=1;
                                foreach($groups as $g){
                                    $users=$db->allUsers(' and ugID ='.$g['ugID']);
                                    if(!empty($users)){
                                        $user=array();foreach($users as $u){$user[]=$u['uID'];}
                                        $ah=$sReport->ahtNart($from,$to,$user);
                                        //if($g['ugID']==7){$echo='d';}else{$echo='No';}
                                        $echo='No';
                                        if(isset($_GET['flush'])&&isset($_GET['showq'])){$echo='a';}
                                        $totalReplyCount=$sReport->commentsAdminActivity($from,$to,$user,'',true,$echo);
                                        $totalReplyCount20Min=$sReport->commentsAdminActivity($from,$to,$user,array(0,60*15),true,$echo);
                                        /* if($qReceived>0&&$totalReplyCount>0){
                                        $rr=round(($totalReplyCount/$qReceived)*100,2);
                                        $sl=round(($totalReplyCount20Min/$totalReplyCount)*100,2);
                                        }
                                        else{
                                        $rr=0;
                                        $sl=0;
                                        }*/
                                        if($qReceived>0&&$totalReplyCount>0){
                                            $rr=str_pad(number_format(round(($totalReplyCount/$qReceived)*100,2),2),5,0,STR_PAD_LEFT);
                                            $sl=str_pad(number_format(round(($totalReplyCount20Min/$totalReplyCount)*100,2),2),5,0,STR_PAD_LEFT);
                                        }
                                        else{
                                            $rr='00.00';
                                            $sl='00.00';
                                        }
                                        $data = array(
                                            's'=>$serial++,
                                            'g' =>  $g['ugTitle'],
                                            'd' =>  date('d_m_Y',$from),
                                            'rc'=>  $qReceived,
                                            'rp'=>  $totalReplyCount,
                                            'r1'=>  $totalReplyCount20Min,
                                            'rt'=>  $ah['art'],
                                            'ht'=>  $ah['aht'],
                                            'rr'=>  $rr."%",
                                            'sl'=>  $sl."%"
                                        );
                                        $jArray['data'][] = $data;

                                    }
                                }
                                $expTime=strtotime('+ 1 hour');    
                                if($to<strtotime('-2 hour')){
                                    $expTime=strtotime('+ 6 month');    
                                }
                                $db->reportCacheSet($cacheKey,json_encode($jArray['data']),$expTime);
                            }
                            $tr=0;
                            $tr20=0;
                            foreach($jArray['data'] as $data){
                                $tr+=$data['rp'];
                                $tr20+=$data['r1'];
                            ?>
                            <tr>
                                <td><?php echo $data['s'];?></td>
                                <td><?php echo $data['g'];?></td>
                                <td><?php echo $data['rc'];?></td>
                                <td><?php echo $data['rp'];?></td>
                                <td><?php echo $data['r1'];?></td>
                                <td><?php echo $data['rt'];?></td>
                                <td><?php echo $data['ht'];?></td>
                                <td><?php echo $data['rr'];?></td>
                                <td><?php echo $data['sl'];?></td>
                            </tr>
                            <?php
                            }
                        }
                        else{
                            $i=0;
                            //$dates = range($from, $to,86400);
                            $jArray=array(
                                'name'=>'Daily_Team_Performance_'.date('d_m_Y',$from).'_'.date('d_m_Y',$to),
                                'title'=>array(
                                    array('title'=>"Serial"                             ,'key'=>'s' ,'w'=>6    ,'hw'=> 3),
                                    array('title'=>"Date"                               ,'key'=>'d' ,'w'=>13    ,'hw'=> 3),
                                    array('title'=>"Total Query Received"               ,'key'=>'rc','w'=>25    ,'hw'=> 12),
                                    array('title'=>"Total Query Replied"                ,'key'=>'rp','w'=>25    ,'hw'=> 15),
                                    array('title'=>"Total Query Replied within 15 min"  ,'key'=>'r1' ,'w'=>35   ,'hw'=> 12),
                                    array('title'=>"ART"                                ,'key'=>'rt' ,'w'=>10   ,'hw'=> 15),
                                    array('title'=>"AHT"                                ,'key'=>'ht' ,'w'=>10   ,'hw'=> 15),
                                    array('title'=>"Response Rate"                      ,'key'=>'rr' ,'w'=>10   ,'hw'=> 15),
                                    array('title'=>"Service Level"                      ,'key'=>'sl' ,'w'=>10   ,'hw'=> 15),
                                ),
                                'data'=>array()
                            );
                            $to_date=$fTo;
                            $from=$from_date;
                            $serial=1;
//                            echo $general->make_date($from,'time');echo '<br>'; 
//                            echo $general->make_date($to_date,'time');echo '<br>'; 
                            while($from<=$to_date){
                                $to =strtotime('+1 day', $from);
                                $nextFrom=$to;
                                $to =strtotime('-1 second',$to);
                                $i++;
                                $cacheKey='dalyTmPer_'.$ugID.'_Cm_'.$from.'_'.$to;
                                $c=$db->reportCacheGet($cacheKey);
                                if($c!=false&&!isset($_GET['flush'])){
                                    $data=json_decode($c,true);
                                }
                                else{
                                    //$com    =$db->selectAll($general->table($reportTable),' WHERE sender_id!='.PAGE_ID.' and created_time between '.$from. ' AND '.$to,' count(created_time) as t');
                                    $echo='No';
                                    if(isset($_GET['flush'])&&isset($_GET['showq'])){$echo='a';}
                                    $qReceived = $sReport->commentsUserActivity($from,$to);
                                    if($ugID==0){
                                        $ah=$sReport->ahtNart($from,$to);
                                        $totalReplyCount=$sReport->commentsAdminActivity($from,$to);
                                        $totalReplyCount20Min=$sReport->commentsAdminActivity($from,$to,0,60*15);
                                    }
                                    else{
                                        $ah=$sReport->ahtNart($from,$to,$uIDs);
                                        $totalReplyCount=$sReport->commentsAdminActivity($from,$to,$uIDs);
                                        $totalReplyCount20Min=$sReport->commentsAdminActivity($from,$to,$uIDs,60*15);
                                    }

                                    if($qReceived>0&&$totalReplyCount>0){
                                        $rr=str_pad(number_format(round(($totalReplyCount/$qReceived)*100,2),2),5,0,STR_PAD_LEFT);
                                        $sl=str_pad(number_format(round(($totalReplyCount20Min/$totalReplyCount)*100,2),2),5,0,STR_PAD_LEFT);
                                    }
                                    else{
                                        $rr='00.00';
                                        $sl='00.00';
                                    }
                                    $data = array(
                                        's' => $serial++,
                                        'd' => date('d-m-y',$from),
                                        'rc'=> $qReceived,
                                        'rp'=> $totalReplyCount,
                                        'r1'=> $totalReplyCount20Min,
                                        'rt'=> $ah['art'],
                                        'ht'=> $ah['aht'],
                                        'rr'=> round(($totalReplyCount/$qReceived)*100,2)."%",
                                        'sl'=> round(($totalReplyCount20Min/$totalReplyCount)*100,2)."%"
                                    );
                                    $expTime=strtotime('+ 1 hour');    
                                    if($to<strtotime('-2 hour')){
                                        $expTime=strtotime('+ 6 month');    
                                    }
                                    $db->reportCacheSet($cacheKey,json_encode($data),$expTime);
                                }


                                $jArray['data'][] = $data;
                                $from=$nextFrom;
                            }
                            $tr=0;
                            $tr20=0;
                            foreach($jArray['data'] as $data){
                                $tr+=$data['rp'];
                                $tr20+=$data['r1'];
                            ?>
                            <tr>
                                <td><?php echo $data['s'];?></td>
                                <td><?php echo $data['d'];?></td>
                                <td><?php echo $data['rc'];?></td>
                                <td><?php echo $data['rp'];?></td>
                                <td><?php echo $data['r1'];?></td>
                                <td><?php echo $data['rt'];?></td>
                                <td><?php echo $data['ht'];?></td>
                                <td><?php echo $data['rr'];?></td>
                                <td><?php echo $data['sl'];?></td>
                            </tr>
                            <?php
                            }
                        }
                    ?>
                    <tr>
                        <td colspan="3"><b>Total</b></td>
                        <td><?php echo $tr;?></td>
                        <td><?php echo $tr20;?></td>
                        <td colspan="4"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Wall Posts</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <a class="btn btn-default" id="exportWall">Export</a>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>
                            <?php
                                if($sameDay==true){
                                ?>Group<?php
                                }
                                else{
                                ?>Date<?php
                                }
                            ?>
                        </th>
                        <th>Total Query Received</th>
                        <th>Total Query Replied</th>
                        <th>Total Query Replied within 5 min</th>
                        <th>ART</th>
                        <th>AHT</th>
                        <th>Response Rate</th>
                        <th>SL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if($sameDay==true){
                            $jArray2=array(
                                'name'=>'Daily_Team_Performance_'.date('d_m_Y',$from).'_'.date('d_m_Y',$to),
                                'title'=>array(
                                    array('title'=>"Serial"                             ,'key'=>'s' ,'w'=>10    ,'hw'=> 3),
                                    array('title'=>"Date"                               ,'key'=>'d' ,'w'=>10    ,'hw'=> 3),
                                    array('title'=>"Group"                              ,'key'=>'g' ,'w'=>10    ,'hw'=> 3),
                                    array('title'=>"Total Query Received"               ,'key'=>'rc','w'=>10    ,'hw'=> 12),
                                    array('title'=>"Total Query Replied"                ,'key'=>'rp','w'=>10    ,'hw'=> 15),
                                    array('title'=>"Total Query Replied within 5 min"   ,'key'=>'r1' ,'w'=>12   ,'hw'=> 12),
                                    array('title'=>"ART"                                ,'key'=>'rt' ,'w'=>12   ,'hw'=> 15),
                                    array('title'=>"AHT"                                ,'key'=>'ht' ,'w'=>12   ,'hw'=> 15),
                                    array('title'=>"Response Rate"                      ,'key'=>'rr' ,'w'=>10   ,'hw'=> 15),
                                    array('title'=>"Service Level"                      ,'key'=>'sl' ,'w'=>10   ,'hw'=> 15),
                                )
                            );
                            $cacheKey='dalyTmPerSmDyWl_'.$from.'_'.$to;
                            $c=$db->reportCacheGet($cacheKey);
                            if($c!=false&&!isset($_GET['flush'])){
                                $jArray2['data']=json_decode($c,true);
                            }
                            else{
                                $groups=$db->allGroups();
                                //$a=$db->selectAll($general->table(12),' WHERE created_time between '.$from. ' AND '.$to,' count(created_time) as t');
                                $qReceived=$sReport->wallPosts($from,$to);

                                foreach($groups as $g){
                                    $users=$db->allUsers(' and ugID ='.$g['ugID']);
                                    if(!empty($users)){
                                        $user=array();foreach($users as $u){$user[]=$u['uID'];}

                                        $echo='No';
                                        if(isset($_GET['flush'])&&isset($_GET['showq'])){$echo='a';}
                                        $ah=$sReport->ahtNartWall($from,$to,$user);
                                        $totalReplyCount=$sReport->wallAdminActivity($from,$to,$user,'',true,$echo);
                                        $totalReplyCount20Min=$sReport->wallAdminActivity($from,$to,$user,array(0,60*5),true,$echo);
                                        if($qReceived>0){
                                            $rr=round(($totalReplyCount/$qReceived)*100,2);
                                            $sl=round(($totalReplyCount20Min/$totalReplyCount)*100,2);
                                        }
                                        else{
                                            $rr=0;
                                            $sl=0;
                                        }
                                        $data = array(
                                            's' => $serial++,
                                            'd' => date('d_m_Y',$from),
                                            'g' => $g['ugTitle'],
                                            'rc'=> $qReceived,
                                            'rp'=> $totalReplyCount,
                                            'r1'=> $totalReplyCount20Min,
                                            'rt'=> $ah['art'],
                                            'ht'=> $ah['aht'],
                                            'rr'=> $rr."%",
                                            'sl'=> $sl."%"
                                        );
                                        $jArray2['data'][] = $data;
                                    }
                                }
                                $expTime=strtotime('+ 1 hour');    
                                if($to<strtotime('-2 hour')){
                                    $expTime=strtotime('+ 6 month');    
                                }
                                $db->reportCacheSet($cacheKey,json_encode($jArray2['data']),$expTime);
                            }
                            $tr20=0;
                            $tr=0;
                            foreach($jArray2['data'] as $data){
                                $tr+=$data['rp'];
                                $tr20+=$data['r1'];
                            ?>
                            <tr>
                                <td><?php echo $data['s'];?></td>
                                <td><?php echo $data['g'];?></td>
                                <td><?php echo $data['rc'];?></td>
                                <td><?php echo $data['rp'];?></td>
                                <td><?php echo $data['r1'];?></td>
                                <td><?php echo $data['rt'];?></td>
                                <td><?php echo $data['ht'];?></td>
                                <td><?php echo $data['rr'];?></td>
                                <td><?php echo $data['sl'];?></td>
                            </tr>  
                            <?php
                            }
                        }
                        else{
                            $jArray2=array(
                                'name'=>'Daily_Team_Performance_'.date('d_m_Y',$from).'_'.date('d_m_Y',$to),
                                'title'=>array(
                                    array('title'=>"Serial"                             ,'key'=>'s' ,'w'=>10    ,'hw'=> 3),
                                    array('title'=>"Date"                               ,'key'=>'d' ,'w'=>10    ,'hw'=> 3),
                                    array('title'=>"Group"                              ,'key'=>'g' ,'w'=>10    ,'hw'=> 3),
                                    array('title'=>"Total Query Received"               ,'key'=>'rc','w'=>10    ,'hw'=> 12),
                                    array('title'=>"Total Query Replied"                ,'key'=>'rp','w'=>10    ,'hw'=> 15),
                                    array('title'=>"Total Query Replied within 5 min"   ,'key'=>'r1' ,'w'=>12   ,'hw'=> 12),
                                    array('title'=>"ART"                                ,'key'=>'rt' ,'w'=>12   ,'hw'=> 15),
                                    array('title'=>"AHT"                                ,'key'=>'ht' ,'w'=>12   ,'hw'=> 15),
                                    array('title'=>"Response Rate"                      ,'key'=>'rr' ,'w'=>10   ,'hw'=> 15),
                                    array('title'=>"Service Level"                      ,'key'=>'sl' ,'w'=>10   ,'hw'=> 15),
                                )
                            );
                            $tr20=0;
                            $tr=0;
                            $i=0;
                            $from=$from_date;
                            while($from<=$fTo){
                                $to =strtotime('+1 day', $from);
                                $nextFrom=$to;
                                $to =strtotime('-1 second',$to);
                                $cacheKey='dalyTmPer_'.$ugID.'_DyWl_'.$from.'_'.$to;
                                $c=$db->reportCacheGet($cacheKey);
                                if($c!=false&&!isset($_GET['flush'])){
                                    $data=json_decode($c,true);
                                }
                                else{
                                    $i++;
                                    $qReceived = $sReport->wallPosts($from,$to);
                                    $echo='No';
                                    if(isset($_GET['flush'])&&isset($_GET['showq'])){$echo='a';}
                                    if($ugID==0){
                                        $ah=$sReport->ahtNartWall($from,$to);
                                        $totalReplyCount=$sReport->wallAdminActivity($from,$to,0,'',true,$echo);
                                        $totalReplyCount20Min=$sReport->wallAdminActivity($from,$to,0,60*5,true,$echo);
                                    }
                                    else{
                                        $ah=$sReport->ahtNartWall($from,$to,$uIDs);
                                        $totalReplyCount=$sReport->wallAdminActivity($from,$to,$uIDs,'',true,$echo);
                                        $totalReplyCount20Min=$sReport->wallAdminActivity($from,$to,$uIDs,60*5,true,$echo);
                                    }
                                    $data = array(
                                        's' =>$serial++,
                                        'd' =>  date('d-m-y',$from),
                                        'rc'=>  $qReceived,
                                        'rp'=>  $totalReplyCount,
                                        'r1'=>  $totalReplyCount20Min,
                                        'rt'=>  $ah['art'],
                                        'ht'=>  $ah['aht'],
                                        'rr'=>  round(($totalReplyCount/$qReceived)*100,2)."%",
                                        'sl'=>  round(($totalReplyCount20Min/$totalReplyCount)*100,2)."%"
                                    );
                                    $expTime=strtotime('+ 1 hour');    
                                    if($to<strtotime('-2 hour')){
                                        $expTime=strtotime('+ 6 month');    
                                    }
                                    $db->reportCacheSet($cacheKey,json_encode($data),$expTime);
                                }
                                $jArray2['data'][] = $data;
                                $from=$nextFrom;
                            }

                            $tr=0;
                            $tr20=0;
                            foreach($jArray2['data'] as $data){
                                $tr+=$data['rp'];
                                $tr20+=$data['r1'];


                            ?>
                            <tr>
                                <td><?php echo $data['s'];?></td>
                                <td><?php echo $data['d'];?></td>
                                <td><?php echo $data['rc'];?></td>
                                <td><?php echo $data['rp'];?></td>
                                <td><?php echo $data['r1'];?></td>
                                <td><?php echo $data['rt'];?></td>
                                <td><?php echo $data['ht'];?></td>
                                <td><?php echo $data['rr'];?></td>
                                <td><?php echo $data['sl'];?></td>
                            </tr>  
                            <?php
                            }
                        }
                    ?>
                    <tr>
                        <td colspan="3"><b>Total</b></td>
                        <td><?php echo $tr;?></td>
                        <td><?php echo $tr20;?></td>
                        <td colspan="4"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    <?php echo "var eIndex = ".json_encode($jArray).";
        ";?>
    <?php echo "var eIndex2 = ".json_encode($jArray2).";";?>
    $("#exportComments").click(function(){
        reportJsonToExcel(eIndex);
    })
    $("#exportWall").click(function(){
        reportJsonToExcel(eIndex2);
    })
</script>
