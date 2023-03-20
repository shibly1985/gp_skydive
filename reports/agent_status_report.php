<script src="vendors/Chart.js/dist/Chart.min.js"></script>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?php echo $rModule['cmTitle'];?></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a href="<?php echo $pUrl;?>&add=1"><i class="fa fa-plus"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <?php

            $from_date=YESTERDAY_TIME;
            if(isset($_GET['show'])){
                $from_date=strtotime($_GET['date']);
            }
            $to_date=strtotime('+1 day',$from_date);
            $to_date=strtotime('-1 second',$to_date);

            if(isset($_GET['ag'])){
                $uID    = intval($_GET['ag']);
            }
            else{
                $uID=0;
            }
        ?>
        <form method="GET" action="" class="form-inline form_inline" style="margin-bottom: 20px;">
            <?php echo URL_INFO;?>
            <div class="form-group">
                <input type="text" class="daterangepicker form-control" name="date" value="<?php echo date('d-m-Y',$from_date);?>" style="position: initial;">
            </div>
            <div class="form-group">
                <select name="ag" class="form-control select2">
                    <option value="">Select Agent</option>
                    <?php
                        $users=$db->allUsers('order by uFullName asc');
                        foreach($users as $u){?><option value="<?php echo $u['uID'];?>" <?php echo $general->selected($u['uID'],@$uID);?>><?php echo $u['uFullName'];?></option><?php } ?>
                </select>
            </div>

            <input type="submit" value="Show" name="show" class="btn btn-success btn-sm">
        </form>
        <?php
            $u=$db->get_rowData($general->table(17),'uID',$uID);
            $logData=array();
            if(!empty($u)){
                $logins=$db->selectAll($general->table(18),'where uID='.$uID.' and ulsStartTime between '.$from_date.' and '.$to_date.' order by ulsStartTime desc');
                //$general->printArray($logins);
                if(!empty($logins)){
                    foreach($logins as $l){
                        if($l['ulsLastActivity']==0){$l['ulsLastActivity']=$l['ulsStartTime'];}
                        $logData[$l['ulsStartTime']]=array(
                            'status'    => 'Available',
                            'start'     => $l['ulsStartTime'],
                            'end'       => $l['ulsLastActivity'],
                            'duration'  => $general->timestampDiffInArray($l['ulsLastActivity'],$l['ulsStartTime'],true)
                        );
                    }
                }
                $query="
                SELECT b.btID,b.btTime,b.btReturnTime,br.ubrTitle
                FROM ".$general->table(43)." b
                left join ".$general->table(53)." br on br.ubrID=b.ubrID
                where b.uID=".$uID." and btTime between ".$from_date." and ".$to_date." order by btTime desc";
                $breaks=$db->fetchQuery($query);
                if(!empty($breaks)){
                    foreach($breaks as $l){
                        $logData[$l['btTime']]=array(
                            'status'    => $l['ubrTitle'],
                            'start'     => $l['btTime'],
                            'end'       => $l['btReturnTime'],
                            'duration'  => $general->timestampDiffInArray($l['btTime'],$l['btReturnTime'],true)
                        );
                    }
                }
            }
            //$general->printArray($logData);
            $jArray = array();
            if(!empty($logData)){
            ?> 
            <div class="x_content">
                <!--<a class="btn btn-default" id="repAttExport">Export</a>-->
                <table class="table table-striped users_view_table" id="repAttData">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Status</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            ksort($logData);
                            $i=1;
                            foreach($logData as $l){ 
                            ?>
                            <tr>
                                <td><?php echo $i++;?></td>
                                <td><?php echo $l['status'] ?></td>
                                <td><?php echo date('h:i:s A',$l['start']) ?></td>
                                <td><?php echo date('h:i:s A',$l['end']) ?></td>
                                <td><?php echo $general->makeTimeAvgI($l['duration']);?></td>
                            </tr>
                            <?php
                            } 
                            $jArray['xData'] = array("Date","Representatives","In Time","Last Activity","Loged in","Service");
                        ?>
                    </tbody>
                </table>
            </div>  
            <?php
            }
            else{
            ?>
            <div class="x_content"><h3>Data Not Found</h3></div>
            <?php
            }
        ?>
    </div> <!--start from common header-->
</div> <!--start from common header-->
<!--<script>
    <?php //echo "var eIndex = ".json_encode($jArray).";";?>
    /*$("#repAttExport").click(function(){
        reportExportToExcel(eIndex);
    })*/
</script>-->
