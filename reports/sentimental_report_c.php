<?php
    //echo $general->make_date($from,'time');echo'<br>'; 
    //echo $general->make_date($to,'time');echo'<br>'; 
    $i=0;
    $totals=array();
    $fromT=$from;
    while($fromT<$to){
        //foreach($dates as $fromT){
        $toT =strtotime('+1 day',$fromT);
        $nextFrom=$toT;$toT=strtotime('-1 second',$toT);
        $query="select c.scentiment, count(c.comment_id) as total from ".$general->table(13)." c where c.scentiment in(".implode(',',array_keys($scentiments)).") and sender_id!='".PAGE_ID."' and replyTime BETWEEN ".$fromT." AND ".$toT ." group by c.scentiment";
        $rep=$db->fetchQuery($query,$general->showQuery());
        $rng=date('d/m',$fromT).' - '.date('d/m',$toT);
        if(!empty($rep)){
            foreach($rep as $k){
                $scentiments[$k['scentiment']]['total']+=intval($k['total']);
                $s=$social->getScentimentTitleById($k['scentiment']);
                $wrapusData[$rng][$s]=intval($k['total']);
                // var_dump($wrapusData[$rng][$s]);
            }
        }
        foreach($scentiments as $k=>$v){
            if(!isset($wrapusData[$rng][$v['title']])){$wrapusData[$rng][$v['title']]=0;}
        }
        $fromT=$nextFrom;
    }
    $graphData['scentimentStateScen']=array(
        'title'=>'',
        'data'=>$wrapusData
    );
    $jArray['xData'] = array('Positive','Nutral','Negetive');
    $jArray['serises'] =  $wrapusData;
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Sentiment <small>Comments</small></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <a class="btn btn-default" id="exportComments">Export</a>
            <!--<a class="btn btn-default" id="exportSentiComments">Export</a>-->
            <div id="scentimentStateScen" style="height:350px;"></div>
        </div>
    </div>
</div>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Sentiment <small>Comments</small></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="x_panel">
                    <div class="x_content">
                        <div id="scentiments_comment_pie" style="height:350px;"></div>
                        <script type="text/javascript">
                            var echartPie = echarts.init(document.getElementById('scentiments_comment_pie'));
                            echartPie.setOption({
                                tooltip: {
                                    trigger: 'item',
                                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                                },
                                legend: {
                                    x: 'center',
                                    y: 'bottom',
                                    data: ['Direct Access', 'E-mail Marketing', 'Union Ad', 'Video Ads', 'Search Engine']
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
                                                    width: '50%',
                                                    funnelAlign: 'left',
                                                    max: 1548
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
                                    name: 'Sentiments',
                                    type: 'pie',
                                    radius: '55%',
                                    center: ['50%', '48%'],
                                    data: [
                                        <?php
                                            foreach($scentiments as $s){
                                            ?>
                                            {
                                                value: '<?php echo $s['total'];?>',
                                                name: '<?php echo $s['title'];?>'
                                            },
                                            <?php
                                            }
                                        ?>
                                    ]
                                }]
                            });

                        </script>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-12">
                <div class="x_title">
                    <div class="clearfix"></div>
                </div>
                <table class="table table-striped users_view_table">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Sentiment</th>
                            <th>Total </th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                            $i=1;
                            foreach($scentiments as $v){
                            ?>
                            <tr>
                                <td><?php echo $i++;?></td>
                                <td><?php echo $v['title'];?></td>
                                <td><?php echo $v['total'];?></td>
                            </tr>
                            <?php
                            } 
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
<?php

    $wrapup=$db->selectAll($general->table(11));
    $general->arrayIndexChange($wrapup,'wuID');
    //$general->printArray($wrapup);
    $i=1;
    $query="SELECT wuID,scentiment, COUNT(scentiment) as total FROM ".$general->table(13)." WHERE scentiment in (".implode(',',array_keys($scentiments)).") AND replyTime between ".$from." AND ".$to." and sender_id!=".PAGE_ID." group by wuID,scentiment";
    $total_sen=$db->fetchQuery($query,$general->showQuery());
    if(isset($_GET['shwoq'])){
     $general->printArray($total_sen);   
    }
    $data=array();foreach($total_sen as $t){$data[$t['wuID']][$t['scentiment']]=$t['total'];}
    //$general->printArray($data);
