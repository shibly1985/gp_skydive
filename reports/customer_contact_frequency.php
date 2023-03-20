
<?php
    include("report_header.php");
    //$dts= strtotime($ed_date)-strtotime($st_date);
    if(strtotime($ed_date)==strtotime($st_date)){
        /*$ed_date =$general->make_future_timestamp(1,strtotime($ed_date));
        $dates = range(strtotime($st_date),$ed_date,3600); 
        function toTime($x){return date('h-i-s', $x);}
        $range_of_dates = array_map("toTime", $dates);
        var_dump($dates);*/
    }
    else{
        $dates = range(strtotime($st_date), strtotime($ed_date),86400);
        function toDate($x){return date('d-m-Y', $x);}
        $range_of_dates = array_map("toDate", $dates);
    }
    $i=0;
    $graphData=array();


    $wrapusData=array();

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
            <div class="col-md-4 col-sm-4 col-xs-12">
                <table class="table table-striped">
                    <?php
                    $jArray = array();
                    $total_c=0;
                    $total_r=0;
                        if(strtotime($ed_date)==strtotime($st_date)){
                        ?>
                        <tr>
                            <th>Date/Time</th>
                            <th>Comment</th>
                            <th>Reply</th>
                        </tr>
                        <?php
                            $oneHour=3600;
                            for($i=0;$i<=23;$i++){
                                $from= strtotime($st_date)+($oneHour*$i);
                                $to = $from+$oneHour;

                            ?>
                            <tr>
                                <td><?php echo $general->make_date($from,'tam').' - '.$general->make_date($to,'tam');?></td>
                                <td>
                                    <?php
                                         $comment = $db->selectAll($general->table(13),"where sender_id!=".PAGE_ID." and created_time BETWEEN ".$from." AND ".$to,'count(comment_id) as total');
                                        echo $tc= intval($comment[0]['total']);
                                    ?>
                                </td>
                                <td>
                                    <?php
                                         $reply = $db->selectAll($general->table(13),"where sender_id=".PAGE_ID." and created_time BETWEEN ".$from." AND ".$to,'replyTime,target_c_id');
                                        $dcom = $db->selectAll($general->table(15),' WHERE  remove_time between '.$from. ' AND '.$to,' count(remove_time) as total');
                                        $hcom = $db->selectAll($general->table(31),' WHERE  hideTime between '.$from. ' AND '.$to,' count(hideTime) as total');
                                        $docom    =$db->selectAll($general->table(13),' WHERE isDone=1 and created_time between '.$from. ' AND '.$to,' count(created_time) as total');
                                        echo $tr= count($reply)+$dcom[0]['total']+$hcom[0]['total']+$docom[0]['total'];
                                        $wrapusData[$general->make_date($from,'tam')]['Comment']=$tc;
                                        $wrapusData[$general->make_date($from,'tam')]['Reply']=$tr;
                                        $total_c = $total_c+$tc;
                                        $total_r = $total_r+$tr;
                                        $jArray['serises']['Comment'] = $tc;
                                    ?>
                                </td>
                            </tr>
                            <?php
                            }
                            $jArray['xData']=$general->make_date($from,'tam');
                        }
                        else{
                            $i=0;
                        ?>
                        <tr>
                            <th>SN</th>
                            <th>Date/Time</th>
                            <th>Comment</th>
                            <th>Reply</th>
                        </tr>
                        <?php
                            foreach($range_of_dates as $rd){                
                                $from=strtotime($range_of_dates[$i]);
                                $to =strtotime('+1 day', $from);
                            ?>
                            <tr>
                                <td><?php echo ++$i;?></td>
                                <td><?php echo $rd;?></td>
                                <td>
                                    <?php
                                         $comment = $db->selectAll($general->table(13),"where sender_id!=".PAGE_ID." and created_time BETWEEN ".$from." AND ".$to,'count(comment_id) as total');

                                        echo $tc= intval($comment[0]['total']);
                                    ?>
                                </td>
                                <td>
                                    <?php
                                         $reply = $db->selectAll($general->table(13),"where sender_id=".PAGE_ID." and created_time BETWEEN ".$from." AND ".$to,'replyTime,target_c_id');
                                         $dcom = $db->selectAll($general->table(15),' WHERE  remove_time between '.$from. ' AND '.$to,' count(remove_time) as total');
                                        $hcom = $db->selectAll($general->table(31),' WHERE  hideTime between '.$from. ' AND '.$to,' count(hideTime) as total');
                                        $docom    =$db->selectAll($general->table(13),' WHERE isDone=1 and created_time between '.$from. ' AND '.$to,' count(created_time) as total');
                                        echo $tr= count($reply)+$dcom[0]['total']+$hcom[0]['total']+$docom[0]['total'];
                                        $total_c = $total_c+$tc;
                                        $total_r = $total_r+$tr;
                                        //echo $tr= count($reply);
                                        $wrapusData[$general->make_date($from)]['Comment']=$tc;
                                        $wrapusData[$general->make_date($from)]['Reply']=$tr;
                                    ?>
                                </td>
                            </tr>
                            <?php
                            }
                        }
                    ?>
                    <tr>
                   
                        <td <?php if(strtotime($ed_date)==strtotime($st_date)){ }else {echo 'colspan="2"';}?>>Total</td>
                        <td><?php echo $total_c; ?></td>
                        <td><?php echo $total_r; ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-8 col-sm-8 col-xs-12">
                <div id="responseContactFrequency_c" style="height:250px;"></div>
            </div>
        </div>
    </div>
