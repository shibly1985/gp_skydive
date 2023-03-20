<script src="vendors/echarts/dist/echarts.min.js"></script>
<script type="text/javascript">
    var cb;
    var start;
    var end;
    $(function() {
        start =moment().subtract(1, 'days');
        end = moment();
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        cb=function (start, end,label) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            $('.graphRequireDiv').show();
            dashboardGraphSet(start.format('DD-MM-YYYY'),end.format('DD-MM-YYYY'));

        }

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
            }, cb);
        //cb(start, end);
    });
    function showDashboardGraph(){
        $(document).ready(function(){
            $('.graphRequireDiv').show();
            cb(start, end);    
        });

    }
</script>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2 class="dashboartGraphTitle"><a href="javascript:void();" onclick="showDashboardGraph()">Show Graph</a></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <div class="dropdown">
                        <button class="btn btn-default dropdown-toggle" type="button" id="exportMenu" data-toggle="dropdown">
                            Export
                        </button>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="exportMenu" id="exportExcel"></ul>
                        <a href="" style="display: none;" target="blank" id="exportFileDownload">Download</a>
                    </div>

                </li>
                <li>
                    <div id="reportrange" class="pull-right sky_datepicker">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                        <span></span> <b class="caret"></b>
                    </div>
                </li>
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content graphRequireDiv" style="display: none;">
            <div id="status" style="height:350px;"></div>
            <div id="wall" style="height:350px;"></div>
            <?php
                if(OPERATION_MESSAGE_ALLWO==true){
                ?>
                <div id="msg" style="height:350px;"></div>
                <?php
                }
            ?>
        </div>
        <div class="x_title graphRequireDiv" style="display: none;">
            <h2>Summary <span class="dashboartGraphTitle"></span></h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content graphRequireDiv" style="display: none;">

            <?php
                if(OPERATION_MESSAGE_ALLWO==true){
                ?>
                <div class="row tile_count">
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top">
                            <i class="fa fa-reply" aria-hidden="true"></i>
                            Message IN
                        </span>
                        <div class="count" id="messageIn"></div>
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top">
                            <i class="fa fa-reply" aria-hidden="true"></i>
                            Message OUT
                        </span>
                        <div class="count" id="messageOut"></div>
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top">AHT</span>
                        <div class="count" id="messageAHT"></div>

                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top">ART</span>
                        <div class="count"  id="messageART"></div>
                    </div>
                </div>
                <div class="row tile_count">
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top">Message Unique Visitor</span>
                        <div class="count" id="messageVisitor"></div>
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top">Message Unique Reply</span>
                        <div class="count" id="messageReply"></div>
                    </div>
                </div>
                <?php
                }
            ?>
            <div class="row tile_count">
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top">
                        <i class="fa fa-comments-o" aria-hidden="true"></i>
                        Admin Post
                    </span>
                    <div class="count" id="adminPost"></div>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top">
                        <i class="fa fa-reply" aria-hidden="true"></i>
                        User Activity
                    </span>
                    <div class="count" id="userActivity"></div>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top">
                        <i class="fa fa-reply" aria-hidden="true"></i>
                        Admin Activity
                    </span>
                    <div class="count" id="adminActivity"></div>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top">
                        <i class="fa fa-reply" aria-hidden="true"></i>
                        AHT
                    </span>
                    <div class="count" id="commentHandleTime"></div>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top">
                        <i class="fa fa-reply" aria-hidden="true"></i>
                        ART
                    </span>
                    <div class="count" id="commentResponseTime"></div>
                </div>
            </div> 
            <div class="row tile_count">
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top">
                        <i class="fa fa-reply" aria-hidden="true"></i>
                        Wall Post
                    </span>
                    <div class="count" id="wallPost"></div>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top">
                        <i class="fa fa-reply" aria-hidden="true"></i>
                        Admin Activity
                    </span>

                    <div class="count" id="wallAdminActivity"></div>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top">
                        <i class="fa fa-reply" aria-hidden="true"></i>
                        AHT
                    </span>
                    <div class="count" id="commentHandleTimeWall"></div>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top">
                        <i class="fa fa-reply" aria-hidden="true"></i>
                        ART
                    </span>
                    <div class="count" id="commentResponseTimeWall"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    if(isset($_GET['flush'])){
        $dd=$db->fetchQuery("select * from ".$general->table(52).' where drType in(1,2)');  
        foreach($dd as $d){
            $data = array(
                'drLastUpdate' => 0
            );
            $where = array(
                'drID'  => $d['drID']
            );
            $update = $db->update($general->table(52),$data,$where);
        } 
    }
    //UPDATE `dashboard_report` SET `drLastUpdate`=1 WHERE  `drID`=1;
    $dd=$db->fetchQuery("select * from ".$general->table(52).' where drType in(1,2)');
    $cap=0;$caa=0;$cua=0;$caht=0;$cart=0;$wap=0;$waa=0;$waht=0;$wart=0;$mi=0;$mo=0;
    $mcap=0;$mcaa=0;$mcua=0;$mcaht=0;$mcart=0;$mwap=0;$mwaa=0;$mwaht=0;$mwart=0;$mmi=0;$mmo=0;
    $dLastUp=TIME;
    $mLastUp=TIME;
    foreach($dd as $a){
        if($a['drType']==1){//1=daily

            //if(date('H',$a['drLastUpdate'])<date('H',TIME)){
            if(strtotime('+ 1 hour',$a['drLastUpdate']) < TIME||date('H',$a['drLastUpdate'])<date('H')||date('d',$a['drLastUpdate'])<date('d')||date('d',$a['drLastUpdate'])<date('m')){
                $from=strtotime('today midnight');
                $from=TODAY_TIME;
                $to=TOMORROW_TIME;
                $to=strtotime('-1 second',$to);
                $c=$db->selectAll($general->table(4),' WHERE created_time between '.$from. ' AND '.$to,'count(created_time) as t');
                //$general->printArray($c);
                $cap=$c[0]['t'];
                $echo=$general->showQuery();
                $cua=$sReport->commentsUserActivity($from,$to,false,$echo);
                $caa=$sReport->commentsAdminActivity($from,$to,0,'',false,$echo);
                $ah=$sReport->ahtNart($from,$to);
                $caht=$ah['aht'];
                $cart=$ah['art'];
                $wap=$sReport->wallPosts($from,$to,false,$echo);
                $waa=$sReport->wallAdminActivity($from,$to,0,'',false,$echo);
                $ah=$sReport->ahtNartWall($from,$to,0,$echo);
                $waht=$ah['aht'];
                $wart=$ah['art'];
                if(OPERATION_MESSAGE_ALLWO==true){
                    $m=$db->selectAll($general->table(9),' WHERE sendType=1 and sendTime between '.$from. ' AND '.$to,' count(mid) as t',$echo);
                    $mi=$m[0]['t'];
                    $m=$db->selectAll($general->table(9),' WHERE sendType=2 and replyTime between '.$from. ' AND '.$to,' count(mid) as t',$echo);
                    $com    =$db->selectAll($general->table(9),' WHERE isDone=1 and replyTime between '.$from. ' AND '.$to,' count(mid) as t');
                    $d2=0;if(!empty($com)){$d2=intval($com[0]['t']);}

                    $mo=$m[0]['t']+$d2;
                    $ahtNart = $sReport->ahtNartMsg($from,$to,'',$echo);
                    $maht= $ahtNart['aht'];
                    $mart= $ahtNart['art'];
                    $muu=$sReport->messageUniqueSender($from,$to);
                    $mur=$sReport->messageUniqueSenderReply($from,$to);

                }
                else{
                    $mi=0;
                    $mo=0;
                    $maht=0;
                    $mart=0;
                    $muu=0;
                    $mur=0;
                }
                $data=array(
                    'drLastUpdate'  =>TIME,
                    'CAP'           =>$cap,
                    'CUA'           =>$cua,
                    'CAA'           =>$caa,
                    'CAHT'          =>$caht,
                    'CART'          =>$cart,
                    'WP'            =>$wap,
                    'WAA'           =>$waa,
                    'WAHT'          =>$waht,
                    'WART'          =>$wart,
                    'MI'            =>$mi,
                    'MO'            =>$mo,
                    'MAHT'          =>$maht,
                    'MRHT'          =>$mart,
                    'MUU'           =>$muu,
                    'MUR'           =>$mur,
                );
                //$general->printArray($data);echo'<br>';echo $mi;echo'<br>';  
                $where=array('drType'=>1);
                $db->update($general->table(52),$data,$where);
            }
            else{
                $dLastUp=$a['drLastUpdate'];
                $cap=$a['CAP'];
                $caa=$a['CAA'];
                $cua=$a['CUA'];
                $caht=$a['CAHT'];
                $cart=$a['CART'];
                $wap=$a['WP'];
                $waa=$a['WAA'];
                $waht=$a['WAHT'];
                $wart=$a['WART'];
                $mi=$a['MI'];
                $mo=$a['MO'];
                $maht=$a['MAHT'];
                $mart=$a['MART'];
                $muu=$a['MUU'];
                $mur=$a['MUR'];
            }
        }
        else if($a['drType']==2){//2=monthly
            //if(strtotime('+ 1 hour',$a['drLastUpdate']) < TIME||date('H',$a['drLastUpdate'])<date('H')){
            if(strtotime('+ 1 hour',$a['drLastUpdate']) < TIME||date('H',$a['drLastUpdate'])<date('H')||date('d',$a['drLastUpdate'])<date('d')||date('d',$a['drLastUpdate'])<date('m')){
                $from = strtotime(date('01-m-Y',TIME));
                $to   = strtotime(date('t-m-Y',TIME));
                $to   = strtotime('-1 second',$to);
                $c=$db->selectAll($general->table(4),' WHERE created_time between '.$from. ' AND '.$to,'count(created_time) as t');
                //$general->printArray($c);
                $mcap=$c[0]['t'];
                $mcua=$sReport->commentsUserActivity($from,$to,false,$echo);
                $mcaa=$sReport->commentsAdminActivity($from,$to,0,'',false,$echo);
                $ah=$sReport->ahtNart($from,$to);
                $mcaht=$ah['aht'];
                $mcart=$ah['art'];
                $mwap=$sReport->wallPosts($from,$to,false,$echo); 
                $mwaa=$sReport->wallAdminActivity($from,$to,0,'',false,$echo);
                $mah=$sReport->ahtNartWall($from,$to,0,$echo);
                $mwaht=$mah['aht'];
                $mwart=$mah['art'];
                if(OPERATION_MESSAGE_ALLWO){
                    $m=$db->selectAll($general->table(9),' WHERE sendType=1 and sendTime between '.$from. ' AND '.$to,' count(mid) as t');
                    $mmi=$m[0]['t'];
                    $m=$db->selectAll($general->table(9),' WHERE sendType=2 and replyTime between '.$from. ' AND '.$to,' count(mid) as t');
                    $com    =$db->selectAll($general->table(9),' WHERE sendType=1 and isDone=1 and replyTime between '.$from. ' AND '.$to,' count(mid) as t');
                    $d2=0;if(!empty($com)){$d2=intval($com[0]['t']);}

                    $mmo=$m[0]['t']+$d2;
                    $ahtNart = $sReport->ahtNartMsg($from,$to);
                    $mmaht= $ahtNart['aht'];
                    $mmart= $ahtNart['art'];
                    $mmuu=$sReport->messageUniqueSender($from,$to);
                    $mmur=$sReport->messageUniqueSenderReply($from,$to);
                }
                else{
                    $mmi=0;
                    $mmo=0;
                    $mmaht=0;
                    $mmart=0;
                    $mmuu=0;
                    $mmur=0;
                }
                $data=array(
                    'drLastUpdate'  =>TIME,
                    'CAP'           =>$mcap,
                    'CUA'           =>$mcua,
                    'CAA'           =>$mcaa,
                    'CAHT'          =>$mcaht,
                    'CART'          =>$mcart,
                    'WP'            =>$mwap,
                    'WAA'           =>$mwaa,
                    'WAHT'          =>$mwaht,
                    'WART'          =>$mwart,
                    'MI'            =>$mmi,
                    'MO'            =>$mmo,
                    'MAHT'          =>$mmaht,
                    'MART'          =>$mmart,
                    'MUU'           =>$mmuu,
                    'MUR'           =>$mmur,
                );
                $where=array('drType'=>2);
                $db->update($general->table(52),$data,$where);
            }
            else{
                $mLastUp=$a['drLastUpdate'];
                $mcap=$a['CAP'];
                $mcaa=$a['CAA'];
                $mcua=$a['CUA'];
                $mcaht=$a['CAHT'];
                $mcart=$a['CART'];
                $mwap=$a['WP'];
                $mwaa=$a['WAA'];
                $mwaht=$a['WAHT'];
                $mwart=$a['WART'];
                $mmi=$a['MI'];
                $mmo=$a['MO'];
                $mmaht=$a['MAHT'];
                $mmart=$a['MART'];
                $mmuu=$a['MUU'];
                $mmur=$a['MUR'];
            }
        }
    }
