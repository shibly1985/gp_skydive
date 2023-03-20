<?php
    $times=array();
    $startTime=time();
    $times[__LINE__]=microtime().' '.date('h:i:s');
    $type=$_POST['type'];if($type!='c')$type='w';
    if($type=='c'){
        $pTbl=4;
        $cTbl=13;
        $times[__LINE__]=microtime().' '.date('h:i:s');
        include(ROOT_PATH."/ajax/request/newStatusComment.php");
    }
    else{
        $pTbl=12;
        $rpTbl=64;
        $cTbl=14;
        $rcTbl=65;
        $times[__LINE__]=microtime().' '.date('h:i:s');
        include(ROOT_PATH."/ajax/request/newPostComment.php");
    }
    $times[__LINE__]=microtime().' '.date('h:i:s');
    $social->lastTime($jArray);
    $jArray['m']=show_msg('yes');
    $jArray['serviceTime']=$social->getAgentTodayActivity(UID);
    $times[__LINE__]=$general->timestampDiffInArray($startTime,time(),true);
    //textFileWrite(json_encode($times).',');
    $jArray['times']=$times;
    $general->jsonHeader($jArray);
?>