</div>
<?php
    $graphData['responseContactFrequency_c']=array(
        'title'=>'',
        'data'=>$wrapusData
    );
//var_dump($jArray);

    $wrapusData=array();

?>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Wall Post</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="col-md-4 col-sm-4 col-xs-12">
                <table class="table table-striped">
                    <?php
                        $total_c = 0;
                        $total_r = 0;
                        if(strtotime($ed_date)==strtotime($st_date)){
                            $oneHour=3600;
                        ?>
                        <tr>
                            <th>Date/Time</th>
                            <th>Comment</th>
                            <th>Reply</th>
                        </tr>
                        <?php
                            for($i=0;$i<=23;$i++){
                                $from= strtotime($st_date)+($oneHour*$i);
                                $to = $from+$oneHour;

                            ?>
                            <tr>
                                <td><?php echo $general->make_date($from,'tam').' - '.$general->make_date($to,'tam');?></td>
                                <td>
                                    <?php
                                        $com    =$db->selectAll($general->table(12),' WHERE  created_time between '.$from. ' AND '.$to,' count(created_time) as total');
                                        $comment = $db->selectAll($general->table(14),'where created_time between '.$from.' and '.$to,' count(created_time) as total');
                                        echo $tc=$com[0]['total']+$comment[0]['total'];
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        $reply = $db->selectAll($general->table(14),'where replyTime between '.$from.' and '.$to);
                                        $docom    =$db->selectAll($general->table(14),' WHERE isDone=1 and  created_time between '.$from. ' AND '.$to,' count(created_time) as total');
                                        echo $tr=count($reply)+$docom[0]['total'];
                                        $wrapusData[$general->make_date($from,'tam')]['Comment']=$tc;
                                        $wrapusData[$general->make_date($from,'tam')]['Reply']=$tr;
                                        $total_c = $total_c+$tc;
                                        $total_r = $total_r+$tr;
                                    ?>
                                </td>
                            </tr>
                            <?php
                            }
                        }
                        else{
                            $i=0;
                        ?>
                        <tr>
                            <th>SN</th>
                            <th>Date/Time</th>
                            <th>Comment</th>
                            <th>Reply</th>
                        </tr>
                        <?php
                            foreach($range_of_dates as $rd){                
                                $from=strtotime($range_of_dates[$i]);
                                $to =strtotime('+1 day', $from);
                            ?>
                            <tr>
                                <td><?php echo ++$i;?></td>
                                <td><?php echo $rd;?></td>
                                <td>
                                    <?php
                                        $comment = $db->selectAll($general->table(14),'where parent_id=post_id and created_time between '.$from.' and '.$to);
                                        echo $tc= count($comment);
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        $reply = $db->selectAll($general->table(14),"where  sender_id='".PAGE_ID."' and created_time between ".$from.' and '.$to);
                                        $docom    =$db->selectAll($general->table(14),' WHERE isDone=1 and  created_time between '.$from. ' AND '.$to,' count(created_time) as total');
                                        echo $tr=count($reply)+$docom[0]['total'];
                                        $wrapusData[$general->make_date($from)]['Comment']=$tc;
                                        $wrapusData[$general->make_date($from)]['Reply']=$tr;
                                        $total_c = $total_c+$tc;
                                        $total_r = $total_r+$tr;
                                    ?>
                                </td>
                            </tr>
                            <?php
                            }
                        }
                    ?>
                    <tr>
                        <td>Total</td>
                        <td><?php echo $total_c; ?></td>
                        <td><?php echo $total_r; ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-8 col-sm-8 col-xs-12">
                <div id="responseContactFrequency_w" style="height:250px;"></div>
            </div>
        </div>
    </div>
