<?php
    $commentIds=$_POST['hideAssignmentThreads'];
    $type=$_POST['type'];
    if(!empty($commentIds)){
        $commentForHide=array();
        foreach($commentIds as $comment_id=>$sender_id){
            if($type=='c'){
                $c=$social->getCommentInfoById($comment_id,$type);
                if(!empty($c)){
                    if($c['sender_id']==$sender_id){
                        $commentForHide[$comment_id]=$c;
                    }
                }
            }
            /*else if($type=='w'){
            $c=$social->getPostInfoById($comment_id,$type);
            if(!empty($c)){
            if($c['sender_id']==$sender_id){
            $idForBan[$comment_id]=$c['sender_id'];
            }
            } 
            }*/
        }
        if($type=='c'){//ওয়ালে কিছু কাজ করতে হবে তাই আপাতত অফ করে রাখা হল
            if(!empty($commentForHide)){
                foreach($commentForHide as $c){
                    $social->hideFromFB($c['comment_id'],$type);
                    $social->commentPostDone($type,$c['comment_id']);
                }
            }
        }
        $rt=$social->assignmentData($type);
        $jArray['comments']=$rt['comments'];
        $jArray['names']=$rt['names'];
        $jArray['status']=1;
    }
    $general->jsonHeader($jArray);
?>