?>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Sentiment <small>By Wrapup</small></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <script>
                var Wrapup_Scentimental_Report={
                    name:'Wrapup_Scentimental_Report',
                    title:[
                        {title:"SL",key:'s',w:10},
                        {title:"Wrapup",key:'w',w:30},
                        <?php
                            foreach($scentiments as $sid=>$s){ 
                                echo '{title:"'.$s['title'].'",key:"sc'.$sid.'",w:30},';
                            }
                        ?>
                    ],
                    data:[]
                };
            </script>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <a class="btn btn-default" id="exportWrapup">Export</a>
                <table class="table table-striped users_view_table">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Wrap-up</th>
                            <?php foreach($scentiments as $s){ ?><th><?php echo $s['title'];?></th><?php }?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $wrapusData=array();
                            foreach($data as $k=>$d){ 
                                $rData=array(
                                    's'=>$i++,
                                    'w'=>$wrapup[$k]['wuTitle'],
                                );
                                foreach($scentiments as $sid=>$s){
                                    if(isset($d[$sid])){
                                        $rData['sc'.$sid]=$d[$sid];
                                    }
                                    else{
                                        $rData['sc'.$sid]=0;
                                    }
                                }
                                $wrapusData[]=$rData;
                            }
                            foreach($wrapusData as $r){
                            ?>
                            <tr>
                            <?php
                                foreach($r as $v){
                                ?><td><?php echo $v;?></td><?php
                                }

                            } 
                            $jArray2['xData'] = array('Wrap-up','Positive','Nutral','Negative');
                            $jArray2['serises'] =  $wrapusData
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <!--<div id="scentimentByWrapup" style="height:350px;"></div>-->
                <?php

                    $graphData['scentimentByWrapup']=array(
                        'title'=>'',
                        'data'=>$wrapusData
                    );
                ?>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Sentiment <small>By operator</small></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <a class="btn btn-default" id="exportSentimental">Export</a>
            <?php
                $users=$db->allUsers('order by uFullName asc');
                //                $users=$db->selectAll($general->table(17),'order by uFullName asc');
                $general->arrayIndexChange($users,'uID');
                $query="SELECT replyBy,scentiment, COUNT(scentiment) as total FROM ".$general->table(13)." WHERE scentiment in (".implode(',',array_keys($scentiments)).") AND replyBy in  (".implode(',',array_keys($users)).") and sender_id!=".PAGE_ID." AND replyTime between ".$from." AND ".$to.' group by replyBy,scentiment';
                $total_sen=$db->fetchQuery($query);
                $data=array();foreach($total_sen as $t){$data[$t['replyBy']][$t['scentiment']]=$t['total'];}
                foreach($data as $k=>$d){
                    foreach($scentiments as $sk=>$v){
                        if(!isset($d[$sk])){$data[$k][$sk]=0;}
                    }
                }
            ?>
            <table class="table table-striped users_view_table">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Operator name</th>
                        <?php foreach($scentiments as $s){ ?><th><?php echo $s['title'];?></th><?php }?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $i=1;
                        foreach($data as $k=>$d){ 
                        ?>
                        <tr>
                            <td><?php echo $i++;?></td>
                            <td><?php echo $users[$k]['uFullName'];?></td>
                            <?php
                                foreach($d as $v){
                                ?><td><?php echo $v;?></td><?php
                                }
                            ?>
                        </tr>
                        <?php
                            $eData[$users[$k]['uFullName']] =  $d;
                        } 
                        $jArray3['xData']= array('Positive','Nutral','Negetive');
                        $jArray3['serises'] =  $eData  ;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    <?php echo "var eIndex = ".json_encode($jArray).";";?>
    <?php echo "Wrapup_Scentimental_Report.data = ".json_encode($wrapusData).";";?>
    <?php echo "var eIndex3 = ".json_encode($jArray3).";";?>
    $("#exportComments").click(function(){
        reportExportToExcel(eIndex); 
    });
    $("#exportWrapup").click(function(){
        t(Wrapup_Scentimental_Report)
        reportJsonToExcel(Wrapup_Scentimental_Report); 
    });
    $("#exportSentimental").click(function(){
        reportExportToExcel(eIndex3); 
    });
</script>
