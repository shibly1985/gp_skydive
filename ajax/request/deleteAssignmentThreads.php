<?php
    $commentIds=$_POST['deleteAssignmentThreads'];    
    $type=$_POST['type'];
    if(!empty($commentIds)){
        $commentIds=explode(',',$commentIds);
        foreach($commentIds as $comment_id){
            $c=$social->getCommentInfoById($comment_id,$type);
            if(!empty($c)){
                //$jArray[__LINE__][]=$c['comment_id'];
                $social->deleteCommentByAgent($c,$type);
            }
        }
        $jArray['status']=1;
        $jArray['comments']=$social->assignmentData($type);
    }
    $general->jsonHeader($jArray);
?>
