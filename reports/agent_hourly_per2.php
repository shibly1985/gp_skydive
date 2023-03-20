<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?php echo $rModule['cmTitle'];?></h2>
            <div class="clearfix"></div>
        </div>
        <?php

            if(isset($_GET['show'])){
                $date = @$_GET['date'];
                $uID = @$_GET['uID'];
                $date_timestamp = strtotime($date);
            }else{
                $date = date('m/d/Y',strtotime('today'));
                $uID = 44;
                $date_timestamp = strtotime($date);
            }
        ?>
        <form method="GET" class="form-inline" action="">
            <input type="hidden" name="<?php echo MODULE_URL;?>" value="<?php echo $pSlug;?>">
            <div class="form-group">
                <label>Date:</label>
                <input type="text" style="position:initial;" class="daterangepicker form-control" value="<?php echo $date;?>" name="date">
            </div>
            <div class="form-group">
                <label>Hour:</label>
                <select name="uID" id="" class="form-control"> 
                <?php
                     $users= $db->allUsers();
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
            <h2></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>From</th>
                        <th>To</th>
                        <th>
                        Total Handling Query<br>
                        C+W=T
                        </th>
                        <th>Average Handling Time</th>
                        <th>Available Time</th>
                        <th>Engaged Time</th>
                        <th>Away Time</th>
                    </tr>
                </thead>
                <tbody>
                        <?php
                            $twp = 0;
                            $tcm = 0;
                            $tactive = 0;
                            $tservice = 0;
                            $taway = 0;
                            $oneHour=3600;
                            for($i=0;$i<=23;$i++){
                            $from= $date_timestamp+($oneHour*$i);
                            $to = $from+$oneHour;    
                            $logins=$db->selectAll($general->table(38),'where serviceStart between '.$from.' and '.$to.' and uID='.$uID);
                            $service=0;
                            $active=0;
                            if(!empty($logins)){ 
                                foreach($logins as $l){
                                    $service=$service+intval($l['service']);
                                    $active=$active+intval($l['active']);
                                }
                                $away=$active-$service;
                            }
                            else{
                                $service=0;
                                $active=0;
                                $away = 0;
                            }      
                        ?>
                            <tr>
                                <td><?php echo $general->make_date($date_timestamp)?></td>   
                                <td><?php echo $general->make_date($from,'tam')?></td>
                                <td><?php echo $general->make_date($to,'tam')?></td>
                                <td>

                                    <?php
                                        $cm=$sReport->commentsAdminActivity($from,$to,$uID);
                                        $wc=$sReport->wallAdminActivity($from,$to,$uID);
                                        echo $cm.'+'.$wc.'='.($cm+$wc);
                                        $tcm=$tcm+$cm;
                                        $twc=$twc+$wc;
                                    ?>
                                </td>
                                <td><?php $ht = $sReport->ahtNart($from,$to,$uID); echo $ht['aht']; ?></td>
                                <td><?php echo gmdate("i:s", $active);  $active= 60*date("i", $active)+date("s",$active); if($active>0){$tactive= $tactive+$active;}?></td>
                                <td><?php echo gmdate("i:s", $service);$service= 60*date("i", $service)+date("s",$service); if($service>0){$tservice= $tservice+$service;}?></td>
                                <td><?php echo gmdate("i:s", $away); $taway = $taway+$away;?></td>
                            </tr>
                            <?php
                            }
                            ?>
                            <tr>
                                <td colspan="3">Total</td>
                                <td><?php echo $tcm.'+'.$twc.'='.($tcm+$twc);?></td>
                                <td></td>
                                <td><?php echo gmdate("h:i:s", $tactive);?></td>
                                <td><?php echo gmdate("H:i:s", $tservice);?></td>
                                <td><?php echo gmdate("H:i:s", $taway);?></td>
                            </tr>
                            <?php
                            /*

                            ?>
                            <tr>
                            <td><?php echo $general->make_date($from)?></td>   
                            <td><?php echo $general->make_date($from,'tam')?></td>
                            <td><?php echo $general->make_date($to,'tam')?></td>
                            <td><?php echo $sReport->commentsAdminActivity($from,$to,$u['uID']);?></td>
                            <td><?php $ht = $sReport->ahtNart($from,$to,$u['uID']); echo $ht['aht']; ?></td>
                            <td>
                            <?php
                            $uq =  ' and uID='.$u['uID'];
                            $logins=$db->selectAll($general->table(18),'where ulsStartTime between '.$from.' and '.$to.$uq.' order by ulsID desc');
                            $time= 0;
                            foreach($logins as $lg){ 
                            if($lg['ulsStartTime']>0 && $lg['ulsEndTime']>0){
                            $startTime = intval($lg['ulsStartTime']) ;
                            $endTime = intval($lg['ulsEndTime']);
                            //echo $startTime.'|'.$endTime;
                            $sTime=  $endTime-$startTime;  
                            $time = $time+$sTime;
                            } 

                            //echo $general->make_date($time,'tam');
                            }
                            echo gmdate("i:s", $time); 
                            ?>
                            </td>
                            <td><?php echo gmdate("i:s", $time); ?></td>
                            <td>
                            <?php
                            $away=$db->selectAll($general->table(18),'where ulsEndTime between '.$from.' and '.$to.$uq.' order by ulsEndTime desc','ulsEndTime'); 
                            if(count($away)>0){
                            echo $general->make_date($away[0]['ulsEndTime'],'tam');
                            }
                            ?>
                            </td>
                            </tr>
                            <?php
                            */
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
