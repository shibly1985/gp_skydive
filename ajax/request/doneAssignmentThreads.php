<?php
    $commentIds=$_POST['doneAssignmentThreads'];
    $type=$_POST['type'];
    if(!empty($commentIds)){
        foreach($commentIds as $comment_id){
            if($type=='c'){
                $c=$social->getCommentInfoById($comment_id,$type);
                if(!empty($c)){
                    $social->commentPostDone($type,$comment_id);
                }
            }
            else if($type=='w'){
                $c=$social->getPostInfoById($comment_id,$type);
                if(!empty($c)){
                    $social->commentPostDone($type,$comment_id);
                }
                else{
                    $c=$social->getCommentInfoById($comment_id,$type);
                    if(!empty($c)){
                        $social->wallPostCommentDone($comment_id);
                    }
                }

            }
        }
        $jArray['status']=1;
        $jArray['comments']=$social->assignmentData($type);
    }
    $general->jsonHeader($jArray);
?>
