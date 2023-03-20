<?php
    $messageLeftSideRefresh=$_POST['messageLeftSideRefresh'];
    if($messageLeftSideRefresh==0){$messageLeftSideRefresh=array();}
    $social->messageNewSenderMsgLoad($jArray,$messageLeftSideRefresh);
    $general->jsonHeader($jArray);
?>