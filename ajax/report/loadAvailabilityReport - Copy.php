<?php
    $from=$_POST['from'];
    $to=$_POST['to'];
    $uID=intval($_POST['userid']);
    $expTime=strtotime('+ 1 hour');    
    if($to<strtotime('-2 hour')){
        $expTime=strtotime('+ 6 month');    
    }
    $cacheKey='availibalityReport_'.$uID.'_'.$from.'_'.$to;
    $c=$db->reportCacheGet($cacheKey);
    //    $jArray[__LINE__]=__LINE__;
    //if($c==true&&!isset($_GET['flash'])){
    if($c!=false&&!isset($_GET['flash'])){
        //        $jArray[__LINE__]=__LINE__;
        $jArray['status']=1;
        $jArray['data']=json_decode($c,true);
    }
    else{
        //        $jArray[__LINE__]=__LINE__;
        if($uID!=0){
            //$jArray[__LINE__]=__LINE__;
            $rData=array();
            $cache=array();
            /*
            $query="select min(uls.ulsStartTime) as firstLogin,max(uls.ulsLastActivity) as lastActivity from ".$general->table(18)." uls
            where uls.ulsStartTime between ".$from." and ".$to.' and uID='.$uID;
            $lInfo=$db->fetchQuery($query);
            $lInfo=$lInfo[0];
            $firstLogin     = intval($lInfo['firstLogin']);
            if($firstLogin>0){
            $firstLogin=date('h:i:s A',$firstLogin);
            }
            else{
            $firstLogin='-';
            }
            $lastActivity   = intval($lInfo['lastActivity']);
            if($lastActivity>0){
            if(date('d-m-Y',$firstLogin)!=date('d-m-Y',$firstLogin)){
            $lastActivity=date('d-m h:i:s A',$lastActivity);
            }
            else{
            $lastActivity=date('h:i:s A',$lastActivity);
            }
            }
            else{
            $lastActivity='-';
            }
            */

            $query="select * from ".$general->table(18)." uls where uls.ulsStartTime between ".$from." and ".$to.' and uID='.$uID.' order by uls.ulsStartTime asc';
            $logins=$db->fetchQuery($query);
            $active=0;
            $away=0;
            $firstLogin=0;
            $lastActivity=0;
            if(!empty($logins)){
                foreach($logins as $l){
                    if($firstLogin==0||$firstLogin>$l['ulsStartTime']){
                        $firstLogin=$l['ulsStartTime'];
                    }
                    if($lastActivity<$l['ulsLastActivity']){
                        $lastActivity=$l['ulsStartTime'];
                    }
                    $ta=$general->timestampDiffInArray($l['ulsLastActivity'],$l['ulsStartTime'],true);
                    $active+=$l['ulsService'];
                    $away+=$general->makeTimeAvgI($ta-$l['ulsService']);
                }
            }
            //$firstLogin     = intval($lInfo['firstLogin']);
            if($firstLogin>0){
                $firstLogin=date('h:i:s A',$firstLogin);
            }
            else{
                $firstLogin='-';
            }
            //$lastActivity   = intval($lInfo['lastActivity']);
            if($lastActivity>0){
                if(date('d-m-Y',$firstLogin)!=date('d-m-Y',$firstLogin)){
                    $lastActivity=date('d-m h:i:s A',$lastActivity);
                }
                else{
                    $lastActivity=date('h:i:s A',$lastActivity);
                }
            }
            else{
                $lastActivity='-';
            }

            /*$logins=$db->selectAll($general->table(38),'where serviceStart between '.$from.' and '.$to.' and uID='.$uID);
            $service    = 0;
            $active     = 0;
            $away       = 0;

            if(!empty($logins)){
                foreach($logins as $l){
                    $service=$service+intval($l['service']);
                    $active=$active+intval($l['active']);
                }
                $away=$active-$service;
            }*/
            $breaks=$db->selectAll($general->table(43),'where btTime between '.$from.' and '.$to.' and uID='.$uID);
            $tb=0;
            if(!empty($breaks)){
                foreach($breaks as $br){
                    $tb+=$general->timestampDiffInArray($br['btReturnTime'],$br['btTime'],true);
                }
            }
            $totalReply=0;
            $reply = $db->selectAll($general->table(13),"where sender_id=".PAGE_ID." and replyTime BETWEEN ".$from." AND ".$to.' and isDone!=1 and replyBy='.$uID,'count(comment_id) as tr');
            $totalReply+=intval($reply[0]['tr']);
            $reply = $db->selectAll($general->table(14),"where sender_id=".PAGE_ID." and replyTime BETWEEN ".$from." AND ".$to.' and isDone!=1 and replyBy='.$uID,'count(comment_id) as tr');
            $totalReply+=intval($reply[0]['tr']);

            $tHide=0;
            $hcom = $db->selectAll($general->table(31),' WHERE  hideTime between '.$from. ' AND '.$to.' and uID='.$uID,' count(hideTime) as total');
            $tHide+=intval($reply[0]['total']);
            $hcom = $db->selectAll($general->table(32),' WHERE  hideTime between '.$from. ' AND '.$to.' and uID='.$uID,' count(hideTime) as total');
            $tHide+=intval($reply[0]['total']);
            $hcom = $db->selectAll($general->table(35),' WHERE  hideTime between '.$from. ' AND '.$to.' and uID='.$uID,' count(hideTime) as total');
            $tHide+=intval($reply[0]['total']);




            $tDone=0;
            $reply = $db->selectAll($general->table(13),"where sender_id=".PAGE_ID." and replyTime BETWEEN ".$from." AND ".$to.' and isDone=1 and replyBy='.$uID,'count(comment_id) as tr');
            $tDone+=intval($reply[0]['tr']);
            $reply = $db->selectAll($general->table(14),"where sender_id=".PAGE_ID." and replyTime BETWEEN ".$from." AND ".$to.' and isDone=1 and replyBy='.$uID,'count(comment_id) as tr');
            $tDone+=intval($reply[0]['tr']);
            $reply = $db->selectAll($general->table(12),"where replyTime BETWEEN ".$from." AND ".$to.' and isDone=1 and replyBy='.$uID,'count(comment_id) as tr');
            $tDone+=intval($reply[0]['tr']);
            $tTransfer=0;

            $tr=$db->selectAll($general->table(54),"where transfer_time BETWEEN ".$from." AND ".$to.' and uID='.$uID,'comment_id');
            if(!empty($tr)){
                $trs=array();
                foreach($tr as $t){$trs[$t['comment_id']]=$t['comment_id'];}
                $tTransfer+=count($trs);
            }
            $tr=$db->selectAll($general->table(55),"where transfer_time BETWEEN ".$from." AND ".$to.' and uID='.$uID,'comment_id');
            if(!empty($tr)){
                $trs=array();
                foreach($tr as $t){$trs[$t['comment_id']]=$t['comment_id'];}
                $tTransfer+=count($trs);
            }
            $tr=$db->selectAll($general->table(56),"where transfer_time BETWEEN ".$from." AND ".$to.' and uID='.$uID,'post_id');
            if(!empty($tr)){
                $trs=array();
                foreach($tr as $t){$trs[$t['post_id']]=$t['post_id'];}
                $tTransfer+=count($trs);
            }

            $ht = $sReport->ahtNart($from,$to,$uID);
            $wht = $sReport->ahtNartWall($from,$to,$uID);
            $caht=$ht['aht'];
            $waht=$wht['aht'];
            $rData=array(
                'date'      => $general->make_date($from),
                'fLogIn'    => $firstLogin,
                'lLogOut'   => $lastActivity,
                'available' => $general->makeTimeAvgI($active),
                'tBbreak'   => $general->makeTimeAvgI($tb),
                'reply'     => $totalReply,
                'tHide'      => $tHide,
                'done'      => $tDone,
                'transfer'  => $tTransfer,
                'caht'      => $caht,
                'waht'      => $waht
            );
            $jArray['status']=1;
            $jArray['data']=$rData;
            $db->reportCacheSet($cacheKey,json_encode($rData),$expTime);
        }
    }
    $general->jsonHeader($jArray);
?>