?>
<div class="col-md-12">
    <div class="x_panel dashboard">
        <div class="x_title">
            <h2>Today <span>Last Update <?php echo date('d-m-Y h:i A',$dLastUp);?></span></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <?php
                if(OPERATION_MESSAGE_ALLWO==true){
                ?>
                <div class="row tile_count">
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-reply" aria-hidden="true"></i>Message IN</span>
                        <div class="count"><?php echo $mi;?></div>
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-reply" aria-hidden="true"></i>Message OUT</span>
                        <div class="count"><?php echo $mo?></div>
                    </div>

                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top">AHT</span>
                        <div class="count"><?php echo $maht;?></div>

                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top">ART</span>
                        <div class="count"><?php echo $mart;?></div>
                    </div>
                    <div class="col-md-1 col-sm-2 col-xs-6 tile_stats_count">
                        <span class="count_top">Visitor</span>
                        <div class="count"><?php echo $muu;?></div>
                    </div>
                    <div class="col-md-1 col-sm-2 col-xs-6 tile_stats_count">
                        <span class="count_top">Reply</span>
                        <div class="count"><?php echo $mur;?></div>
                    </div>
                </div>
                <?php
                }
            ?>
            <div class="row tile_count">
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-comments-o" aria-hidden="true"></i>Admin Post</span>
                    <div class="count"><?php echo $cap;?></div>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-reply" aria-hidden="true"></i>User Activity</span>
                    <div class="count"><?php echo $cua;?></div>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-reply" aria-hidden="true"></i>Admin Activity</span>
                    <div class="count"><?php echo $caa?></div>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-reply" aria-hidden="true"></i>AHT</span>
                    <div class="count"><?php echo $caht;?></div>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-reply" aria-hidden="true"></i>ART</span>
                    <div class="count"><?php echo $cart;?></div>
                </div>
            </div>
            <div class="row tile_count">
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-reply" aria-hidden="true"></i>Wall Post</span>
                    <div class="count"><?php echo $wap;?></div>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-reply" aria-hidden="true"></i>Admin Activity</span>
                    <div class="count"><?php echo $waa;?></div>

                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-reply" aria-hidden="true"></i>AHT</span>
                    <div class="count"><?php echo $waht;?></div>

                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-reply" aria-hidden="true"></i>ART</span>
                    <div class="count"><?php echo $wart;?></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="x_panel dashboard">
        <div class="x_title">
            <h2>This Month  <span>Last Update <?php echo date('d-m-Y h:i A',$mLastUp);?></span></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <?php
                if(OPERATION_MESSAGE_ALLWO==true){
                ?>
                <div class="row tile_count">
                    <div class="col-md-2 col-sm-3 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-reply" aria-hidden="true"></i>Message IN</span>
                        <div class="count"><?php echo $mmi;?></div>
                    </div>
                    <div class="col-md-2 col-sm-3 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-reply" aria-hidden="true"></i>Message OUT</span>
                        <div class="count"><?php echo $mmo?></div>
                    </div>

                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top">AHT</span>
                        <div class="count"><?php echo $mmaht;?></div>

                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top">ART</span>
                        <div class="count"><?php echo $mmart;?></div>
                    </div>
                </div>
                <div class="row tile_count">
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top">Message Unique Visitor</span>
                        <div class="count"><?php echo $mmuu;?></div>
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top">Message Unique Reply</span>
                        <div class="count"><?php echo $mmur;?></div>
                    </div>
                </div>
                <?php
                }
            ?>
            <div class="row tile_count">
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-comments-o" aria-hidden="true"></i>Admin Post</span>
                    <div class="count"><?php echo $mcap;?></div>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-reply" aria-hidden="true"></i>User Activity</span>
                    <div class="count"><?php echo $mcua;?></div>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-reply" aria-hidden="true"></i>Admin Activity</span>
                    <div class="count"><?php echo $mcaa?></div>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-reply" aria-hidden="true"></i>AHT</span>
                    <div class="count"><?php echo $mcaht;?></div>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-reply" aria-hidden="true"></i>ART</span>
                    <div class="count"><?php echo $mcart;?></div>
                </div>
            </div>
            <div class="row tile_count">
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-reply" aria-hidden="true"></i>Wall Post</span>
                    <div class="count"><?php echo $mwap;?></div>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-reply" aria-hidden="true"></i>Admin Activity</span>
                    <div class="count"><?php echo $mwaa;?></div>

                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-reply" aria-hidden="true"></i>AHT</span>
                    <div class="count"><?php echo $mwaht;?></div>

                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-reply" aria-hidden="true"></i>ART</span>
                    <div class="count"><?php echo $mwart;?></div>

                </div>
            </div>
        </div>
    </div>
    </div>