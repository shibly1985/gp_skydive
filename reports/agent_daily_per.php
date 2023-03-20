<script src="vendors/Chart.js/dist/Chart.min.js"></script>
<script src="vendors/echarts/dist/echarts.min.js"></script>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?php echo $rModule['cmTitle'];?></h2>
            <div class="clearfix"></div>
        </div>
        <?php

            if(isset($_GET['show'])){
                $date = @$_GET['date'];
                $from =  strtotime($date);
                $to =strtotime('+1 day',$from);
                
            }else{
                $date = date('m/d/Y',strtotime('today'));
                $from = strtotime($date);
                $to = strtotime('+1 day',$from); 
            }
        ?>
        <form method="GET" class="form-inline form_inline" action="">
            <input type="hidden" name="<?php echo MODULE_URL;?>" value="<?php echo $pSlug;?>">
            <div class="form-group">
                <label>Date:</label>
                <input type="text" class="cdatepicker hasDatepicker form-control" value="<?php echo $date;?>" name="date" id="to_date">
            </div>
            <input type="submit" value="Show" name="show" class="btn btn-success btn-sm">
        </form>
        
</div>
</div>
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
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Agent</th>
                        <th>First Login</th>
                        <th>Last Logout</th>
                        <th>Agent Available Time</th>
                        <th>Agent Engaged time</th>
                        <th>Average Handling Time</th>
                        <th>Total Handled Query</th>
                        <th>Busy Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $users= $db->selectAll($general->table(17),'WHERE isActive=1 and uID!='.SUPERADMIN_USER);
                        foreach($users as $u){
                        ?>
                        <tr>
                            <td><?php echo $general->make_date($from);?></td>
                            <td><?php echo $u['uFullName'] ?></td>
                            <td>
                                <?php
                                    $logins = $db->selectAll($general->table(18),' WHERE ulsStartTime BETWEEN '.$from.' and '.$to.' order by ulsStartTime asc LIMIT 1');
                                    $general->make_date($logins[0]['ulsStartTime'],'tam');
                                ?>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
