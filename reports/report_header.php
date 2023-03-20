<script src="vendors/Chart.js/dist/Chart.min.js"></script>
<script src="vendors/echarts/dist/echarts.min.js"></script>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><a href="<?php echo $pUrl;?>"><?php echo $rModule['cmTitle'];?></a></h2>
            <div class="clearfix"></div>
        </div>
        <?php

            /*  if(isset($_GET['show'])){
            $st_date = $_GET['from'];
            $ed_date = $_GET['to'];
            //                echo $st_date;
            }else{
            $st_date = date('m/d/Y',strtotime('-7 day'));
            $ed_date = date('m/d/Y',strtotime('today'));
            //                echo $st_date;
            }*/
        ?>
        <?php
            if(isset($_GET['date_range'])){
                $date_range=$_GET['date_range'];
            }
            else{
                $dr = date('d-m-Y 00:00:00');
                $dr2= date('d-m-Y 00:00:00');
                $date_range=$dr.'__'.$dr2;
            }

            $dates=explode('__',$date_range);
            $from_date=strtotime($dates[0]);
            $from=$from_date;
            $to=strtotime($dates[1]);
            if(date('H:i',$to)=='00:00'){
                $to=strtotime('+1 day',$to);
                $to=strtotime('-1 second',$to);
            }
//            echo $general->make_date($from,'time');echo'<br>'; 
//            echo $general->make_date($to,'time');echo'<br>'; 
            $diff=$to-$from;
            $dateRangeVal=date('d-m-Y h:i A',$from_date).'__'.date('d-m-Y h:i A',$to);
            $dateRangeShow=date('d-m-Y h:i A',$from_date).' - '.date('d-m-Y h:i A',$to);
            $st_date = $dates[0];
            $ed_date = $dates[1];
        ?>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#reportrange').daterangepicker({
                    timePicker: true,
                    opens: "right",
                    autoApply: true,
                    timePicker24Hour: true,
                    startDate: "<?php echo date('m/d/Y',$from_date);?>",
                    endDate: "<?php echo date('m/d/Y',$to);?>"
                    }, 
                    function(start, end) {
                        $('#reportrange span').html(start.format('DD-MM-YYYY hh:mm A') + ' - ' + end.format('DD-MM-YYYY hh:mm A'));
                        $('#date_range').val(start.format('DD-MM-YYYY HH:mm')+'__'+end.format('DD-MM-YYYY HH:mm'));
                });
            });
        </script>
        <form method="GET" class="form-inline form_inline" action="">
            <?php echo URL_INFO;?>
            <div class="form-group">
                <input type="hidden" id="date_range" name="date_range" value="<?php echo $dateRangeVal;?>">
                <?php
                    if(isset($type)){
                        ?>
                        <input type="hidden" name="type" value="<?php echo $type;?>">
                        <?php
                    }
                ?>
                <div id="reportrange" class="pull-right sky_datepicker" >
                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                    <span><?php echo $dateRangeShow;?></span> <b class="caret"></b>
                </div>
            </div>
            <input type="submit" value="Show" name="show" class="btn btn-success btn-sm">
        </form>

    </div>
</div>