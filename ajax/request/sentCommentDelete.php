<?php
    $jArray=array('status'=>0);
    if(!isset($_POST['targetType'])){$targetType='c';}else{$targetType=$_POST['targetType'];}
    $comment_id=$_POST['sentCommentDelete'];
    $type=$_POST['type'];
    if($type=='c'){
        $c=$social->getCommentInfoById($comment_id,$type);
    }
    else{
        if($targetType=='c'){
            $c=$social->getCommentInfoById($comment_id,$type);
        }
        else{
            $c=$social->getPostInfoById($comment_id,$type);
        }
    }
    $hide=$social->deleteCommentByAgent($c,$type,$targetType);
    if($hide==true){
        $jArray['status']=1;
    }
    $general->jsonHeader($jArray);
?>