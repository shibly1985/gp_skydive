<?php
    $jArray=$social->nextPicup($_POST['nextPicup']);
    $jArray['login']=1;
//    SetMessage(66); 
    $jArray['m']=show_msg('Yes');
    $jArray['serviceTime']=$social->getAgentTodayActivity(UID);
    $social->lastTime($jArray);
    $general->jsonHeader($jArray);
?>