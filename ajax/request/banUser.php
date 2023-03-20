<?php
    $sender_id    = $_POST['banUser'];
    $comment_id   = $_POST['comment_id'];
    $type         = $_POST['type'];
    $jArray['st']=$social->banFromFB($type,array($comment_id=>$sender_id));
    if($jArray['st']==true){
        $jArray['status']=1;
        $jArray['nextComment']=$social->nextPicup($type);
    }
    $general->jsonHeader($jArray);
?>
