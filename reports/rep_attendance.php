<?php
    include("report_header_with_operator.php");
    $u=$db->get_rowData($general->table(17),'uID',$uID);
    $logData=array();
    if(!empty($u)){
        $logins=$db->selectAll($general->table(18),'where uID='.$uID.' and ulsStartTime between '.$from_date.' and '.$to_date.' order by ulsID desc');

        if(!empty($logins)){
            foreach($logins as $l){
                $start=strtotime(date('d-m-Y',$l['ulsStartTime']));
                $logData[]=array(
                    'uID'           => $l['uID'],
                    'in'            => $l['ulsStartTime'],
                    'lastActivity'  => $l['ulsLastActivity'],
                    'logedIn'       => $general->timestampDiffInArray($l['ulsLastActivity'],$l['ulsStartTime'],true),
                    'ulsService'    => $l['ulsService']
                );
            }
        }
    }
    else{
        $superAdmins=$db->selectAll($general->table(17),'where ugID='.SUPERADMIN_USER);
        $cq='';
        if(!empty($superAdmins)){
            $d=$general->arrayIndexChange($superAdmins,'uID');
            $cq=' and uID not in ('. implode(',',array_keys($d)).')';
        }
        $logins=$db->selectAll($general->table(18),'where ulsStartTime between '.$from_date.' and '.$to_date.$cq.' order by ulsID desc');
        if(!empty($logins)){
            foreach($logins as $l){
                $start=strtotime(date('d-m-Y',$l['ulsStartTime']));
                if(!isset($logData[$start.$l['uID']])){
                    $logData[$start.$l['uID']]=array(
                        'uID'           => $l['uID'],
                        'in'            => $l['ulsStartTime'],
                        'lastActivity'  => $l['ulsLastActivity'],
                        'logedIn'       => $general->timestampDiffInArray($l['ulsLastActivity'],$l['ulsStartTime'],true),
                        'ulsService'    => $l['ulsService']
                    );
                    if($l['ulsLastActivity']==0){
                        $logData[$start.$l['uID']]['logedIn']=0;
                    }
                }
                else{
                    $c=$logData[$start.$l['uID']];
                    if($c['in']>$l['ulsStartTime']){$c['in']=$l['ulsStartTime'];}
                    if($c['lastActivity']<$l['ulsLastActivity']){$c['lastActivity']=$l['ulsLastActivity'];}
                    if($l['ulsLastActivity']==!0){
                        $c['logedIn']+=$general->timestampDiffInArray($l['ulsLastActivity'],$l['ulsStartTime'],true);
                    }
                    $c['ulsService']+=$l['ulsService'];
                    $logData[$start.$l['uID']]=$c;
                }
                //                $general->printArray($logData);
            }

        }
    }

    $users=$db->allUsers();
    $general->arrayIndexChange($users,'uID');
    $jArray = array();
    if(!empty($logData)){
    ?> 
    <div class="x_content">
        <a class="btn btn-default" id="repAttExport">Export</a>
        <table class="table table-striped users_view_table" id="repAttData">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>Date</th>
                    <th>Representatives</th>
                    <th>In Time</th>
                    <th>Last Activity</th>
                    <th>Duration</th>
                    <th>Service</th>
                </tr>
            </thead>
            <tbody>
                <?php

                    $i=1;
                    foreach($logData as $l){ 
                    ?>
                    <tr>
                        <td><?php echo $i++;?></td>
                        <td><?php echo $dateEx = $general->make_date($l['in']) ?></td>
                        <td><?php echo $nameEx = $users[$l['uID']]['uFullName'];?></td>
                        <td><?php echo $timeEx = $general->make_date($l['in'],'time') ?></td>
                        <td><?php echo $lastActEx = $general->make_date($l['lastActivity'],'time') ?></td>
                        <td><?php echo $logedEx = $general->makeTimeAvgI($l['logedIn']);?></td>
                        <td><?php echo $ulsEx = $general->makeTimeAvgI($l['ulsService']);?></td>
                    </tr>
                    <?php
                        $j=$i-1;
                        $jArray['serises'][$j] = array($dateEx,$nameEx,$timeEx,$lastActEx,$logedEx,$ulsEx);
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
    <div class="x_content"><h3>Any data not found</h3></div>
    <?php
    }
?>
</div> <!--start from common header-->
</div> <!--start from common header-->
<script>
    <?php echo "var eIndex = ".json_encode($jArray).";";?>
    $("#repAttExport").click(function(){
        reportExportToExcel(eIndex);
    })
</script>
