<?php
    $type='c';
    if(isset($_GET['type'])){
        if($_GET['type']=='m'&&OPERATION_MESSAGE_ALLWO==true)$type='m';
        elseif($_GET['type']=='w')$type='w';
    }
?>
<div class="animated flipInY col-lg-2 col-md-2 col-sm-3 col-xs-12">
    <div class="tile-stats senti_box">
        <a href="<?php echo $pUrl;?>&type=c">
            <h3><i class="fa fa-comments-o"></i> Comments</h3>
        </a>
    </div>
</div>
<div class="animated flipInY col-lg-2 col-md-2 col-sm-3 col-xs-12">
    <div class="tile-stats senti_box">
        <a href="<?php echo $pUrl;?>&type=w">
            <h3><i class="fa fa-comments-o"></i> Wall Post</h3>
        </a>
    </div>
</div>
<?php
    if(OPERATION_MESSAGE_ALLWO==true){
    ?>
    <div class="animated flipInY col-lg-2 col-md-2 col-sm-3 col-xs-12">
        <div class="tile-stats senti_box">
            <a href="<?php echo $pUrl;?>&type=m">
                <h3><i class="fa fa-weixin" aria-hidden="true"></i> Message</h3>
            </a>
        </div>
    </div>
    <?php
    }
    include("report_header.php");
    $from   = strtotime($st_date);
    $to     = strtotime($ed_date);
    if(date('H:i',$to)=='00:00'){
        $to=strtotime('+1 day',$to);
        $to=strtotime('-1 second',$to);
    }
    $graphData=array();

    $rng=$to-$from;
    // echo $rng;echo '<br>';
    if($rng>(3600*24*2)){
        $dif=(3600*24);
    }
    else if($rng>3600){
        $dif=3600;
    }
    else{
        $dif=60;
    }
    $dif=(3600*24);
    /*var_dump($from,$to,$dif);
    $dates = range($from, $to,$dif);
    $general->printArray($dates);exit;*/
    $scentiments=array(
        SCENTIMENT_TYPE_POSITIVE    => array('total'=>0,'title'=>$social->getScentimentTitleById(SCENTIMENT_TYPE_POSITIVE)),
        SCENTIMENT_TYPE_NUTRAL      => array('total'=>0,'title'=>$social->getScentimentTitleById(SCENTIMENT_TYPE_NUTRAL)),
        SCENTIMENT_TYPE_NEGETIVE    => array('total'=>0,'title'=>$social->getScentimentTitleById(SCENTIMENT_TYPE_NEGETIVE))
    );
    //    $scentiments=array(SCENTIMENT_TYPE_POSITIVE,SCENTIMENT_TYPE_NEGETIVE,SCENTIMENT_TYPE_NUTRAL);
    $wrapusData=array();
    $range=array();
    if($type=='c')include("reports/sentimental_report_c.php");
    elseif($type=='w')include("reports/sentimental_report_w.php");
    elseif($type=='m')include("reports/sentimental_report_m.php");


    $general->makeGraph($graphData);
?>