</div>
<?php
    $graphData['responseContactFrequency_w']=array(
        'title'=>'',
        'data'=>$wrapusData
    );



    $general->makeGraph($graphData);
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
            <div class="col-md-4 col-sm-4 col-xs-12">
                <table class="table table-striped">
                    <?php
                        if(strtotime($ed_date)==strtotime($st_date)){
                            $oneHour=3600;
                        ?>
                        <tr>
                            <th>Date/Time</th>
                            <th>Comment</th>
                            <th>Reply</th>
                        </tr>
                        <?php
                            for($i=1;$i<=24;$i++){
                                $from= strtotime($st_date)+($oneHour*$i);
                                $to = $from+$oneHour;

                            ?>
                            <tr>
                                <td><?php echo $general->make_date($from,'tam').' - '.$general->make_date($to,'tam');?></td>
                                <td>
                                    <?php
                                        $comment = $db->selectAll($general->table(14),'where parent_id=post_id and created_time between '.$from.' and '.$to);
                                        echo $tc=count($comment);
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        $reply = $db->selectAll($general->table(14),'where replyTime between '.$from.' and '.$to);
                                        echo $tr=count($reply);
                                        $wrapusData[$general->make_date($from,'tam')]['Comment']=$tc;
                                        $wrapusData[$general->make_date($from,'tam')]['Reply']=$tr;
                                    ?>
                                </td>
                            </tr>
                            <?php
                            }
                        }
                        else{
                            $i=0;
                        ?>
                        <tr>
                            <th>SN</th>
                            <th>Date/Time</th>
                            <th>Message</th>
                            <th>Reply</th>
                        </tr>
                        <?php
                            foreach($range_of_dates as $rd){                
                                $from=strtotime($range_of_dates[$i]);
                                $to =strtotime('+1 day', $from);
                            ?>
                            <tr>
                                <td><?php echo ++$i;?></td>
                                <td><?php echo $rd;?></td>
                                <td>
                                    <?php
                                        $comment = $db->selectAll($general->table(9),'where sendTime between '.$from.' and '.$to);
                                        echo $tm= count($comment);
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        $reply = $db->selectAll($general->table(9),"where  replyed=1 and replyTime between ".$from.' and '.$to);
                                        echo $tr= count($reply);
                                        $wrapusData='';
                                        $wrapusData[$general->make_date($from)]['Message']=$tm;
                                        $wrapusData[$general->make_date($from)]['Reply']=$tr;
                                    ?>
                                </td>
                            </tr>
                            <?php
                            }
                        }
                    ?>
                </table>
            </div>
            <div class="col-md-8 col-sm-8 col-xs-12">
                <div id="responseContactFrequency_m" style="height:250px;"></div>
            </div>
        </div>
    </div>
</div>
<?php
    $graphData['responseContactFrequency_m']=array(
        'title'=>'',
        'data'=>$wrapusData
    );



    $general->makeGraph($graphData);
?>