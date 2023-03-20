<?php
    if(isset($_GET['date_range'])){
        $date_range=$_GET['date_range'];
    }
    else{
        $dr = date('d-m-Y 00:00:00');
        $dr2= date('d-m-Y 00:00:00');
        $date_range=$dr.'__'.$dr2;
    }
    if(isset($_GET['uID'])){
        $uID = $_GET['uID'];
    }
    $dates=explode('__',$date_range);
    //$general->printArray($dates);
    $from=strtotime($dates[0]);
    //echo $from;echo'<br>'; 
    $to=strtotime($dates[1]);
    if(date('H:i',$to)=='00:00'){
        $to=strtotime('+1 day',$to);
        $to=strtotime('-1 second',$to);
    }
    $dateRangeVal=date('d-m-Y h:i A',$from).'__'.date('d-m-Y h:i A',$to);
    $dateRangeShow=date('d-m-Y h:i A',$from).' - '.date('d-m-Y h:i A',$to);
    $link=$pUrl.'&date_range='.$dateRangeVal;
?>
<script type="text/javascript">
    $(document).ready(function(){
        $('#reportrange').daterangepicker({
            timePicker: true,
            timePicker24Hour: true,
            opens: "right",
            autoApply: true,
            startDate: "<?php echo date('m/d/Y',$from);?>",
            endDate: "<?php echo date('m/d/Y',$to);?>"
            }, 
            function(start, end) {
                $('#reportrange span').html(start.format('DD-MM-YYYY hh:mm A') + ' - ' + end.format('DD-MM-YYYY hh:mm A'));
                $('#date_range').val(start.format('DD-MM-YYYY HH:mm')+'__'+end.format('DD-MM-YYYY HH:mm'));
        });
    });
</script>
<?php
    if($to-$from<=86400){
        $dates['0'] =$to;
    }else{
        $dates = range($from,$to,86400); 
    }
    function toDate($x){return date('Y-m-d', $x);}
    $range_of_dates = array_map("toDate", $dates);

?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?php echo $rModule['cmTitle'];?><span id="totalDisplay"></span></h2>
            <div class="clearfix"></div>
        </div>
        <form method="GET" action="" class="form-inline form_inline" style="margin-bottom: 20px;">
            <?php echo URL_INFO;?>
            <input type="hidden" id="date_range" name="date_range" value="<?php echo $dateRangeVal;?>">
            <div class="form-group">
                <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                    <span><?php echo $dateRangeShow;?></span> <b class="caret"></b>
                </div>
            </div>
            <div class="form-group" id="groupuID">
                <label>User:</label>
                <select name="uID" id="selectuID" class="form-control select2"> 
                    <?php
                        $users= $db->allUsers('order by uFullName asc');
                        foreach($users as $u){                       
                        ?>
                        <option value="<?php echo $u['uID'];?>" <?php if($u['uID']==@$uID){echo 'selected';}?>><?php echo $u['uFullName']; ?></option>
                        <?php
                        }
                    ?>
                </select>
            </div>
            <input type="submit" value="Show" name="show" class="btn btn-success btn-sm">
        </form>
    </div>
</div>

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
                if(isset($_GET['uID'])){
                    $gtPlanned = 0;
                    $gtActual = 0;
                    $reasons = $db->selectAll($general->table(53));
                    $reason = array();
                    foreach($reasons as $re){
                        $reason[$re['ubrID']] = $re['ubrTitle']; 
                    }  
                    $i=1;
                    foreach($range_of_dates as $d){
                        $from=strtotime($d);
                        $to =strtotime('+1 day', $from);
                        $to= $to-1;
                        $break = $db->selectAll($general->table(43),'where uID='.$uID.' and btTime between '.$from.' and '.$to);

                        if($break){
                            $tPlanned=0;
                            $tActual=0;
                            $aReason=array();
                            foreach($break as $br){
                                array_push($aReason, $reason[$br['ubrID']]);
                                $planned = $br['btAppReturnTime']-$br['btTime'];
                                $tPlanned =$tPlanned+$planned;
                                $actual = $br['btReturnTime']-$br['btTime'];
                                $tActual =  $tActual+$actual;
                            }
                            $gtActual =$gtActual + $tActual;
                            $gtPlanned = $gtPlanned + $tPlanned;
                            $data = array(
                                'date'  =>$d,
                                'reason'  => implode(", ",$aReason),
                                'planned'  => gmdate("H:i:s", $tPlanned),
                                'actual'  => gmdate("H:i:s", $tActual),
                            );
                            $jArray['serises'][$i++] = $data;

                        }
                    }
                    $jArray['xData'] = array('Date','Reasons','Planned','Actual');
                    if(count($jArray['serises'])>0){
                    ?>
                    <a class="btn btn-default" id="exportTotalBreak">Export</a>
                    <?php
                    }
                ?>
                <table class="table table-striped users_view_table">
                    <tr>
                        <th>Date</th>
                        <th>Reason</th>
                        <th>Planned</th>
                        <th>Actual</th>
                    </tr>
                    <?php
                        foreach($jArray['serises'] as $d){
                        ?>
                        <tr>
                            <td><?php echo $d['date']; ?></td>
                            <td><?php echo $d['reason'];?></td>
                            <td><?php echo $d['planned'];?></td>
                            <td><?php echo $d['actual'];?></td>
                        </tr>
                        <?php
                        }
                        if($gtPlanned>0 || $gtActual>0){
                        ?>
                        <tr>
                            <td colspan="2">Total</td>
                            <td><?php echo  gmdate("H:i:s", $gtPlanned);?></td>
                            <td><?php  echo  gmdate("H:i:s", $gtActual);?></td>
                        </tr>
                        <?php
                        }
                    }
                ?>
            </table>
        </div>
    </div>
</div>
<script>
    <?php echo "var eIndex = ".json_encode($jArray).";";?>
    $("#exportTotalBreak").click(function(){
        reportExportToExcel(eIndex);
    });

</script>