<?php
for($x = 5; 1 <= $x; $x--){
                $from=strtotime("-$x minutes");
                $t=$x-1;
                $to =strtotime("-$t minutes");
              //  echo $general->make_date($from,'time').'====to===='.$general->make_date($to,'time');
                $total_comment = $db->selectAll($general->table(14),"where created_time BETWEEN ".$from." AND ".$to,'count(comment_id) as total');
                $total_comment = intval($total_comment[0]['total']);
                $total_reply = $db->selectAll($general->table(14),"where replyed=1 and replyTime BETWEEN ".$from." AND ".$to,'replyTime,assignTime');

                 foreach($wrapup as $wrap){
                $reply = $db->selectAll($general->table(14),"where wuID=".$wrap['wuID']." and replyed =1 and replyTime BETWEEN ".$from." AND ".$to);
                $wrapusData[$range_of_dates[$x]][$wrap['wuID']]=count($reply);
                }
                foreach($scentiment as $sc){
                $reply = $db->selectAll($general->table(14),"where scentiment=".$sc." and replyed =1 and replyTime BETWEEN ".$from." AND ".$to);
                $scentimentData[$range_of_dates[$x]][$sc]=count($reply);
                }

                $totalReplyCount = count($total_reply);
                if($totalReplyCount>1){
                    $response_time = array();
                    $totalTime = 0;
                    foreach($total_reply as $tr){
                        $rTime = $tr['replyTime']-$tr['assignTime'];
                        $response_time[] =  $rTime;
                        $totalTime+= $rTime;
                    }  
                    if($totalReplyCount<1){ $totalReplyCount=1; }
                    $totalTime = $totalTime/$totalReplyCount;
                    $totalPureTime = gmdate("i.s", $totalTime);
                }
                else{
                    $totalPureTime=0; 
                }
                //$dates[$i]=$range_of_dates[$i];
                $comments[$i]=intval($total_comment);
                $replys[$i]=intval($totalReplyCount);
                $avTimes[$i]=floatval($totalPureTime);
                $i++;
            }
            //$general->printArray($wrapusData);
        ?>
        <div class="row">
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
                    <a class="btn btn-default" id="exportResponseFive">Export</a>
                        <div id="responseState" style="height:350px;"></div>
                        <!--                            <canvas id="mybarChart"></canvas>-->
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Wrapup <small>Stats</small></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                     <a class="btn btn-default" id="exportWrapupFive">Export</a>
                        <!--<div id="responseDailyState" style="height:350px;"></div>-->
                        <canvas id="responseDailyState"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
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
                    <a class="btn btn-default" id="exportSentimentFive">Export</a>
                        <!--<div id="responseDailyState" style="height:350px;"></div>-->
                        <canvas id="responseDailyStateScen"></canvas>
                    </div>
                </div>
            </div>
        </div>