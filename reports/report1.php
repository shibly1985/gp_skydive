<script src="vendors/Chart.js/dist/Chart.min.js"></script>
<script src="vendors/echarts/dist/echarts.min.js"></script>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><a href="<?php echo $pUrl;?>"><?php echo $rModule['cmTitle'];?></a></h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="animated flipInY col-lg-2 col-md-2 col-sm-3 col-xs-12">
                <div class="tile-stats senti_box">
                    <a href="<?php echo $pUrl;?>&type=r">
                        <h3><i class="fa fa-comments-o"></i> Response Stats</h3>
                    </a>
                </div>
            </div>
            <div class="animated flipInY col-lg-2 col-md-2 col-sm-3 col-xs-12">
                <div class="tile-stats senti_box">
                    <a href="<?php echo $pUrl;?>&type=w">
                        <h3><i class="fa fa-comments-o"></i> Wrap-up Stats</h3>
                    </a>
                </div>
            </div>
            <div class="animated flipInY col-lg-2 col-md-2 col-sm-3 col-xs-12">
                <div class="tile-stats senti_box">
                    <a href="<?php echo $pUrl;?>&type=s">
                        <h3><i class="fa fa-comments-o"></i> Sentiment Stats</h3>
                    </a>
                </div>
            </div>
        </div>
        <?php
            $type='s';
            if(isset($_GET['type'])){
                if($_GET['type']=='w'){
                    $type='w';
                }elseif($_GET['type']=='r'){
                    $type='r';
                }
            }
            if(isset($_GET['date_range'])){
                $date_range=$_GET['date_range'];
            }
            else{
                $dr = date('d-m-Y 00:00:00');
                $dr2= date('d-m-Y 00:00:00');
                $date_range=$dr.'__'.$dr2;
            }

            $dates=explode('__',$date_range);
            $from_date=strtotime($dates[0]);
            $from=$from_date;
            $to=strtotime($dates[1]);
            if(date('H:i',$to)=='00:00'){
                $to=strtotime('+1 day',$to);
                $to=strtotime('-1 second',$to);
            }
            //            echo $general->make_date($from,'time');echo'<br>'; 
            //            echo $general->make_date($to,'time');echo'<br>'; 
            $diff=$to-$from;
            $dateRangeVal=date('d-m-Y h:i A',$from_date).'__'.date('d-m-Y h:i A',$to);
            $dateRangeShow=date('d-m-Y h:i A',$from_date).' - '.date('d-m-Y h:i A',$to);
            $st_date = $dates[0];
            $ed_date = $dates[1];
        ?>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#reportrange').daterangepicker({
                    timePicker: true,
                    opens: "right",
                    autoApply: true,
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
            <input type="hidden" name="type" value="<?php echo $type;?>">
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
<?php
    //var_dump($st_date);echo'<br>';var_dump($ed_date);echo'<br>';  
    $st_date=$from;
    $ed_date=$to;
    $i=0;
    $dates=array();
    $comments=array();
    $replys=array();
    $avTimes=array();
    $reportTable            = $db->settingsValue('commentReportTable');
    $wallCommentReportTable = $db->settingsValue('commentWallReportTable');
    $wallPostReportTable    = $db->settingsValue('postWallReportTable');
    if($type=='w'){
        $wrapusData=array();
        $wrapusDataw=array();
        $wrapusDatam=array();
        $query="
        select w.wuID,w.wcID,w.wuTitle,wc.wcTitle from ".$general->table(11)." w 
        left join ".$general->table(34)." wc on wc.wcID=w.wcID";
        $wrapup =$db->fetchQuery($query,$general->showQuery());$general->arrayIndexChange($wrapup,'wuID');
        //$general->printArray($wrapup);
    }
    else if($type=='s'){
        $scentimentData=array();
        $scentiments=array(
            SCENTIMENT_TYPE_POSITIVE    => array('total'=>0,'title'=>$social->getScentimentTitleById(SCENTIMENT_TYPE_POSITIVE)),
            SCENTIMENT_TYPE_NUTRAL      => array('total'=>0,'title'=>$social->getScentimentTitleById(SCENTIMENT_TYPE_NUTRAL)),
            SCENTIMENT_TYPE_NEGETIVE    => array('total'=>0,'title'=>$social->getScentimentTitleById(SCENTIMENT_TYPE_NEGETIVE))
        );

        $scentiment=array(
            'Positive'  => SCENTIMENT_TYPE_POSITIVE,
            'Nutral'    => SCENTIMENT_TYPE_NUTRAL,
            'Negetive'  => SCENTIMENT_TYPE_NEGETIVE
        );
    }
    else{

    }




    $from=$st_date;
    while($from<$ed_date){
        $fD=$general->make_date($from);
        $to =strtotime('+1 day', $from);
        $to =strtotime('-1 second', $to);
        if($type=='w'){
            if(OPERATION_MESSAGE_ALLWO==true){
                $query="
                select count(mid) as t,wuID from ".$general->table(9)." where sendType=2 and replyTime BETWEEN ".$from." AND ".$to." and wuID!=0 group by wuID";
                $wrapupd=$db->fetchQuery($query,$general->showQuery());

                if(!empty($wrapupd)){$general->arrayIndexChange($wrapupd,'wuID');}
                //$general->printArray($wrapupd);
                foreach($wrapup as $wrap){
                    if(isset($wrapupd[$wrap['wuID']])){
                        $wrapusDatam[$fD][$wrap['wuID']]=$wrapupd[$wrap['wuID']]['t'];
                    }
                    else{
                        $wrapusDatam[$fD][$wrap['wuID']]=0;
                    }
                }
            }
            $query="
            select count(comment_id) as t,wuID from ".$general->table($reportTable)." where sender_id!=".PAGE_ID." and replyTime BETWEEN ".$from." AND ".$to." and wuID!=0 and replyed!=0 group by wuID";
            $wrapupd=$db->fetchQuery($query,$general->showQuery());
//            $wrapupd=array();

            if(!empty($wrapupd)){$general->arrayIndexChange($wrapupd,'wuID');}
            foreach($wrapup as $wrap){
                if(isset($wrapupd[$wrap['wuID']])){
                    $wrapusData[$fD][$wrap['wuID']]=$wrapupd[$wrap['wuID']]['t'];
                }
                else{
                    $wrapusData[$fD][$wrap['wuID']]=0;
                }
            }
            $query="select count(post_id) as t,wuID from ".$general->table($wallPostReportTable)." where replyTime BETWEEN ".$from." AND ".$to." and wuID!=0 and replyed!=0 group by wuID";
            //$wrapupd=$db->fetchQuery($query,$general->showQuery());
            $wrapupd=array();
            if(!empty($wrapupd)){$general->arrayIndexChange($wrapupd,'wuID');}
            foreach($wrapup as $wrap){
                if(isset($wrapupd[$wrap['wuID']])){
                    $wrapusDataw[$fD][$wrap['wuID']]=$wrapupd[$wrap['wuID']]['t'];
                }
                else{
                    $wrapusDataw[$fD][$wrap['wuID']]=0;
                }
            }
            $query="select count(comment_id) as t,wuID from ".$general->table($wallCommentReportTable)." where sender_id!=".PAGE_ID." and replyTime BETWEEN ".$from." AND ".$to." and wuID!=0 and replyed!=0 group by wuID";
            $wrapupd=$db->fetchQuery($query,$general->showQuery());
            if(!empty($wrapupd)){$general->arrayIndexChange($wrapupd,'wuID');}
            foreach($wrapup as $wrap){
                if(isset($wrapupd[$wrap['wuID']])){
                    $wrapusDataw[$fD][$wrap['wuID']]+=$wrapupd[$wrap['wuID']]['t'];
                }
                else{
                    if(!isset($wrapusDataw[$fD][$wrap['wuID']])){
                        $wrapusDataw[$fD][$wrap['wuID']]=0;    
                    }

                }
            }
            /*$tccccc=0;
            foreach($wrapusDataw[$fd] as $w){
            $tccccc+=$w;
            echo $tccccc;echo'<br>'; 
            }*/
            /*$ot=array();
            foreach($wrapupd as $k=> $w){
            if(isset($wrapusDataw[$fD][$k])){

            }
            else{
            $ot[$k]=$wrapup[$k]['wuTitle'];
            }
            }*/
            //$general->printArray($ot);
        }
        elseif($type=='s'){

            $query="select c.scentiment, count(c.comment_id) as total from ".$general->table($reportTable)." c where c.scentiment in(".implode(',',array_keys($scentiments)).") and sender_id!='".PAGE_ID."' and replyTime BETWEEN ".$from." AND ".$to ." group by c.scentiment";
            $rep=$db->fetchQuery($query,$general->showQuery());
            $scentimentDataGraph=array();
            foreach($rep as $k){
                $scentimentData[$fD][$social->getScentimentTitleById($k['scentiment'])]=intval($k['total']);
                if(!isset($scentimentDataGraph[$social->getScentimentTitleById($k['scentiment'])])){
                    $scentimentDataGraph[$social->getScentimentTitleById($k['scentiment'])]=0;
                }
                $scentimentDataGraph[$social->getScentimentTitleById($k['scentiment'])]+=intval($k['total']);
            }
        }
        else{
            $total_comment = $sReport->commentsUserActivity($from,$to);
            $total_reply = $sReport->commentsAdminActivity($from,$to);
            $art=$sReport->ahtNart($from,$to);
            //$general->printArray($art);
            $comments[$i] = $total_comment;
            $replys[$i] = $total_reply;
            $responsTime = $art['arti']/60;
            $avTimes[$i] =$responsTime;
        }



        $dates[$i]=$from;

        $i++;
        $from=$to;
        $from =strtotime('+1 second', $from);
    }
    if($type=='w'){
        if(OPERATION_MESSAGE_ALLWO==true){
            $wrm=array();
            foreach($wrapusDatam as $date=>$w){

                foreach($w as $wu=>$count){
                    if($count>0){
                        $wrm[$wu][$date]=$count;
                    }
                }
            }
            $dft=array();
            $maxesm=array();
            $jArraym=array();
            $sl=1;
            foreach($wrm as $k=>$w){
                $rt=array(
                    's' => $sl++,
                    'wc'=> $wrapup[$k]['wcTitle'],
                    'w' => $wrapup[$k]['wuTitle']
                );
                $t=0;
                foreach($w as $d=>$c){
                    $dft[$d]=$d;
                    $t+=$c;
                    $rt[$d]=$c;
                }
                $rt['t']=$t;
                $maxesm[$k]=$t;
                $jArraym[]=$rt;
            }
            //$general->printArray($maxes);
            if(!empty($maxesm)){
                $general->arraySortMaxtToMinWithKey($maxesm);
                $n=array();
                $i=0;
                foreach($maxesm as $k=>$v){
                    $n[$wrapup[$k]['wuTitle']]=$v;
                    $i++;
                    if($i>9)break;
                }
                $maxesm=$n;
                //$general->printArray($maxes);
            }
            //exit;
        }
        $wr=array();
        foreach($wrapusData as $date=>$w){
            foreach($w as $wu=>$count){
                if($count>0){
                    $wr[$wu][$date]=$count;
                }
            }
        }
        $dft=array();
        $maxes=array();
        $jArray2=array();
        $sl=1;
        foreach($wr as $k=>$w){
            $rt=array(
                's' => $sl++,
                'wc'=> $wrapup[$k]['wcTitle'],
                'w' => $wrapup[$k]['wuTitle']
            );
            $t=0;
            foreach($w as $d=>$c){
                $dft[$d]=$d;
                $t+=$c;
                $rt[$d]=$c;
            }
            $rt['t']=$t;
            $maxes[$k]=$t;
            $jArray2[]=$rt;
        }
        //$general->printArray($maxes);
        if(!empty($maxes)){
            $general->arraySortMaxtToMinWithKey($maxes);
            $n=array();
            $i=0;
            foreach($maxes as $k=>$v){
                $n[$wrapup[$k]['wuTitle']]=$v;
                $i++;
                if($i>9)break;
            }
            $maxes=$n;
            //$general->printArray($maxes);
        }
        //exit;

        $wrw=array();
        foreach($wrapusDataw as $date=>$w){
            foreach($w as $wu=>$count){
                if($count>0){
                    $wrw[$wu][$date]=$count;
                }
            }
        }
        $dftw=array();
        $maxesw=array();
        $jArray2w=array();
        $sl=1;
        foreach($wrw as $k=>$w){
            $rt=array(
                's' => $sl++,
                'wc'=> $wrapup[$k]['wcTitle'],
                'w' => $wrapup[$k]['wuTitle']
            );
            $t=0;
            foreach($w as $d=>$c){
                $dftw[$d]=$d;
                $t+=$c;
                $rt[$d]=$c;
            }
            $rt['t']=$t;
            $maxesw[$k]=$t;
            $jArray2w[]=$rt;
        }
        //$general->printArray($maxesw);
        if(!empty($maxesw)){
            //asort($maxesw);
            $general->arraySortMaxtToMinWithKey($maxesw);
            $n=array();
            $i=0;
            foreach($maxesw as $k=>$v){
                $n[$wrapup[$k]['wuTitle']]=$v;
                $i++;
                if($i>9)break;
            }
            $maxesw=$n;
            //$general->printArray($maxesw);
        }
    }
    else if($type=='s'){
        $t=array();
        foreach($scentimentData as $w){
            foreach($w as $k=>$b){
                $t[$k][]=$b;
            }
        }
        foreach($t as $k=>$b){
            $jArray3['serises'][$social->getScentimentTitleById($k)] = $b;

        }
        //$general->printArray($scentimentData);
    }
    else{
        $jArray = array();
        $jArray['xData'] = $dates; 
        $jArray['serises']['comments'] = $comments; 
        $jArray['serises']['replys'] = $replys; 
        $jArray['serises']['avTimes'] = $avTimes; 
    }
