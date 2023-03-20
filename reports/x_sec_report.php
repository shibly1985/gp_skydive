<?php
    if(date('i')<10&&PROJECT!='gpc'){
        echo '<h1>Generating Please wait till '.date('h:15:00 A').'</h1>';
    }
    else{
    ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><a href="<?php echo $pUrl;?>"><?php echo $rModule['cmTitle'];?></a></h2>
                <div class="clearfix"></div>
            </div>
            <?php

                if(isset($_GET['show'])){
                    $date_range=$_GET['date_range'];
                }
                else{
                    $dr = date('d-m-Y',YESTERDAY_TIME);
                    $dr2= date('d-m-Y',YESTERDAY_TIME);
                    $date_range=$dr.'__'.$dr2;
                }
                $dates=explode('__',$date_range);
                $from_date=strtotime($dates[0]);
                $from=$from_date;
                $to=strtotime($dates[1]);
                $to_date=$to;//it's user for cache set
                if(date('H:i',$to)=='00:00'){
                    $to=strtotime('+1 day',$to);
                    $to=strtotime('-1 second',$to);
                }
                $sameDay=false;
                if(date('d-m-Y',$from)==date('d-m-Y',$to)){
                    $sameDay=true;
                }
                $diff=$to-$from;
                $dateRangeVal=date('d-m-Y',$from_date).'__'.date('d-m-Y',$to);
                $dateRangeShow=date('d-m-Y',$from_date).' - '.date('d-m-Y',$to);
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
                        startDate: "<?php echo date('m/d/Y',$from_date);?>",
                        endDate: "<?php echo date('m/d/Y',$to);?>"
                        }, 
                        function(start, end) {
                            $('#reportrange span').html(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'));
                            $('#date_range').val(start.format('DD-MM-YYYY')+'__'+end.format('DD-MM-YYYY'));
                    });
                });
            </script>
            <form method="GET" class="form-inline form_inline" action="">
                <?php echo URL_INFO;?>
                <div class="form-group">
                    <input type="hidden" id="date_range" name="date_range" value="<?php echo $dateRangeVal;?>">
                    <div id="reportrange" class="pull-right sky_datepicker">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                        <span><?php echo $dateRangeShow;?></span> <b class="caret"></b>
                    </div>
                </div>
                <input type="submit" value="Show" name="show" class="btn btn-success btn-sm">
            </form>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <?php
                $reportTable=$db->settingsValue('commentReportTable');
                $wallCommentReportTable=$db->settingsValue('commentWallReportTable');

                $cacheKey='x_sec_report_'.$from.'_'.$to;
                $expTime=strtotime('+ 6 month');
                /*$c=$db->reportCacheGet($cacheKey);
                if($c==false||isset($_GET['flash'])){*/
                $jArray=array(
                    'commentData'=>array(),
                    'commentTotal'=>array(),
                    'wallData'=>array(),
                    'wallTotal'=>array(),
                );
                $tua=0;
                $tr=0;
                $trMin0=0;
                $trMin6=0;
                $trMin16=0;
                $trMin21=0;
                $trMin31=0;
                $trMin61=0;
                $trMin361=0;
                $wtua=0;
                $wtr=0;
                $wtrMin0=0;
                $wtrMin6=0;
                $wtrMin16=0;
                $wtrMin21=0;
                $wtrMin31=0;
                $wtrMin61=0;
                $wtrMin361=0;

                //$ddd=0;
                $dates = range($from, $to,3600);
                $loopRun=1;
                foreach($dates as $from){
                    if($loopRun>7)break;
                    if($from>=strtotime('-1 hour'))break;
                    $to=strtotime('+ 1 hour',$from);
                    $to=strtotime('-1 second',$to);
                    if($to>strtotime('+1 hour'))continue;
                    $cacheKey2='x_sec_report_hour_'.$from.'_'.$to;
                    $c2=$db->reportCacheGet($cacheKey2);
                    if($c2==false||isset($_GET['flash'])){
                        $loopRun++;
                        $echo='No';
                        //$echo='o';
                        $totalUserActivityComment=$sReport->commentsUserActivity($from,$to,$echo);
                        $totalReplyCount=$sReport->commentsAdminActivity($from,$to,0,'',$echo);
                        $totalReplyCount0=$sReport->commentsAdminActivity($from,$to,0,array(0,60*5,'all'),$echo);
                        $totalReplyCount6=$sReport->commentsAdminActivity($from,$to,0,array(60*5+1,60*15,'all'),$echo);
                        $totalReplyCount21=$sReport->commentsAdminActivity($from,$to,0,array(60*15+1,60*20,'all'),$echo);
                        $totalReplyCount16=$sReport->commentsAdminActivity($from,$to,0,array(60*20+1,60*30,'all'),$echo);
                        $totalReplyCount31=$sReport->commentsAdminActivity($from,$to,0,array(60*30+1,60*60,'all'),$echo);

                        $totalReplyCount61=$sReport->commentsAdminActivity($from,$to,0,array(60*60+1,60*360,'all'),$echo);
                        $totalReplyCount361=$sReport->commentsAdminActivity($from,$to,0,array(60*360+1,0,'all'),$echo);

                        $ah=$sReport->ahtNart($from,$to);
                        //$general->printArray($ah);
                        $cData=array(
                            'date'=>date('d-M-y',$from),
                            'from'=>date('h:i A',$from),
                            'to'=>date('h:i A',$to),
                            'ua'=>$totalUserActivityComment,
                            'ar'=>$totalReplyCount,
                            'art'=>$ah['art'],
                            'min0'=>$totalReplyCount0,
                            'min6'=>$totalReplyCount6,
                            'min16'=>$totalReplyCount16,
                            'min21'=>$totalReplyCount21,
                            'min31'=>$totalReplyCount31,
                            'min61'=>$totalReplyCount61,
                            'min361'=>$totalReplyCount361
                        );
                        //$general->printArray($cData);
                        $totalUserActivityWall=$sReport->wallPosts($from,$to);
                        $totalReplyCount=$sReport->wallPosts($from,$to);
                        $totalReplyCount=$sReport->wallAdminActivity($from,$to);
                        $totalReplyCount0=$sReport->wallAdminActivity($from,$to,0,array(0,60*5,'all'));
                        $totalReplyCount6=$sReport->wallAdminActivity($from,$to,0,array(60*5+1,60*15,'all'));
                        $totalReplyCount16=$sReport->wallAdminActivity($from,$to,0,array(60*15+1,60*20,'all'));
                        $totalReplyCount21=$sReport->wallAdminActivity($from,$to,0,array(60*20+1,60*30,'all'));
                        $totalReplyCount31=$sReport->wallAdminActivity($from,$to,0,array(60*30+1,60*60,'all'));
                        $totalReplyCount61=$sReport->wallAdminActivity($from,$to,0,array(60*60+1,60*360,'all'));
                        $totalReplyCount361=$sReport->wallAdminActivity($from,$to,0,array(60*360+1,0,'all'));
                        $ah=$sReport->ahtNartWall($from,$to);
                        //$general->printArray($ah);
                        $wData=array(
                            'date'  => date('d-M-y',$from),
                            'from'  => date('h:i A',$from),
                            'to'    => date('h:i A',$to),
                            'ua'    => $totalUserActivityWall,
                            'ar'    => $totalReplyCount,
                            'art'   => $ah['art'],
                            'min0'  => $totalReplyCount0,
                            'min6'  => $totalReplyCount6,
                            'min16' => $totalReplyCount16,
                            'min21' => $totalReplyCount21,
                            'min31' => $totalReplyCount31,
                            'min61' => $totalReplyCount61,
                            'min361'=> $totalReplyCount361
                        );
                        $cacheData=array('wallData'=>$wData,'commentData'=>$cData);
                        $db->reportCacheSet($cacheKey2,json_encode($cacheData),$expTime);
                    }
                    else{
                        $cacheData=json_decode($c2,true);
                    }
                    $tua+=$cacheData['commentData']['ua'];
                    $tr+=$cacheData['commentData']['ar'];
                    $trMin0+=$cacheData['commentData']['min0'];
                    $trMin6+=$cacheData['commentData']['min6'];
                    $trMin16+=$cacheData['commentData']['min16'];
                    $trMin21+=$cacheData['commentData']['min21'];
                    $trMin31+=$cacheData['commentData']['min31'];
                    $trMin61+=$cacheData['commentData']['min61'];
                    $trMin361+=$cacheData['commentData']['min361'];

                    $wtua+=$cacheData['wallData']['ua'];
                    $wtr+=$cacheData['wallData']['ar'];
                    $wtrMin0+=$cacheData['wallData']['min0'];
                    $wtrMin6+=$cacheData['wallData']['min6'];
                    $wtrMin16+=$cacheData['wallData']['min16'];
                    $wtrMin21+=$cacheData['wallData']['min21'];
                    $wtrMin31+=$cacheData['wallData']['min31'];
                    $wtrMin61+=$cacheData['wallData']['min61'];
                    $wtrMin361+=$cacheData['wallData']['min361'];

                    $jArray['wallData'][]=$cacheData['wallData'];
                    $jArray['commentData'][]=$cacheData['commentData'];
                    //}
                }
                $jArray['commentTotal']=array(
                    'ua'    => $tua,
                    'ar'    => $tr,
                    'min0'  => $trMin0,
                    'min6'  => $trMin6,
                    'min16' => $trMin16,
                    'min21' => $trMin21,
                    'min31' => $trMin31,
                    'min61' => $trMin61,
                    'min361'=> $trMin361

                );
                $jArray['wallTotal']=array(
                    'ua'    => $wtua,
                    'ar'    => $wtr,
                    'min0'  => $wtrMin0,
                    'min6'  => $wtrMin6,
                    'min16' => $wtrMin16,
                    'min21' => $wtrMin21,
                    'min31' => $wtrMin31,
                    'min61' => $wtrMin61,
                    'min361'=> $wtrMin361

                );
                $exArrayc=array();
                $exArrayw=array();
            ?>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="col-lg-12">
                            <h3>Comment</h3>
                            <a class="btn btn-default" id="exportComments">Export</a>
                            <table class="table table-striped table-bordered" style="text-align: right;">
                                <tr>
                                    <th>Date</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Query Received</th>
                                    <th>Query Replied</th>
                                    <th>ART</th>
                                    <th>00-05</th>
                                    <th>06-15</th>
                                    <th>16-20</th>
                                    <th>21-30</th>
                                    <th>31-60</th>
                                    <th>61-360</th>
                                    <th>360+</th>
                                </tr>
                                <?php
                                    foreach($jArray['commentData'] as $a){
                                    ?>
                                    <tr>
                                        <td><?php echo $a['date'];?></td>
                                        <td><?php echo $a['from'];?></td>
                                        <td><?php echo $a['to'];?></td>
                                        <td><?php echo $a['ua'];?></td>
                                        <td><?php echo $a['ar'];?></td>
                                        <td><?php echo $a['art'];?></td>
                                        <td><?php echo $a['min0'];?></td>
                                        <td><?php echo $a['min6'];?></td>
                                        <td><?php echo $a['min16'];?></td>
                                        <td><?php echo $a['min21'];?></td>
                                        <td><?php echo $a['min31'];?></td>
                                        <td><?php echo $a['min61'];?></td>
                                        <td><?php echo $a['min361'];?></td>
                                    </tr>
                                    <?php
                                    }
                                    $a=$jArray['commentTotal'];
                                    $exArrayc['xData'] = array("Date","From","To","Query Received","Query Replied","ART","00-05","06-15","16-20","21-30","31-60","61-360","360+"); 
                                    $exArrayc['serises'] = $jArray['commentData'];
                                ?>
                                <tr>
                                    <td><b>Total</b></td>
                                    <td colspan="2"></td>
                                    <td><?php echo $a['ua'];?></td>
                                    <td><?php echo $a['ar'];?></td>
                                    <td>&nbsp;</td>
                                    <td><?php echo $a['min0'];?></td>
                                    <td><?php echo $a['min6'];?></td>
                                    <td><?php echo $a['min16'];?></td>
                                    <td><?php echo $a['min21'];?></td>
                                    <td><?php echo $a['min31'];?></td>
                                    <td><?php echo $a['min61'];?></td>
                                    <td><?php echo $a['min361'];?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-lg-12">
                            <h3>Wall</h3>
                            <a class="btn btn-default" id="exportWall">Export</a>
                            <table class="table table-striped table-bordered" style="text-align: right;">
                                <tr>
                                    <th>Date</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Query Received</th>
                                    <th>Query Replied</th>
                                    <th>ART</th>
                                    <th>00-05</th>
                                    <th>06-15</th>
                                    <th>16-20</th>
                                    <th>21-30</th>
                                    <th>31-60</th>
                                    <th>61-360</th>
                                    <th>360+</th>
                                </tr>
                                <?php
                                    $a=$jArray['commentTotal'];
                                    foreach($jArray['wallData'] as $a){
                                    ?>
                                    <tr>
                                        <td><?php echo $a['date'];?></td>
                                        <td><?php echo $a['from'];?></td>
                                        <td><?php echo $a['to'];?></td>
                                        <td><?php echo $a['ua'];?></td>
                                        <td><?php echo $a['ar'];?></td>
                                        <td><?php echo $a['art'];?></td>
                                        <td><?php echo $a['min0'];?></td>
                                        <td><?php echo $a['min6'];?></td>
                                        <td><?php echo $a['min16'];?></td>
                                        <td><?php echo $a['min21'];?></td>
                                        <td><?php echo $a['min31'];?></td>
                                        <td><?php echo $a['min61'];?></td>
                                        <td><?php echo $a['min361'];?></td>
                                    </tr>
                                    <?php
                                    }
                                    $a=$jArray['wallTotal'];
                                    $exArrayw['xData'] = array("Date","From","To","Query Received","Query Replied","ART","00-05","06-15","16-20","21-30","31-60","61-360","360+"); 
                                    $exArrayw['serises'] = $jArray['wallData'];
                                ?>
                                <tr>
                                    <td><b>Total</b></td>
                                    <td colspan="2"></td>
                                    <td><?php echo $a['ua'];?></td>
                                    <td><?php echo $a['ar'];?></td>
                                    <td>&nbsp;</td>
                                    <td><?php echo $a['min0'];?></td>
                                    <td><?php echo $a['min6'];?></td>
                                    <td><?php echo $a['min16'];?></td>
                                    <td><?php echo $a['min21'];?></td>
                                    <td><?php echo $a['min31'];?></td>
                                    <td><?php echo $a['min61'];?></td>
                                    <td><?php echo $a['min361'];?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        <?php echo "var eIndex = ".json_encode($exArrayc).";";?>
        <?php echo "var eIndex2 = ".json_encode($exArrayw).";";?>
        $("#exportComments").click(function(){
            reportExportToExcel(eIndex);
        })
        $("#exportWall").click(function(){
            reportExportToExcel(eIndex2);
        })
    </script>
    <?php
    }
?>