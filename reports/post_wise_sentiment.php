<?php
    $scentiments=array(
        SCENTIMENT_TYPE_POSITIVE    => array('total'=>0,'title'=>$social->getScentimentTitleById(SCENTIMENT_TYPE_POSITIVE)),
        SCENTIMENT_TYPE_NUTRAL      => array('total'=>0,'title'=>$social->getScentimentTitleById(SCENTIMENT_TYPE_NUTRAL)),
        SCENTIMENT_TYPE_NEGETIVE    => array('total'=>0,'title'=>$social->getScentimentTitleById(SCENTIMENT_TYPE_NEGETIVE))
    );
?>
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
            $dateRangeVal=date('d-m-Y h:i A',$from_date).'__'.date('d-m-Y h:i A',$to);
            $dateRangeShow=date('d-m-Y h:i A',$from_date).' - '.date('d-m-Y h:i A',$to);

//echo $general->printArray($general->make_date(1507658340,'time'));

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
            <input type="submit" value="Show" name="show" class="btn btn-success btn-sm">
        </form>

    </div>
</div>

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
            <a class="btn btn-default" id="export">Export</a>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th style="width: 50%;">Facebook Post</th>
                        <th>Posting Date</th>
                        <th>Positive</th>
                        <th>Neutral</th>
                        <th>Negative</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                        $reportTable=$db->settingsValue('commentReportTable');
                        $sentiment=$db->fetchQuery("
                            select 
                            c.post_id,c.scentiment,count(c.scentiment) t
                            from ".$general->table($reportTable)." c
                            where c.scentiment in(".implode(',',array_keys($scentiments)).") and replyed !=0 and replyBy!=0 and replyTime between ".$from." and ".$to." and sender_id!=".PAGE_ID." 
                            group by c.post_id,c.scentiment
                            ",$general->showQuery());


                        if(!empty($sentiment)){
                            $s2=$sentiment;
                            $general->arrayIndexChange($s2,'post_id');
                            $post_ids=array_keys($s2);
                            $posts=$db->selectAll($general->table(4),"where post_id in('".implode("','",$post_ids)."')");

                            $general->arrayIndexChange($posts,'post_id');
                            $sq='';
                            $i=0;
                            //$dates = range($from, $to,86400);
                            $jArray=array(
                                'name'=>'post_wise_sentiment_report_'.date('d_m_Y',$from).'_'.date('d_m_Y',$to),
                                'title'=>array(
                                    array('title'=>"Serial"         ,'key'=>'s'     ,'w'=>6     ,'hw'=> 3),
                                    array('title'=>"Facebook Post"  ,'key'=>'fp'    ,'w'=>50    ,'hw'=> 3),
                                    array('title'=>"Posting Date"   ,'key'=>'pd'    ,'w'=>25    ,'hw'=> 12),
                                    array('title'=>"Positive"       ,'key'=>'ps'    ,'w'=>25    ,'hw'=> 15),
                                    array('title'=>"Neutral"        ,'key'=>'nu'    ,'w'=>35    ,'hw'=> 12),
                                    array('title'=>"Negative"       ,'key'=>'ng'    ,'w'=>10    ,'hw'=> 15),
                                ),
                                'data'=>array()
                            );
                            $serial=1;

                            foreach($sentiment as $s){
                                if(!isset($result[$s['post_id']])){
                                    $result[$s['post_id']]=array();
                                }
                                if(!isset($result[$s['post_id']][$s['scentiment']])){
                                    $result[$s['post_id']][$s['scentiment']]=0;
                                }
                                $result[$s['post_id']][$s['scentiment']]+=$s['t'];
                            }
                            //$general->printArray($result);exit;
                            $total=array(
                                'ps'=>0,
                                'nu'=>0,
                                'ng'=>0
                            );
                            foreach($result as $post_id=>$s){
                                $p=$posts[$post_id];
                                $data = array(
                                    's' => $serial++,
                                    'fp' => $p['message'],
                                    'pd'=> $general->make_date($p['created_time']),
                                );
                                if(isset($s[SCENTIMENT_TYPE_POSITIVE])){
                                    $data['ps']=$s[SCENTIMENT_TYPE_POSITIVE];
                                }
                                else{
                                    $data['ps']=0;
                                }
                                if(isset($s[SCENTIMENT_TYPE_NUTRAL])){
                                    $data['nu']=$s[SCENTIMENT_TYPE_NUTRAL];
                                }
                                else{
                                    $data['nu']=0;
                                }
                                if(isset($s[SCENTIMENT_TYPE_NEGETIVE])){
                                    $data['ng']=$s[SCENTIMENT_TYPE_NEGETIVE];
                                }
                                else{
                                    $data['ng']=0;
                                }
                                $total['ps']+=$data['ps'];
                                $total['nu']+=$data['nu'];
                                $total['ng']+=$data['ng'];
                                $jArray['data'][] = $data;
                            }
                            $data = array(
                                's' => '',
                                'fp' => "Total",
                                'pd'=> '',
                                'ps'=>$total['ps'],
                                'nu'=>$total['nu'],
                                'ng'=>$total['ng']
                            );
                            $jArray['data'][]=$data;
                            foreach($jArray['data'] as $data){
                            ?>
                            <tr>
                                <td><?php echo $data['s'];?></td>
                                <td><?php echo $data['fp'];?></td>
                                <td><?php echo $data['pd'];?></td>
                                <td><?php echo $data['ps'];?></td>
                                <td><?php echo $data['nu'];?></td>
                                <td><?php echo $data['ng'];?></td>
                            </tr>
                            <?php
                            }
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    <?php echo "
        var eIndex = ".json_encode($jArray).";
        ";?>
    $("#export").click(function(){
        reportJsonToExcel(eIndex);
    });
</script>
