<?php
    $from=$_POST['from'];
    $to=$_POST['to'];
    $uIDs=$uID=intval($_POST['userid']);
    $ugID=intval($_POST['groutid']);
    $sq='and uID='.$uID;
    if($ugID!=0){
        $gUsers=$db->allUsers(' and ugID='.$ugID);
        if(!empty($gUsers)){
            $general->arrayIndexChange($gUsers,'uID');
            $uIDs=array_keys($gUsers);
            $sq='and uID in('.implode(',',$uIDs).')';
        }
        else{
            $sq='uID=-1';
        }
    }
    $expTime=strtotime('+ 1 hour');    
    if($to<strtotime('-2 hour')){
        $expTime=strtotime('+ 6 month');    
    }
    $cacheKey='agentHourlyPer_'.$uID.'_'.$ugID.'_'.$from.'_'.$to;
    $c=$db->reportCacheGet($cacheKey);
    if($c!=false&&!isset($_GET['flush'])){
        $jArray['status']=1;
        $jArray['data']=json_decode($c,true);
    }
    else{
        if($from<strtotime('+2 hour')){
            $logins=$db->selectAll($general->table(38),'where serviceStart between '.$from.' and '.$to.' '.$sq);
            $service=0;
            $active=0;
            $away = 0;
            if(!empty($logins)){ 
                foreach($logins as $l){
                    $service=$service+intval($l['service']);
                    $active=$active+intval($l['active']);
                }
                $away=$active-$service;
            }
            $cm=$sReport->commentsAdminActivity($from,$to,$uIDs);
            $wc=$sReport->wallAdminActivity($from,$to,$uIDs);
            $ht = $sReport->ahtNart($from,$to,$uIDs);
            $wht = $sReport->ahtNartWall($from,$to,$uIDs);
            $rData=array(
                'date'      => $general->make_date($from),
                'from'      => $general->make_date($from,'tam'),
                'to'        => $general->make_date($to,'tam'),
                'cm'        => $cm,
                'wc'        => $wc,
                'caht'      => $ht['aht'],
                'waht'      => $wht['aht'],
                'active'    => $general->makeTimeAvgI($active),
                'service'   => $general->makeTimeAvgI($service),
                'away'      => $general->makeTimeAvgI($away),
            );
            $jArray['status']=1;
            $jArray['data']=$rData;
            $db->reportCacheSet($cacheKey,json_encode($rData),$expTime);
        }
        else{
            $rData=array(
                'date'      => $general->make_date($from),
                'from'      => $general->make_date($from,'tam'),
                'to'        => $general->make_date($to,'tam'),
                'cm'        => 0,
                'wc'        => 0,
                'caht'      => '00:00:00',
                'waht'      => '00:00:00',
                'active'    => '00:00:00',
                'service'   => '00:00:00',
                'away'      => '00:00:00',
            );
            $jArray['status']=1;
            $jArray['data']=$rData;
        }
    }
    $general->jsonHeader($jArray);
?>