?>
<div class="row">
    <?php
        if($type=='w'){
            if(OPERATION_MESSAGE_ALLWO==true){
            ?>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-6 col-sm-12 col-xs-6">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Wrapup Stats <small>Message</small></h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="btn btn-default" id="exportWrapupm">Export</a></li>
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">
                            <div id="responseDailyStatem" style="height:400px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            }
        ?>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-6 col-sm-12 col-xs-6">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Wrapup Stats <small>Comment</small></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="btn btn-default" id="exportWrapup">Export</a></li>
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">
                        <div id="responseDailyState" style="height:400px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Wrapup Stats <small>Wall</small></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="btn btn-default" id="exportWrapupw">Export</a></li>
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div id="responseDailyStatew" style="height:400px;"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        }
        else if($type=='s'){
        ?>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-4 col-sm-12 col-xs-4">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Sentiment <small>Stats</small></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div id="responseDailyStateScen" style="height:400px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-sm-12 col-xs-4">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Sentiment <small>Stats</small></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <a class="btn btn-default" id="exportSentimental">Export</a>
                        <canvas id="responseDailyStateScen2"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <?php
        }
        else{
        ?>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Response <small>Stats</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">

                    <a class="btn btn-default" id="exportResponse">Export</a>
                    <div id="responseState" style="height:350px;"></div>
                    <!--                            <canvas id="mybarChart"></canvas>-->
                </div>
            </div>
        </div>
        <?php
        }
    ?>
</div>
<?php

    //$general->printArray($wrapusData);

    $sl=1;

    $jd=array();

    $jArray3 = array();
    $jArray3['xData'] = $dates; 
    $t=array();
    //$general->printArray($scentimentData);
?>

<script>
    var theme = {
        color: [
            '#26B99A', '#34495E', '#BDC3C7', '#3498DB',
            '#9B59B6', '#8abb6f', '#759c6a', '#bfd3b7'
        ],

        title: {
            itemGap: 8,
            textStyle: {
                fontWeight: 'normal',
                color: '#408829'
            }
        },

        dataRange: {
            color: ['#1f610a', '#97b58d']
        },

        toolbox: {
            color: ['#408829', '#408829', '#408829', '#408829']
        },

        tooltip: {
            backgroundColor: 'rgba(0,0,0,0.5)',
            axisPointer: {
                type: 'line',
                lineStyle: {
                    color: '#408829',
                    type: 'dashed'
                },
                crossStyle: {
                    color: '#408829'
                },
                shadowStyle: {
                    color: 'rgba(200,200,200,0.3)'
                }
            }
        },

        dataZoom: {
            dataBackgroundColor: '#eee',
            fillerColor: 'rgba(64,136,41,0.2)',
            handleColor: '#408829'
        },
        grid: {
            borderWidth: 0
        },

        categoryAxis: {
            axisLine: {
                lineStyle: {
                    color: '#408829'
                }
            },
            splitLine: {
                lineStyle: {
                    color: ['#eee']
                }
            }
        },

        valueAxis: {
            axisLine: {
                lineStyle: {
                    color: '#408829'
                }
            },
            splitArea: {
                show: true,
                areaStyle: {
                    color: ['rgba(250,250,250,0.1)', 'rgba(200,200,200,0.1)']
                }
            },
            splitLine: {
                lineStyle: {
                    color: ['#eee']
                }
            }
        },
        timeline: {
            lineStyle: {
                color: '#408829'
            },
            controlStyle: {
                normal: {color: '#408829'},
                emphasis: {color: '#408829'}
            }
        },

        k: {
            itemStyle: {
                normal: {
                    color: '#68a54a',
                    color0: '#a9cba2',
                    lineStyle: {
                        width: 1,
                        color: '#408829',
                        color0: '#86b379'
                    }
                }
            }
        },
        map: {
            itemStyle: {
                normal: {
                    areaStyle: {
                        color: '#ddd'
                    },
                    label: {
                        textStyle: {
                            color: '#c12e34'
                        }
                    }
                },
                emphasis: {
                    areaStyle: {
                        color: '#99d2dd'
                    },
                    label: {
                        textStyle: {
                            color: '#c12e34'
                        }
                    }
                }
            }
        },
        force: {
            itemStyle: {
                normal: {
                    linkStyle: {
                        strokeColor: '#408829'
                    }
                }
            }
        },
        chord: {
            padding: 4,
            itemStyle: {
                normal: {
                    lineStyle: {
                        width: 1,
                        color: 'rgba(128, 128, 128, 0.5)'
                    },
                    chordStyle: {
                        lineStyle: {
                            width: 1,
                            color: 'rgba(128, 128, 128, 0.5)'
                        }
                    }
                },
                emphasis: {
                    lineStyle: {
                        width: 1,
                        color: 'rgba(128, 128, 128, 0.5)'
                    },
                    chordStyle: {
                        lineStyle: {
                            width: 1,
                            color: 'rgba(128, 128, 128, 0.5)'
                        }
                    }
                }
            }
        },
        gauge: {
            startAngle: 225,
            endAngle: -45,
            axisLine: {
                show: true,
                lineStyle: {
                    color: [[0.2, '#86b379'], [0.8, '#68a54a'], [1, '#408829']],
                    width: 8
                }
            },
            axisTick: {
                splitNumber: 10,
                length: 12,
                lineStyle: {
                    color: 'auto'
                }
            },
            axisLabel: {
                textStyle: {
                    color: 'auto'
                }
            },
            splitLine: {
                length: 18,
                lineStyle: {
                    color: 'auto'
                }
            },
            pointer: {
                length: '90%',
                color: 'auto'
            },
            title: {
                textStyle: {
                    color: '#333'
                }
            },
            detail: {
                textStyle: {
                    color: 'auto'
                }
            }
        },
        textStyle: {
            fontFamily: 'Arial, Verdana, sans-serif'
        }
    };
    <?php
        if($type=='w'){
            $t=array();
            foreach($maxes as $k=>$b){
                $t[$k]=$b;
            }
            $lbl=array();
            foreach($t as $k=>$b){
                $lbl[]=$wrapup[$k]['wuTitle'].$k;
            }
            if(OPERATION_MESSAGE_ALLWO==true){
                $tm=array();
                foreach($maxesm as $k=>$b){
                    $tm[$k]=$b;
                }
                $lblm=array();
                foreach($tm as $k=>$b){
                    $lblm[]=$wrapup[$k]['wuTitle'].$k;
                }
            }
            $tw=array();
            foreach($maxesw as $k=>$b){
                $tw[$k]=$b;
            }
            $lblw=array();
            foreach($tw as $k=>$b){
                $lblw[]=$wrapup[$k]['wuTitle'].$k;
            }
            if(OPERATION_MESSAGE_ALLWO==true){
            ?>

            var echartPie = echarts.init(document.getElementById('responseDailyStatem'), theme);

            echartPie.setOption({
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                legend: {
                    x: 'center',
                    y: 'bottom',
                    data: ['<?php echo implode("','",$lblm) ?>']
                },
                toolbox: {
                    show: true,
                    feature: {
                        magicType: {
                            show: true,
                            type: ['pie', 'funnel'],
                            option: {
                                funnel: {
                                    x: '25%',
                                    width: '70%',
                                    funnelAlign: 'right'
                                    /*max: 1548*/
                                }
                            }
                        },
                        restore: {show: true,title: "Restore"},
                        saveAsImage: {show: true,title: "Save Image"}
                    }
                },
                calculable: true,
                series: [{
                    name: 'Wrapup Stats Comment Top 10',
                    type: 'pie',
                    radius: '55%',
                    center: ['50%', '48%'],
                    data: [
                        <?php
                            foreach($tm as $k=>$b){
                            ?>
                            {
                                name: '<?php echo $wrapup[$k]['wuTitle'].$k;?>',
                                value:'<?php echo $b;?>'
                            },
                            <?php
                            }
                        ?>
                    ]
                }]
            });


            <?php
            }
        ?>
        var echartPie = echarts.init(document.getElementById('responseDailyState'), theme);

        echartPie.setOption({
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                x: 'center',
                y: 'bottom',
                data: ['<?php echo implode("','",$lbl) ?>']
            },
            toolbox: {
                show: true,
                feature: {
                    magicType: {
                        show: true,
                        type: ['pie', 'funnel'],
                        option: {
                            funnel: {
                                x: '25%',
                                width: '70%',
                                funnelAlign: 'right'
                                /*max: 1548*/
                            }
                        }
                    },
                    restore: {show: true,title: "Restore"},
                    saveAsImage: {show: true,title: "Save Image"}
                }
            },
            calculable: true,
            series: [{
                name: 'Wrapup Stats Comment Top 10',
                type: 'pie',
                radius: '55%',
                center: ['50%', '48%'],
                data: [
                    <?php
                        foreach($t as $k=>$b){
                        ?>
                        {
                            name: '<?php echo $wrapup[$k]['wuTitle'].$k;?>',
                            value:'<?php echo $b;?>'
                        },
                        <?php
                        }
                    ?>
                ]
            }]
        });


        var echartPie = echarts.init(document.getElementById('responseDailyStatew'), theme);

        echartPie.setOption({
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                x: 'center',
                y: 'bottom',
                data: ['<?php echo implode("','",$lblw) ?>']
            },
            toolbox: {
                show: true,
                feature: {
                    magicType: {
                        show: true,
                        type: ['pie', 'funnel'],
                        option: {
                            funnel: {
                                x: '25%',
                                width: '70%',
                                funnelAlign: 'right'
                                /*max: 1548*/
                            }
                        }
                    },
                    restore: {
                        show: true,
                        title: "Restore"
                    },
                    saveAsImage: {
                        show: true,
                        title: "Save Image"
                    }
                }
            },
            calculable: true,
            series: [{
                name: 'Wrapup Stats Wall Top 10',
                type: 'pie',
                radius: '55%',
                center: ['50%', '48%'],
                data: [
                    <?php
                        foreach($tw as $k=>$b){ ?>{name: '<?php echo $wrapup[$k]['wuTitle'].$k;?>',value:'<?php echo $b;?>'},<?php } ?>
                ]
            }]
        });


        var reportHead={
            name:'Wrapup_Report_Comment_<?php echo $general->make_url($general->make_date($st_date));?>-<?php echo $general->make_url($general->make_date($ed_date));?>',
            title:[
                {title:"SL",key:'s',w:10},
                {title:"Category",key:'wc',w:15},
                {title:"Wrapup",key:'w',w:40},
                <?php
                    foreach($dft as $d){
                        echo "{title:".'"'.$d.'"'.",key:".'"'.$d.'"'.",w:15},";
                    }
                ?>
                {title:"Total",key:'t',w:15}
            ],
            data:[]
        };
        <?php
            if(OPERATION_MESSAGE_ALLWO==true){
            ?>
            var reportHeadm={
                name:'Wrapup_Report_Message_<?php echo $general->make_url($general->make_date($st_date));?>-<?php echo $general->make_url($general->make_date($ed_date));?>',
                title:[
                    {title:"SL",key:'s',w:10},
                    {title:"Category",key:'wc',w:15},
                    {title:"Wrapup",key:'w',w:40},
                    <?php
                        foreach($dftm as $d){
                            echo "{title:".'"'.$d.'"'.",key:".'"'.$d.'"'.",w:15},";
                        }
                    ?>
                    {title:"Total",key:'t',w:15}
                ],
                data:[]
            };

            <?php echo "reportHeadm.data = ".json_encode($jArraym).";";?>
            $("#exportWrapupm").click(function(){
                reportJsonToExcel(reportHeadm); 
            });
            <?php
            }
        ?>
        var reportHead={
            name:'Wrapup_Report_Comment_<?php echo $general->make_url($general->make_date($st_date));?>-<?php echo $general->make_url($general->make_date($ed_date));?>',
            title:[
                {title:"SL",key:'s',w:10},
                {title:"Category",key:'wc',w:15},
                {title:"Wrapup",key:'w',w:40},
                <?php
                    foreach($dft as $d){
                        echo "{title:".'"'.$d.'"'.",key:".'"'.$d.'"'.",w:15},";
                    }
                ?>
                {title:"Total",key:'t',w:15}
            ],
            data:[]
        };
        var reportHeadw={
            name:'Wrapup_Report_Wall_<?php echo $general->make_url($general->make_date($st_date));?>-<?php echo $general->make_url($general->make_date($ed_date));?>',
            title:[
                {title:"SL",key:'s',w:10},
                {title:"Category",key:'wc',w:15},
                {title:"Wrapup",key:'w',w:40},
                <?php
                    foreach($dftw as $d){
                        echo "{title:".'"'.$d.'"'.",key:".'"'.$d.'"'.",w:15},";
                    }
                ?>
                {title:"Total",key:'t',w:15}
            ],
            data:[]
        };
        <?php echo "reportHead.data = ".json_encode($jArray2).";";?>
        <?php echo "reportHeadw.data = ".json_encode($jArray2w).";";?>
        $(document).ready(function(){
            $("#exportWrapup").click(function(){
                reportJsonToExcel(reportHead); 
            });
            $("#exportWrapupw").click(function(){
                reportJsonToExcel(reportHeadw); 
            }); 
        });
        <?php
        }
        else if($type=='s'){
        ?>
        var reportHead={
            name:'Scentiment_Report_<?php echo $general->make_url($general->make_date($st_date));?>-<?php echo $general->make_url($general->make_date($ed_date));?>',
            title:[
                {title:"Date",key:'d',w:10},
                {title:"<?php echo $social->getScentimentTitleById(SCENTIMENT_TYPE_POSITIVE);?>",key:'ps',w:15},
                {title:"<?php echo $social->getScentimentTitleById(SCENTIMENT_TYPE_NUTRAL);?>",key:'nt',w:15},
                {title:"<?php echo $social->getScentimentTitleById(SCENTIMENT_TYPE_NEGETIVE);?>",key:'ng',w:15},
                {title:"Total",key:'t',w:15}
            ],
            data:[]
        };
        var sce = document.getElementById("responseDailyStateScen2");
        var mybarChart = new Chart(sce, {
            type: 'bar',
            data: {
                labels: [<?php echo '"'.implode('","',array_keys($scentimentData)).'"';?>],
                datasets: [
                    <?php
                        $t=array();
                        foreach($scentimentData as $w){
                            foreach($w as $k=>$b){
                                $t[$k][]=$b;
                            }
                        }

                        $jArray3=array();
                        foreach($scentimentData as $date=>$w){
                            $ng=array(
                                'd'=>$date,
                                'ps'=>$w[$social->getScentimentTitleById(SCENTIMENT_TYPE_POSITIVE)],
                                'nt'=>$w[$social->getScentimentTitleById(SCENTIMENT_TYPE_NUTRAL)],
                                'ng'=>$w[$social->getScentimentTitleById(SCENTIMENT_TYPE_NEGETIVE)]
                            );
                            $ng['t']=$ng['ps']+$ng['nt']+$ng['ng'];
                            $jArray3[]=$ng;
                        }
                        $i=0;
                        $colorCodes[0]='#388E3C';
                        $colorCodes[1]='#F9890C';
                        $colorCodes[2]='#DB2724';
                        foreach($t as $k=>$b){
                        ?>
                        {
                            label: '<?php echo $k;?>',
                            backgroundColor: "<?php echo $colorCodes[$i];?>",
                            data: [<?php echo implode(',',$b);?>]
                        },
                        <?php
                            $i++;
                        }
                    ?>
                ]
            },

            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });

        var echartPie = echarts.init(document.getElementById('responseDailyStateScen'), theme);

        echartPie.setOption({
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                x: 'center',
                y: 'bottom',
                data: ['<?php echo implode("','",$lbl) ?>']
            },
            color: ['#388E3C','#F9890C','#DB2724'],
            toolbox: {
                show: true,
                feature: {
                    magicType: {
                        show: true,
                        //                        type: ['pie', 'funnel'],
                        type: ['pie', 'funnel'],
                        option: {
                            funnel: {
                                x: '25%',
                                width: '70%',
                                funnelAlign: 'right'
                                /*max: 1548*/
                            }
                        }
                    },
                    restore: {
                        show: true,
                        title: "Restore"
                    },
                    saveAsImage: {
                        show: true,
                        title: "Save Image"
                    }
                }
            },
            calculable: true,
            series: [{
                name: 'Sentiment Stats ',
                type: 'pie',
                radius: '55%',
                center: ['50%', '48%'],
                data: [
                    <?php

                        $clc=0;
                        foreach($scentimentDataGraph as $k=>$b){
                        ?>
                        {
                            name: '<?php echo $k;?>',
                            value:'<?php echo $b;?>'
                        },
                        <?php
                        }
                    ?>
                    /*  
                    {
                    value: 335,
                    name: 'Direct Access'
                    }, {
                    value: 310,
                    name: 'E-mail Marketing'
                    }, {
                    value: 234,
                    name: 'Union Ad'
                    }, {
                    value: 135,
                    name: 'Video Ads'
                    }, {
                    value: 1548,
                    name: 'Search Engine'
                    }*/
                ]
            }]
        });

        <?php echo "reportHead.data = ".json_encode($jArray3).";";?>
        $("#exportSentimental").click(function(){
            reportJsonToExcel(reportHead); 
        });
        <?php
        }
        else{
            $dt=array();
            foreach($dates as $d){
                $dt[]=$general->make_date($d);
            }
        ?>
        var responseState = echarts.init(document.getElementById('responseState'), theme);
        responseState.setOption({
            title: {
                x: 'center',
                y: 'top',
                padding: [0, 0, 20, 0],
                text: 'Daily Comment , Reply and Avg. Response Time',
                textStyle: {
                    fontSize: 15,
                    fontWeight: 'normal'
                }
            },
            tooltip: {
                trigger: 'axis'
            },
            toolbox: {
                show: true,
                feature: {
                    dataView: {
                        show: true,
                        readOnly: false,
                        title: "Text View",
                        lang: [
                            "Text View",
                            "Back",
                            "Refresh",
                        ],
                    },
                    restore: {
                        show: true,
                        title: 'Restore'
                    },
                    saveAsImage: {
                        show: true,
                        title: 'Save'
                    }
                }
            },
            calculable: true,
            legend: {
                data: ['Comments', 'Reply', 'ART'],
                y: 'bottom'
            },
            xAxis: [{
                type: 'category',
                data: [<?php echo "'".implode("','",$dt)."'";?>]
            }],
            yAxis: [{
                type: 'value',
                name: 'Comments',
                axisLabel: {
                    formatter: '{value}'
                }
                }, {
                    type: 'value',
                    name: 'Reply',
                    axisLabel: {
                        formatter: '{value} Min'
                    }
            }],
            series: [
                {
                    name: 'Comments',
                    type: 'bar',
                    data: [<?php echo implode(',',$comments);?>]
                }, 
                {
                    name: 'Reply',
                    type: 'bar',
                    data: [<?php echo implode(',',$replys);?>]
                },
                {
                    name: 'Average Reply',
                    type: 'line',
                    yAxisIndex: 1,
                    data: [<?php echo implode(',',$avTimes);?>]
            }]
        });
        <?php echo "var eIndex = ".json_encode($jArray).";";?>
        $("#exportResponse").click(function(){
            reportExportToExcel(eIndex); 
        });
        <?php
        }
    ?>
</script> 
<script type="">




</script>

