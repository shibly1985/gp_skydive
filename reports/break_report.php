<?php 
    include("report_header_with_operator.php");
    $link = $pUrl;
    if(isset($_GET['date_range'])){
        $link.=  '&date_range='.urldecode($date_range).'&&show=Show';
    }
    $link.='&'.PAGE_INATION_CURRENT_PAGE_NAME.'=';
    $from=$from_date;
    $to=$to_date;
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
            <?php
                $operators = $db->allUsers();
                $operator = array();
                foreach($operators as $op){
                    $operator[$op['uID']]=$op['uFullName'];
                }
                $reasons = $db->selectAll($general->table(53));
                $reason = array();
                foreach($reasons as $re){
                    $reason[$re['ubrID']] = $re['ubrTitle']; 
                }
                // $breaks = $db->selectAll($general->table(43),"where btTime between ".$from." and ".$to." and btReturnTime!=0 and uID!=52");
                $query ="select * from ".$general->table(43)." where btTime between ".$from." and ".$to." and btReturnTime!=0";
                if($uID!=0){
                    $query.=' and uID='.$uID;
                }
                $cp=1;if(isset($_GET[PAGE_INATION_CURRENT_PAGE_NAME]))$cp=intval($_GET[PAGE_INATION_CURRENT_PAGE_NAME]);
                $pageination=$general->pagination_init_customQuery($query,100,$cp);
                $breaks=$db->fetchQuery($query.$pageination['limit']);
                $i=1;
                foreach ($breaks as $br){
                    $dOperator = $operator[$br['uID']];//.' - '.$br['btID'];
                    $dDate =  $general->make_date($br['btTime']);
                    $dReason = $reason[$br['ubrID']];
                    $dPlanned = $general->timestampDiffInArray($br['btAppReturnTime'],$br['btTime'],true);
                    $dPlanned = $general->makeTimeAvgI($dPlanned);
                    $dActual = $general->timestampDiffInArray($br['btReturnTime'],$br['btTime'],true);
                    $dActual =  $general->makeTimeAvgI($dActual);

                    $jArray['serises'][$i++] = array(
                        'opName'    =>$dOperator,
                        'date'      =>$dDate,
                        'start'     =>date('h:i:s A',$br['btTime']),
                        'reason'    => $dReason,
                        'planned'   =>$dPlanned,
                        'actual'    =>$dActual
                    );
                }

                $jArray['xData'] = array('Operator Name','Date','Reason','Planned','Actual');
                if(count($jArray['serises'])>0){
                ?>
                <a class="btn btn-default" id="exportBreak">Export</a>
                <?php
                }
            ?>
            <table class="table table-striped users_view_table">
                <tr>
                    <th>SN</th>
                    <th>Operator Name</th>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>Reason</th>
                    <th>Planned</th>
                    <th>Actual</th>
                </tr>
                <?php
                    $sn=1;
                    $cp=$cp-1;
                    foreach($jArray['serises'] as $d){
                    ?>
                    <tr>
                        <td><?php echo $cp*100+$sn++;?></td>
                        <td><?php echo $d['opName']; ?></td>
                        <td><?php echo $d['date'];?></td>
                        <td><?php echo $d['start'];?></td>
                        <td><?php echo $d['reason'];?></td>
                        <td><?php  echo  $d['planned'];  ?>  </td>
                        <td><?php  echo  $d['actual']; ?></td>
                    </tr>
                    <?php
                    }
                ?>
            </table>
            <ul class="pagination"><?php $general->pagination($pageination['currentPage'],$pageination['TotalPage'],$link);?></ul>
        </div>
    </div>
</div>
<script>
    <?php echo "var eIndex = ".json_encode($jArray).";";?>
    $("#exportBreak").click(function(){
        reportExportToExcel(eIndex);
    });

</script>
