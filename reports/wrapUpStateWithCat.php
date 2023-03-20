<?php
    $wcID=intval($_GET['cat']);
    if($wcID!=0){
        $wc=$db->get_rowData($general->table(34),'wcID',$wcID);
        if(empty($wc)){$wcID=0;}
    }
    if($wcID==0){
        include("report_header.php");
        $st_date   = strtotime($st_date);
        $ed_date   = strtotime($ed_date);
        $dates = range($st_date, $ed_date,86400);
        $wrapusData=array();
        $graphData=array();
        $totalData=array();
        $totalDataDaily=array();
        $wrapupCat = $db->selectAll($general->table(34),'where isActive=1');
        $general->arrayIndexChange($wrapupCat,'wcID');
        $rp=array();
        foreach($wrapupCat as $wc){
            $wr=$db->selectAll($general->table(11),'where isActive=1 and wcID='.$wc['wcID']);
            if(!empty($wr)){
                foreach($wr as $w){
                    $rp[$wc['wcID']][]=$w['wuID'];
                }
            }
        }
    ?>

    <div class="x_content">
        <div class="col-md-8 col-sm-8 col-xs-12">
            <a class="btn btn-default" id="exportCommentWrapup">Export</a>
            <div class="x_content">
                <div id="wrapupStateComment" style="height:250px;"></div>
            </div>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <table class="table table-striped users_view_table">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Category Title</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $jArray=array();
                        foreach($dates as $from){
                            //        echo $general->make_date($from,'time');echo ' '.$from. '<br>'; 
                            $to=strtotime('+1 day',$from);
                            foreach($rp as $k=> $v){
                                $com    =$db->selectAll($general->table(13),' WHERE wuID in ('.implode(',',$v).') and sender_id='.PAGE_ID.' and replyed =1 and replyTime between '.$from. ' AND '.$to,'count(replyTime) as total');
                                $d=0;
                                if(!empty($com)){$d=intval($com[0]['total']);}
                                $wrapusData[date('d M',$from).' - '.date('d M',$to)][$wrapupCat[$k]['wcTitle']]=$d;
                                $totalDataDaily[date('d M',$from).' - '.date('d M',$to)][$wrapupCat[$k]['wcTitle']]=$d;
                                $jArray['serises'][$wrapupCat[$k]['wcTitle']][] =$d;
                            }
                            $jArray['xData'][] = date('d M',$from).' - '.date('d M',$to);

                        }
                        $graphData['wrapupStateComment']=array(
                            'title'=>'Comments',
                            'data'=>$wrapusData
                        );
                        $wrapusData=array();

                        $sn=1;
                        //$rpArray=array();
                        foreach($rp as $k=>$v){
                            $reply = $db->selectAll($general->table(13),"where wuID in (".implode(',',$v).") and sender_id=".PAGE_ID." and replyed =1 and replyTime BETWEEN ".$st_date." AND ".$ed_date,'count(wuID) as total');
                            $total_wrapup = intval($reply[0]['total']);
                            //                        $rpArray[$wrap['wuTitle'].' ('.$total_wrapup.')']=$total_wrapup;
                            $totalData[$wrapupCat[$k]['wcTitle']]=$total_wrapup;
                        ?>
                        <tr>
                            <td><?php echo $sn++; ?></td>
                            <td><a href="<?php echo $pUrl;?>&cat=<?php echo $k;?>"><?php echo $wrapupCat[$k]['wcTitle']?></a></td>
                            <td><?php echo $total_wrapup; ?></td>
                        </tr>
                        <?php
                        } 
                    ?>
                </tbody>
            </table>
        </div>

        <div class="col-md-8 col-sm-8 col-xs-12">
            <a class="btn btn-default" id="exportWallWrapup">Export</a>
            <div class="x_content">
                <div id="wrapupStateWall" style="height:250px;"></div>
            </div>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <table class="table table-striped users_view_table">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Category Title</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $jArray2 = array();
                        foreach($dates as $from){
                            //        echo $general->make_date($from,'time');echo ' '.$from. '<br>'; 
                            $to=strtotime('+1 day',$from);
                            foreach($rp as $k=>$v){
                                $com    =$db->selectAll($general->table(12),' WHERE wuID in ('.implode(',',$v).') and replyed =1 and replyTime between '.$from. ' AND '.$to,'count(replyTime) as total');
                                $d=0;
                                if(!empty($com)){$d=intval($com[0]['total']);}
                                $wrapusData[date('d M',$from).' - '.date('d M',$to)][$wrapupCat[$k]['wcTitle']]=$d;
                                $totalDataDaily[date('d M',$from).' - '.date('d M',$to)][$wrapupCat[$k]['wcTitle']]+=$d;
                                $jArray2['serises'][$wrapupCat[$k]['wcTitle']][] =$d;
                            }
                            $jArray2['xData'][] = date('d M',$from).' - '.date('d M',$to);
                        }
                        $graphData['wrapupStateWall']=array(
                            'title'=>'Wall Post',
                            'data'=>$wrapusData
                        );
                        $wrapusData=array();

                        $sn=1;
                        //$rpArray=array();
                        foreach($rp as $k=>$v){
                            $reply = $db->selectAll($general->table(12),"where wuID in (".implode(',',$v).") and replyed =1 and replyTime BETWEEN ".$st_date." AND ".$ed_date,'count(wuID) as total');
                            $total_wrapup = intval($reply[0]['total']);
                            //$rpArray[$wrap['wuTitle'].' ('.$total_wrapup.')']=$total_wrapup;
                            $totalData[$wrapupCat[$k]['wcTitle']]+=$total_wrapup;
                        ?>
                        <tr>
                            <td><?php echo $sn++; ?></td>
                            <td><a href="<?php echo $pUrl;?>&cat=<?php echo $k;?>"><?php echo $wrapupCat[$k]['wcTitle']?></a></td>
                            <td><?php echo $total_wrapup; ?></td>
                        </tr>
                        <?php
                        } 
                    ?>
                </tbody>
            </table>
        </div>

        <div class="col-md-8 col-sm-8 col-xs-12">
            <a class="btn btn-default" id="exportMessageWrapup">Export</a>
            <div class="x_content">
                <div id="wrapupStateMessage" style="height:250px;"></div>
            </div>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <table class="table table-striped users_view_table">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Category Title</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $jArray3 = array();
                        foreach($dates as $from){
                            //        echo $general->make_date($from,'time');echo ' '.$from. '<br>'; 
                            $to=strtotime('+1 day',$from);
                            foreach($rp as $k=>$v){
                                $com    =$db->selectAll($general->table(9),' WHERE wuID in ('.implode(',',$v).') and replyed =1 and replyTime between '.$from. ' AND '.$to,'count(replyTime) as total');
                                $d=0;
                                if(!empty($com)){$d=intval($com[0]['total']);}
                                $wrapusData[date('d M',$from).' - '.date('d M',$to)][$wrapupCat[$k]['wcTitle']]=$d;
                                $totalDataDaily[date('d M',$from).' - '.date('d M',$to)][$wrapupCat[$k]['wcTitle']]+=$d;
                                $jArray3['serises'][$wrapupCat[$k]['wcTitle']][] =$d;
                            }
                            $jArray3['xData'][] = date('d M',$from).' - '.date('d M',$to);
                        }
                        $graphData['wrapupStateMessage']=array(
                            'title'=>'Messages',
                            'data'=>$wrapusData
                        );

                        $sn=1;
                        foreach($rp as $k=>$v){
                            $reply = $db->selectAll($general->table(9),"where wuID in (".implode(',',$v).") and replyed =1 and replyTime BETWEEN ".$st_date." AND ".$ed_date,'count(wuID) as total');
                            $total_wrapup = intval($reply[0]['total']);
                            //$rpArray[$wrap['wuTitle'].' ('.$total_wrapup.')']=$total_wrapup;
                            $totalData[$wrapupCat[$k]['wcTitle']]+=$total_wrapup;
                        ?>
                        <tr>
                            <td><?php echo $sn++; ?></td>
                            <td><a href="<?php echo $pUrl;?>&cat=<?php echo $k;?>"><?php echo $wrapupCat[$k]['wcTitle']?></a></td>
                            <td><?php echo $total_wrapup; ?></td>
                        </tr>
                        <?php
                        } 
                    ?>
                </tbody>
            </table>
        </div>

        <div class="col-md-8 col-sm-8 col-xs-12">
            <a class="btn btn-default" id="exportAllWrapup">Export</a>
            <div class="x_content">
                <div id="wrapupStateAll" style="height:250px;"></div>
            </div>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <table class="table table-striped users_view_table">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Category Title</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $graphData['wrapupStateAll']=array(
                            'title'=>'All',
                            'data'=>$totalDataDaily
                        );

                        $sn=1;
                        //$rpArray=array();
                        foreach($totalData as $k=>$v){
                        ?>
                        <tr>
                            <td><?php echo $sn++; ?></td>
                            <td><a href="<?php echo $pUrl;?>&cat=<?php echo $k;?>"><?php echo $wrapupCat[$k]['wcTitle']?></a></td>
                            <td><?php echo $v; ?></td>
                        </tr>
                        <?php
                        } 
                        $jArray4 = array();
                        foreach($jArray['serises'] as $k=>$s){
                            $jArray4['xData'] = $jArray['xData'];
                            $jArray4['serises'][$k] = $jArray['serises'][$k]+$jArray2['serises'][$k]+$jArray3['serises'][$k];
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php

        $general->makeGraph($graphData);
    ?>

    <script type="">
            <?php echo "var eIndex = ".json_encode($jArray).";";?>
            <?php echo "var eIndex2 = ".json_encode($jArray2).";";?>
            <?php echo "var eIndex3 = ".json_encode($jArray3).";";?>
            <?php echo "var eIndex4 = ".json_encode($jArray4).";";?>
        $("#exportCommentWrapup").click(function(){
            reportExportToExcel(eIndex); 
        });
        $("#exportWallWrapup").click(function(){
            reportExportToExcel(eIndex2); 
        });
        $("#exportMessageWrapup").click(function(){
            reportExportToExcel(eIndex3); 
        });
        $("#exportAllWrapup").click(function(){
            reportExportToExcel(eIndex4); 
        });

    </script>
    <?php
    }
    else{
        include("report_header.php");
        $st_date   = strtotime($st_date);
        $ed_date     = strtotime($ed_date);
        $dates = range($st_date, $ed_date,86400);
        $wrapusData=array();
        $graphData=array();
        $totalData=array();
        $totalDataDaily=array();
        $wrapup = $db->selectAll($general->table(11),'where isActive=1 and wcID='.$wcID);
    ?>
    <a href="<?php echo $pUrl;?>&cat=0">Categorys</a>
    <div class="x_content">
        <div class="col-md-8 col-sm-8 col-xs-12">
            <a class="btn btn-default" id="exportCommentWrapup">Export</a>
            <div class="x_content">
                <div id="wrapupStateComment" style="height:250px;"></div>
            </div>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <table class="table table-striped users_view_table">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Wrapup Title</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $jArray=array();
                        foreach($dates as $from){

                            //        echo $general->make_date($from,'time');echo ' '.$from. '<br>'; 
                            $to=strtotime('+1 day',$from);
                            foreach($wrapup as $wrap){
                                $com    =$db->selectAll($general->table(13),' WHERE wuID='.$wrap['wuID'].' and sender_id='.PAGE_ID.' and replyed =1 and replyTime between '.$from. ' AND '.$to,'count(replyTime) as total');
                                $d=0;
                                if(!empty($com)){$d=intval($com[0]['total']);}
                                $wrapusData[date('d M',$from).' - '.date('d M',$to)][$wrap['wuTitle']]=$d;
                                $totalDataDaily[date('d M',$from).' - '.date('d M',$to)][$wrap['wuTitle']]=$d;
                                $jArray['serises'][$wrap['wuTitle']][] =$d;
                            }
                            $jArray['xData'][] = date('d M',$from).' - '.date('d M',$to);

                        }
                        $graphData['wrapupStateComment']=array(
                            'title'=>'Comments',
                            'data'=>$wrapusData
                        );
                        $wrapusData=array();

                        $sn=1;
                        //$rpArray=array();
                        foreach($wrapup as $wrap){
                            $reply = $db->selectAll($general->table(13),"where wuID=".$wrap['wuID']." and sender_id=".PAGE_ID." and replyed =1 and replyTime BETWEEN ".$st_date." AND ".$ed_date,'count(wuID) as total');
                            $total_wrapup = intval($reply[0]['total']);
                            //                        $rpArray[$wrap['wuTitle'].' ('.$total_wrapup.')']=$total_wrapup;
                            $totalData[$wrap['wuTitle']]=$total_wrapup;
                        ?>
                        <tr>
                            <td><?php echo $sn++; ?></td>
                            <td><?php echo $wrap['wuTitle']?></td>
                            <td><?php echo $total_wrapup; ?></td>
                        </tr>
                        <?php
                        } 
                    ?>
                </tbody>
            </table>
        </div>

        <div class="col-md-8 col-sm-8 col-xs-12">
            <a class="btn btn-default" id="exportWallWrapup">Export</a>
            <div class="x_content">
                <div id="wrapupStateWall" style="height:250px;"></div>
            </div>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <table class="table table-striped users_view_table">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Wrapup Title</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $jArray2 = array();
                        foreach($dates as $from){
                            //        echo $general->make_date($from,'time');echo ' '.$from. '<br>'; 
                            $to=strtotime('+1 day',$from);
                            foreach($wrapup as $wrap){
                                $com    =$db->selectAll($general->table(12),' WHERE wuID='.$wrap['wuID'].' and replyed =1 and replyTime between '.$from. ' AND '.$to,'count(replyTime) as total');
                                $d=0;
                                if(!empty($com)){$d=intval($com[0]['total']);}
                                $wrapusData[date('d M',$from).' - '.date('d M',$to)][$wrap['wuTitle']]=$d;
                                $totalDataDaily[date('d M',$from).' - '.date('d M',$to)][$wrap['wuTitle']]+=$d;
                                $jArray2['serises'][$wrap['wuTitle']][] =$d;
                            }
                            $jArray2['xData'][] = date('d M',$from).' - '.date('d M',$to);
                        }
                        $graphData['wrapupStateWall']=array(
                            'title'=>'Wall Post',
                            'data'=>$wrapusData
                        );
                        $wrapusData=array();

                        $sn=1;
                        //$rpArray=array();
                        foreach($wrapup as $wrap){
                            $reply = $db->selectAll($general->table(12),"where wuID=".$wrap['wuID']." and replyed =1 and replyTime BETWEEN ".$st_date." AND ".$ed_date,'count(wuID) as total');
                            $total_wrapup = intval($reply[0]['total']);
                            //$rpArray[$wrap['wuTitle'].' ('.$total_wrapup.')']=$total_wrapup;
                            $totalData[$wrap['wuTitle']]+=$total_wrapup;
                        ?>
                        <tr>
                            <td><?php echo $sn++; ?></td>
                            <td><?php echo $wrap['wuTitle']?></td>
                            <td><?php echo $total_wrapup; ?></td>
                        </tr>
                        <?php
                        } 
                    ?>
                </tbody>
            </table>
        </div>

        <div class="col-md-8 col-sm-8 col-xs-12">
            <a class="btn btn-default" id="exportMessageWrapup">Export</a>
            <div class="x_content">
                <div id="wrapupStateMessage" style="height:250px;"></div>
            </div>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <table class="table table-striped users_view_table">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Wrapup Title</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $jArray3 = array();
                        foreach($dates as $from){
                            //        echo $general->make_date($from,'time');echo ' '.$from. '<br>'; 
                            $to=strtotime('+1 day',$from);
                            foreach($wrapup as $wrap){
                                $com    =$db->selectAll($general->table(9),' WHERE wuID='.$wrap['wuID'].' and replyed =1 and replyTime between '.$from. ' AND '.$to,'count(replyTime) as total');
                                $d=0;
                                if(!empty($com)){$d=intval($com[0]['total']);}
                                $wrapusData[date('d M',$from).' - '.date('d M',$to)][$wrap['wuTitle']]=$d;
                                $totalDataDaily[date('d M',$from).' - '.date('d M',$to)][$wrap['wuTitle']]+=$d;
                                $jArray3['serises'][$wrap['wuTitle']][] =$d;
                            }
                            $jArray3['xData'][] = date('d M',$from).' - '.date('d M',$to);
                        }
                        $graphData['wrapupStateMessage']=array(
                            'title'=>'Messages',
                            'data'=>$wrapusData
                        );

                        $sn=1;
                        foreach($wrapup as $wrap){
                            $reply = $db->selectAll($general->table(9),"where wuID=".$wrap['wuID']." and replyed =1 and replyTime BETWEEN ".$st_date." AND ".$ed_date,'count(wuID) as total');
                            $total_wrapup = intval($reply[0]['total']);
                            //$rpArray[$wrap['wuTitle'].' ('.$total_wrapup.')']=$total_wrapup;
                            $totalData[$wrap['wuTitle']]+=$total_wrapup;
                        ?>
                        <tr>
                            <td><?php echo $sn++; ?></td>
                            <td><?php echo $wrap['wuTitle']?></td>
                            <td><?php echo $total_wrapup; ?></td>
                        </tr>
                        <?php
                        } 
                    ?>
                </tbody>
            </table>
        </div>

        <div class="col-md-8 col-sm-8 col-xs-12">
            <a class="btn btn-default" id="exportAllWrapup">Export</a>
            <div class="x_content">
                <div id="wrapupStateAll" style="height:250px;"></div>
            </div>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <table class="table table-striped users_view_table">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Wrapup Title</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $graphData['wrapupStateAll']=array(
                            'title'=>'All',
                            'data'=>$totalDataDaily
                        );

                        $sn=1;
                        //$rpArray=array();
                        foreach($totalData as $k=>$v){
                        ?>
                        <tr>
                            <td><?php echo $sn++; ?></td>
                            <td><?php echo $k?></td>
                            <td><?php echo $v; ?></td>
                        </tr>
                        <?php
                        } 
                        $jArray4 = array();
                        foreach($jArray['serises'] as $k=>$s){
                            $jArray4['xData'] = $jArray['xData'];
                            $jArray4['serises'][$k] = $jArray['serises'][$k]+$jArray2['serises'][$k]+$jArray3['serises'][$k];
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php

        $general->makeGraph($graphData);
    ?>

    <script type="">
        var eIndex = <?php echo json_encode($jArray);?>;
        var eIndex2 = <?php echo json_encode($jArray2);?>;
        var eIndex3 = <?php echo json_encode($jArray3);?>;
        var eIndex4 = <?php echo json_encode($jArray4);?>;
        $("#exportCommentWrapup").click(function(){
            reportExportToExcel(eIndex); 
        });
        $("#exportWallWrapup").click(function(){
            reportExportToExcel(eIndex2); 
        });
        $("#exportMessageWrapup").click(function(){
            reportExportToExcel(eIndex3); 
        });
        $("#exportAllWrapup").click(function(){
            reportExportToExcel(eIndex4); 
        });

    </script>
    <?php
}