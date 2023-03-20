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


    if(isset($_GET['show'])){
        $date_range=$_GET['date_range'];
    }
    else{
        $dr = date('d-m-Y 00:00:00',YESTERDAY_TIME).'';
        $dr2= date('d-m-Y 00:00:00').'';
        $date_range=$dr.'__'.$dr2;
    }

    $dates=explode('__',$date_range);
    $from_date=strtotime($dates[0]);
    $to_date=strtotime($dates[1]);
    if(date('h:i',$to_date)=='12:00'){
        $to_date=strtotime('+1 day',$to_date);
        $to_date=strtotime('-1 second',$to_date);
    }
    if(isset($_GET['ag'])){
        $uID    = intval($_GET['ag']);
    }
    else{
        $uID=0;
    }
    $dateRangeVal=date('d-m-Y h:i A',$from_date).'__'.date('d-m-Y h:i A',$to_date);
    $dateRangeShow=date('d-m-Y h:i A',$from_date).' - '.date('d-m-Y h:i A',$to_date);
?>
<script type="text/javascript">
    $(document).ready(function(){
        $('#reportrange').daterangepicker({
            timePicker: true,
            opens: "right",
            autoApply: true,
            timePicker24Hour: true,
            startDate: "<?php echo date('m/d/Y',$from_date);?>",
            endDate: "<?php echo date('m/d/Y',$to_date);?>"
            }, 
            function(start, end) {
                $('#reportrange span').html(start.format('DD-MM-YYYY hh:mm A') + ' - ' + end.format('DD-MM-YYYY hh:mm A'));
                $('#date_range').val(start.format('DD-MM-YYYY HH:mm')+'__'+end.format('DD-MM-YYYY HH:mm'));
        });
    });
</script>
<form method="GET" action="" class="form-inline form_inline" style="margin-bottom: 20px;">
    <?php echo URL_INFO;?>
    <?php
        if(isset($type)){
        ?>
        <input type="hidden" name="type" value="<?php echo $type; ?>">
        <?php
        }
    ?>
    <div class="form-group">
        <input type="hidden" id="date_range" name="date_range" value="<?php echo $dateRangeVal;?>">
        <div id="reportrange" class="pull-right sky_datepicker" >
            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
            <span><?php echo $dateRangeShow;?></span> <b class="caret"></b>
        </div>
    </div>
    <div class="form-group">
        <select name="ag" class="form-control select2">
            <option value="">All User</option>
            <?php
                $users=$db->allUsers('order by uFullName asc');
                foreach($users as $u){?><option value="<?php echo $u['uID'];?>" <?php echo $general->selected($u['uID'],@$uID);?>><?php echo $u['uFullName'];?></option><?php } ?>
        </select>
    </div>

    <input type="submit" value="Show" name="show" class="btn btn-success btn-sm">
</form>