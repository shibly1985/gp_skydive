<?php

    /*if(class_exists('Memcache')) {
    $server = 'localhost';

    $memcache = new Memcache;
    $isMemcacheAvailable = @$memcache->connect($server);
    }*/

    $run=true;
    /*if($isMemcacheAvailable){
    if($memcache->get('dashboardGraph')){
    $jArray=$memcache->get('dashboardGraph');

    $jArray['mem']=__LINE__;
    $run=true;  
    }
    }*/

    if($run==true){
        $jArray['run']=__LINE__;
        $start  = strtotime($_POST['st']);
        $end    = strtotime($_POST['en']);
        $diff = $end-$start;
        //echo $diff;
        $df='D h A';
        $enc='1 hour';
        if($diff<=172800){//86400=48hours
            $r=3600;
        }
        elseif($diff<=604800){//604800=7 days
            $r=3600*4;
            $df="d-m \n  h A";
            $enc='4 hour';
        }
        else{
            $r=3600*24;
            $df='d-m-h';
            $enc='1 day';
        }
        $end    = strtotime('+1 day',$end);
        $end    = strtotime('-1 second',$end);

        $times=array(
            'st'=>$general->make_date($start,'time'),
            'en'=>$general->make_date($end,'time'),
        );
        $wrapusData=array(
            'status'=>array('title'=>'Comments'),
            'wall'=>array('title'=>'Wall Post'),
            'msg'=>array('title'=>'Message')
        );
        $dates = range($start, $end,$r);
        $from=$start;
        $summery=array(
            'adminPost'          => 0,
            'userActivity'       => 0,
            'adminActivity'      => 0,
            'commentHandleTime'  => 0,
            'commentResponseTime'=> 0,
            'wallPost'           => 0,
            'wallAdminActivity'  => 0,
            'messageIn'          => 0,
            'messageOut'         => 0,
            'muu'               =>0,
            'mur'               =>0,
        ); 
        $totalReplyCount = 0;
        $totalTime = 0;
        $totalRtime = 0;
        $times['step']=array();
        $to=strtotime('+'.$enc,$from);
        $lr=1;
        while($from<$end){
            //echo $from."\n";
            $lr++;if($lr>50)break;
            $to=strtotime('+'.$enc,$from);
            $sTo=$to;
            $to=strtotime('-1 second',$to);
            /*}
            foreach($dates as $from){*/
            /*$to=strtotime('+'.$enc,$from);
            $sTo=$to;
            $to=strtotime('-1 second',$to);*/

            $times['step'][$from]=array(
                'f'=>$from,
                't'=>$to,
                'st' => $general->make_date($from,'time'),
                'en' => $general->make_date($to,'time'),
            );
            $cacheKey='dashboard_graph_'.$from.'_'.$to;

            if($from>strtotime('- 2 hour')&&$to<strtotime('+1 hour')||isset($_GET['flush'])){
                $c=false;
            }
            else{
                $c=$db->reportCacheGet($cacheKey);
            }
            //$c=false;
            $jArray[__LINE__]=$c;
            //$general->printArray($c);
            if($c==false){
                $expTime=strtotime('+30 minute');
                if($to<strtotime('-3 hour')){$expTime=strtotime('+ 6 month');}

                $cData=array();
                //if(PROJECT!='gpc')
                $com    =$db->selectAll($general->table(4),' WHERE created_time between '.$from. ' AND '.$to,' count(created_time) as total');
                $d=0;
                if(!empty($com)){$d=intval($com[0]['total']);}
                $cData['adminPost']=$d;

                $replicaUse=true;
                if($to<strtotime('-3 hour')){
                    $replicaUse=false;
                }

                //if(PROJECT!='gpc')
                $d=$sReport->commentsUserActivity($from,$to,$replicaUse);
                $cData['userActivity']=$d;
                //if(PROJECT!='gpc')
                $d=$sReport->commentsAdminActivity($from,$to,0,'',$replicaUse);
                $cData['adminActivity']=$d;
                //if(PROJECT!='gpc')
                $d= $sReport->wallPosts($from,$to,$replicaUse);
                $cData['wallPost']=$d;
                //if(PROJECT!='gpc')
                $d=$sReport->wallAdminActivity($from,$to,0,'',$replicaUse);
                $cData['wallAdminActivity']=$d;
                if(OPERATION_MESSAGE_ALLWO==true){
                    //$jArray[__LINE__][]=' WHERE sendType=1 and sendTime between '.$from. ' AND '.$to;
//                    $com    =$db->selectAll($general->table(9),' WHERE sendType=1 and sendTime between '.$from. ' AND '.$to,' count(mid) as total');
//                    $d=0;if(!empty($com)){$d=intval($com[0]['total']);}
                    //$jArray[__LINE__][$from]=$d;
                    $cData['messageIn']=$sReport->messageIn($from,$to);
                    //$com    =$db->selectAll($general->table(9),' WHERE sendType=2 and replyTime between '.$from. ' AND '.$to,' count(mid) as total');
//                    $d=0;if(!empty($com)){$d=intval($com[0]['total']);}
//
//                    $com    =$db->selectAll($general->table(9),' WHERE sendType=1 and isDone=1 and replyTime between '.$from. ' AND '.$to,' count(mid) as total');
//                    $d2=0;if(!empty($com)){$d2=intval($com[0]['total']);}
                    $cData['messageOut']=$sReport->messageAdminActivity($from,$to,0,'array',$jArray);
                    $cData['mUniqueIn']=$sReport->messageUniqueSender($from,$to);
                    $cData['mUniqueOut']=$sReport->messageUniqueSenderReply($from,$to);
                }
                $db->reportCacheSet($cacheKey,json_encode($cData),$expTime);
            }
            else{
                $cData=json_decode($c,true);
            }
            $summery['adminPost']+=$cData['adminPost'];
            $wrapusData['status']['data'][date($df,$from)."\n".date($df,$sTo)]['Admin Post']=$cData['adminPost'];

            $summery['userActivity']+=$cData['userActivity'];
            $wrapusData['status']['data'][date($df,$from)."\n".date($df,$sTo)]['User Activity']=$cData['userActivity'];
            $summery['adminActivity']+=$cData['adminActivity'];
            $wrapusData['status']['data'][date($df,$from)."\n".date($df,$sTo)]['Admin Activity']=$cData['adminActivity'];
            $summery['wallPost']+=$cData['wallPost'];
            $wrapusData['wall']['data'][date($df,$from)."\n".date($df,$sTo)]['Wall post']=$cData['wallPost'];
            $summery['wallAdminActivity']+=$cData['wallAdminActivity'];
            $wrapusData['wall']['data'][date($df,$from)."\n".date($df,$sTo)]['Admin Activity']=$cData['wallAdminActivity'];
            if(OPERATION_MESSAGE_ALLWO==true){
                $jArray[__LINE__]=$cData['messageIn'];
                $summery['messageIn']+=$cData['messageIn'];
                $wrapusData['msg']['data'][date($df,$from)."\n".date($df,$sTo)]['Message IN']=$cData['messageIn'];
                $summery['messageOut']+=$cData['messageOut'];
                $wrapusData['msg']['data'][date($df,$from)."\n".date($df,$sTo)]['Message OUT']=$cData['messageOut'];
//                $summery['muu']+=$cData['mUniqueIn'];
                $wrapusData['msg']['data'][date($df,$from)."\n".date($df,$sTo)]['Message Unique IN']=$cData['mUniqueIn'];
//                $summery['mur']+=$cData['mUniqueOut'];
                $wrapusData['msg']['data'][date($df,$from)."\n".date($df,$sTo)]['Message Unique OUT']=$cData['mUniqueOut'];
            }
            $from=$sTo;
        }
        //        $cacheKey='dashboard_graph_aht_nrt'.$start.'_'.$end;
        //        $expTime=strtotime('+30 minute');
        /*$c=$db->reportCacheGet($cacheKey);
        if($c==false){*/
        $cData=array();
        $ah=$sReport->ahtNart($start,$end);
        $ahtWall=$sReport->ahtNartWall($start,$end);
        $cData['ah']=$ah;
        $cData['wah']=$ahtWall;
        $ahtNart = $sReport->ahtNartMsg($start,$end);
        $summery['mAht']=$ahtNart['aht'];
        $summery['mArt']=$ahtNart['art'];
        $summery['muu']=$sReport->messageUniqueSender($start,$end);
        $summery['mur']=$sReport->messageUniqueSenderReply($start,$end);
        /*}
        else{$cData=json_decode($c,true);}*/
        $ah=$cData['ah'];
        $ahtWall=$cData['wah'];
        $summery['commentHandleTime']   = $ah['aht'];
        $summery['commentResponseTime'] = $ah['art'];
        $summery['commentHandleTimeWall'] = $ahtWall['aht'];
        $summery['commentResponseTimeWall'] = $ahtWall['art'];
        foreach($wrapusData as $k=>$r){
            $graphData[$k]=array(
                'title'=>$r['title'],
                'data'=>$r['data']
            );
        }
        $graph  = $general->makeGraphArray($graphData);
        $jArray['status']=1;
        $jArray['fr']=$times;
        $jArray['graph']=$graph;
        $jArray['summary']=$summery;
        //$jArray[__LINE__]=$summery;
        /*$jArray= array(
        'status'=>1,
        'fr'=>$times,
        'graph'=>$graph,
        'summary' =>$summery
        );*/
    }
    $general->jsonHeader($jArray);

?>
