<?php
    $cacheKey='topque';
    $c=$db->reportCacheGet($cacheKey);
//    $c=false;
    if($c!=false&&!isset($_GET['flush'])){
        $c=json_decode($c,true);
        $jArray['commentQueue']=$c['commentQueue'];
        $jArray['wallPostQue']=$c['wallPostQue'];
        $jArray['msgQue']=$c['msgQue'];
    }
    else{
        $service24hours=intval($db->settingsValue('service24hours'));//1=24 hours and 2=custom hour
        $service24hours=$service24hours==1?1:2;

        $timeQuery='';
        if($service24hours==2){$timeQuery=' and created_time<='.TIME;}
        $query="select count(c.comment_id) as total from ".$general->table(63)." c where c.replyed=0".$timeQuery;
        $total=$db->fetchQuery($query);
        $total=$total[0]['total'];
        $jArray['commentQueue']=$total;
        $query="select count(c.post_id) as total from ".$general->table(64)." c where c.replyed=0".$timeQuery;
        //      $jArray[__LINE__]=$query;
        $total=$db->fetchQuery($query);
        $total=$total[0]['total'];
        $jArray['wallPostQue']=$total;
        $query="select count(c.comment_id) as total from ".$general->table(65)." c where c.replyed=0".$timeQuery;
        //      $jArray[__LINE__]=$query;
        $total=$db->fetchQuery($query);
        $total=$total[0]['total'];
        $jArray['wallPostQue']+=$total;
        if(OPERATION_MESSAGE_ALLWO==true){
            if($service24hours==2){
                $timeQuery=' and sendTime<='.TIME;
            }
            $query="select count(c.sender_id) as total from ".$general->table(16)." c where c.replyed=0".$timeQuery;
            //$jArray[__LINE__]=$query;
            $total=$db->fetchQuery($query);
            $total=$total[0]['total'];
            $jArray['msgQue']=$total;
        }
        else{
            //$jArray[__LINE__]=OPERATION_MESSAGE_ALLWO;
            $jArray['msgQue']=0;    
        }
        $expTime=strtotime('+30 second');
        $db->reportCacheSet($cacheKey,json_encode($jArray),$expTime);
    }
    $jArray['systemClock']=date('d-m-y h:i A');
    $general->jsonHeader($jArray);
?>
