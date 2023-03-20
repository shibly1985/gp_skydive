<?php
    $commentIds=$_POST['banNdeleteAssignmentThreads'];
    $type=$_POST['type'];
    if(!empty($commentIds)){
        $idForBan=array();
        $commentForDelete=array();
        foreach($commentIds as $comment_id=>$sender_id){
            if($type=='c'){
                $c=$social->getCommentInfoById($comment_id,$type);
                if(!empty($c)){
                    if($c['sender_id']==$sender_id){
                        $idForBan[$comment_id]=$c['sender_id'];
                        $commentForDelete[$comment_id]=$c;
                    }
                }
            }
            else if($type=='w'){
                $c=$social->getPostInfoById($comment_id,$type);
                if(!empty($c)){
                    if($c['sender_id']==$sender_id){
                        $idForBan[$comment_id]=$c['sender_id'];
                    }
                } 
            }
        }
        if(!empty($idForBan)){
            $jArray['ban']=$idForBan;
            $jArray['banStatus']=$social->banFromFB($type,$idForBan);
            foreach($commentForDelete as $c){
                $social->deleteCommentByAgent($c,$type);
            }
            $rt=$social->assignmentData($type);
            $jArray['comments']=$rt['comments'];
            $jArray['names']=$rt['names'];
            
        }
        else{
            $jArray[__LINE__][]=$comment_id;
        }
        $jArray['status']=1;
    }
    $general->jsonHeader($jArray);
?>
