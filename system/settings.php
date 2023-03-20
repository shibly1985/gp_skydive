<?php
    $data = array($pUrl=>$rModule['cmTitle']);$general->breadcrumb($data);
    $qCleanPermission=$db->permission(PER_QUEUE_CLEAN);
    echo PER_SERVICE_TIME;echo '<br>';
    $service24hoursPermission=$db->permission(PER_SERVICE_TIME);
    var_dump($service24hoursPermission);

    $sArray=array('commentFlowType','commentsInclude','wallPostInclude','wallPostAssign','wallPostFlowType','messageFlowType','sessionLifeTime','page_name','page_id','appid','appsecret','access_token','userLicence','messageMaxService');
    $extArray=array(
        'commentFlowType'   => array('permission'=>89,'tips'=>'If FIFO Then type '.WALL_POST_COMMENT_FLOW_FIFO.'<br>If LIFO Then '.WALL_POST_COMMENT_FLOW_LIFO),
        'messageFlowType'   => array('permission'=>89,'tips'=>'If FIFO Then type '.WALL_POST_COMMENT_FLOW_FIFO.'<br>If LIFO Then '.WALL_POST_COMMENT_FLOW_LIFO),
        'commentsInclude'   => array('permission'=>90,'tips'=>'0 =none<br>1=customer<br>2=me<br>3=both'),
        'sessionLifeTime'   => array('permission'=>91,'tips'=>'min 2 max 59 minute'),
        'page_name'         => array('permission'=>91),
        'page_id'           => array('permission'=>91),
        'appid'             => array('permission'=>91),
        'appsecret'         => array('permission'=>91),
        'access_token'      => array('permission'=>91,'tips'=>
            "<b>This permissions required for this app</b><br>
            publish_actions<br>
            manage_pages<br>
            pages_manage_cta<br>
            publish_pages<br>
            pages_messaging<br>
            pages_messaging_payments<br>
            pages_messaging_phone_number<br>
            pages_messaging_subscriptions<br>
            pages_show_list<br>
            read_page_mailboxes<br>
            rsvp_event<br>
            user_events<br>
            user_managed_groups<br>
            pages_manage_instant_articles<br>
            <b>user_posts<br>
            user_status</b>
        "),
        'userLicence'       => array('permission'=>PER_USER_LICENCE),
    );
    $sv=$db->settingsValues($sArray);
    //$general->printArray($sv);
    foreach($sv as $k=>$v){
        if(isset($_POST[$k.'Up'])){
            $db->settingsUpdate($_POST['title'],$k);$general->redirect($pUrl);
            break;
        }
    }
    if($service24hoursPermission==true){
        if(isset($_POST['serviceTime'])){
            $service24hours=intval($_POST['title'])==1?1:2;

            $startHour      = intval($_POST['startHour']);
            $startMin       = intval($_POST['startMin']);
            $endHour        = intval($_POST['endHour']);
            $endMin         = intval($_POST['endMin']);
            $startTime      = $startHour.':'.$startMin;
            $endTime        = $endHour.':'.$endMin;
            $db->settingsUpdate($service24hours,'service24hours');
            $db->settingsUpdate($startTime,'serviceStartTime');
            $db->settingsUpdate($endTime,'serviceEndTime');
            $general->redirect($pUrl);
        }
    }
    if($qCleanPermission==true){
        if(isset($_GET['clean_assignment_comment'])){
            $cClean = $db->runQuery("DELETE from ".$general->table(10));
            if($cClean){
                $general->redirect($pUrl,6,"Comments Queue has been Cleaned!");
            }
        }
        if(isset($_GET['clean_assignment_wall'])){
            $pClean = $db->runQuery("DELETE from ".$general->table(5));
            $cClean = $db->runQuery("DELETE from ".$general->table(36));
            if($pClean && $cClean){
                $general->redirect($pUrl,6,"Wall Post Queue has been Cleaned!"); 
            }
        }
    }
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?php echo $rModule['cmTitle'];?></h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content dashboard_design">
            <?php show_msg();?>
            <div class="row"> 
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a href="?module=privilege&amp;perm">
                        <div class="icon"><img src="images/user_2.png" alt=""></div>
                        <h3>Privilege</h3>
                    </a>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a href="?module=wrapup">
                        <div class="icon"><img src="images/request_icon.png" alt=""></div>
                        <h3>Wrapup</h3>
                    </a>
                </div> 
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a href="?module=message_templates">
                        <div class="icon"><img src="images/message2.png" alt=""></div>
                        <h3>Message Templates</h3>
                    </a>
                </div> 
            </div>
        </div>
    </div>
</div>
<?php
    if($qCleanPermission==true){
    ?>
    <div class="col-md-10 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Clear Queue</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="col-md-3">
                    <div class="tile-stats senti_box">
                        <a href="?module=settings&clean_assignment_comment"
                            onclick="return confirm('Are you sure want clean ?');">
                            <h3>Clear Comments Queue</h3>
                        </a>
                    </div>
                </div> 
                <div class="col-md-3">
                    <div class="tile-stats senti_box">
                        <a href="?module=settings&clean_assignment_wall" onclick="return confirm('Are you sure want clean ?');">
                            <h3>Clear Wall Post Queue</h3>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    }
?>
<div class="col-md-10 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Core Settings</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <?php
                foreach($sv as $s=>$v){
                    $show=true;
                    if(isset($extArray[$s]['permission'])){
                        //var_dump($extArray[$s]['permission']);var_dump($show);
                        $show=$db->permission($extArray[$s]['permission']);
                    }
                    if($show==true){
                    ?>
                    <form action="" method="POST">
                        <table class="table span6 table-hover">
                            <tr>
                                <td style="width: 25%;"><?php echo $v['ssTitle'];?></td>
                                <td style="width: 25%;"><input type="text" value="<?php echo $v['ssVal'];?>" name="title" class="inp" style="width: 100%;"></td>
                                <td style="width: 10%;"><input type="submit" class="btn" name="<?php echo $s;?>Up" value="Save"></td>
                                <td>
                                    <?php
                                        if(isset($extArray[$s]['tips'])){
                                            echo $extArray[$s]['tips'];
                                        }
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </form>
                    <?php
                    }
                }
            ?>
        </div>
    </div>
</div>
<?php
    if($service24hoursPermission==true){
    ?>
    <div class="col-md-10 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Service Time</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <?php
                    $service24hours=intval($db->settingsValue('service24hours'));//1=24 hours and 2=custom hour
                    $service24hours=$service24hours==1?1:2;
                ?>
                <form action="" method="POST">
                    <table class="table span6 table-hover">
                        <tr>
                            <td style="width: 25%;">Service running time</td>
                            <td style="width: 25%;">
                                <select name="title">
                                    <option value="1" <?php echo $general->selected(1,$service24hours);?>>24 Hour</option>
                                    <option value="2" <?php echo $general->selected(2,$service24hours);?>>Custom Hour</option>
                                </select>
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                        <?php
                            $startTime=explode(':',$db->settingsValue('serviceStartTime'));
                            $startHour=intval(@$startTime[0]);
                            $startMin=intval(@$startTime[1]);
                            $endTime=explode(':',$db->settingsValue('serviceEndTime'));
                            $endHour=intval(@$endTime[0]);
                            $endMin=intval(@$endTime[1]);

                            $todayStartTime=strtotime(date('d-m-Y '.str_pad(intval(@$startTime[0]),2,0,STR_PAD_LEFT).':'.str_pad(intval(@$startTime[1]),2,0,STR_PAD_LEFT).':00'));
                            $todayEndTime=strtotime('+ '.$endHour.' hour',$todayStartTime);
                            $todayEndTime=strtotime('+ '.$endMin.' minute',$todayEndTime);


                        ?>
                        <tr>
                            <td>Service start time</td>
                            <td>
                                <select name="startHour">
                                    <?php
                                        for($i=0;$i<24;$i++){
                                        ?>
                                        <option value="<?php echo $i;?>" <?php echo $general->selected($startHour,$i);?>><?php echo $i;?></option>
                                        <?php
                                        }
                                    ?>
                                </select>
                                :
                                <select name="startMin">
                                    <?php
                                        for($i=0;$i<60;$i++){
                                        ?>
                                        <option value="<?php echo $i;?>" <?php echo $general->selected($startMin,$i);?>><?php echo $i;?></option>
                                        <?php
                                        }
                                    ?>
                                </select>
                            </td>
                            <td><?php echo date('h:i A',$todayStartTime);?></td>
                        </tr>
                        <tr>
                            <td>Total service time(কত সময় সার্ভিস চলবে)</td>
                            <td>
                                <select name="endHour">
                                    <?php
                                        for($i=0;$i<24;$i++){
                                        ?>
                                        <option value="<?php echo $i;?>" <?php echo $general->selected($endHour,$i);?>><?php echo $i;?></option>
                                        <?php
                                        }
                                    ?>
                                </select>
                                :
                                <select name="endMin">
                                    <?php
                                        for($i=0;$i<60;$i++){
                                        ?>
                                        <option value="<?php echo $i;?>" <?php echo $general->selected($endMin,$i);?>><?php echo $i;?></option>
                                        <?php
                                        }
                                    ?>
                                </select>
                            </td>
                            <td><?php echo date('h:i A',$todayEndTime);?></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><input type="submit" value="Save" class="btn" name="serviceTime"></td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
    <?php
    }
?>