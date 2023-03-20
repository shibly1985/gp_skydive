<?php

    $q=array();
    $q[]='c.replyed=1';
    $q[]='c.wuID!=0';
    $q[]='c.wuID in(select wuID from '.$general->table(11).' where wuForFcr=1)';
    $q[]="c.sender_id!='".PAGE_ID."'";
    $type='c';
    $tbl=$db->settingsValue('commentReportTable');
    if(isset($_GET['type'])){
        if($_GET['type']=='m'){$type='m'; $tbl=9;}
        elseif($_GET['type']=='w'){$type='w';$tbl=$db->settingsValue('postWallReportTable');$cTbl=$db->settingsValue('commentWallReportTable');}
    }
    $rp='';
    if(isset($_GET['rp'])){
        $rp=$_GET['rp'];
    }
    if(isset($_GET['details'])){
        if($type=='c'){
            $from=strtotime($_GET['date']);
            $to=strtotime('+1 day',$from);
            $to=strtotime('-1 second',$to);
            //echo $general->make_date($from,'time');echo'<br>'; 
            //echo $general->make_date($to,'time');echo'<br>'; 
            $q[]="c.replyTime between ".$from." and ".$to;
            $sq="where ".implode(" and ",$q);

            $query  = "
            select 
            c.sender_id,c.comment_id,c.created_time,c.wuID,c.replyBy,c.message
            from ".$general->table($tbl)." c 
            ".$sq."
            order by c.sender_id,c.created_time desc";
            $all_data=$db->fetchQuery($query);
            //                exit;
            $senders=array();
            //$general->printArray($all_data);
            if(!empty($all_data)){
                $general->arrayIndexChange($all_data,'comment_id');
                foreach($all_data as $a){
                    $senders[$a['sender_id']]=$a['sender_id'];
                    $fcrData[$from][$a['sender_id']][$a['wuID']][]=$a['comment_id'];
                }
                $senderNames=$social->getNamesByUserId($senders);
                $general->arrayIndexChange($senderNames,'id');
            }
            $fcr=array();
            $nfcr=array();
            //$general->printArray($fcrData);
            if(!empty($fcrData)){
                foreach($fcrData as $from=>$f){
                    foreach($f as $sender=>$s){
                        foreach($s as $wrapup=>$w){
                            foreach($w as $ll){
                                if(count($w)==1){
                                    $fcr[]=$ll;
                                }
                                else{
                                    $nfcr[]=$ll;
                                }   
                            }
                        }
                    }
                }
            }
            $i=1;
            $reporData=array(
                'name'=>'Contributor_for_'.strtoupper($rp).'_'.date('d_m_Y',$from),
                'title'=>array(
                    array('title'=>"SL"                 ,'key'=>'s'     ,'w'=>10    ,'hw'=> 3),
                    array('title'=>"Date"               ,'key'=>'d'     ,'w'=>25    ,'hw'=> 6),
                    array('title'=>"Time"               ,'key'=>'t'     ,'w'=>15    ,'hw'=> 8),
                    array('title'=>"Customer Account ID",'key'=>'ca'    ,'w'=>15    ,'hw'=> 10),
                    array('title'=>"Contact Count"      ,'key'=>'cc'    ,'w'=>15),
                    array('title'=>"Customer Post"      ,'key'=>'cp'    ,'w'=>15    ,'hw'=> 20),
                    array('title'=>"Agent Response"     ,'key'=>'ar'    ,'w'=>15    ,'hw'=> 20),
                    array('title'=>"Agent Name"         ,'key'=>'a'     ,'w'=>15    ,'hw'=> 10),
                    array('title'=>"Wrap Up Code"       ,'key'=>'w'     ,'w'=>15    ,'hw'=> 10),
                )
            );

            $wrapups=$db->selectAll($general->table(11));
            $general->arrayIndexChange($wrapups,'wuID');
            $users=$db->allUsers();
            if($rp=='fcr'){
                $nd=$fcr;
            }
            else{
                $nd=$nfcr;
            }
            foreach($nd as $w){
                $reply=$db->get_rowData($general->table(13),'target_c_id',$w);

                $reporData['data'][]=array(
                    's'     => $i++,
                    'd'     => date('d-m-y',$all_data[$w]['created_time']),
                    't'     => date('h:i:s A',$all_data[$w]['created_time']),
                    'ca'    => $senderNames[$all_data[$w]['sender_id']]['name'],
                    'cc'    => 1,
                    'cp'    => $all_data[$w]['message'],
                    'ar'    => $reply['message'],
                    'a'     => $all_data[$w]['replyBy'],
                    'w'     => $wrapups[$all_data[$w]['wuID']]['wuTitle']
                );
            }
            //                $general->printArray($reporData);
        ?>
        <div class="x_content">
            <div class="col-md-12 col-sm-12 col-xs-12">
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
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2><a href="<?php echo $pUrl;?>"><?php echo $rModule['cmTitle'];?></a></h2>
                            <div class="clearfix"></div>
                        </div>

                        <h4>Date: <?php echo $_GET['date'];?> <?php echo strtoupper($rp);?> Report Details</h4>
                        <a style="display: none;" id="replyExport" class="btn btn-default">Export</a>
                        <?php
                            $sReport->arrayReportTable($reporData);

                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
        }
        elseif($type=='w'){
            $from=strtotime($_GET['date']);
            $to=strtotime('+1 day',$from);
            $to=strtotime('-1 second',$to);
            //echo $general->make_date($from,'time');echo'<br>'; 
            //echo $general->make_date($to,'time');echo'<br>'; 
            $q[]="c.replyTime between ".$from." and ".$to;
            $sq="where ".implode(" and ",$q);

            $query  = "
            select 
            c.sender_id,c.post_id as comment_id,c.created_time,c.wuID,c.replyBy,c.message
            from ".$general->table($tbl)." c 
            ".$sq."
            order by c.sender_id,c.created_time desc";
            $all_data=$db->fetchQuery($query);
            //                exit;
            $senders=array();
            //$general->printArray($all_data);

            if(!empty($all_data)){
                $general->arrayIndexChange($all_data,'comment_id');
                foreach($all_data as $a){
                    $senders[$a['sender_id']]=$a['sender_id'];
                    $fcrData[$from][$a['sender_id']][$a['wuID']][]=$a['comment_id'];
                }

            }
            $query  = "
            select 
            c.sender_id,c.comment_id,c.created_time,c.wuID,c.replyBy,c.message
            from ".$general->table($cTbl)." c 
            ".$sq."
            order by c.sender_id,c.created_time desc";
            $all_data=$db->fetchQuery($query);

            //$general->printArray($all_data);
            if(!empty($all_data)){
                $general->arrayIndexChange($all_data,'comment_id');
                foreach($all_data as $a){
                    $senders[$a['sender_id']]=$a['sender_id'];
                    $fcrData[$from][$a['sender_id']][$a['wuID']][]=$a['comment_id'];
                }
            }
            if(!empty($senders)){
                $senderNames=$social->getNamesByUserId($senders);
                $general->arrayIndexChange($senderNames,'id');
            }
            $fcr=array();
            $nfcr=array();
            //$general->printArray($fcrData);
            if(!empty($fcrData)){
                foreach($fcrData as $from=>$f){
                    foreach($f as $sender=>$s){
                        foreach($s as $wrapup=>$w){
                            foreach($w as $ll){
                                if(count($w)==1){
                                    $fcr[]=$ll;
                                }
                                else{
                                    $nfcr[]=$ll;
                                }   
                            }
                        }
                    }
                }
            }
            $i=1;
            $reporData=array(
                'name'=>'Contributor_for_'.strtoupper($rp).'_'.date('d_m_Y',$from),
                'title'=>array(
                    array('title'=>"SL"                 ,'key'=>'s'     ,'w'=>10    ,'hw'=> 3),
                    array('title'=>"Date"               ,'key'=>'d'     ,'w'=>25    ,'hw'=> 6),
                    array('title'=>"Time"               ,'key'=>'t'     ,'w'=>15    ,'hw'=> 8),
                    array('title'=>"Customer Account ID",'key'=>'ca'    ,'w'=>15    ,'hw'=> 10),
                    array('title'=>"Contact Count"      ,'key'=>'cc'    ,'w'=>15),
                    array('title'=>"Customer Post"      ,'key'=>'cp'    ,'w'=>15    ,'hw'=> 20),
                    array('title'=>"Agent Response"     ,'key'=>'ar'    ,'w'=>15    ,'hw'=> 20),
                    array('title'=>"Agent Name"         ,'key'=>'a'     ,'w'=>15    ,'hw'=> 10),
                    array('title'=>"Wrap Up Code"       ,'key'=>'w'     ,'w'=>15    ,'hw'=> 10),
                )
            );

            $wrapups=$db->selectAll($general->table(11));
            $general->arrayIndexChange($wrapups,'wuID');
            $users=$db->allUsers();
            if($rp=='fcr'){
                $nd=$fcr;
            }
            else{
                $nd=$nfcr;
            }
            foreach($nd as $w){
                $reply=$db->get_rowData($general->table($cTbl),'target_c_id',$w);

                $reporData['data'][]=array(
                    's'     => $i++,
                    'd'     => date('d-m-y',$all_data[$w]['created_time']),
                    't'     => date('h:i:s A',$all_data[$w]['created_time']),
                    'ca'    => $senderNames[$all_data[$w]['sender_id']]['name'],
                    'cc'    => 1,
                    'cp'    => $all_data[$w]['message'],
                    'ar'    => $reply['message'],
                    'a'     => $all_data[$w]['replyBy'],
                    'w'     => $wrapups[$all_data[$w]['wuID']]['wuTitle']
                );
            }
            //                $general->printArray($reporData);
        ?>
        <div class="x_content">
            <div class="col-md-12 col-sm-12 col-xs-12">
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
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2><a href="<?php echo $pUrl;?>&type=<?php echo $type;?>"><?php echo $rModule['cmTitle'];?></a></h2>
                            <div class="clearfix"></div>
                        </div>

                        <h4>Date: <?php echo $_GET['date'];?> <?php echo strtoupper($rp);?> Report Details</h4>
                        <a style="display: none;" id="replyExport" class="btn btn-default">Export</a>
                        <?php
                            $sReport->arrayReportTable($reporData);

                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
        }
        else{
            echo '<h1>Under Construction</h1>';
        }
    }
    else{
        if($rp=='nfcr'){
            if($type=='c'){
                $from=strtotime($_GET['date']);
                $to=strtotime('+1 day',$from);
                $to=strtotime('-1 second',$to);
                $q[]="c.replyTime between ".$from." and ".$to;
                $sq="where ".implode(" and ",$q);
                $query  = "
                select 
                c.sender_id,c.comment_id,c.created_time,c.wuID,c.replyBy
                from ".$general->table($tbl)." c 
                ".$sq."
                order by c.created_time desc";
                $all_data=$db->fetchQuery($query);
                //$general->printArray($all_data);
                if(!empty($all_data)){
                    foreach($all_data as $a){
                        if(isset($fcrData[$from][$a['sender_id']][$a['wuID']][$a['replyBy']])){
                            $fcrData[$from][$a['sender_id']][$a['wuID']][$a['replyBy']]+=1;
                        }
                        else{
                            $fcrData[$from][$a['sender_id']][$a['wuID']][$a['replyBy']]=1;
                        }
                    }
                }
                $fcr=array();
                $nfcr=array();
                //$general->printArray($fcrData);
                if(!empty($fcrData)){
                    foreach($fcrData as $from=>$f){
                        foreach($f as $sender=>$s){
                            foreach($s as $wrapup=>$w){
                                if(!isset($fcr[$from][$wrapup])){$fcr[$from][$wrapup]=0;}
                                if(!isset($nfcr[$from][$wrapup])){$nfcr[$from][$wrapup]=0;}
                                $length=count($w);
                                $run=1;
                                foreach($w as $replyBy=>$c){
                                    //if($length==1&&$c==1){// এই র‍্যাপআপে এই ইউজার একটা কমেন্ট করেছে
                                    if($length==1&&$c<=2){// এই র‍্যাপআপে এই ইউজার একটা কমেন্ট করেছে
                                        $fcr[$from][$wrapup]+=1;
                                    }
                                    else{
                                        $nfcr[$from][$wrapup]+=$c;
                                    }
                                    $sTotal+=$c;
                                    $run++;
                                }
                            }
                        }
                    }
                }
                $i=1;

                $reporData=array(
                    'name'=>'Contributor_for_NON_FCR'.date('d_m_Y',$from),
                    'title'=>array(
                        array('title'=>"SL"             ,'key'=>'s'     ,'w'=>10),
                        array('title'=>"Wrapup Category",'key'=>'wc'    ,'w'=>25),
                        array('title'=>"Wrapup"         ,'key'=>'w'     ,'w'=>25),
                        array('title'=>"NON FCR"        ,'key'=>'nfcr'  ,'w'=>15,),
                        array('title'=>"NON FCR %"      ,'key'=>'nfcrp' ,'w'=>15),
                    )
                );

                $wrapups=$db->selectAll($general->table(11));$general->arrayIndexChange($wrapups,'wuID');
                $wrapupCat=$db->selectAll($general->table(34),'order by wcTitle asc');$general->arrayIndexChange($wrapupCat,'wcID');
                foreach($fcr as $from=>$w){
                    foreach($w as $wrapup=>$f){
                        $t=$nfcr[$from][$wrapup]+$f;
                        $reporData['data'][]=array(
                            's'     => $i++,
                            'w'     => $wrapups[$wrapup]['wuTitle'],
                            'wc'    => $wrapupCat[$wrapups[$wrapup]['wcID']]['wcTitle'],
                            'nfcr'  => $nfcr[$from][$wrapup],
                            'nfcrp' => round($general->percentageFrom($t,$nfcr[$from][$wrapup]),2)
                        );
                    }
                }
                //$general->printArray($reporData);
            ?>
            <div class="x_content">
                <div class="col-md-12 col-sm-12 col-xs-12">
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
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2><a href="<?php echo $pUrl;?>"><?php echo $rModule['cmTitle'];?></a></h2>
                                <div class="clearfix"></div>
                            </div>

                            <h4>Date: <?php echo $_GET['date'];?></h4>
                            <a style="display: none;" id="replyExport" class="btn btn-default">Export</a>
                            <?php
                                $sReport->arrayReportTable($reporData);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            }
            elseif($type=='w'){
                $from=strtotime($_GET['date']);
                $to=strtotime('+1 day',$from);
                $to=strtotime('-1 second',$to);
                $q[]="c.replyTime between ".$from." and ".$to;
                $sq="where ".implode(" and ",$q);
                $query  = "
                select 
                c.sender_id,c.post_id as comment_id,c.created_time,c.wuID,c.replyBy
                from ".$general->table($tbl)." c 
                ".$sq."
                order by c.created_time desc";
                $all_data=$db->fetchQuery($query);
                //$general->printArray($all_data);
                if(!empty($all_data)){
                    foreach($all_data as $a){
                        if(isset($fcrData[$from][$a['sender_id']][$a['wuID']][$a['replyBy']])){
                            $fcrData[$from][$a['sender_id']][$a['wuID']][$a['replyBy']]+=1;
                        }
                        else{
                            $fcrData[$from][$a['sender_id']][$a['wuID']][$a['replyBy']]=1;
                        }
                    }
                }
                $query  = "
                select 
                c.sender_id,c.comment_id,c.created_time,c.wuID,c.replyBy
                from ".$general->table($cTbl)." c 
                ".$sq."
                order by c.created_time desc";
                $all_data=$db->fetchQuery($query);
                //$general->printArray($all_data);
                if(!empty($all_data)){
                    foreach($all_data as $a){
                        if(isset($fcrData[$from][$a['sender_id']][$a['wuID']][$a['replyBy']])){
                            $fcrData[$from][$a['sender_id']][$a['wuID']][$a['replyBy']]+=1;
                        }
                        else{
                            $fcrData[$from][$a['sender_id']][$a['wuID']][$a['replyBy']]=1;
                        }
                    }
                }
                $fcr=array();
                $nfcr=array();
                //$general->printArray($fcrData);
                if(!empty($fcrData)){
                    foreach($fcrData as $from=>$f){
                        foreach($f as $sender=>$s){
                            foreach($s as $wrapup=>$w){
                                if(!isset($fcr[$from][$wrapup])){$fcr[$from][$wrapup]=0;}
                                if(!isset($nfcr[$from][$wrapup])){$nfcr[$from][$wrapup]=0;}
                                $length=count($w);
                                $run=1;
                                foreach($w as $replyBy=>$c){
                                    if($length==1&&$c==1){// এই র‍্যাপআপে এই ইউজার একটা কমেন্ট করেছে
//                                    if($length==1&&$c<=2){// এই র‍্যাপআপে এই ইউজার একটা কমেন্ট করেছে
                                        $fcr[$from][$wrapup]+=1;
                                    }
                                    else{
                                        $nfcr[$from][$wrapup]+=$c;
                                    }
                                    $sTotal+=$c;
                                    $run++;
                                }
                            }
                        }
                    }
                }
                $i=1;

                $reporData=array(
                    'name'=>'Contributor_for_NON_FCR'.date('d_m_Y',$from),
                    'title'=>array(
                        array('title'=>"SL"             ,'key'=>'s'     ,'w'=>10),
                        array('title'=>"Wrapup Category",'key'=>'wc'    ,'w'=>25),
                        array('title'=>"Wrapup"         ,'key'=>'w'     ,'w'=>25),
                        array('title'=>"NON FCR"        ,'key'=>'nfcr'  ,'w'=>15,),
                        array('title'=>"NON FCR %"      ,'key'=>'nfcrp' ,'w'=>15),
                    )
                );

                $wrapups=$db->selectAll($general->table(11));$general->arrayIndexChange($wrapups,'wuID');
                $wrapupCat=$db->selectAll($general->table(34),'order by wcTitle asc');$general->arrayIndexChange($wrapupCat,'wcID');
                foreach($fcr as $from=>$w){
                    foreach($w as $wrapup=>$f){
                        $t=$nfcr[$from][$wrapup]+$f;
                        $reporData['data'][]=array(
                            's'     => $i++,
                            'w'     => $wrapups[$wrapup]['wuTitle'],
                            'wc'    => $wrapupCat[$wrapups[$wrapup]['wcID']]['wcTitle'],
                            'nfcr'  => $nfcr[$from][$wrapup],
                            'nfcrp' => round($general->percentageFrom($t,$nfcr[$from][$wrapup]),2)
                        );
                    }
                }
                //$general->printArray($reporData);
            ?>
            <div class="x_content">
                <div class="col-md-12 col-sm-12 col-xs-12">
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
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2><a href="<?php echo $pUrl;?>"><?php echo $rModule['cmTitle'];?></a></h2>
                                <div class="clearfix"></div>
                            </div>

                            <h4>Date: <?php echo $_GET['date'];?></h4>
                            <a style="display: none;" id="replyExport" class="btn btn-default">Export</a>
                            <?php
                                $sReport->arrayReportTable($reporData);
                                /*
                                ?>
                                <script type="text/javascript">
                                var reportHead={
                                name:'Contributor_for_FCR<?php echo date('d_m_Y',$from);?>',
                                title:[
                                {title:"SL",key:'s',w:10},
                                {title:"Wrapup",key:'d',w:15},
                                {title:"FCR",key:'fcr',w:15},
                                {title:"FCR %",key:'fcrp',w:15},
                                ],
                                data:[]
                                };
                                </script>
                                <table class="table table-striped table-bordered fixtWidthReport">
                                <tr>
                                <td style="width: 5%;">SL</td>
                                <td>Total</td>
                                <td>FCR</td>
                                <td>FCR %</td>
                                </tr>
                                <?php
                                $i=1;
                                foreach($reporData as $r){
                                ?>
                                <tr>
                                <td><?php echo $r['s'];?></td>
                                <td><?php echo $r['w'];?></td>
                                <td><?php echo $r['fcr'];?></td>
                                <td><?php echo $r['fcrp'];?></td>
                                </tr>
                                <?php
                                }
                                ?>
                                </table>
                                <script>
                                <?php echo 'reportHead.data='.json_encode($reporData).';';?> 
                                $(document).ready(function(){
                                $('#replyExport').show();
                                $("#replyExport").click(function(){
                                reportJsonToExcel(reportHead); 
                                });
                                });
                                </script>
                                <?php
                                */
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            }
            else{
                echo '<h1>Under Construction</h1>';
            }
        }
        elseif($rp=='fcr'){
            if($type=='c'){
                $from=strtotime($_GET['date']);
                $to=strtotime('+1 day',$from);
                $to=strtotime('-1 second',$to);
                //echo $general->make_date($from,'time');echo'<br>'; 
                //echo $general->make_date($to,'time');echo'<br>'; 
                $q[]="c.replyTime between ".$from." and ".$to;
                $sq="where ".implode(" and ",$q);

                if(isset($_GET['details'])){
                    /*$query  = "
                    select 
                    c.sender_id,c.comment_id,c.created_time,c.wuID,c.replyBy,c.message,r.message as replyMessage
                    from ".$general->table($tbl)." c 
                    left join ".$general->table($tbl)." r on r.target_c_id=c.comment_id
                    ".$sq."
                    order by c.sender_id,c.created_time desc";*/
                    $query  = "
                    select 
                    c.sender_id,c.comment_id,c.created_time,c.wuID,c.replyBy,c.message
                    from ".$general->table($tbl)." c 
                    ".$sq."
                    order by c.sender_id,c.created_time desc";
                    $all_data=$db->fetchQuery($query);
                    //                exit;
                    $senders=array();
                    //$general->printArray($all_data);
                    if(!empty($all_data)){
                        $general->arrayIndexChange($all_data,'comment_id');
                        foreach($all_data as $a){
                            $senders[$a['sender_id']]=$a['sender_id'];
                            $fcrData[$from][$a['sender_id']][$a['wuID']][]=$a['comment_id'];
                        }
                        $senderNames=$social->getNamesByUserId($senders);
                        $general->arrayIndexChange($senderNames,'id');
                    }
                    $fcr=array();
                    $nfcr=array();
                    //$general->printArray($fcrData);
                    if(!empty($fcrData)){
                        foreach($fcrData as $from=>$f){
                            foreach($f as $sender=>$s){
                                foreach($s as $wrapup=>$w){
                                    foreach($w as $ll){
                                        if(count($w)==1){
                                            $fcr[]=$ll;
                                        }
                                        else{
                                            $nfcr[]=$ll;
                                        }   
                                    }
                                }
                            }
                        }
                    }
                    $i=1;

                    $reporData=array(
                        'name'=>'Contributor_for_FCR'.date('d_m_Y',$from),
                        'title'=>array(
                            array('title'=>"SL"                 ,'key'=>'s'     ,'w'=>10    ,'hw'=> 3),
                            array('title'=>"Date"               ,'key'=>'d'     ,'w'=>25    ,'hw'=> 6),
                            array('title'=>"Time"               ,'key'=>'t'     ,'w'=>15    ,'hw'=> 10),
                            array('title'=>"Customer Account ID",'key'=>'ca'    ,'w'=>15    ,'hw'=> 10),
                            array('title'=>"Contact Count"      ,'key'=>'cc'    ,'w'=>15),
                            array('title'=>"Customer Post"      ,'key'=>'cp'    ,'w'=>15    ,'hw'=> 20),
                            array('title'=>"Agent Response"     ,'key'=>'ar'    ,'w'=>15    ,'hw'=> 20),
                            array('title'=>"Agent Name"         ,'key'=>'a'     ,'w'=>15    ,'hw'=> 10),
                            array('title'=>"Wrap Up Code"       ,'key'=>'w'     ,'w'=>15    ,'hw'=> 10),
                        )
                    );

                    $wrapups=$db->selectAll($general->table(11));
                    $general->arrayIndexChange($wrapups,'wuID');
                    $users=$db->allUsers();
                    foreach($fcr as $w){
                        $reply=$db->get_rowData($general->table(13),'target_c_id',$w);

                        $reporData['data'][]=array(
                            's'     => $i++,
                            'd'     => date('d-m-y',$from),
                            't'     => date('h:i:s A',$from),
                            'ca'    => $senderNames[$all_data[$w]['sender_id']]['name'],
                            'cc'    => 1,
                            'cp'    => $all_data[$w]['message'],
                            'ar'    => $reply['message'],
                            'a'     => 'w',
                            'w'     => $wrapups[$all_data[$w]['wuID']]['wuTitle']
                        );
                    }
                    //                $general->printArray($reporData);
                ?>
                <div class="x_content">
                    <div class="col-md-12 col-sm-12 col-xs-12">
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
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2><a href="<?php echo $pUrl;?>"><?php echo $rModule['cmTitle'];?></a></h2>
                                    <div class="clearfix"></div>
                                </div>

                                <h4>Date: <?php echo $_GET['date'];?></h4>
                                <a style="display: none;" id="replyExport" class="btn btn-default">Export</a>
                                <?php
                                    $sReport->arrayReportTable($reporData);

                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                }
                else{
                    $query  = "
                    select 
                    c.sender_id,c.comment_id,c.created_time,c.wuID,c.replyBy
                    from ".$general->table($tbl)." c 
                    ".$sq."
                    order by c.created_time desc";
                    $all_data=$db->fetchQuery($query);
                    //$general->printArray($all_data);
                    if(!empty($all_data)){
                        foreach($all_data as $a){
                            if(isset($fcrData[$from][$a['sender_id']][$a['wuID']][$a['replyBy']])){
                                $fcrData[$from][$a['sender_id']][$a['wuID']][$a['replyBy']]+=1;
                            }
                            else{
                                $fcrData[$from][$a['sender_id']][$a['wuID']][$a['replyBy']]=1;
                            }
                        }
                    }
                    $fcr=array();
                    $nfcr=array();
                    //$general->printArray($fcrData);
                    if(!empty($fcrData)){
                        foreach($fcrData as $from=>$f){
                            foreach($f as $sender=>$s){
                                foreach($s as $wrapup=>$w){
                                    if(!isset($fcr[$from][$wrapup])){$fcr[$from][$wrapup]=0;}
                                    if(!isset($nfcr[$from][$wrapup])){$nfcr[$from][$wrapup]=0;}
                                    $length=count($w);
                                    $run=1;
                                    foreach($w as $replyBy=>$c){
                                        //if($length==1&&$c==1){// এই র‍্যাপআপে এই ইউজার একটা কমেন্ট করেছে
                                        if($length==1&&$c<=2){// এই র‍্যাপআপে এই ইউজার একটা কমেন্ট করেছে
                                            $fcr[$from][$wrapup]+=1;
                                        }
                                        else{// NON FCR মাল্টি কমেন্ট করেছে
                                            $nfcr[$from][$wrapup]+=$c;
                                        }
                                        $sTotal+=$c;
                                        $run++;
                                    }
                                }
                            }
                        }
                    }
                    $i=1;

                    $reporData=array(
                        'name'=>'Contributor_for_FCR'.date('d_m_Y',$from),
                        'title'=>array(
                            array('title'=>"SL"             ,'key'=>'s'     ,'w'=>10),
                            array('title'=>"Wrapup Category",'key'=>'wc'    ,'w'=>25),
                            array('title'=>"Wrapup"         ,'key'=>'w'     ,'w'=>25),
                            array('title'=>"FCR"            ,'key'=>'fcr'   ,'w'=>15,),
                            array('title'=>"FCR %"          ,'key'=>'fcrp'  ,'w'=>15),
                        )
                    );

                    $wrapups=$db->selectAll($general->table(11));$general->arrayIndexChange($wrapups,'wuID');
                    $wrapupCat=$db->selectAll($general->table(34),'order by wcTitle asc');$general->arrayIndexChange($wrapupCat,'wcID');
                    foreach($fcr as $from=>$w){
                        foreach($w as $wrapup=>$f){
                            $t=$nfcr[$from][$wrapup]+$f;
                            $reporData['data'][]=array(
                                's'     => $i++,
                                'wc'    => $wrapupCat[$wrapups[$wrapup]['wcID']]['wcTitle'],
                                'w'     => $wrapups[$wrapup]['wuTitle'],
                                'fcr'   => $f,
                                'fcrp'  => round($general->percentageFrom($t,$f),2)
                            );
                        }
                    }
                    //$general->printArray($reporData);
                ?>
                <div class="x_content">
                    <div class="col-md-12 col-sm-12 col-xs-12">
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
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2><a href="<?php echo $pUrl;?>"><?php echo $rModule['cmTitle'];?></a></h2>
                                    <div class="clearfix"></div>
                                </div>

                                <h4>Date: <?php echo $_GET['date'];?></h4>
                                <a style="display: none;" id="replyExport" class="btn btn-default">Export</a>
                                <?php
                                    $sReport->arrayReportTable($reporData);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                }
            }
            elseif($type=='w'){
                $from=strtotime($_GET['date']);
                $to=strtotime('+1 day',$from);
                $to=strtotime('-1 second',$to);
                //echo $general->make_date($from,'time');echo'<br>'; 
                //echo $general->make_date($to,'time');echo'<br>'; 
                $q[]="c.replyTime between ".$from." and ".$to;
                $sq="where ".implode(" and ",$q);
                $query  = "
                select 
                c.sender_id,c.post_id as comment_id,c.created_time,c.wuID,c.replyBy
                from ".$general->table($tbl)." c 
                ".$sq."
                order by c.created_time desc";
                $all_data=$db->fetchQuery($query);
                //$general->printArray($all_data);
                if(!empty($all_data)){
                    foreach($all_data as $a){
                        if(isset($fcrData[$from][$a['sender_id']][$a['wuID']][$a['replyBy']])){
                            $fcrData[$from][$a['sender_id']][$a['wuID']][$a['replyBy']]+=1;
                        }
                        else{
                            $fcrData[$from][$a['sender_id']][$a['wuID']][$a['replyBy']]=1;
                        }
                    }
                }
                $query  = "
                select 
                c.sender_id,c.comment_id,c.created_time,c.wuID,c.replyBy
                from ".$general->table($cTbl)." c 
                ".$sq."
                order by c.created_time desc";
                $all_data=$db->fetchQuery($query);
                //$general->printArray($all_data);
                if(!empty($all_data)){
                    foreach($all_data as $a){
                        if(isset($fcrData[$from][$a['sender_id']][$a['wuID']][$a['replyBy']])){
                            $fcrData[$from][$a['sender_id']][$a['wuID']][$a['replyBy']]+=1;
                        }
                        else{
                            $fcrData[$from][$a['sender_id']][$a['wuID']][$a['replyBy']]=1;
                        }
                    }
                }
                $fcr=array();
                $nfcr=array();
                //$general->printArray($fcrData);
                if(!empty($fcrData)){
                    foreach($fcrData as $from=>$f){
                        foreach($f as $sender=>$s){
                            foreach($s as $wrapup=>$w){
                                if(!isset($fcr[$from][$wrapup])){$fcr[$from][$wrapup]=0;}
                                if(!isset($nfcr[$from][$wrapup])){$nfcr[$from][$wrapup]=0;}
                                $length=count($w);
                                $run=1;
                                foreach($w as $replyBy=>$c){
                                    if($length==1&&$c==1){// এই র‍্যাপআপে এই ইউজার একটা কমেন্ট করেছে
//                                    if($length==1&&$c<=2){// এই র‍্যাপআপে এই ইউজার একটা কমেন্ট করেছে
                                        $fcr[$from][$wrapup]+=1;
                                    }
                                    else{// NON FCR মাল্টি কমেন্ট করেছে
                                        $nfcr[$from][$wrapup]+=$c;
                                    }
                                    $sTotal+=$c;
                                    $run++;
                                }
                            }
                        }
                    }
                }
                $i=1;

                $reporData=array(
                    'name'=>'Contributor_for_FCR'.date('d_m_Y',$from),
                    'title'=>array(
                        array('title'=>"SL"             ,'key'=>'s'     ,'w'=>10),
                        array('title'=>"Wrapup Category",'key'=>'wc'    ,'w'=>25),
                        array('title'=>"Wrapup"         ,'key'=>'w'     ,'w'=>25),
                        array('title'=>"FCR"            ,'key'=>'fcr'   ,'w'=>15,),
                        array('title'=>"FCR %"          ,'key'=>'fcrp'  ,'w'=>15),
                    )
                );

                $wrapups=$db->selectAll($general->table(11));$general->arrayIndexChange($wrapups,'wuID');
                $wrapupCat=$db->selectAll($general->table(34),'order by wcTitle asc');$general->arrayIndexChange($wrapupCat,'wcID');
                foreach($fcr as $from=>$w){
                    foreach($w as $wrapup=>$f){
                        $t=$nfcr[$from][$wrapup]+$f;
                        $reporData['data'][]=array(
                            's'     => $i++,
                            'wc'    => $wrapupCat[$wrapups[$wrapup]['wcID']]['wcTitle'],
                            'w'     => $wrapups[$wrapup]['wuTitle'],
                            'fcr'   => $f,
                            'fcrp'  => round($general->percentageFrom($t,$f),2)
                        );
                    }
                }
                //$general->printArray($reporData);
            ?>
            <div class="x_content">
                <div class="col-md-12 col-sm-12 col-xs-12">
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
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2><a href="<?php echo $pUrl;?>"><?php echo $rModule['cmTitle'];?></a></h2>
                                <div class="clearfix"></div>
                            </div>

                            <h4>Date: <?php echo $_GET['date'];?></h4>
                            <a style="display: none;" id="replyExport" class="btn btn-default">Export</a>
                            <?php
                                $sReport->arrayReportTable($reporData);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                /*}*/
            }
            else{
                echo '<h1>Under Construction</h1>';
            }
        }
        else{
            $wrapups=$db->selectAll($general->table(11),'order by wuTitle asc');
            $wrapupCat=$db->selectAll($general->table(34),'order by wcTitle asc');
            $general->arrayIndexChange($wrapups,'wuID');
            $users=$db->allUsers('order by uFullName asc');
            $general->arrayIndexChange($users,'uID');
            if(isset($_GET['date_range'])){
                $date_range=$_GET['date_range'];
                if(intval($_GET['wr'])!=0){
                    $q[]="c.wuID =".intval($_GET['wr']);
                }
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
            $uID=0;
            if(isset($_GET['ag'])){
                $uID    = intval($_GET['ag']);
            }
            $ugID=0;
            if(isset($_GET['ug'])){
                $ugID    = intval($_GET['ug']);
            }
//            echo $general->make_date($from_date,'time');echo'<br>'; 
//            echo $general->make_date($to_date,'time');echo'<br>'; 
            $dateRangeVal=date('d-m-Y h:i A',$from_date).'__'.date('d-m-Y h:i A',$to_date);
            $dateRangeShow=date('d-m-Y h:i A',$from_date).' - '.date('d-m-Y h:i A',$to_date);
            $link=$pUrl.'&type='.$type.'&date_range='.urldecode($date_range).'&&show=Show';
            if($uID!=0){
                $link.='&ag='.$uID;
            }
            $link.='&'.PAGE_INATION_CURRENT_PAGE_NAME.'=';
            $vq=$q;
            $sq="where ".implode(" and ",$q);
            $fcrData=array();
            if($type=='c'){
                $from=$from_date;
                while($from<$to_date){
                    $to=strtotime('+1 day',$from);
                    $nextFrom=$to;
                    $to=strtotime('-1 second',$to);
                    $nq=$vq;
                    $nq[]="c.replyTime between ".$from." and ".$to;
                    $sq="where ".implode(" and ",$nq);
                    $query  = "
                    select 
                    c.sender_id,c.comment_id,c.created_time,c.wuID,c.replyBy
                    from ".$general->table($tbl)." c 
                    ".$sq."
                    order by c.created_time desc";
                    //var_dump($general->showQuery());
                    $all_data=$db->fetchQuery($query,$general->showQuery());
                    //echo count($all_data);echo'<br>'; 
                    if(!empty($all_data)){
                        foreach($all_data as $a){
                            if(isset($fcrData[$from][$a['sender_id']][$a['wuID']][$a['replyBy']])){
                                $fcrData[$from][$a['sender_id']][$a['wuID']][$a['replyBy']]+=1;
                            }
                            else{
                                $fcrData[$from][$a['sender_id']][$a['wuID']][$a['replyBy']]=1;
                            }
                        }
                    }
                    $from=$nextFrom;

                }
                $fcr=array();
                $nfcr=array();
                $unique=array();
                $nUnique=array();
                if(!empty($fcrData)){
                    foreach($fcrData as $from=>$f){
                        $fcr[$from]=0;
                        $nfcr[$from]=0;
                        $unique[$from]=0;
                        $nUnique[$from]=0;
                        foreach($f as $sender=>$s){
                            $sTotal=0;
                            foreach($s as $wrapup=>$w){
                                $length=count($w);
                                $run=1;
                                foreach($w as $replyBy=>$c){
//                                    if($length==1&&$c==1){// এই র‍্যাপআপে এই ইউজার একটা কমেন্ট করেছে
                                    if($length==1&&$c<=2){// এই র‍্যাপআপে এই ইউজার একটা কমেন্ট করেছে
                                        $fcr[$from]+=1;
                                    }
                                    else{
                                        $nfcr[$from]+=$c;

                                    }
                                    $sTotal+=$c;
                                    $run++;
                                }
                            }
                            if($sTotal==1){
                                $unique[$from]+=1;
                            }
                            else{
                                $nUnique[$from]+=$sTotal;
                            }
                        }
                    }
                }
                //echo 'FCR=';$general->printArray($fcr);echo'<br>'; 
                //echo 'NFCR=';$general->printArray($nfcr);echo'<br>'; 
                $i=1;
                $reporData=array(
                    'name'=>'FCR_Report',
                    'title'=>array(
                        array('title'=>"SL"            ,'key'=>'s'      ,'w'=>10),
                        array('title'=>"Date"          ,'key'=>'d'      ,'w'=>15),
                        array('title'=>"TOTAL"         ,'key'=>'total'  ,'w'=>15),
                        array('title'=>"Unique Count"  ,'key'=>'u'      ,'w'=>15),
                        array('title'=>"Repeat Count"  ,'key'=>'nu'     ,'w'=>15),
                        array('title'=>"FCR"           ,'key'=>'fcr'    ,'w'=>15,),
                        array('title'=>"FCR %"         ,'key'=>'fcrp'   ,'w'=>15),
                        array('title'=>"NON FCR"       ,'key'=>'nfcr'   ,'w'=>15),
                        array('title'=>"NON FCR %"     ,'key'=>'nfcrp'  ,'w'=>15),
                    )
                );
                foreach($fcr as $from=>$f){
                    $t=$nfcr[$from]+$f;
                    $reporData['data'][$from]=array(
                        's'     => $i++,
                        'd'     => date('d-m-Y',$from),
                        'u'     => $unique[$from],
                        'nu'    => $nUnique[$from],
                        'fcr'   => $f,
                        'fcrp'  => round($general->percentageFrom($t,$f),2),
                        'nfcr'  => $nfcr[$from],
                        'nfcrp' => round($general->percentageFrom($t,$nfcr[$from]),2),
                        'total' => $t
                    );
                }
            }
            elseif($type=='w'){
                $from=$from_date;
                while($from<$to_date){
                    $to=strtotime('+1 day',$from);
                    $nextFrom=$to;
                    $to=strtotime('-1 second',$to);
                    $nq=$vq;
                    $nq[]="c.replyTime between ".$from." and ".$to;
                    $sq="where ".implode(" and ",$nq);
                    $query = "
                    select 
                    c.sender_id,c.post_id as comment_id,c.created_time,c.wuID,c.replyBy
                    from ".$general->table($tbl)." c 
                    ".$sq;
                    $all_data=$db->fetchQuery($query,$general->showQuery());
                    //echo count($all_data);echo'<br>'; 
                    if(!empty($all_data)){
                        foreach($all_data as $a){
                            if(isset($fcrData[$from][$a['sender_id']][$a['wuID']][$a['replyBy']])){
                                $fcrData[$from][$a['sender_id']][$a['wuID']][$a['replyBy']]+=1;
                            }
                            else{
                                $fcrData[$from][$a['sender_id']][$a['wuID']][$a['replyBy']]=1;
                            }
                        }
                    }
                    //$general->printArray($fcrData);
                    $query = "
                    select 
                    c.sender_id,c.comment_id,c.created_time,c.wuID,c.replyBy
                    from ".$general->table($db->settingsValue('commentWallReportTable'))." c 
                    ".$sq;
                    $all_data=$db->fetchQuery($query,$general->showQuery());
                    //echo count($all_data);echo'<br>'; 
                    if(!empty($all_data)){
                        foreach($all_data as $a){
                            if(isset($fcrData[$from][$a['sender_id']][$a['wuID']][$a['replyBy']])){
                                $fcrData[$from][$a['sender_id']][$a['wuID']][$a['replyBy']]+=1;
                            }
                            else{
                                $fcrData[$from][$a['sender_id']][$a['wuID']][$a['replyBy']]=1;
                            }
                        }
                    }
                    //$general->printArray($fcrData);
                    $from=$nextFrom;

                }
                $fcr=array();
                $nfcr=array();
                $unique=array();
                $nUnique=array();
                if(!empty($fcrData)){
                    foreach($fcrData as $from=>$f){
                        $fcr[$from]=0;
                        $nfcr[$from]=0;
                        $unique[$from]=0;
                        $nUnique[$from]=0;
                        foreach($f as $sender=>$s){
                            $sTotal=0;
                            foreach($s as $wrapup=>$w){
                                $length=count($w);
                                $run=1;
                                //$general->printArray($w);
                                foreach($w as $replyBy=>$c){
                                    if($length==1&&$c==1){// এই র‍্যাপআপে এই ইউজার একটা কমেন্ট করেছে
//                                    if($length==1&&$c<=2){// এই র‍্যাপআপে এই ইউজার একটা কমেন্ট করেছে
                                        $fcr[$from]+=1;
                                    }
                                    else{
                                        $nfcr[$from]+=$c;

                                    }
                                    $sTotal+=$c;
                                    $run++;
                                }
                            }
                            if($sTotal==1){
                                $unique[$from]+=1;
                            }
                            else{
                                $nUnique[$from]+=$sTotal;
                            }
                        }
                    }
                }
                //echo 'FCR=';$general->printArray($fcr);echo'<br>'; 
                //echo 'NFCR=';$general->printArray($nfcr);echo'<br>'; 
                $i=1;
                $reporData=array(
                    'name'=>'FCR_Report',
                    'title'=>array(
                        array('title'=>"SL"            ,'key'=>'s'      ,'w'=>10),
                        array('title'=>"Date"          ,'key'=>'d'      ,'w'=>15),
                        array('title'=>"TOTAL"         ,'key'=>'total'  ,'w'=>15),
                        array('title'=>"Unique Count"  ,'key'=>'u'      ,'w'=>15),
                        array('title'=>"Repeat Count"  ,'key'=>'nu'     ,'w'=>15),
                        array('title'=>"FCR"           ,'key'=>'fcr'    ,'w'=>15,),
                        array('title'=>"FCR %"         ,'key'=>'fcrp'   ,'w'=>15),
                        array('title'=>"NON FCR"       ,'key'=>'nfcr'   ,'w'=>15),
                        array('title'=>"NON FCR %"     ,'key'=>'nfcrp'  ,'w'=>15),
                    )
                );
                foreach($fcr as $from=>$f){
                    $t=$nfcr[$from]+$f;
                    $reporData['data'][$from]=array(
                        's'     => $i++,
                        'd'     => date('d-m-Y',$from),
                        'u'     => $unique[$from],
                        'nu'    => $nUnique[$from],
                        'fcr'   => $f,
                        'fcrp'  => round($general->percentageFrom($t,$f),2),
                        'nfcr'  => $nfcr[$from],
                        'nfcrp' => round($general->percentageFrom($t,$nfcr[$from]),2),
                        'total' => $t
                    );
                }
            }
            //$general->printArray($reporData);
        ?>
        <div class="x_content">
            <div class="col-md-12 col-sm-12 col-xs-12">
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
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2><?php echo $rModule['cmTitle'];?></h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a href="<?php echo $pUrl;?>&add=1"><i class="fa fa-plus"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
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

                            /*var reportHead={
                            name:'FCR_Report',
                            title:[
                            {title:"SL",key:'s',w:10},
                            {title:"Date",key:'d',w:15},
                            {title:"Unique Count",key:'u',w:15},
                            {title:"Repeat Count",key:'nu',w:15},
                            {title:"FCR",key:'fcr',w:15},
                            {title:"FCR %",key:'fcrp',w:15},
                            {title:"NON FCR",key:'nfcr',w:15},
                            {title:"NON FCR %",key:'nfcrp',w:15},
                            {title:"TOTAL",key:'total',w:15},
                            ],
                            data:[]
                            };*/
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
                                <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                    <span><?php echo $dateRangeShow;?></span> <b class="caret"></b>
                                </div>
                            </div>
                            <div class="form-group">
                                <select name="wr" class="form-control select2">
                                    <option value="">All Wrapup</option>
                                    <?php
                                        foreach($wrapupCat as $wc){
                                        ?><optgroup label="<?php echo $wc['wcTitle'];?>"><?php
                                            foreach($wrapups as $w){
                                                if($w['wcID']==$wc['wcID']){
                                                ?><option value="<?php echo $w['wuID'];?>" <?php echo $general->selected($w['wuID'],@$_GET['wr']);?>><?php echo $w['wuTitle'];?></option><?php
                                                }
                                            }
                                        ?></optgroup><?php
                                        }
                                    ?>
                                </select>
                            </div>
                            <input type="submit" value="Show" name="show" class="btn btn-success btn-sm">
                        </form>
                        <?php
                            if(isset($reporData['data'])){
                            ?>
                            <a style="display: none;" id="replyExport" class="btn btn-default">Export</a>
                            <table class="table table-striped table-bordered fixtWidthReport">
                                <tr>
                                    <?php
                                        foreach($reporData['title'] as $t){
                                        ?>
                                        <td><?php echo $t['title'];?></td>
                                        <?php
                                        }
                                    ?>
                                </tr>
                                <?php
                                    $i=1;
                                    foreach($reporData['data'] as $r){
                                    ?>
                                    <tr>
                                        <td><?php echo $r['s'];?></td>
                                        <td><?php echo $r['d'];?></td>
                                        <td><?php echo $r['total'];?></td>
                                        <td><?php echo $r['u'];?></td>
                                        <td><?php echo $r['nu'];?></td>
                                        <td><a href="<?php echo $pUrl;?>&rp=fcr&details=1&date=<?php echo $r['d'];?>&type=<?php echo $type;?>"><?php echo $r['fcr'];?></a></td>
                                        <td><a href="<?php echo $pUrl;?>&rp=fcr&date=<?php echo $r['d'];?>&type=<?php echo $type;?>"><?php echo $r['fcrp'];?></a></td>
                                        <td><a href="<?php echo $pUrl;?>&rp=nfcr&details=1&date=<?php echo $r['d'];?>&type=<?php echo $type;?>"><?php echo $r['nfcr'];?></a></td>
                                        <td><a href="<?php echo $pUrl;?>&rp=nfcr&date=<?php echo $r['d'];?>&type=<?php echo $type;?>"><?php echo $r['nfcrp'];?></a></td>
                                    </tr>
                                    <?php 
                                    }
                                ?>
                            </table>
                            <script>
                                <?php echo 'reportHead='.json_encode($reporData).';';?> 
                                $(document).ready(function(){
                                    $('#replyExport').show();
                                    $("#replyExport").click(function(){
                                        reportJsonToExcel(reportHead); 
                                    });
                                });
                            </script>
                            <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
        }
    }
?>